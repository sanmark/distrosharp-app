<?php

namespace Controllers\Reports ;

class RepFinanceReportController extends \Controller
{

	public function filter ()
	{
		$repSelectBox = \SellingInvoiceButler::getAllRepsForHtmlSelect () ;

		$repId			 = \Input::get ( 'rep_id' ) ;
		$dateFrom		 = \Input::get ( 'date_from' ) ;
		$dateTo			 = \Input::get ( 'date_to' ) ;
		$dates			 = [ ] ;
		$totalTotal		 = NULL ;
		$totalCash		 = NULL ;
		$totalCheque	 = NULL ;
		$totalCredit	 = NULL ;
		$dateTimeFrom	 = NULL ;
		$dateTimeTo		 = NULL ;

		$dateFrom	 = \NullHelper::ifNullEmptyOrWhitespace ( $dateFrom , \DateTimeHelper::dateRefill ( date ( 'Y-m-d' , strtotime ( 'first day of this month' ) ) ) ) ;
		$dateTo		 = \NullHelper::ifNullEmptyOrWhitespace ( $dateTo , \DateTimeHelper::dateRefill ( date ( 'Y-m-d' , strtotime ( 'today' ) ) ) ) ;

		$dateTimeFrom	 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateFrom . ' 00:00:00' ) ;
		$dateTimeTo		 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTo . ' 23:59:59' ) ;

		$sellingInvoicesForDate = \SellingInvoiceButler::getSellingInvoicesForDates ( [
				'date_time_from' => $dateTimeFrom ,
				'date_time_to'	 => $dateTimeTo ,
				'rep_id'		 => $repId
			] ) ;

		foreach ( $sellingInvoicesForDate as $date => $sellingInvoices )
		{
			foreach ( $sellingInvoices as $sellingInvoice )
			{
				$dates[ $date ][ 'cash' ]	 = \ArrayHelper::getValueIfKeyExistsOrNull ( \ArrayHelper::getValueIfKeyExistsOrNull ( $dates , $date ) , 'cash' ) + $sellingInvoice -> getPaymentValueByCash () ;
				$dates[ $date ][ 'cheque' ]	 = \ArrayHelper::getValueIfKeyExistsOrNull ( \ArrayHelper::getValueIfKeyExistsOrNull ( $dates , $date ) , 'cheque' ) + $sellingInvoice -> getPaymentValueByCheque () ;
				$dates[ $date ][ 'credit' ]	 = \ArrayHelper::getValueIfKeyExistsOrNull ( \ArrayHelper::getValueIfKeyExistsOrNull ( $dates , $date ) , 'credit' ) + $sellingInvoice -> getInvoiceCredit () ;
				$dates[ $date ][ 'total' ]	 = $dates[ $date ][ 'cash' ] + $dates[ $date ][ 'cheque' ] + $dates[ $date ][ 'credit' ] ;

				$totalCash += $sellingInvoice -> getPaymentValueByCash () ;
				$totalCheque += $sellingInvoice -> getPaymentValueByCheque () ;
				$totalCredit += $sellingInvoice -> getInvoiceCredit () ;
				$totalTotal += $sellingInvoice -> getPaymentValueByCash () + $sellingInvoice -> getPaymentValueByCheque () + $sellingInvoice -> getInvoiceCredit () ;
			}
		}

		$data = compact ( [
			'repSelectBox' ,
			'repId' ,
			'dateFrom' ,
			'dateTo' ,
			'dates' ,
			'totalCash' ,
			'totalCheque' ,
			'totalCredit' ,
			'totalTotal'
			] ) ;

		return \View::make ( 'web.reports.repFinanceReport.home' , $data ) ;
	}

}
