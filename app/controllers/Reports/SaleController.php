<?php

namespace Controllers\Reports ;

class SaleController extends \Controller
{

	public function all ()
	{

		$filterValues			 = \Input::all () ;
		$sellingInvoices		 = \Models\SellingInvoice::filter ( $filterValues ) ;
		$customerSelectBox		 = \Models\Customer::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Any' ] ) ;
		$routeSelectBox			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Any' ] ) ;
		$repSelectBox			 = \SellingInvoiceButler::getAllRepsForHtmlSelect () ;
		$isActiveSelectBox		 = \ViewButler::htmlSelectAnyYesNo () ;
		$id						 = \InputButler::get ( 'id' ) ;
		$printedInvoiceNumber	 = \InputButler::get ( 'printed_invoice_number' ) ;
		$dateTimeFrom			 = \InputButler::get ( 'date_time_from' ) ;
		$dateTimeTo				 = \InputButler::get ( 'date_time_to' ) ;
		$customerId				 = \InputButler::get ( 'customer_id' ) ;
		$repId					 = \InputButler::get ( 'rep_id' ) ;
		$isCompletelyPaid		 = \InputButler::get ( 'is_completely_paid' ) ;
		$routeId				 = \InputButler::get ( 'route_id' ) ;


		$creditBalance		 = [ ] ;
		$totalPayment		 = [ ] ;
		$invoiceTotalSum	 = [ ] ;
		$totalOfTotalPaid	 = 0 ;
		$totalOfTotalCredit	 = 0 ;
		$totalOfInvoiceSum	 = 0 ;
		$totalOfDiscountSum	 = 0 ;
		for ( $i = 0 ; $i < count ( $sellingInvoices ) ; $i ++ )
		{
			$creditBalance[ $sellingInvoices[ $i ][ 'id' ] ]	 = \Models\SellingInvoice::find ( $sellingInvoices[ $i ][ 'id' ] ) -> getInvoiceBalance () ;
			$totalPayment[ $sellingInvoices[ $i ][ 'id' ] ]		 = \Models\SellingInvoice::find ( $sellingInvoices[ $i ][ 'id' ] ) -> getTotalPaymentValue () ;
			$invoiceTotalSum[ $sellingInvoices[ $i ][ 'id' ] ]	 = \Models\SellingInvoice::find ( $sellingInvoices[ $i ][ 'id' ] ) -> getInvoiceTotal () ;


			$totalOfDiscountSum	 = $totalOfDiscountSum + $sellingInvoices[ $i ][ 'discount' ] ;
			$totalOfTotalPaid	 = $totalOfTotalPaid + $totalPayment[ $sellingInvoices[ $i ][ 'id' ] ] ;
			$totalOfTotalCredit	 = $totalOfTotalCredit + $creditBalance[ $sellingInvoices[ $i ][ 'id' ] ] ;
			$totalOfInvoiceSum	 = $totalOfInvoiceSum + $invoiceTotalSum[ $sellingInvoices[ $i ][ 'id' ] ] ;
		}

		if ( is_null ( $dateTimeFrom ) )
		{
			$dateTimeFrom = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( '-7 days midnight' ) ) ) ;
		}

		if ( is_null ( $dateTimeTo ) )
		{
			$dateTimeTo = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( 'today 23:59:59' ) ) ) ;
		}

		//var_dump($customerId);

		$data = compact ( [
			'sellingInvoices' ,
			'customerSelectBox' ,
			'repSelectBox' ,
			'isActiveSelectBox' ,
			'id' ,
			'printedInvoiceNumber' ,
			'dateTimeFrom' ,
			'dateTimeTo' ,
			'customerId' ,
			'repId' ,
			'isCompletelyPaid' ,
			'creditBalance' ,
			'totalPayment' ,
			'totalOfTotalPaid' ,
			'totalOfTotalCredit' ,
			'totalOfInvoiceSum' ,
			'totalOfDiscountSum' ,
			'routeSelectBox' ,
			'routeId'
			] ) ;
		return \View::make ( 'web.reports.sales.view' , $data ) ;
	}

}
