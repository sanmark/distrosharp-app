<?php

class HomeController extends BaseController
{

	public function showHome ()
	{
		$dailyWorkflow			 = $this -> updateDailyWorkFlow () ;
		$stockSummery			 = $this -> stockSummery () ;
		$lastThirtyDaysPurchase	 = $this -> lastThirtyDaysPurchase () ;
		$lastThirtyDaysSales	 = $this -> lastThirtyDaysSalses () ;

		return \View::make ( 'web.home' )
				-> with ( 'dailyWorkflow' , $dailyWorkflow )
				-> with ( 'stockSummery' , $stockSummery )
				-> with ( 'lastThirtyDaysPurchase' , $lastThirtyDaysPurchase )
				-> with ( 'lastThirtyDaysSales' , $lastThirtyDaysSales ) ;
	}

	public function refreshHome ()
	{
		$view = \View::make ( 'web.home' ) ;

		$stockSummery			 = $this -> stockSummery () ;
		$lastThirtyDaysPurchase	 = $this -> lastThirtyDaysPurchase () ;
		$lastThirtyDaysSales	 = $this -> lastThirtyDaysSalses () ;

		$view	 = $view -> with ( 'stockSummery' , $stockSummery ) ;
		$view	 = $view -> with ( 'lastThirtyDaysPurchase' , $lastThirtyDaysPurchase ) ;
		$view	 = $view -> with ( 'lastThirtyDaysSales' , $lastThirtyDaysSales ) ;

		$theSubmitedForm = \Input::get ( 'submitedForm' ) ;
		if ( $theSubmitedForm == 'dailyWorkFlow' )
		{
			$dailyWorkflow	 = $this -> updateDailyWorkFlow () ;
			$view			 = $view -> with ( 'dailyWorkflow' , $dailyWorkflow ) ;
		}

		return $view ;
	}

	private function updateDailyWorkFlow ()
	{
		$theDate = Input::get ( 'the_date' ) ;
		$theDate = NullHelper::ifNullEmptyOrWhitespace ( $theDate , date ( 'Y-m-d' ) ) ;
		$theDate = DateTimeHelper::convertTextToFormattedDateTime ( $theDate , 'Y-m-d' ) ;

		$dateRange = [$theDate . ' 00:00:00' , $theDate . ' 23:59:59' ] ;

		$dailyWorkflow = [ ] ;

		$dailyWorkflow[ 'today' ] = $theDate ;

		$dailyWorkflow[ 'buyingInvoiceCount' ] = Models\BuyingInvoice::whereBetween ( 'date_time' , $dateRange ) -> count () ;

		$dailyWorkflow[ 'stockTransferCount' ] = Models\Transfer::whereBetween ( 'date_time' , $dateRange ) -> count () ;

		$dailyWorkflow[ 'salesCount' ] = \Models\SellingInvoice::whereBetween ( 'date_time' , $dateRange ) -> count () ;

		$dailyWorkflow[ 'financeAccountVerificationCount' ] = Models\FinanceAccountVerification::whereBetween ( 'date_time' , $dateRange ) -> count () ;

		$dailyWorkflow[ 'stockVerificationCount' ] = Models\StockConfirmation::whereBetween ( 'date_time' , $dateRange ) -> count () ;

		return $dailyWorkflow ;
	}

	private function stockSummery ()
	{
		$objStockDetail	 = \Models\StockDetail::all () ;
		$goodQntVal		 = 0 ;
		$returnQntVal	 = 0 ;
		$totalStockVal	 = 0 ;
		foreach ( $objStockDetail as $stockItem )
		{
			$goodQntVal		 = $goodQntVal + ($stockItem -> good_quantity * $stockItem -> item -> current_buying_price) ;
			$returnQntVal	 = $returnQntVal + ($stockItem -> return_quantity * $stockItem -> item -> current_buying_price) ;
		}
		$totalStockVal					 = $goodQntVal + $returnQntVal ;
		$returnArr						 = [ ] ;
		$returnArr [ 'goodQntVal' ]		 = $goodQntVal ;
		$returnArr [ 'returnQntVal' ]	 = $returnQntVal ;
		$returnArr [ 'totalStockVal' ]	 = $totalStockVal ;
		return $returnArr ;
	}

	private function lastThirtyDaysPurchase ()
	{
		$currentDateTime = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-dTH:i:s' ) ) ;
		$startDate		 = date ( 'Y-m-d' , strtotime ( 'today - 30 days' ) ) ;

		$purchaseTotalCost = $this -> getTotalPurchaseCostByDateRange ( $startDate . ' 00:00:00' , $currentDateTime ) ;

		$totalPaid					 = 0 ;
		$totalOfIncompletePayments	 = $this -> totalOfIncompleteBillPaymentsByDateRange ( $startDate . ' 00:00:00' , $currentDateTime ) ;
		$totalOfCompleatePayments	 = $this -> totalOfCompleateBillPaymentsByDateRange ( $startDate . ' 00:00:00' , $currentDateTime ) ;

		$totalPaid = $totalOfIncompletePayments + $totalOfCompleatePayments ;

		$balanceToBePay = $purchaseTotalCost - $totalPaid ;

		$returnArr						 = [ ] ;
		$returnArr[ 'totalCost' ]		 = $purchaseTotalCost ;
		$returnArr[ 'totalPaid' ]		 = $totalPaid ;
		$returnArr[ 'balanceToBePay' ]	 = $balanceToBePay ;

		return $returnArr ;
	}

	private function getTotalPurchaseCostByDateRange ( $startDate , $endDate )
	{
		$objBuyingInvoices	 = \Models\BuyingInvoice::whereBetween ( 'date_time' , [$startDate , $endDate ] )
			-> get () ;
		$totalCost			 = 0 ;
		foreach ( $objBuyingInvoices as $buyingInvoice )
		{
			foreach ( $buyingInvoice -> buyingItems as $item )
			{
				$totalCost = $totalCost + ($item -> price * $item -> quantity) ;
			}
		}
		return $totalCost ;
	}

	private function totalOfIncompleteBillPaymentsByDateRange ( $startDate , $endDate )
	{
		$buyingInvoiceWithBalance	 = \Models\BuyingInvoice::whereBetween ( 'date_time' , [$startDate , $endDate ] )
			-> where ( 'completely_paid' , '=' , 0 )
			-> get () ;
		$totalOfIncompleatePayments	 = 0 ;
		foreach ( $buyingInvoiceWithBalance as $invoice )
		{
			foreach ( $invoice -> financeTransfers as $payment )
			{
				$totalOfIncompleatePayments = $totalOfIncompleatePayments + $payment -> amount ;
			}
		}
		return $totalOfIncompleatePayments ;
	}

	private function totalOfCompleateBillPaymentsByDateRange ( $startDate , $endDate )
	{
		$buyingInvoiceWithBalance	 = \Models\BuyingInvoice::whereBetween ( 'date_time' , [$startDate , $endDate ] )
			-> where ( 'completely_paid' , '=' , 1 )
			-> get () ;
		$totalOfCompleatePayments	 = 0 ;
		foreach ( $buyingInvoiceWithBalance as $invoice )
		{
			foreach ( $invoice -> financeTransfers as $payment )
			{
				$totalOfCompleatePayments = $totalOfCompleatePayments + $payment -> amount ;
			}
		}
		return $totalOfCompleatePayments ;
	}

	private function lastThirtyDaysSalses ()
	{
		$currentDateTime = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
		$startDate		 = date ( 'Y-m-d' , strtotime ( 'today - 30 days' ) ) . ' 00:00:00' ;

		$sellingInvoices = \Models\SellingInvoice::whereBetween ( 'date_time' , [$startDate , $currentDateTime ] )
			-> get () ;


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
		$returnArr							 = [ ] ;
		$returnArr [ 'totalOfDiscountSum' ]	 = $totalOfDiscountSum ;
		$returnArr [ 'totalOfTotalPaid' ]	 = $totalOfTotalPaid ;
		$returnArr [ 'totalOfTotalCredit' ]	 = $totalOfTotalCredit ;
		$returnArr [ 'totalOfInvoiceSum' ]	 = $totalOfInvoiceSum ;
		return $returnArr ;
	}

}
