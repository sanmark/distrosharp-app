<?php

class SalesSeeder extends Seeder
{

	public function run ()
	{
		$sales = [
			[
				'date_time'				 => '2014-10-02 11:54:52' ,
				'customer_id'			 => 1 ,
				'rep_id'				 => 1 ,
				'printed_invoice_number' => '1' ,
				'discount'				 => 40 ,
				'is_completely_paid'	 => 1 ,
				'selling_items'			 => [
					[
						'item_id'					 => 1 ,
						'price'						 => 100 ,
						'paid_quantity'				 => 10 ,
						'free_quantity'				 => 20 ,
						'good_return_price'			 => 100 ,
						'good_return_quantity'		 => NULL ,
						'company_return_price'		 => 100 ,
						'company_return_quantity'	 => NULL
					] ,
					[
						'item_id'					 => 2 ,
						'price'						 => 98 ,
						'paid_quantity'				 => 30 ,
						'free_quantity'				 => 40 ,
						'good_return_price'			 => 98 ,
						'good_return_quantity'		 => NULL ,
						'company_return_price'		 => 98 ,
						'company_return_quantity'	 => NULL
					] ,
					[
						'item_id'					 => 3 ,
						'price'						 => 96 ,
						'paid_quantity'				 => 50 ,
						'free_quantity'				 => 50 ,
						'good_return_price'			 => 96 ,
						'good_return_quantity'		 => NULL ,
						'company_return_price'		 => 96 ,
						'company_return_quantity'	 => NULL
					] ,
//					[
//						'item_id'					 => NULL ,
//						'price'						 => NULL ,
//						'paid_quantity'				 => NULL ,
//						'free_quantity'				 => NULL ,
//						'good_return_price'			 => NULL ,
//						'good_return_quantity'		 => NULL ,
//						'company_return_price'		 => NULL ,
//						'company_return_quantity'	 => NULL
//					] ,
				] ,
				'cash_payments'			 => [
					[
						'amount'		 => 700 ,
						'description'	 => NULL
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL
//					] ,
				] ,
				'cheque_payments'		 => [
					[
						'amount'		 => 8000 ,
						'description'	 => NULL ,
						'bank_id'		 => 1 ,
						'cheque_number'	 => '321' ,
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
				'date_time'				 => '2014-10-01 14:19:52' ,
				'customer_id'			 => 2 ,
				'rep_id'				 => 1 ,
				'printed_invoice_number' => '2' ,
				'discount'				 => 40 ,
				'is_completely_paid'	 => 0 ,
				'selling_items'			 => [
					[
						'item_id'					 => 1 ,
						'price'						 => 100 ,
						'paid_quantity'				 => 10 ,
						'free_quantity'				 => 20 ,
						'good_return_price'			 => 100 ,
						'good_return_quantity'		 => NULL ,
						'company_return_price'		 => 100 ,
						'company_return_quantity'	 => NULL
					] ,
					[
						'item_id'					 => 2 ,
						'price'						 => 98 ,
						'paid_quantity'				 => 30 ,
						'free_quantity'				 => 40 ,
						'good_return_price'			 => 98 ,
						'good_return_quantity'		 => NULL ,
						'company_return_price'		 => 98 ,
						'company_return_quantity'	 => NULL
					] ,
//					[
//						'item_id'					 => NULL ,
//						'price'						 => NULL ,
//						'paid_quantity'				 => NULL ,
//						'free_quantity'				 => NULL ,
//						'good_return_price'			 => NULL ,
//						'good_return_quantity'		 => NULL ,
//						'company_return_price'		 => NULL ,
//						'company_return_quantity'	 => NULL
//					] ,
				] ,
				'cash_payments'			 => [
					[
						'amount'		 => 900 ,
						'description'	 => NULL
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL
//					] ,
				] ,
				'cheque_payments'		 => [
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL ,
//						'bank_id'		 => NULL ,
//						'cheque_number'	 => NULL ,
//						'issued_date'	 => NULL ,
//						'payable_date'	 => NULL
//					] ,
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
				'date_time'				 => '2014-10-01 14:39:19' ,
				'customer_id'			 => 3 ,
				'rep_id'				 => 1 ,
				'printed_invoice_number' => '3' ,
				'discount'				 => NULL ,
				'is_completely_paid'	 => 1 ,
				'selling_items'			 => [
					[
						'item_id'					 => 1 ,
						'price'						 => 100 ,
						'paid_quantity'				 => 10 ,
						'free_quantity'				 => 20 ,
						'good_return_price'			 => 100 ,
						'good_return_quantity'		 => NULL ,
						'company_return_price'		 => 100 ,
						'company_return_quantity'	 => NULL
					] ,
					[
						'item_id'					 => 3 ,
						'price'						 => 96 ,
						'paid_quantity'				 => 40 ,
						'free_quantity'				 => 60 ,
						'good_return_price'			 => 96 ,
						'good_return_quantity'		 => NULL ,
						'company_return_price'		 => 96 ,
						'company_return_quantity'	 => NULL
					] ,
//					[
//						'item_id'					 => NULL ,
//						'price'						 => NULL ,
//						'paid_quantity'				 => NULL ,
//						'free_quantity'				 => NULL ,
//						'good_return_price'			 => NULL ,
//						'good_return_quantity'		 => NULL ,
//						'company_return_price'		 => NULL ,
//						'company_return_quantity'	 => NULL
//					] ,
				] ,
				'cash_payments'			 => [
					[
						'amount'		 => 800 ,
						'description'	 => NULL
					] ,
//					[
//						'amount'		 => NULL ,
//						'description'	 => NULL
//					] ,
				] ,
				'cheque_payments'		 => [
					[
						'amount'		 => 5000 ,
						'description'	 => NULL ,
						'bank_id'		 => 1 ,
						'cheque_number'	 => '987' ,
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
//				'customer_id'			 => NULL ,
//				'rep_id'				 => NULL ,
//				'printed_invoice_number' => NULL ,
//				'discount'				 => NULL ,
//				'is_completely_paid'	 => NULL ,
//				'selling_items'			 => [
//					[
//						'item_id'					 => NULL ,
//						'price'						 => NULL ,
//						'paid_quantity'				 => NULL ,
//						'free_quantity'				 => NULL ,
//						'good_return_price'			 => NULL ,
//						'good_return_quantity'		 => NULL ,
//						'company_return_price'		 => NULL ,
//						'company_return_quantity'	 => NULL
//					] ,
////					[
////						'item_id'					 => NULL ,
////						'price'						 => NULL ,
////						'paid_quantity'				 => NULL ,
////						'free_quantity'				 => NULL ,
////						'good_return_price'			 => NULL ,
////						'good_return_quantity'		 => NULL ,
////						'company_return_price'		 => NULL ,
////						'company_return_quantity'	 => NULL
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

		foreach ( $sales as $sale )
		{
			$sellingInvoice = new \Models\SellingInvoice() ;

			$sale[ 'date_time' ] = DateTimeHelper::convertTextToFormattedDateTime ( $sale[ 'date_time' ] ) ;

			$sourceStock = \Models\Stock::where ( 'incharge_id' , '=' , $sale[ 'rep_id' ] )
			-> firstOrFail () ;

			$sellingInvoice -> date_time				 = $sale[ 'date_time' ] ;
			$sellingInvoice -> customer_id				 = $sale[ 'customer_id' ] ;
			$sellingInvoice -> rep_id					 = $sale[ 'rep_id' ] ;
			$sellingInvoice -> printed_invoice_number	 = $sale[ 'printed_invoice_number' ] ;
			$sellingInvoice -> discount					 = $sale[ 'discount' ] ;
			$sellingInvoice -> is_completely_paid		 = $sale[ 'is_completely_paid' ] ;
			$sellingInvoice -> stock_id					 = $sourceStock -> id ;

			$sellingInvoice -> save () ;

			$this -> saveSellingItems ( $sellingInvoice , $sale[ 'selling_items' ] ) ;
			$this -> saveCashPayments ( $sellingInvoice , $sale[ 'cash_payments' ] ) ;
			$this -> saveChequePayments ( $sellingInvoice , $sale[ 'cheque_payments' ] ) ;
		}
	}

	private function saveSellingItems ( $sellingInvoice , $sellingItems )
	{
		foreach ( $sellingItems as $sellingItem )
		{
			$sellingItemO = new \Models\SellingItem() ;

			$sellingItemO -> selling_invoice_id		 = $sellingInvoice -> id ;
			$sellingItemO -> item_id				 = $sellingItem[ 'item_id' ] ;
			$sellingItemO -> price					 = $sellingItem[ 'price' ] ;
			$sellingItemO -> paid_quantity			 = $sellingItem[ 'paid_quantity' ] ;
			$sellingItemO -> free_quantity			 = $sellingItem[ 'free_quantity' ] ;
			$sellingItemO -> good_return_price		 = $sellingItem[ 'good_return_price' ] ;
			$sellingItemO -> good_return_quantity	 = $sellingItem[ 'good_return_quantity' ] ;
			$sellingItemO -> company_return_price	 = $sellingItem[ 'company_return_price' ] ;
			$sellingItemO -> company_return_quantity = $sellingItem[ 'company_return_quantity' ] ;

			$sellingItemO -> save () ;
		}
	}

	public function saveCashPayments ( $sellingInvoice , $cashPayments )
	{
		$sellingInvoice -> load ( 'customer.financeAccount' ) ;

		$cashTargetAccount = FinanceAccountButler::getCashTargetAccount () ;

		foreach ( $cashPayments as $cashPayment )
		{
			$financeTransfer = new \Models\FinanceTransfer() ;

			$financeTransfer -> from_id		 = $sellingInvoice -> customer -> financeAccount -> id ;
			$financeTransfer -> to_id		 = $cashTargetAccount -> id ;
			$financeTransfer -> date_time	 = $sellingInvoice -> date_time ;
			$financeTransfer -> amount		 = $cashPayment[ 'amount' ] ;
			$financeTransfer -> description	 = $cashPayment[ 'description' ] ;

			$financeTransfer -> save () ;

			$sellingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;
		}
	}

	public function saveChequePayments ( $sellingInvoice , $chequePayments )
	{
		$sellingInvoice -> load ( 'customer.financeAccount' ) ;
		$chequeTargetAccount = FinanceAccountButler::getChequeTargetAccount () ;

		foreach ( $chequePayments as $chequePayment )
		{
			$financeTransfer = new \Models\FinanceTransfer() ;

			$financeTransfer -> from_id		 = $sellingInvoice -> customer -> financeAccount -> id ;
			$financeTransfer -> to_id		 = $chequeTargetAccount -> id ;
			$financeTransfer -> date_time	 = $sellingInvoice -> date_time ;
			$financeTransfer -> amount		 = $chequePayment[ 'amount' ] ;
			$financeTransfer -> description	 = $chequePayment[ 'description' ] ;

			$financeTransfer -> save () ;

			$sellingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;

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
