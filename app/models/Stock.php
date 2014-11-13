<?php

namespace Models ;

class Stock extends BaseEntity implements \Interfaces\iEntity
{

	public function stockDetails ()
	{
		return $this -> hasMany ( 'Models\StockDetail' ) ;
	}

	public function stockConfirmations ()
	{
		return $this -> hasMany ( 'Models\StockConfirmation' ) ;
	}

	public function incharge ()
	{
		return $this -> belongsTo ( 'User' , 'incharge_id' ) ;
	}

	public function stockType ()
	{
		return $this -> belongsTo ( 'Models\StockType' ) ;
	}

	public function loadings ()
	{
		return $this -> hasMany ( 'Models\Transfer' , 'to_stock_id' ) ;
	}

	public function unloadings ()
	{
		return $this -> hasMany ( 'Models\Transfer' , 'from_stock_id' ) ;
	}

	public function totalItemQuantities ()
	{
		$goodQuantity	 = $this -> goodQuantities () ;
		$returnQuantity	 = $this -> returnQuantities () ;

		$totalItemQuantity = \ArrayHelper::AddSameKeyElements ( $goodQuantity , $returnQuantity ) ;

		return $totalItemQuantity ;
	}

	public function goodQuantities ()
	{
		$this -> load ( 'stockDetails' ) ;
		$stockDetails = $this -> stockDetails ;

		$goodQuantity = $stockDetails -> lists ( 'good_quantity' , 'item_id' ) ;

		return $goodQuantity ;
	}

	public function returnQuantities ()
	{
		$this -> load ( 'stockDetails' ) ;
		$stockDetails = $this -> stockDetails ;

		$returnQuantity = $stockDetails -> lists ( 'return_quantity' , 'item_id' ) ;

		return $returnQuantity ;
	}

	public function isUnloaded ()
	{
		$lastLoadTime	 = $this -> loadings -> last ()[ 'date_time' ] ;
		$lastUnloadTime	 = $this -> unloadings -> last ()[ 'date_time' ] ;

		if ( $lastLoadTime != NULL && $lastUnloadTime != NULL && $lastUnloadTime > $lastLoadTime )
		{
			return TRUE ;
		}

		return FALSE ;
	}

	public function isLoaded ()
	{
		$lastLoadTime	 = $this -> loadings -> last ()[ 'date_time' ] ;
		$lastUnloadTime	 = $this -> unloadings -> last ()[ 'date_time' ] ;

		if ( $lastLoadTime != NULL && $lastLoadTime > $lastUnloadTime )
		{
			return TRUE ;
		}

		return FALSE ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::update ( $attributes ) ;
	}

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	private function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'id'			 => [
				'required' ,
				'unique:stocks,id,' . $this -> id
			] ,
			'name'			 => [
				'required' ,
				'unique:stocks,name,' . $this -> id
			] ,
			'incharge_id'	 => [
				'unique:stocks,incharge_id,' . $this -> id
			] ,
			'stock_type_id'	 => [
				'required'
			]
			] ;

		$messages = [
			'incharge_id.unique' => 'This user has already been assigned to another stock.'
			] ;

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public function getLastLoadDate ( $stockId )
	{
		$imbalanceStock = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;

		$lastLoadDate = \Models\Transfer::where ( 'to_stock_id' , '=' , $stockId )
			-> where ( 'from_stock_id' , '!=' , $imbalanceStock )
			-> max ( 'date_time' ) ;

		return $lastLoadDate ;
	}

	public function isLoadedWithItems ()
	{
		$loadedGoodQuantities	 = \Models\StockDetail::where ( 'stock_id' , '=' , $this -> id ) -> lists ( 'good_quantity' ) ;
		$hasNoLoadedItems		 = \ArrayHelper::areAllElementsEmpty ( $loadedGoodQuantities ) ;

		$lastLoadDate = $this -> getLastLoadDate ( $this -> id ) ;


		if ( $hasNoLoadedItems == TRUE || $lastLoadDate == NULL )
		{
			return FALSE ;
		} else
		{
			return TRUE ;
		}
	}

	public function saveUnload ( $toStockId , $dateTime , $availableAmounts , $transferAmounts , $description )
	{
		$transferId = $this -> saveBasicTransferDetails ( $this -> id , $toStockId , $dateTime , $description ) ;

		$this -> saveTransfersByDifferentTransferAmounts ( $transferAmounts , $availableAmounts , $toStockId , $dateTime , $description , $transferId ) ;
	}

	public function saveTransfersByDifferentTransferAmounts ( $transferAmounts , $availableAmounts , $toStockId , $dateTime , $description , $transferId )
	{

		$prunedTransferAmounts		 = \ArrayHelper::pruneEmptyElements ( $transferAmounts ) ;
		$transferAmountHigherArray	 = [ ] ;
		$transferAmountSmallerArray	 = [ ] ;
		$transferAmountEqualArray	 = [ ] ;

		foreach ( $prunedTransferAmounts as $item => $transferAmount )
		{
			if ( $availableAmounts [ $item ] < $transferAmount )
			{
				$transferAmountHigherArray[ $item ] = 'higher' ;
			}
			if ( $availableAmounts [ $item ] > $transferAmount )
			{
				$transferAmountSmallerArray[ $item ] = 'smaller' ;
			}
			if ( $availableAmounts [ $item ] == $transferAmount )
			{
				$transferAmountEqualArray[ $item ] = 'equal' ;
			}
		}

		if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $transferAmountHigherArray ) )
		{
			$this -> saveTransferWhenTransferAmountHigherThanAvailable ( $transferAmountHigherArray , $toStockId , $dateTime , $description , $availableAmounts , $prunedTransferAmounts , $transferId ) ;
		}
		if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $transferAmountSmallerArray ) )
		{
			$this -> saveTransferWhenTransferAmountSmallerThanAvailable ( $transferAmountSmallerArray , $toStockId , $dateTime , $description , $availableAmounts , $prunedTransferAmounts , $transferId ) ;
		}
		if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $transferAmountEqualArray ) )
		{
			$this -> saveTransferWhenTransferAmountEqualToAvailable ( $transferAmountEqualArray , $toStockId , $prunedTransferAmounts , $transferId ) ;
		}
	}

	public function saveTransferWhenTransferAmountHigherThanAvailable ( $transferAmountHigherArray , $toStockId , $dateTime , $description , $availableAmounts , $transferAmounts , $transferId )
	{
		$imbalanceAccount	 = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;
		$returnedTransferId	 = $this -> saveBasicTransferDetails ( $imbalanceAccount , $this -> id , $dateTime , $description ) ;

		foreach ( $transferAmountHigherArray as $itemHigher => $transferAmountHigher )
		{
			$systemVsRealDifferrenceForHigher[ $itemHigher ] = $availableAmounts [ $itemHigher ] - $transferAmounts[ $itemHigher ] ;

			$this -> saveItemWiseTransfer ( $imbalanceAccount , $this -> id , $itemHigher , -( $systemVsRealDifferrenceForHigher[ $itemHigher ]) , $returnedTransferId ) ;
			$this -> saveItemWiseTransfer ( $this -> id , $toStockId , $itemHigher , $transferAmounts[ $itemHigher ] , $transferId ) ;
		}
	}

	public function saveTransferWhenTransferAmountSmallerThanAvailable ( $transferAmountSmallerArray , $toStockId , $dateTime , $description , $availableAmounts , $transferAmounts , $transferId )
	{
		$imbalanceAccount	 = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;
		$returnedTransferId	 = $this -> saveBasicTransferDetails ( $this -> id , $imbalanceAccount , $dateTime , $description ) ;

		foreach ( $transferAmountSmallerArray as $itemSmaller => $transferAmountSmaller )
		{
			$systemVsRealDifferrenceForSmaller[ $itemSmaller ] = $availableAmounts [ $itemSmaller ] - $transferAmounts[ $itemSmaller ] ;

			$this -> saveItemWiseTransfer ( $this -> id , $imbalanceAccount , $itemSmaller , $systemVsRealDifferrenceForSmaller[ $itemSmaller ] , $returnedTransferId ) ;

			$this -> saveItemWiseTransfer ( $this -> id , $toStockId , $itemSmaller , $transferAmounts[ $itemSmaller ] , $transferId ) ;
		}
	}

	public function saveTransferWhenTransferAmountEqualToAvailable ( $transferAmountEqualArray , $toStockId , $transferAmounts , $transferId )
	{
		foreach ( $transferAmountEqualArray as $itemEqual => $transferAmountEqual )
		{
			$this -> saveItemWiseTransfer ( $this -> id , $toStockId , $itemEqual , $transferAmounts[ $itemEqual ] , $transferId ) ;
		}
	}

	public function saveBasicTransferDetails ( $fromStockId , $toStockId , $dateTime , $description )
	{
		$transfer = new \Models\Transfer() ;

		$transfer -> from_stock_id	 = $fromStockId ;
		$transfer -> to_stock_id	 = $toStockId ;
		$transfer -> date_time		 = $dateTime ;
		$transfer -> description	 = $description ;

		$transfer -> save () ;

		$transferId = $transfer -> id ;
		return

			$transferId ;
	}

	private function saveItemWiseTransfer ( $fromStock , $toStock , $item , $quantity , $transferId )
	{

		$newTransferDetail = new \Models\TransferDetail() ;

		$newTransferDetail -> transfer_id	 = $transferId ;
		$newTransferDetail -> item_id		 = $item ;
		$newTransferDetail -> quantity		 = $quantity ;

		$newTransferDetail -> save () ;

		\StockDetailButler::decreaseGoodQuantity ( $fromStock , $item , $quantity ) ;
		\StockDetailButler::increaseGoodQuantity ( $toStock , $item , $quantity ) ;
	}

	public function saveNonUnload ( $toStockId , $dateTime , $transferAmounts , $description )
	{
		$transferId = $this -> saveBasicTransferDetails ( $this -> id , $toStockId , $dateTime , $description ) ;

		foreach ( $transferAmounts as $itemId => $transferAmount )
		{
			if ( ! \NullHelper::isNullEmptyOrWhitespace ( $transferAmount ) )
			{
				$this -> saveItemWiseTransfer ( $this -> id , $toStockId , $itemId , $transferAmount , $transferId
				) ;
			}
		}
	}

	public function getLastConfirmDate ()
	{
		$this -> load ( 'stockConfirmations' ) ;
		$stockConfirmations	 = $this -> stockConfirmations ;
		$lastConfirmDate	 = $stockConfirmations -> last () ;
		if ( count ( $lastConfirmDate ) == 0 )
		{
			return NULL ;
		}
		return $lastConfirmDate -> date_time ;
	}

	public function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [

			'name'			 => [
				'required' ,
				'unique:stocks,name,' . $this -> id
			] ,
			'incharge_id'	 => [
				'unique:stocks,incharge_id,' . $this -> id
			] ,
			'stock_type_id'	 => [
				'required'
			]
			] ;

		$messages = [
			'incharge_id.unique' => 'This user has already been assigned to another stock.'
			] ;

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
