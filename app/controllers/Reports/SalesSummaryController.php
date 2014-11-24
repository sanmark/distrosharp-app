<?php

namespace Controllers\Reports ;

class SalesSummaryController extends \Controller
{

	public function all ()
	{
		$filterValues	 = \Input::all () ;
		$sellingInvoices = \Models\SellingInvoice::filterForSalesSummary ( $filterValues ) ;

		$routes		 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Routes' ] ) ;
		$customers	 = [NULL => 'Select Route First' ] ;

		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes )
			-> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;

		$routesId	 = \InputButler::get ( 'route_id' ) ;
		$customerId	 = \InputButler::get ( 'customerId' ) ;
		$repId		 = \InputButler::get ( 'rep_id' ) ;
		$dateFrom	 = \InputButler::get ( 'date_from' ) ;
		$dateTo		 = \InputButler::get ( 'date_to' ) ;
		$invoiceNum	 = \InputButler::get ( 'invoice_number' ) ;
		 
		if ( ! $dateFrom )
		{
			$dateFrom = date ( 'Y-m-d' , mktime ( 0 , 0 , 0 , date ( 'm' ) , date ( 'd' ) - 3 , date ( 'Y' ) ) );
		}


		if ( ! $dateTo )
		{
			$dateTo = date ( 'Y-m-d' );
		}

		$totalOfDiscountSum		 = 0 ;
		$totalOfInvoiceSum		 = 0 ;
		$invoiceByCashTotalSum	 = 0 ;
		$invoiceByChequeTotalSum = 0 ;
		$invoiceByCreditTotalSum = 0 ;
		$invoiceGrossAmountTotal = 0 ;

		foreach ( $sellingInvoices as $sellingInvoice )
		{
			$totalOfDiscountSum += $sellingInvoice -> discount ;
			$invoiceByCashTotalSum += $sellingInvoice -> getPaymentValueByCash () ;
			$invoiceByChequeTotalSum += $sellingInvoice -> getPaymentValueByCheque () ;
			$invoiceByCreditTotalSum += $sellingInvoice -> getInvoiceCredit () ;
			$totalOfInvoiceSum += $sellingInvoice -> getInvoiceTotal () ;
			$invoiceGrossAmountTotal += $sellingInvoice -> getGrossAmount () ;
		}

		$totalNetAmount = $invoiceGrossAmountTotal - $totalOfDiscountSum ;

		$data = compact ( [
			'routes' ,
			'customers' ,
			'reps' ,
			'routesId' ,
			'customerId' ,
			'repId' ,
			'dateFrom' ,
			'dateTo' ,
			'invoiceNum' ,
			'sellingInvoices' ,
			'totalOfDiscountSum' ,
			'totalOfInvoiceSum' ,
			'invoiceByCashTotalSum' ,
			'invoiceByChequeTotalSum' ,
			'invoiceByCreditTotalSum' ,
			'invoiceGrossAmountTotal' ,
			'totalNetAmount'
			] ) ;

		return \View::make ( 'web.reports.sales.home' , $data ) ;
	}

}
