<?php

namespace Controllers\Processes ;

class PurchaseController extends \Controller
{

	public function add ()
	{

		$data = [ ] ;

		$item_rows = \Models\Item::all () ;

		$data[ 'item_rows' ] = $item_rows ;

		return \View::make ( 'web.processes.purchases.add' , $data ) ;
	}

	public function save ()
	{
		try
		{


			$purchase_date		 = \Input::get ( 'purchase_date' ) ;
			$vendor_id			 = \Input::get ( 'vendor_id' ) ;
			$printed_invoice_num = \Input::get ( 'printed_invoice_num' ) ;
			$is_paid			 = \NullHelper::zeroIfNull ( \Input::get ( 'is_paid' ) ) ;
			if ( empty ( \Input::get ( 'other_expense_amount' ) ) )
			{
				$other_expense_amount = 0 ;
			} else
			{
				$other_expense_amount = \Input::get ( 'other_expense_amount' ) ;
			}
			if ( empty ( \Input::get ( 'other_expense_total' ) ) )
			{
				$other_expense_total = 0 ;
			} else
			{
				$other_expense_total = \Input::get ( 'other_expense_total' ) ;
			}
			$buying_invoices							 = new \Models\Buying_invoice() ;
			$buying_invoices -> date					 = $purchase_date ;
			$buying_invoices -> vendor_id				 = $vendor_id ;
			$buying_invoices -> printed_invoice_num		 = $printed_invoice_num ;
			$buying_invoices -> completely_paid			 = $is_paid ;
			$buying_invoices -> other_expenses_amount	 = $other_expense_amount ;
			$buying_invoices -> other_expenses_total	 = $other_expense_total ;
			$buying_invoices -> save () ;

			$count_rows = \Models\Item::all () ;

			foreach ( $count_rows as $rows )
			{

				if ( \Input::get ( 'quantity_' . $rows -> id ) != '' )
				{
					$item_id	 = \Input::get ( 'item_id_' . $rows -> id ) ;
					$price		 = \Input::get ( 'buying_price_' . $rows -> id ) ;
					$quantity	 = \Input::get ( 'quantity_' . $rows -> id ) ;
					if ( \Input::get ( 'free_quantity_' . $rows -> id ) == '' )
					{
						$free_quantity = 0 ;
					} else
					{
						$free_quantity = \Input::get ( 'free_quantity_' . $rows -> id ) ;
					}
					if ( \Input::get ( 'exp_date_' . $rows -> id ) == '' )
					{
						$exp_date = '0000-00-00' ;
					} else
					{
						$exp_date = \Input::get ( 'exp_date_' . $rows -> id ) ;
					}
					$batch_number = \Input::get ( 'batch_number_' . $rows -> id ) ;

					$buying_items = new \Models\Buying_item() ;

					$buying_items -> invoice_id	 = $buying_invoices -> id ;
					$buying_items -> item_id	 = $item_id ;

					$buying_items -> price			 = $price ;
					$buying_items -> quantity		 = $quantity ;
					$buying_items -> free_quantity	 = $free_quantity ;
					$buying_items -> exp_date		 = $exp_date ;
					$buying_items -> batch_number	 = $batch_number ;
					$buying_items -> save () ;

					$stock_details = new \Models\Stock_detail() ;



					$stock_row = \Models\Stock_detail::where ( 'item_id' , '=' , $item_id )
					-> lists ( 'good_quantity' ) ;


					$new_quantity = $stock_row[ 0 ] + ($quantity + $free_quantity) ;


					$stock_details -> where ( 'item_id' , '=' , $item_id )
					-> update ( ['good_quantity' => $new_quantity ] ) ;
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
