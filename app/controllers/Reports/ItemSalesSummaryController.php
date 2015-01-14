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
		$repId		 = \InputButler::get ( 'rep_id' ) ;
		$fromDate	 = \InputButler::get ( 'from_date' ) ;
		$toDate		 = \InputButler::get ( 'to_date' ) ;

		$repSelectBox = \SellingInvoiceButler::getAllRepsForHtmlSelect () ;

		$items = \Models\Item::all () ;


		$freeAmountValueSum	 = 0 ;
		$paidAmountValueSum	 = 0 ;

		foreach ( $items as $index => $item )
		{
			$item -> totalFreeAmount = $item -> getTotalFreeAmountSoldForRepAndTimeRange ( $repId , $fromDate , $toDate ) ;

			$item -> totalPaidAmount = $item -> getTotalPaidAmountSoldForRepAndTimeRange ( $repId , $fromDate , $toDate ) ;

			$item -> selling_price = $item -> current_selling_price ;


			$freeAmountValueSum	 = $freeAmountValueSum + $item -> totalFreeAmount * $item -> selling_price ;
			$paidAmountValueSum	 = $paidAmountValueSum + $item -> totalPaidAmount * $item -> selling_price ;

			$items[ $index ] = $item ;
		}

		$data = compact ( [
			'repId' ,
			'fromDate' ,
			'toDate' ,
			'repSelectBox' ,
			'items',
			'freeAmountValueSum',
			'paidAmountValueSum'
			
			] ) ;

		return \View::make ( 'web.reports.itemSalesSummary.home' , $data ) ;
	}

}
