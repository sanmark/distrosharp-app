<?php

namespace Controllers\Reports ;

class ItemSalesSummaryController extends \Controller
{

	public function home ()
	{
		$repId		 = NULL ;
		$fromDate	 = NULL ;
		$toDate		 = NULL ;

		$repSelectBox = \SellingInvoiceButler::getAllRepsForHtmlSelect () ;

		$data = compact ( [
			'repId' ,
			'fromDate' ,
			'toDate' ,
			'repSelectBox' ,
			] ) ;

		return \View::make ( 'web.reports.itemSalesSummary.home' , $data ) ;
	}

	public function filter ()
	{
		$repId		 = \Input::get ( 'rep_id' ) ;
		$fromDate	 = \Input::get ( 'from_date' ) ;
		$toDate		 = \Input::get ( 'to_date' ) ;

		$repSelectBox = \SellingInvoiceButler::getAllRepsForHtmlSelect () ;

		$items = \Models\Item::all () ;

		foreach ( $items as $index => $item )
		{
			$item -> totalFreeAmount = $item -> getTotalFreeAmountSoldForRepAndTimeRange ( $repId , $fromDate , $toDate ) ;
			$item -> totalPaidAmount = $item -> getTotalPaidAmountSoldForRepAndTimeRange ( $repId , $fromDate , $toDate ) ;

			$items[ $index ] = $item ;
		}

		$data = compact ( [
			'repId' ,
			'fromDate' ,
			'toDate' ,
			'repSelectBox' ,
			'items'
			] ) ;

		return \View::make ( 'web.reports.itemSalesSummary.home' , $data ) ;
	}

}
