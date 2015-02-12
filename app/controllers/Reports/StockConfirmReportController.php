<?php

namespace Controllers\Reports ;

class StockConfirmReportController extends \Controller
{

	public function home ()
	{
		$stocksList			 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [0 => 'All' ] ) ;
		$toDate				 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
		$fromDate			 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( '-7 days midnight' ) ) ) ;
		$stockConfirmations	 = \Models\StockConfirmation::orderBy ( 'date_time' , 'DESC' )
			-> whereBetween ( 'date_time' , [$fromDate , $toDate ] )
			-> get () ;
		$selectedStock		 = 0 ;
		$data				 = compact ( [
			'stocksList' ,
			'selectedStock' ,
			'stockConfirmations' ,
			'fromDate' ,
			'toDate'
			] ) ;
		return \View::make ( 'web.reports.stockConfirmReport.home' , $data ) ;
	}

	public function filter ()
	{
		$stocksList			 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [0 => 'All' ] ) ;
		$selectedStock		 = \Input::get ( 'stock' ) ;
		$toDate				 = \Input::get ( 'to_date_time' ) ;
		$fromDate			 = \Input::get ( 'from_date_time' ) ;
		$filterValues		 = \Input::all () ;
		$stockConfirmations	 = new \Models\StockConfirmation() ;
		$stockConfirmations	 = $stockConfirmations -> stockConfirmationFilter ( $filterValues ) ;
		$data				 = compact ( [
			'stocksList' ,
			'selectedStock' ,
			'stockConfirmations' ,
			'fromDate' ,
			'toDate'
			] ) ;
		return \View::make ( 'web.reports.stockConfirmReport.home' , $data ) ;
	}

	public function view ( $id )
	{
		$stockDetails				 = \Models\StockConfirmation::findOrFail ( $id ) ;
		$stockConfirmationDetails	 = new \Models\StockConfirmationDetail() ;
		$stockConfirmationDetails	 = $stockConfirmationDetails -> getConfirmationDetails ( $id ) ;


		$totalBuyingValueOfGoodQnt		 = [ ] ;
		$totalSellingValueOfGoodQnt		 = [ ] ;
		$totalBuyingValueOfReturnQnt	 = [ ] ;
		$totalSellingValueOfReturnQnt	 = [ ] ;

		foreach ( $stockConfirmationDetails as $stockValue )
		{
			$totalBuyingValueOfGoodQnt[ $stockValue -> item_id ]	 = $stockValue -> good_item_quantity * $stockValue[ 'item' ] -> current_buying_price ;
			$totalSellingValueOfGoodQnt[ $stockValue -> item_id ]	 = $stockValue -> good_item_quantity * $stockValue[ 'item' ] -> current_selling_price ;
			$totalBuyingValueOfReturnQnt[ $stockValue -> item_id ]	 = $stockValue -> return_item_quantity * $stockValue[ 'item' ] -> current_buying_price ;
			$totalSellingValueOfReturnQnt[ $stockValue -> item_id ]	 = $stockValue -> return_item_quantity * $stockValue[ 'item' ] -> current_selling_price ;
		}

		$goodItemQuantityTotal				 = 0 ;
		$goodItemQuantityBuyingValueTotal	 = 0 ;
		$goodItemQuantitySellingValueTotal	 = 0 ;
		$returnItemQuantityTotal			 = 0 ;
		$returnItemQuantityBuyingValueTotal	 = 0 ;
		$returnItemQuantitySellingValueTotal = 0 ;

		foreach ( $stockConfirmationDetails as $value )
		{
			$goodItemQuantityTotal+=$value -> good_item_quantity ;
			$returnItemQuantityTotal+=$value -> return_item_quantity ;
			$goodItemQuantityBuyingValueTotal+=$totalBuyingValueOfGoodQnt[ $value -> item_id ] ;
			$goodItemQuantitySellingValueTotal+=$totalSellingValueOfGoodQnt[ $value -> item_id ] ;
			$returnItemQuantityBuyingValueTotal+=$totalBuyingValueOfReturnQnt[ $value -> item_id ] ;
			$returnItemQuantitySellingValueTotal+=$totalSellingValueOfReturnQnt[ $value -> item_id ] ;
		}

		$data = compact ( [
			'stockConfirmationDetails' ,
			'dateTime' ,
			'stock' ,
			'stockDetails' ,
			'totalBuyingValueOfGoodQnt' ,
			'totalSellingValueOfGoodQnt' ,
			'totalBuyingValueOfReturnQnt' ,
			'totalSellingValueOfReturnQnt' ,
			'goodItemQuantityTotal' ,
			'goodItemQuantityBuyingValueTotal' ,
			'goodItemQuantitySellingValueTotal' ,
			'returnItemQuantityTotal' ,
			'returnItemQuantityBuyingValueTotal' ,
			'returnItemQuantitySellingValueTotal'
			] ) ;
		return \View::make ( 'web.reports.stockConfirmReport.view' , $data ) ;
	}

}
