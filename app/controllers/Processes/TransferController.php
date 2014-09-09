<?php

namespace Controllers\Processes ;

class TransferController extends \Controller
{

	public function selectStocksInvolved ()
	{
		$data = [ ] ;

		$stocksHtmlSelect = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Any' ] ) ;

		$data[ 'stocksHtmlSelect' ] = $stocksHtmlSelect ;

		return \View::make ( 'web.processes.transfers.selectStocksInvolved' , $data ) ;
	}

	public function pSelectStocksInvolved ()
	{
		try
		{
			$from	 = \Input::get ( 'from' ) ;
			$to		 = \Input::get ( 'to' ) ;

			$this -> validateSelectedTransfers ( $from , $to ) ;

			return \Redirect::action ( 'processes.transfers.add' , [$from , $to ] ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	public function add ( $fromStockId , $toStockId )
	{
		try
		{
			$this -> validateSelectedTransfers ( $fromStockId , $toStockId ) ;

			$fromStock	 = \Models\Stock::with ( 'stockDetails' ) -> findOrFail ( $fromStockId ) ;
			$toStock	 = \Models\Stock::with ( 'stockDetails' ) -> findOrFail ( $toStockId ) ;
			$items		 = \Models\Item::orderBy('buying_invoice_order', 'ASC')->get();

			$fromStockDetails	 = $fromStock -> goodQuantities () ;
			$toStockDetails		 = $toStock -> goodQuantities () ;

			$data[ 'fromStock' ]		 = $fromStock ;
			$data[ 'toStock' ]			 = $toStock ;
			$data[ 'items' ]			 = $items ;
			$data[ 'fromStockDetails' ]	 = $fromStockDetails ;
			$data[ 'toStockDetails' ]	 = $toStockDetails ;

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
			$dateTime			 = \Input::get ( 'date_time' ) ;
			$availableAmounts	 = \Input::get ( 'availale_amounts' ) ;
			$transferAmounts	 = \Input::get ( 'transfer_amounts' ) ;

			$this -> validateItemTransfers ( $transferAmounts ) ;

			$transfer = new \Models\Transfer() ;

			$transfer -> from_stock_id	 = $fromStockId ;
			$transfer -> to_stock_id	 = $toStockId ;
			$transfer -> date_time		 = $dateTime ;

			$transfer -> save () ;

			$transferId = $transfer -> id ;

			foreach ( $transferAmounts as $itemId => $transferAmount )
			{
				if ( ! \NullHelper::isNullEmptyOrWhitespace ( $transferAmount ) )
				{
					$transferDetail = new \Models\TransferDetail() ;

					$transferDetail -> transfer_id	 = $transferId ;
					$transferDetail -> item_id		 = $itemId ;
					$transferDetail -> quantity		 = $transferAmount ;

					$transferDetail -> save () ;

					\StockDetailButler::decreaseGoodQuantity ( $fromStockId , $itemId , $transferAmount ) ;
					\StockDetailButler::increaseGoodQuantity ( $toStockId , $itemId , $transferAmount ) ;
				}
			}

			\MessageButler::setSuccess ( 'Transfer recorded successfully.' ) ;

			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	private function validateSelectedTransfers ( $from , $to )
	{
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

		$validator = \Validator::make ( $data , $rules ) ;

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

}
