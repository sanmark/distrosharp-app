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
		$data						 = compact ( [
			'stockConfirmationDetails' ,
			'dateTime' ,
			'stock',
			'stockDetails'
			] ) ;
		return \View::make ( 'web.reports.stockConfirmReport.view' , $data ) ;
	}

}
