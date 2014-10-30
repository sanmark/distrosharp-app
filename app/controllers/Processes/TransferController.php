<?php

namespace Controllers\Processes ;

class TransferController extends \Controller
{

	public function all ()
	{
		$filterValues	 = \Input::all () ;
		$transfers		 = \Models\Transfer::filter ( $filterValues ) ;
		$stocks			 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Any' ] ) ;

		$fromStockId	 = \Input::get ( 'from_stock_id' ) ;
		$toStockId		 = \Input::get ( 'to_stock_id' ) ;
		$dateTimeFrom	 = \Input::get ( 'date_time_from' ) ;
		$dateTimeTo		 = \Input::get ( 'date_time_to' ) ;

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
			$from		 = \Input::get ( 'from' ) ;
			$to			 = \Input::get ( 'to' ) ;
			$isUnloaded	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_unload' ) ) ;

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
			if ( $isUnloaded == TRUE )
			{
				$stockObj		 = \Models\Stock::findOrFail ( $fromStockId ) ;
				$isUnloadable	 = $stockObj -> isUnloadable () ;

				if ( $isUnloadable == FALSE )
				{
					\MessageButler::setError ( 'Please load stock/make at least one sale before unload' ) ;
					return \Redirect::back ()
							-> withInput () ;
				}
			}
			$this -> validateSelectedTransfers ( $fromStockId , $toStockId , $isUnloaded ) ;

			$fromStock	 = \Models\Stock::with ( 'stockDetails' ) -> findOrFail ( $fromStockId ) ;
			$toStock	 = \Models\Stock::with ( 'stockDetails' ) -> findOrFail ( $toStockId ) ;
			$items		 = \Models\Item::where ( 'is_active' , '=' , '1' )
				-> orderBy ( 'buying_invoice_order' , 'ASC' )
				-> get () ;
			$dateTime	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;

			$fromStockDetails	 = $fromStock -> goodQuantities () ;
			$toStockDetails		 = $toStock -> goodQuantities () ;

			$data = compact ( [
				'fromStock' ,
				'toStock' ,
				'items' ,
				'dateTime' ,
				'fromStockDetails' ,
				'toStockDetails' ,
				'isUnloaded'
				] ) ;

			return \View::make ( 'web.processes.transfers.add' , $data ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::action ( 'processes.transfers.selectStocksInvolved' )
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function save ( $fromStockId , $toStockId )
	{
		try
		{
			$items				 = \Models\Item::where ( 'is_active' , '=' , '1' )
				-> orderBy ( 'buying_invoice_order' , 'ASC' )
				-> lists ( 'id' ) ;
			$dateTime			 = \Input::get ( 'date_time' ) ;
			$availableAmounts	 = \Input::get ( 'availale_amounts' ) ;
			$transferAmounts	 = \Input::get ( 'transfer_amounts' ) ;
			$description		 = \Input::get ( 'description' ) ;
			$unload				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_unload' ) ) ;
			$fromStockObj		 = \Models\Stock::findOrFail ( $fromStockId ) ;

			if ( $unload == TRUE )
			{
				$itemsWithoutZero = [ ] ;
				foreach ( $items as $item )
				{
					if ( $availableAmounts[ $item ] == 0 && $transferAmounts[ $item ] == '' )
					{
						
					}
					if ( $availableAmounts[ $item ] == 0 && $transferAmounts[ $item ] != '' )
					{
						$itemsWithoutZero[ $item ] = $transferAmounts[ $item ] ;
					}
					if ( $availableAmounts[ $item ] != 0 && $transferAmounts[ $item ] == '' )
					{
						$itemsWithoutZero[ $item ] = $transferAmounts[ $item ] ;
					}
				}
				$this -> validateUnloadTransfer ( $itemsWithoutZero ) ;
				$fromStockObj -> saveUnload ( $toStockId , $dateTime , $availableAmounts , $transferAmounts , $description ) ;
				\MessageButler::setSuccess ( 'Unload details saved successfully.' ) ;

				return \Redirect::action ( 'processes.transfers.selectStocksInvolved' ) ;
			} else
			{
				$this -> validateItemTransfers ( $transferAmounts ) ;
				$fromStockObj -> saveNonUnload ( $toStockId , $dateTime , $transferAmounts , $description ) ;
				\MessageButler::setSuccess ( 'Transfer recorded successfully.' ) ;

				return \Redirect::action ( 'processes.transfers.selectStocksInvolved') ;
			}
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
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
		$validator = \Validator::make ( $data , $rules , $messages ) ;

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

		$validator = \Validator::make ( $data , $rules , $messages ) ;

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

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
