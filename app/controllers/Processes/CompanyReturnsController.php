<?php

namespace Controllers\Processes ;

class CompanyReturnsController extends \Controller
{

	public function add ()
	{
		$vendorsList		 = \Models\Vendor::getArrayForHtmlSelect ( 'id' , 'name' , [null => 'Select Vendor' ] ) ;
		$normalStockId		 = \StockTypeButler::getNormalStockType () ;
		$stocksListObject	 = \Models\Stock::where ( 'stock_type_id' , '=' , $normalStockId -> id ) ;
		$stocksList			 = \Models\Stock::getArrayForHtmlSelectByRequestObject ( 'id' , 'name' , $stocksListObject , [null => 'Select Stock' ] ) ;
		$currentDateTime	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-dTH:i:s' ) ) ;
		$data				 = compact ( [
			'vendorsList' ,
			'stocksList' ,
			'currentDateTime'
			] ) ;
		return \View::make ( 'web.processes.companyReturns.add' , $data ) ;
	}

	public function save ()
	{
		try
		{
			$dateTime			 = \InputButler::get ( 'date_time' ) ;
			$vendorId			 = \InputButler::get ( 'vendor_id' ) ;
			$returnNumber		 = \InputButler::get ( 'return_number' ) ;
			$stock				 = \InputButler::get ( 'from_stock' ) ;
			$returnItems		 = \Input::get ( 'itemName' ) ;
			$returnItemIds		 = \Input::get ( 'itemId' ) ;
			$returnBuyingPrice	 = \Input::get ( 'itemPrice' ) ;
			$returnQuantity		 = \Input::get ( 'itemQuantity' ) ;
			$items				 = \Models\StockDetail::where ( 'stock_id' , '=' , $stock ) -> where ( 'return_quantity' , '!=' , 0 ) -> lists ( 'item_id' ) ;

			$companyReturn = new \Models\CompanyReturn() ;

			$companyReturn -> printed_return_number	 = $returnNumber ;
			$companyReturn -> vendor_id				 = $vendorId ;
			$companyReturn -> from_stock_id			 = $stock ;
			$companyReturn -> date_time				 = $dateTime ;

			$companyReturn -> save () ;

			$companyReturnId = $companyReturn -> id ;


			foreach ( $items as $key )
			{
				if ( isset ( $returnItemIds[ $key ] ) )
				{
					\StockDetailButler::decreaseReturnQuantity ( $stock , $returnItemIds[ $key ] , $returnQuantity[ $key ] ) ;

					$companyReturnDetails = new \Models\CompanyReturnDetail() ;

					$companyReturnDetails -> return_id		 = $companyReturnId ;
					$companyReturnDetails -> item_id		 = $returnItemIds[ $key ] ;
					$companyReturnDetails -> buying_price	 = $returnBuyingPrice[ $key ] ;
					$companyReturnDetails -> quantity		 = $returnQuantity[ $key ] ;
					$this -> validateCompanyReturnItems ( $returnBuyingPrice[ $key ] , $returnQuantity[ $key ] ) ;
					$companyReturnDetails -> save () ;
				}
			}
			\MessageButler::setSuccess ( "Company Returns was added successfully" ) ;

			return \Redirect::action ( 'processes.companyReturns.add' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	private function validateCompanyReturnItems ( $buyingPrice , $quantity )
	{
		$messages = [ ] ;

		$data = [
			'buyingPrice'	 => $buyingPrice ,
			'quantity'		 => $quantity
			] ;

		$rules = [
			'buyingPrice'	 => [
				'required' ,
				'numeric'
			] ,
			'quantity'		 => [
				'required' ,
				'numeric'
			]
			] ;

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public function view ()
	{
		$filterValues		 = \Input::all () ;
		$companyReturnObject = new \Models\CompanyReturn() ;
		$companyReturns		 = $companyReturnObject -> companyReturnfilter ( $filterValues ) ;
		$vendorList			 = \Models\Vendor::getArrayForHtmlSelect ( 'id' , 'name' , [null => 'Select Vendor' ] ) ;
		$normalStockId		 = \StockTypeButler::getNormalStockType () ;
		$stockListObject	 = \Models\Stock::where ( 'stock_type_id' , '=' , $normalStockId -> id ) ;
		$stockList			 = \Models\Stock::getArrayForHtmlSelectByRequestObject ( 'id' , 'name' , $stockListObject , [null => 'Select Stock' ] ) ;
		$fromDate			 = \NullHelper::ifNullEmptyOrWhitespace ( \InputButler::get ( 'from_date' ) , date ( 'Y-m-d H:i:s' , strtotime ( '-7 days midnight' ) ) ) ;
		$toDate				 = \NullHelper::ifNullEmptyOrWhitespace ( \InputButler::get ( 'to_date' ) , date ( 'Y-m-d H:i:s' ) ) ;
		$fromDate			 = \DateTimeHelper::dateTimeRefill ( $fromDate ) ;
		$toDate				 = \DateTimeHelper::dateTimeRefill ( $toDate ) ;
		$selectedVendor		 = \InputButler::get ( 'vendor_id' ) ;
		$selectedStock		 = \InputButler::get ( 'from_stock_id' ) ;

		$data = compact ( [
			'companyReturns' ,
			'fromDate' ,
			'toDate' ,
			'vendorList' ,
			'stockList' ,
			'selectedVendor' ,
			'selectedStock'
			] ) ;
		return \View::make ( 'web.processes.companyReturns.view' , $data ) ;
	}

	public function viewItems ( $id )
	{
		$returnDetails		 = \Models\CompanyReturn::findOrFail ( $id ) ;
		$companyReturnItems	 = \Models\CompanyReturnDetail::where ( 'return_id' , '=' , $id ) -> get () ;
		$data				 = compact ( [
			'companyReturnItems' ,
			'returnDetails'
			] ) ;
		return \View::make ( 'web.processes.companyReturns.viewItems' , $data ) ;
	}

	public function getQuantity ()
	{
		$itemId			 = \Input::get ( 'itemId' ) ;
		$stockId		 = \Input::get ( 'stockId' ) ;
		$returnQuantity	 = \StockDetailButler::getStockDetailByStocIdkAndItemId ( $stockId , $itemId ) ;
		return $returnQuantity -> return_quantity ;
	}

}
