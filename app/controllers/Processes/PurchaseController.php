<?php

namespace Controllers\Processes ;

class PurchaseController extends \Controller
{

	public function add ()
	{

		$data = [ ] ;

		$itemRows = \Models\Item::all () ;

		$data[ 'itemRows' ] = $itemRows ;

		return \View::make ( 'web.processes.purchases.add' , $data ) ;
	}

	public function save ()
	{
		try
		{


			$purchaseDate		 = \Input::get ( 'purchase_date' ) ;
			$vendorId			 = \Input::get ( 'vendor_id' ) ;
			$printedInvoiceNum	 = \Input::get ( 'printed_invoice_num' ) ;
			$isPaid				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_paid' ) ) ;
			if ( empty ( \Input::get ( 'other_expense_amount' ) ) )
			{
				$otherExpenseAmount = 0 ;
			} else
			{
				$otherExpenseAmount = \Input::get ( 'other_expense_amount' ) ;
			}
			if ( empty ( \Input::get ( 'other_expense_total' ) ) )
			{
				$otherExpenseTotal = 0 ;
			} else
			{
				$otherExpenseTotal = \Input::get ( 'other_expense_total' ) ;
			}
			$buyingInvoices							 = new \Models\BuyingInvoice() ;
			$buyingInvoices -> date					 = $purchaseDate ;
			$buyingInvoices -> vendor_id			 = $vendorId ;
			$buyingInvoices -> printed_invoice_num	 = $printedInvoiceNum ;
			$buyingInvoices -> completely_paid		 = $isPaid ;
			$buyingInvoices -> other_expenses_amount = $otherExpenseAmount ;
			$buyingInvoices -> other_expenses_total	 = $otherExpenseTotal ;
			$buyingInvoices -> save () ;

			$countRows = \Models\Item::all () ;

			foreach ( $countRows as $rows )
			{

				if ( \Input::get ( 'quantity_' . $rows -> id ) != '' )
				{
					$itemId	 = \Input::get ( 'item_id_' . $rows -> id ) ;
					$price		 = \Input::get ( 'buying_price_' . $rows -> id ) ;
					$quantity	 = \Input::get ( 'quantity_' . $rows -> id ) ;
					if ( \Input::get ( 'free_quantity_' . $rows -> id ) == '' )
					{
						$freeQuantity = 0 ;
					} else
					{
						$freeQuantity = \Input::get ( 'free_quantity_' . $rows -> id ) ;
					}
					if ( \Input::get ( 'exp_date_' . $rows -> id ) == '' )
					{
						$expDate = '0000-00-00' ;
					} else
					{
						$expDate = \Input::get ( 'exp_date_' . $rows -> id ) ;
					}
					$batchNumber = \Input::get ( 'batch_number_' . $rows -> id ) ;

					$buyingItems = new \Models\BuyingItem() ;

					$buyingItems -> invoice_id	 = $buyingInvoices -> id ;
					$buyingItems -> item_id		 = $itemId ;

					$buyingItems -> price			 = $price ;
					$buyingItems -> quantity		 = $quantity ;
					$buyingItems -> free_quantity	 = $freeQuantity ;
					$buyingItems -> exp_date		 = $expDate ;
					$buyingItems -> batch_number	 = $batchNumber ;
					$buyingItems -> save () ;

					$stockDetails = new \Models\StockDetail() ;



					$stockRow = \Models\StockDetail::where ( 'item_id' , '=' , $itemId )
					-> lists ( 'good_quantity' ) ;


					$newQuantity = $stockRow[ 0 ] + ($quantity + $freeQuantity) ;


					$stockDetails -> where ( 'item_id' , '=' , $itemId )
					-> update ( ['good_quantity' => $newQuantity ] ) ;
				}
			}
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
