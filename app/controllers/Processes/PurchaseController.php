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

	public function home ()
	{
		$data = [ ] ;

		$filterValues		 = \Input::all () ;
		$buyingInvoiceRows	 = \Models\BuyingInvoice::filter ( $filterValues ) ;
		$id					 = \Input::get ( 'id' ) ;
		$vendorId			 = \Input::get ( 'vendor_id' ) ;
		$date				 = \Input::get ( 'date' ) ;
		$isPaid				 = \Input::get ( 'is_paid' ) ;
		$sortBy				 = \Input::get ( 'sort_by' ) ;
		$sortOrder			 = \Input::get ( 'sort_order' ) ;
		$vendors			 = \Models\BuyingInvoice::distinct () -> lists ( 'vendor_id' ) ;
		$vendorSelectBox	 = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $vendors ) ;




		$data[ 'buyingInvoiceRows' ] = $buyingInvoiceRows ;
		$data[ 'id' ]				 = $id ;
		$data[ 'vendorId' ]			 = $vendorId ;
		$data[ 'date' ]				 = $date ;
		$data[ 'isPaid' ]			 = \NullHelper::zeroIfNull ( $isPaid ) ;
		$data[ 'sortBy' ]			 = $sortBy ;
		$data[ 'sortOrder' ]		 = $sortOrder ;
		$data[ 'vendorSelectBox' ]	 = $vendorSelectBox ;
		return \View::make ( 'web.processes.purchases.home' , $data ) ;
	}

	public function edit ( $id )
	{
		$data					 = [ ] ;
		$purchaseInvoice		 = \Models\BuyingInvoice::findOrFail ( $id ) ;
		$ItemRows				 = \Models\Item::lists ( 'id' , 'name' ) ;
		$purchaseInvoiceItemRows = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> get () ;
		$price					 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> lists ( 'price' , 'item_id' ) ;
		$quantity				 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> lists ( 'quantity' , 'item_id' ) ;
		$freeQuantity			 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> lists ( 'free_quantity' , 'item_id' ) ;
		$expDate				 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> lists ( 'exp_date' , 'item_id' ) ;
		$batchNumber			 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> lists ( 'batch_number' , 'item_id' ) ;

		$vendors		 = \Models\BuyingInvoice::distinct () -> lists ( 'vendor_id' ) ;
		$vendorSelectBox = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $vendors ) ;
		$purchaseRows	 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> lists ( 'item_id' ) ;


		$data[ 'purchaseInvoice' ]			 = $purchaseInvoice ;
		$data[ 'ItemRows' ]					 = $ItemRows ;
		$data[ 'vendorSelectBox' ]			 = $vendorSelectBox ;
		$data[ 'purchaseInvoiceItemRows' ]	 = $purchaseInvoiceItemRows ;
		$data[ 'purchaseRows' ]				 = $purchaseRows ;
		$data[ 'price' ]					 = $price ;
		$data[ 'quantity' ]					 = $quantity ;
		$data[ 'freeQuantity' ]				 = $freeQuantity ;
		$data[ 'expDate' ]					 = $expDate ;
		$data[ 'batchNumber' ]				 = $batchNumber ;

		return \View::make ( 'web.processes.purchases.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{

			$purchaseItem = \Models\BuyingInvoice::findOrFail ( $id ) ;

			$purchaseItem -> date					 = \Input::get ( 'date' ) ;
			$purchaseItem -> vendor_id				 = \Input::get ( 'vendor_id' ) ;
			$purchaseItem -> printed_invoice_num	 = \Input::get ( 'printed_invoice_num' ) ;
			$purchaseItem -> completely_paid		 = \NullHelper::zeroIfNull ( \Input::get ( 'completely_paid' ) ) ;
			$purchaseItem -> other_expenses_amount	 = \Input::get ( 'other_expenses_amount' ) ;
			$purchaseItem -> other_expenses_total	 = \Input::get ( 'other_expenses_total' ) ;

			$purchaseItem -> update () ;


			$countRows = \Models\Item::all () ;

			foreach ( $countRows as $rows )
			{
				if ( \Input::get ( 'quantity_' . $rows -> id ) != '' )
				{
					$itemId		 = \Input::get ( 'item_id_' . $rows -> id ) ;
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

					if ( in_array ( $itemId , \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> lists ( 'item_id' ) ) )
					{
						
						
						$stockDetails = new \Models\StockDetail() ;



						$stockRow = \Models\StockDetail::where ( 'item_id' , '=' , $itemId )
						-> lists ( 'good_quantity' ) ;

						$previousPurchaseFreeQuantity	 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> where ( 'item_id' , '=' , $itemId ) -> lists ( 'free_quantity' ) ;
						$previousPurchaseQuantity		 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> where ( 'item_id' , '=' , $itemId ) -> lists ( 'quantity' ) ;

						$newQuantity1	 = $quantity + $freeQuantity ;
						
						$preQuantity1	 = $previousPurchaseFreeQuantity[ 0 ] + $previousPurchaseQuantity[ 0 ] ;
						$newQuantity	 = $stockRow[0]-$preQuantity1+$newQuantity1 ;

						$stockDetails -> where ( 'item_id' , '=' , $itemId )
						-> update ( ['good_quantity' => $newQuantity ] ) ;
						

						$buyingItems = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> where ( 'item_id' , '=' , $rows -> id ) -> first () ;

						$buyingItems -> item_id = $itemId ;

						$buyingItems -> price			 = $price ;
						$buyingItems -> quantity		 = $quantity ;
						$buyingItems -> free_quantity	 = $freeQuantity ;
						$buyingItems -> exp_date		 = $expDate ;
						$buyingItems -> batch_number	 = $batchNumber ;
						$buyingItems -> update () ;
						
						
					} else
					{
						$buyingItems				 = new \Models\BuyingItem() ;
						$buyingItems -> invoice_id	 = $id ;
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
						
						
						$buyingItems = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id ) -> where ( 'item_id' , '=' , $rows -> id ) -> first () ;

						$buyingItems -> item_id = $itemId ;

						$buyingItems -> price			 = $price ;
						$buyingItems -> quantity		 = $quantity ;
						$buyingItems -> free_quantity	 = $freeQuantity ;
						$buyingItems -> exp_date		 = $expDate ;
						$buyingItems -> batch_number	 = $batchNumber ;
						$buyingItems -> update () ;

						
					}
				}
			}
			return \Redirect::action ( 'processes.purchases.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
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
					$itemId		 = \Input::get ( 'item_id_' . $rows -> id ) ;
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

			return \Redirect::action ( 'processes.purchases.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
