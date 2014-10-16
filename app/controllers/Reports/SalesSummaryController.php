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

		$routesId	 = \Input::get ( 'route_id' ) ;
		$customerId	 = \Input::get ( 'customerId' ) ;
		$repId		 = \Input::get ( 'rep_id' ) ;
		$dateFrom	 = \Input::get ( 'date_from' ) ;
		$dateTo		 = \Input::get ( 'date_to' ) ;
		$invoiceNum	 = \Input::get ( 'invoice_number' ) ;


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
