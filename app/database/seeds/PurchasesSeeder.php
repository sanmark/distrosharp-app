<?php

class PurchasesSeeder extends Seeder
{

	public function run ()
	{
		$purchases = [
			[
				'date_time'				 => '2014-10-01 09:34:52' ,
				'vendor_id'				 => 1 ,
				'printed_invoice_num'	 => '1' ,
				'completely_paid'		 => 1 ,
				'other_expenses_amount'	 => NULL ,
				'other_expenses_details' => NULL ,
				'stock_id'				 => 1 ,
				'buying_items'			 => [
					[
						'item_id'		 => 1 ,
						'price'			 => 98 ,
						'quantity'		 => 1 ,
						'free_quantity'	 => 4 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
					[
						'item_id'		 => 2 ,
						'price'			 => 96 ,
						'quantity'		 => 2 ,
						'free_quantity'	 => 5 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
					[
						'item_id'		 => 3 ,
						'price'			 => 94 ,
						'quantity'		 => 3 ,
						'free_quantity'	 => 6 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
//					[
//						'item_id'		 => NULL ,
//						'price'			 => NULL ,
//						'quantity'		 => NULL ,
//						'free_quantity'	 => NULL ,
//						'exp_date'		 => NULL ,
//						'batch_number'	 => NULL
//					] ,
				] ,
				'cash_payments'			 => [
					[
						'amount'		 => 72 ,
						'description'	 => NULL
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL
//					] ,
				] ,
				'cheque_payments'		 => [
					[
						'amount'		 => 500 ,
						'description'	 => NULL ,
						'bank_id'		 => 1 ,
						'cheque_number'	 => '123' ,
						'issued_date'	 => '2014-10-01' ,
						'payable_date'	 => '2014-10-08'
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL ,
//						'bank_id'		 => NULL ,
//						'cheque_number'	 => NULL ,
//						'issued_date'	 => NULL ,
//						'payable_date'	 => NULL
//					] ,
				]
			] ,
			[
				'date_time'				 => '2014-10-01 10:16:07' ,
				'vendor_id'				 => 2 ,
				'printed_invoice_num'	 => '2' ,
				'completely_paid'		 => 0 ,
				'other_expenses_amount'	 => NULL ,
				'other_expenses_details' => NULL ,
				'stock_id'				 => 1 ,
				'buying_items'			 => [
					[
						'item_id'		 => 1 ,
						'price'			 => 98 ,
						'quantity'		 => 10 ,
						'free_quantity'	 => 20 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
					[
						'item_id'		 => 2 ,
						'price'			 => 96 ,
						'quantity'		 => 30 ,
						'free_quantity'	 => 40 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
					[
						'item_id'		 => 3 ,
						'price'			 => 94 ,
						'quantity'		 => 50 ,
						'free_quantity'	 => 60 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
//					[
//						'item_id'		 => NULL ,
//						'price'			 => NULL ,
//						'quantity'		 => NULL ,
//						'free_quantity'	 => NULL ,
//						'exp_date'		 => NULL ,
//						'batch_number'	 => NULL
//					] ,
				] ,
				'cash_payments'			 => [
					[
						'amount'		 => 500 ,
						'description'	 => NULL
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL
//					] ,
				] ,
				'cheque_payments'		 => [
					[
						'amount'		 => 800 ,
						'description'	 => NULL ,
						'bank_id'		 => 2 ,
						'cheque_number'	 => '456' ,
						'issued_date'	 => '2014-10-02' ,
						'payable_date'	 => '2014-10-09'
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL ,
//						'bank_id'		 => NULL ,
//						'cheque_number'	 => NULL ,
//						'issued_date'	 => NULL ,
//						'payable_date'	 => NULL
//					] ,
				]
			] ,
			[
				'date_time'				 => '2014-10-01 11:15:49' ,
				'vendor_id'				 => 3 ,
				'printed_invoice_num'	 => '3' ,
				'completely_paid'		 => 0 ,
				'other_expenses_amount'	 => NULL ,
				'other_expenses_details' => NULL ,
				'stock_id'				 => 1 ,
				'buying_items'			 => [
					[
						'item_id'		 => 1 ,
						'price'			 => 98 ,
						'quantity'		 => 100 ,
						'free_quantity'	 => 200 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
					[
						'item_id'		 => 3 ,
						'price'			 => 94 ,
						'quantity'		 => 500 ,
						'free_quantity'	 => 600 ,
						'exp_date'		 => NULL ,
						'batch_number'	 => NULL
					] ,
//					[
//						'item_id'		 => NULL ,
//						'price'			 => NULL ,
//						'quantity'		 => NULL ,
//						'free_quantity'	 => NULL ,
//						'exp_date'		 => NULL ,
//						'batch_number'	 => NULL
//					] ,
				] ,
				'cash_payments'			 => [
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL
//					] ,
				] ,
				'cheque_payments'		 => [
					[
						'amount'		 => 47000 ,
						'description'	 => NULL ,
						'bank_id'		 => 3 ,
						'cheque_number'	 => '789' ,
						'issued_date'	 => '2014-10-03' ,
						'payable_date'	 => '2014-10-10'
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL ,
//						'bank_id'		 => NULL ,
//						'cheque_number'	 => NULL ,
//						'issued_date'	 => NULL ,
//						'payable_date'	 => NULL
//					] ,
				]
			] ,
//			[
//				'date_time'				 => NULL ,
//				'vendor_id'				 => NULL ,
//				'printed_invoice_num'	 => NULL ,
//				'completely_paid'		 => FALSE ,
//				'other_expenses_amount'	 => NULL ,
//				'other_expenses_details' => NULL ,
//				'stock_id'				 => NULL ,
//				'buying_items'			 => [
//					[
//						'item_id'		 => NULL ,
//						'price'			 => NULL ,
//						'quantity'		 => NULL ,
//						'free_quantity'	 => NULL ,
//						'exp_date'		 => NULL ,
//						'batch_number'	 => NULL
//					] ,
////					[
////						'item_id'		 => NULL ,
////						'price'			 => NULL ,
////						'quantity'		 => NULL ,
////						'free_quantity'	 => NULL ,
////						'exp_date'		 => NULL ,
////						'batch_number'	 => NULL
////					] ,
//				] ,
//				'cash_payments'			 => [
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL
//					] ,
////					[
////						'amount'		 => NULL ,
////						'description'	 => NULL
////					] ,
//				] ,
//				'cheque_payments'		 => [
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL ,
//						'bank_id'		 => NULL ,
//						'cheque_number'	 => NULL ,
//						'issued_date'	 => NULL ,
//						'payable_date'	 => NULL
//					] ,
////					[
////						'amount'		 => NULL ,
////						'description'	 => NULL ,
////						'bank_id'		 => NULL ,
////						'cheque_number'	 => NULL ,
////						'issued_date'	 => NULL ,
////						'payable_date'	 => NULL
////					] ,
//				]
//			] ,
		] ;

		foreach ( $purchases as $purchase )
		{
			$buyingInvoice = new \Models\BuyingInvoice() ;

			$purchase[ 'date_time' ] = DateTimeHelper::convertTextToFormattedDateTime ( $purchase[ 'date_time' ] ) ;

			$buyingInvoice -> date_time				 = $purchase[ 'date_time' ] ;
			$buyingInvoice -> vendor_id				 = $purchase[ 'vendor_id' ] ;
			$buyingInvoice -> printed_invoice_num	 = $purchase[ 'printed_invoice_num' ] ;
			$buyingInvoice -> completely_paid		 = $purchase[ 'completely_paid' ] ;
			$buyingInvoice -> other_expenses_amount	 = $purchase[ 'other_expenses_amount' ] ;
			$buyingInvoice -> other_expenses_details = $purchase[ 'other_expenses_details' ] ;
			$buyingInvoice -> stock_id				 = $purchase[ 'stock_id' ] ;

			$buyingInvoice -> save () ;

			$this -> saveBuyingItems ( $buyingInvoice , $purchase[ 'buying_items' ] ) ;
			$this -> saveCashPayments ( $buyingInvoice , $purchase[ 'cash_payments' ] ) ;
			$this -> saveChequePayments ( $buyingInvoice , $purchase[ 'cheque_payments' ] ) ;
		}
	}

	private function saveBuyingItems ( $buyingInvoice , $buyingItems )
	{
		foreach ( $buyingItems as $buyingItem )
		{
			$buyingItemO = new Models\BuyingItem() ;

			$buyingItemO -> invoice_id		 = $buyingInvoice -> id ;
			$buyingItemO -> item_id			 = $buyingItem[ 'item_id' ] ;
			$buyingItemO -> price			 = $buyingItem[ 'price' ] ;
			$buyingItemO -> quantity		 = $buyingItem[ 'quantity' ] ;
			$buyingItemO -> free_quantity	 = $buyingItem[ 'free_quantity' ] ;
			$buyingItemO -> exp_date		 = $buyingItem[ 'exp_date' ] ;
			$buyingItemO -> batch_number	 = $buyingItem[ 'batch_number' ] ;

			$buyingItemO -> save () ;
		}
	}

	private function saveCashPayments ( $buyingInvoice , $cashPayments )
	{
		$buyingInvoice -> load ( 'vendor.financeAccount' ) ;

		$cashSourceAccount = FinanceAccountButler::getCashSourceAccount () ;

		foreach ( $cashPayments as $cashPayment )
		{
			$financeTransfer = new Models\FinanceTransfer() ;

			$financeTransfer -> from_id		 = $cashSourceAccount -> id ;
			$financeTransfer -> to_id		 = $buyingInvoice -> vendor -> financeAccount -> id ;
			$financeTransfer -> date_time	 = $buyingInvoice -> date_time ;
			$financeTransfer -> amount		 = $cashPayment[ 'amount' ] ;
			$financeTransfer -> description	 = $cashPayment[ 'description' ] ;

			$financeTransfer -> save () ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;
		}
	}

	private function saveChequePayments ( $buyingInvoice , $chequePayments )
	{
		$buyingInvoice -> load ( 'vendor.financeAccount' ) ;

		$chequeSourceAccount = FinanceAccountButler::getChequeSourceAccount () ;

		foreach ( $chequePayments as $chequePayment )
		{
			$financeTransfer = new Models\FinanceTransfer() ;

			$financeTransfer -> from_id		 = $chequeSourceAccount -> id ;
			$financeTransfer -> to_id		 = $buyingInvoice -> vendor -> financeAccount -> id ;
			$financeTransfer -> date_time	 = $buyingInvoice -> date_time ;
			$financeTransfer -> amount		 = $chequePayment[ 'amount' ] ;
			$financeTransfer -> description	 = $chequePayment[ 'description' ] ;

			$financeTransfer -> save () ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;

			$chequeDetail = new Models\ChequeDetail() ;

			$chequeDetail -> finance_transfer_id = $financeTransfer -> id ;
			$chequeDetail -> bank_id			 = $chequePayment[ 'bank_id' ] ;
			$chequeDetail -> cheque_number		 = $chequePayment[ 'cheque_number' ] ;
			$chequeDetail -> issued_date		 = $chequePayment[ 'issued_date' ] ;
			$chequeDetail -> payable_date		 = $chequePayment[ 'payable_date' ] ;

			$chequeDetail -> save () ;
		}
	}

}
