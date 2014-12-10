<?php

namespace Controllers\Processes ;

class TransferController extends \Controller
{

	public function all ()
	{
		$filterValues	 = \Input::all () ;
		$transfers		 = \Models\Transfer::filter ( $filterValues ) ;
		$stocks			 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Any' ] ) ;

		$fromStockId	 = \InputButler::get ( 'from_stock_id' ) ;
		$toStockId		 = \InputButler::get ( 'to_stock_id' ) ;
		$dateTimeFrom	 = \InputButler::get ( 'date_time_from' ) ;
		$dateTimeTo		 = \InputButler::get ( 'date_time_to' ) ;


		if ( is_null ( $dateTimeFrom ) )
		{
			$dateTimeFrom = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( '-7 days midnight' ) ) ) ;
		}
		if ( is_null ( $dateTimeTo ) )
		{
			$dateTimeTo = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( 'today 23:59:59' ) ) ) ;
		}

		$data = compact ( [
			'transfers' ,
			'stocks' ,
			'fromStockId' ,
			'toStockId' ,
			'dateTimeFrom' ,
			'dateTimeTo'
			] ) ;

		return \View::make ( 'web.processes.transfers.all' , $data ) ;
	}

	public function viewTransfer ( $id )
	{
		$basicStockDetails	 = \Models\Transfer::findOrFail ( $id ) ;
		$transferData		 = \Models\TransferDetail::where ( 'transfer_id' , '=' , $id ) -> get () ;
		$data				 = compact ( [
			'basicStockDetails' ,
			'transferData'
			] ) ;
		return \View::make ( 'web.processes.transfers.view' , $data ) ;
	}

	public function selectStocksInvolved ()
	{
		$data = [ ] ;

		$stocksHtmlSelect = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Any' ] ) ;

		$data[ 'stocksHtmlSelect' ] = $stocksHtmlSelect ;

		return \View::make ( 'web.processes.transfers.selectStocksInvolved' , $data ) ;
	}

	public function pSelectStocksInvolved ()
	{
		try
		{
			$from		 = \InputButler::get ( 'from' ) ;
			$to			 = \InputButler::get ( 'to' ) ;
			$isUnloaded	 = \NullHelper::zeroIfNull ( \InputButler::get ( 'is_unload' ) ) ;

			$this -> validateSelectedTransfers ( $from , $to , $isUnloaded ) ;

			return \Redirect::action ( 'processes.transfers.add' , [ $from , $to , $isUnloaded ] ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function add ( $fromStockId , $toStockId , $isUnloaded )
	{
		try
		{
			$this -> validateSelectedTransfers ( $fromStockId , $toStockId , $isUnloaded ) ;

			$fromStock	 = \Models\Stock::with ( 'stockDetails' ) -> findOrFail ( $fromStockId ) ;
			$toStock	 = \Models\Stock::with ( 'stockDetails' ) -> findOrFail ( $toStockId ) ;

			if ( $isUnloaded == true )
			{
				$LoadedItems = \StockButler::getItemsForUnload ( $fromStockId ) ;
			} else
			{
				$LoadedItems = \Models\StockDetail::where ( 'stock_id' , '=' , $fromStock -> id )
					-> lists ( 'item_id' ) ;
			}

			$loadedItemNames = [ ] ;

			foreach ( $LoadedItems as $loadedItem )
			{
				$itemNames						 = \Models\Item::findOrFail ( $loadedItem ) ;
				$loadedItemNames[ $loadedItem ]	 = $itemNames -> name ;
			}

			$LoadedItems	 = json_encode ( $LoadedItems ) ;
			$loadedItemNames = json_encode ( $loadedItemNames ) ;


			$dateTime				 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
			$returnQuantityArray	 = $fromStock -> returnQuantities () ;
			$returnQuantityStatus	 = \ArrayHelper::hasAtLeastOneElementWithValue ( $returnQuantityArray ) ;

			$fromStockDetails	 = $fromStock -> goodQuantities () ;
			$toStockDetails		 = $toStock -> goodQuantities () ;

			if ( $isUnloaded == TRUE )
			{
				$stockObj	 = \Models\Stock::findOrFail ( $fromStockId ) ;
				$isLoaded	 = $stockObj -> isLoadedWithItems () ;

				if ( $isLoaded == FALSE )
				{
					\MessageButler::setError ( 'Your stock is empty.Please load the stock.' ) ;
					return \Redirect::back ()
							-> withInput () ;
				}

				if ( $returnQuantityStatus == TRUE )
				{
					\MessageButler::setInfo ( 'There are <b>company returns</b> in the "' . $fromStock -> name . '" stock, We will unload them automatically' ) ;
				}
			}

			$data = compact ( [
				'fromStock' ,
				'toStock' ,
				'loadedItemNames' ,
				'LoadedItems' ,
				'dateTime' ,
				'fromStockDetails' ,
				'toStockDetails' ,
				'isUnloaded' ,
				'returnQuantityArray' ,
				'itemNames'
				] ) ;

			return \View::make ( 'web.processes.transfers.add' , $data ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::action ( 'processes.transfers.selectStocksInvolved' )
					-> withErrors ( $ex -> validator )
					->
					withInput () ;
		}
	}

	public function getAvailableQuantity ()
	{
		$itemId	 = \Input::get ( 'itemId' ) ;
		$fromId	 = \Input::get ( 'fromStock_id' ) ;

		$itemAvailable = \Models\StockDetail:: where ( 'stock_id' , '=' , $fromId ) -> where ( 'item_id' , '=' , $itemId ) -> firstOrFail () ;
		return $itemAvailable ->
			good_quantity ;
	}

	public function getTargetStockQuantity ()
	{
		$itemId	 = \Input::get ( 'itemId' ) ;
		$toId	 = \Input::get ( 'toStock_id' ) ;

		$itemAvailable = \Models\StockDetail:: where ( 'stock_id' , '=' , $toId ) -> where ( 'item_id' , '=' , $itemId ) -> firstOrFail () ;
		return $itemAvailable ->
			good_quantity ;
	}

	public function save ( $fromStockId , $toStockId )
	{
		try
		{
			$items					 = \StockButler::getItemsForUnload ( $fromStockId ) ;
			$dateTime				 = \InputButler::get ( 'date_time' ) ;
			$availableAmounts		 = \InputButler::get ( 'availale_amounts' ) ;
			$transferAmounts		 = \InputButler::get ( 'transfer_amounts' ) ;
			$description			 = \InputButler::get ( 'description' ) ;
			$unload					 = \NullHelper::zeroIfNull ( \InputButler::get ( 'is_unload' ) ) ;
			$fromStockObj			 = \Models\Stock::findOrFail ( $fromStockId ) ;
			$fromStock				 = \Models\Stock::with ( 'stockDetails' ) -> findOrFail ( $fromStockId ) ;
			$companyReturns			 = $fromStock -> returnQuantities () ;
			$returnQuantityStatus	 = \ArrayHelper::hasAtLeastOneElementWithValue ( $companyReturns ) ;

			if ( $unload == TRUE )
			{
				$itemsWithoutZero = [ ] ;
				foreach ( $items as $item )
				{
					if ( $availableAmounts [ $item ] == 0 && $transferAmounts [ $item ] == '' )
					{
						continue ;
					}

					if ( $availableAmounts [ $item ] == 0 && $transferAmounts [ $item ] != '' )
					{
						$itemsWithoutZero[ $item ] = $transferAmounts[ $item ] ;
					}
					if ( $availableAmounts [ $item ] != 0 && $transferAmounts [ $item ] == '' )
					{
						$itemsWithoutZero[ $item ] = $transferAmounts[ $item ] ;
					}
				}
				$this -> validateUnloadTransfer ( $itemsWithoutZero ) ;

				$fromStockObj -> saveUnload ( $toStockId , $dateTime , $availableAmounts , $transferAmounts , $description ) ;

				if ( $returnQuantityStatus == TRUE )
				{
					$this -> unloadCompanyReturns ( $companyReturns , $fromStockId , $dateTime ) ;
				}

				\MessageButler::setSuccess ( 'Unload details saved successfully.' ) ;

				\ActivityLogButler::add ( "Unload from stock " . $fromStockObj -> id . " to stock " . $toStockId . "." ) ;

				return \Redirect::action ( 'processes.transfers.selectStocksInvolved' ) ;
			} else
			{
				$this -> validateItemTransfers ( $transferAmounts ) ;

				$fromStockObj -> saveNonUnload ( $toStockId , $dateTime , $transferAmounts , $description ) ;
				\MessageButler::setSuccess ( 'Transfer recorded successfully.' ) ;

				\ActivityLogButler::add ( "Transfer from stock " . $fromStockObj -> id . " to stock " . $toStockId . "." ) ;

				return \Redirect::action ( 'processes.transfers.selectStocksInvolved' ) ;
			}
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					->
					withInput () ;
		}
	}

	private function validateSelectedTransfers ( $from , $to , $isUnloaded )
	{
		$messages = [ ] ;

		$data = [
			'from'	 => $from ,
			'to'	 => $to
			] ;

		$rules = [
			'from'	 => [
				'required' ,
				'different:to'
			] ,
			'to'	 => [
				'required'
			]
			] ;

		if ( $isUnloaded == TRUE )
		{
			$rulesIfUnload = [
				'from'	 => [
					'a_vehicle_stock'
				] ,
				'to'	 => [
					'a_normal_stock'
				]
				] ;

			$rules = array_merge_recursive ( $rules , $rulesIfUnload ) ;

			$messages = [
				'a_vehicle_stock'	 => 'Can not unload from non vehicle stock .' ,
				'a_normal_stock'	 => 'Can not unload to vehicle stock.' ,
				] ;
		}
		$validator = \ Validator:: make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	private function validateItemTransfers ( $transferAmounts )
	{
		$data = [
			'transfer_amounts' => $transferAmounts
			] ;

		$rules = [
			'transfer_amounts' => [
				'at_least_one_array_element_has_value'
			]
			] ;

		$messages = [
			'transfer_amounts.at_least_one_array_element_has_value' => 'Please enter at least one Item transfer amount.'
			] ;

		$validator = \ Validator:: make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	private function validateUnloadTransfer ( $transferAmount )
	{
		$data = [
			'transfer_amounts' => $transferAmount ,
			] ;

		$rules = [
			'transfer_amounts' => [
				'all_fields_filled'
			]
			] ;

		$messages = [
			'transfer_amounts.all_fields_filled' => 'Please unload all items in stock'
			] ;

		$validator = \ Validator:: make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public function unloadCompanyReturns ( $returnQuantityArray , $fromStockId , $dateTime )
	{
		$description = "Company Return Unload" ;
		$mainStock	 = \SystemSettingButler::getValue ( 'main_stock' ) ;
		$stock		 = new \Models\Stock() ;
		$transferId	 = $stock -> saveBasicTransferDetails ( $fromStockId , $mainStock , $dateTime , $description ) ;

		foreach ( $returnQuantityArray as $item => $returnQuantity )
		{
			if ( ! \NullHelper::isNullEmptyOrWhitespace ( $returnQuantity ) )
			{
				$companyReturnsPrunedArray[ $item ]	 = $returnQuantity ;
				$transferDetails					 = new \Models\TransferDetail() ;
				$transferDetails -> transfer_id		 = $transferId ;
				$transferDetails -> item_id			 = $item ;
				$transferDetails -> quantity		 = $companyReturnsPrunedArray[ $item ] ;
				$transferDetails -> save () ;

				\StockDetailButler::decreaseReturnQuantity ( $fromStockId , $item , $companyReturnsPrunedArray[ $item ] ) ;
				\StockDetailButler::increaseReturnQuantity ( $mainStock , $item , $companyReturnsPrunedArray[ $item ] ) ;
			}
		}
	}

}
