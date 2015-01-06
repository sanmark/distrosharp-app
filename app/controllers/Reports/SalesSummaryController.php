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
			$dateFrom = date ( 'Y-m-d' , mktime ( 0 , 0 , 0 , date ( 'm' ) , date ( 'd' ) - 3 , date ( 'Y' ) ) ) ;
		}


		if ( ! $dateTo )
		{
			$dateTo = date ( 'Y-m-d' ) ;
		}

		$totalOfDiscountSum		 = 0 ;
		$totalOfInvoiceSum		 = 0 ;
		$invoiceByCashTotalSum	 = 0 ;
		$invoiceByChequeTotalSum = 0 ;
		$invoiceByCreditTotalSum = 0 ;
		$creditPaymentsByCash	 = 0 ;
		$creditPaymentsByCheque	 = 0 ;
		$invoiceSubAmountTotal	 = 0 ;
		$totalAmount			 = 0 ;

		foreach ( $sellingInvoices as $key => $sellingInvoice )
		{
			$paymentValueByCash		 = $sellingInvoice -> getPaymentValueByCash () ;
			$paymentValueByCheque	 = $sellingInvoice -> getPaymentValueByCheque () ;
			$lateCreditPayments		 = $sellingInvoice -> getLateCreditPayments () ;
			$invoiceTotal			 = $sellingInvoice -> getInvoiceTotal () ;
			$invoiceCredit			 = $sellingInvoice -> getInvoiceCredit ( $paymentValueByCash , $paymentValueByCheque, $invoiceTotal ) ;
			$subTotal				 = $sellingInvoice -> getSubTotal ( $paymentValueByCash , $paymentValueByCheque , $invoiceCredit ) ;
			$total					 = $sellingInvoice -> getTotal ( $paymentValueByCash , $paymentValueByCheque , $invoiceCredit ) ;
			$totalCollection		 = $sellingInvoice -> getTotalCollection ( $lateCreditPayments[ 'amount_cash' ] , $lateCreditPayments[ 'amount_cheque' ] , $paymentValueByCash , $paymentValueByCheque ) ;

			$totalOfDiscountSum += $sellingInvoice -> discount ;
			$invoiceByCashTotalSum += $paymentValueByCash ;
			$invoiceByChequeTotalSum += $paymentValueByCheque ;
			$invoiceByCreditTotalSum += $invoiceCredit ;
			$creditPaymentsByCash += $lateCreditPayments[ 'amount_cash' ] ;
			$creditPaymentsByCheque += $lateCreditPayments[ 'amount_cheque' ] ;
			$totalOfInvoiceSum += $totalCollection ;
			$invoiceSubAmountTotal += $subTotal ;
			$totalAmount += $total ;

			$sellingInvoice -> subTotal				 = $subTotal ;
			$sellingInvoice -> total				 = $total ;
			$sellingInvoice -> paymentValueByCash	 = $paymentValueByCash ;
			$sellingInvoice -> paymentValueByCheque	 = $paymentValueByCheque ;
			$sellingInvoice -> invoiceCredit		 = $invoiceCredit ;
			$sellingInvoice -> lateCreditPayments	 = $lateCreditPayments ;
			$sellingInvoice -> totalCollection		 = $totalCollection ;
			$sellingInvoice -> invoiceTotal			 = $invoiceTotal ;

			$sellingInvoices[ $key ] = $sellingInvoice ;
		}

		$totalNetAmount = $invoiceSubAmountTotal - $totalOfDiscountSum ;

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
			'invoiceSubAmountTotal' ,
			'totalNetAmount' ,
			'totalAmount' ,
			'creditPaymentsByCash' ,
			'creditPaymentsByCheque'
			] ) ;

		return \View::make ( 'web.reports.sales.home' , $data ) ;
	}

}
