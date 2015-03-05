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
 
		$firstDate	 = \SellingInvoiceButler::getFirstSellingInvoiceDate () ;
		$today		 = date ( 'Y-m-d' ) ;

		$fromDate	 = \NullHelper::ifNullEmptyOrWhitespace ( $fromDate , $firstDate ) ;
		$toDate		 = \NullHelper::ifNullEmptyOrWhitespace ( $toDate , $today ) ;

		$fromDateTime	 = \DateTimeHelper::convertTextToFormattedDateTime ( $fromDate . ' 00:00:00' ) ;
		$toDateTime		 = \DateTimeHelper::convertTextToFormattedDateTime ( $toDate . ' 23:59:59' ) ;
		$repIds			 = [ $repId ] ;

		if ( \NullHelper::isNullEmptyOrWhitespace ( $repId ) )
		{
			$repIds = \Models\SellingInvoice::distinct ()
				-> lists ( 'rep_id' ) ;
		}

		$sellingInvoices = \Models\SellingInvoice::whereIn ( 'rep_id' , $repIds )
			-> whereBetween ( 'date_time' , [$fromDateTime , $toDateTime ] )
			-> get () ;

		
		$freeAmountValueSum	 = 0 ;
		$paidAmountValueSum	 = 0 ;

		foreach ( $items as $index => $item )
		{
			$result = $item -> getTotalPaidAndFreeAmountSoldForRepAndTimeRange ( $sellingInvoices ) ;

			$item -> totalFreeAmount = $result[ 'free' ] ;

			$item -> totalPaidAmount = $result[ 'paid' ] ;

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
			'items' ,
			'freeAmountValueSum' ,
			'paidAmountValueSum'
			] ) ;

		return \View::make ( 'web.reports.itemSalesSummary.home' , $data ) ;
	}

}
