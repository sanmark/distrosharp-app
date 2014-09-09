<?php

namespace Controllers\Processes ;

class PurchaseController extends \Controller
{

	public function add ()
	{
		try
		{
			$this -> checkIfPaymentAccountsAreSet () ;
			$itemRows			 = \Models\Item::where ( 'is_active' , '=' , 1 ) -> orderBy ( 'buying_invoice_order' , 'ASC' ) -> get () ;
			$itemRowsForTotal	 = $itemRows -> lists ( 'id' ) ;
			$stocks				 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'Select Stock' ] ) ;
			$currentDateTime	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-dTH:i:s' ) ) ;

			$data = compact ( [
				'itemRows' ,
				'itemRowsForTotal' ,
				'stocks' ,
				'currentDateTime' ,
			] ) ;

			return \View::make ( 'web.processes.purchases.add' , $data ) ;
		} catch ( \Exceptions\NotAllPaymentAccountsAreSetException $ex )
		{
			\MessageButler::setInfo ( 'Please set Payment Source Accounts before adding purchases.' ) ;
			return \Redirect::action ( 'system.settings.paymentSourceAccounts' ) ;
		}
	}

	public function home ()
	{
		$filterValues		 = \Input::all () ;
		$buyingInvoiceRows	 = \Models\BuyingInvoice::filter ( $filterValues ) ;

		$data = [ ] ;

		$id				 = \Input::get ( 'id' ) ;
		$vendorId		 = \Input::get ( 'vendor_id' ) ;
		$fromDate		 = \Input::get ( 'from_date_time' ) ;
		$toDate			 = \Input::get ( 'to_date_time' ) ;
		$isPaid			 = \Input::get ( 'is_paid' ) ;
		$sortBy			 = \Input::get ( 'sort_by' ) ;
		$sortOrder		 = \Input::get ( 'sort_order' ) ;
		$stockId		 = \Input::get ( 'stock_id' ) ;
		$vendors		 = \Models\BuyingInvoice::distinct () -> lists ( 'vendor_id' ) ;
		$vendorSelectBox = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $vendors , [ NULL => 'Any' ] ) ;
		$stockSelectBox	 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [ '' => 'Any' ] ) ;

		$data[ 'buyingInvoiceRows' ] = $buyingInvoiceRows ;
		$data[ 'id' ]				 = $id ;
		$data[ 'vendorId' ]			 = $vendorId ;
		$data[ 'fromDate' ]			 = $fromDate ;
		$data[ 'toDate' ]			 = $toDate ;
		$data[ 'isPaid' ]			 = $isPaid ;
		$data[ 'sortBy' ]			 = $sortBy ;
		$data[ 'sortOrder' ]		 = $sortOrder ;
		$data[ 'stockId' ]			 = $stockId ;
		$data[ 'vendorSelectBox' ]	 = $vendorSelectBox ;
		$data[ 'stockSelectBox' ]	 = $stockSelectBox ;
		return \View::make ( 'web.processes.purchases.home' , $data ) ;
	}

	public function edit ( $id )
	{
		$data					 = [ ] ;
		$purchaseInvoice		 = \Models\BuyingInvoice::with ( 'financeTransfers.fromAccount' ) -> findOrFail ( $id ) ;
		$purchaseInvoiceDate	 = \Models\BuyingInvoice::findOrFail ( $id ) ;
		$ItemRows				 = \Models\Item::lists ( 'id' , 'name' ) ;
		$itemRowsForTotal		 = \Models\Item::lists ( 'id' ) ;
		$purchaseInvoiceItemRows = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
		-> get () ;
		$price					 = \Models\Item::lists ( 'current_buying_price' , 'id' ) ;
		$quantity				 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
		-> lists ( 'quantity' , 'item_id' ) ;
		$freeQuantity			 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
		-> lists ( 'free_quantity' , 'item_id' ) ;
		$expDate				 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
		-> lists ( 'exp_date' , 'item_id' ) ;
		$batchNumber			 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
		-> lists ( 'batch_number' , 'item_id' ) ;

		$vendors		 = \Models\BuyingInvoice::distinct () -> lists ( 'vendor_id' ) ;
		$vendorSelectBox = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $vendors , [NULL => 'Any' ] ) ;
		$purchaseRows	 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
		-> lists ( 'item_id' ) ;

		$purchaseDateRefill = \DateTimeHelper::dateTimeRefill ( $purchaseInvoiceDate -> date_time ) ;

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
		$data[ 'purchaseDateRefill' ]		 = $purchaseDateRefill ;
		$data[ 'itemRowsForTotal' ]			 = $itemRowsForTotal ;

		return \View::make ( 'web.processes.purchases.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{

			$purchaseItem = \Models\BuyingInvoice::findOrFail ( $id ) ;

			$purchaseItem -> date_time				 = \Input::get ( 'date_time' ) ;
			$purchaseItem -> vendor_id				 = \Input::get ( 'vendor_id' ) ;
			$purchaseItem -> printed_invoice_num	 = \Input::get ( 'printed_invoice_num' ) ;
			$purchaseItem -> completely_paid		 = \NullHelper::zeroIfNull ( \Input::get ( 'completely_paid' ) ) ;
			$purchaseItem -> other_expenses_amount	 = \Input::get ( 'other_expenses_amount' ) ;
			$purchaseItem -> other_expenses_details	 = \Input::get ( 'other_expenses_details' ) ;

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

					if ( in_array ( $itemId , \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
					-> lists ( 'item_id' ) ) )
					{

						$stockDetails = new \Models\StockDetail() ;

						$stockRow = \Models\StockDetail::where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
						-> where ( 'item_id' , '=' , $itemId )
						-> lists ( 'good_quantity' ) ;

						$previousPurchaseFreeQuantity	 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
						-> where ( 'item_id' , '=' , $itemId ) -> lists ( 'free_quantity' ) ;
						$previousPurchaseQuantity		 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
						-> where ( 'item_id' , '=' , $itemId ) -> lists ( 'quantity' ) ;

						$newQuantity1 = $quantity + $freeQuantity ;

						$preQuantity1	 = $previousPurchaseFreeQuantity[ 0 ] + $previousPurchaseQuantity[ 0 ] ;
						$newQuantity	 = ($stockRow[ 0 ] - $preQuantity1) + $newQuantity1 ;

						$stockDetails -> where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
						-> where ( 'item_id' , '=' , $itemId )
						-> update ( [ 'good_quantity' => $newQuantity ] ) ;

						$buyingItems = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
						-> where ( 'item_id' , '=' , $rows -> id )
						-> first () ;

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

						$stockRow = \Models\StockDetail::where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
						-> where ( 'item_id' , '=' , $itemId )
						-> lists ( 'good_quantity' ) ;

						$newQuantity = $stockRow[ 0 ] + ($quantity + $freeQuantity) ;

						$stockDetails -> where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
						-> where ( 'item_id' , '=' , $itemId )
						-> update ( [ 'good_quantity' => $newQuantity ] ) ;

						$buyingItems = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
						-> where ( 'item_id' , '=' , $rows -> id )
						-> first () ;

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

			$toStockId			 = \Input::get ( 'stock_id' ) ;
			$purchaseDate		 = \Input::get ( 'date_time' ) ;
			$vendorId			 = \Input::get ( 'vendor_id' ) ;
			$printedInvoiceNum	 = \Input::get ( 'printed_invoice_num' ) ;
			$isPaid				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_paid' ) ) ;
			$cashPayment		 = \Input::get ( 'cash_payment' ) ;
			$chequePayment		 = \Input::get ( 'cheque_payment' ) ;

			if ( empty ( \Input::get ( 'other_expense_amount' ) ) )
			{
				$otherExpensesAmount = 0 ;
			} else
			{
				$otherExpensesAmount = \Input::get ( 'other_expense_amount' ) ;
			}
			if ( empty ( \Input::get ( 'other_expenses_details' ) ) )
			{
				$otherExpensesDetail = '' ;
			} else
			{
				$otherExpensesDetail = \Input::get ( 'other_expenses_details' ) ;
			}

			$buyingInvoice							 = new \Models\BuyingInvoice() ;
			$buyingInvoice -> date_time				 = $purchaseDate ;
			$buyingInvoice -> vendor_id				 = $vendorId ;
			$buyingInvoice -> printed_invoice_num	 = $printedInvoiceNum ;
			$buyingInvoice -> completely_paid		 = $isPaid ;
			$buyingInvoice -> other_expenses_amount	 = $otherExpensesAmount ;
			$buyingInvoice -> other_expenses_details = $otherExpensesDetail ;
			$buyingInvoice -> stock_id				 = $toStockId ;
			$buyingInvoice -> save () ;

			$this -> savePayments ( $buyingInvoice , $cashPayment , $chequePayment ) ;

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

					$buyingItems -> invoice_id		 = $buyingInvoice -> id ;
					$buyingItems -> item_id			 = $itemId ;
					$buyingItems -> price			 = $price ;
					$buyingItems -> quantity		 = $quantity ;
					$buyingItems -> free_quantity	 = $freeQuantity ;
					$buyingItems -> exp_date		 = $expDate ;
					$buyingItems -> batch_number	 = $batchNumber ;
					$buyingItems -> save () ;

					$stockDetails = new \Models\StockDetail() ;

					$stockRow = \Models\StockDetail::where ( 'stock_id' , '=' , $toStockId )
					-> where ( 'item_id' , '=' , $itemId )
					-> lists ( 'good_quantity' ) ;

					$newQuantity = $stockRow[ 0 ] + ($quantity + $freeQuantity) ;

					$stockDetails -> where ( 'stock_id' , '=' , $toStockId )
					-> where ( 'item_id' , '=' , $itemId )
					-> update ( [ 'good_quantity' => $newQuantity ] ) ;
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

	private function checkIfPaymentAccountsAreSet ()
	{
		$paymentSourceCash	 = \SystemSettingButler::getValue ( 'payment_source_cash' ) ;
		$paymentSourceCheque = \SystemSettingButler::getValue ( 'payment_source_cheque' ) ;

		if ( \NullHelper::isNullEmptyOrWhitespace ( $paymentSourceCash ) )
		{
			throw new \Exceptions\NotAllPaymentAccountsAreSetException() ;
		}

		if ( \NullHelper::isNullEmptyOrWhitespace ( $paymentSourceCheque ) )
		{
			throw new \Exceptions\NotAllPaymentAccountsAreSetException() ;
		}
	}

	private function savePayments ( $buyingInvoice , $cashPayment , $chequePayment )
	{
		$vendorId	 = $buyingInvoice -> vendor_id ;
		$dateTime	 = $buyingInvoice -> date_time ;
		$dateTime	 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTime ) ;

		$this -> validateSavePayment ( $vendorId , $dateTime , $cashPayment , $chequePayment ) ;

		$vendor = \Models\Vendor::findOrFail ( $vendorId ) ;

		$vendorAccountId = $vendor -> finance_account_id ;
		$cashAccountId	 = \SystemSettingButler::getValue ( 'payment_source_cash' ) ;
		$chequeAccountId = \SystemSettingButler::getValue ( 'payment_source_cheque' ) ;

		$vendorAccount	 = \Models\FinanceAccount::findOrFail ( $vendorAccountId ) ;
		$cashAccount	 = \Models\FinanceAccount::findOrFail ( $cashAccountId ) ;
		$chequeAccount	 = \Models\FinanceAccount::findOrFail ( $chequeAccountId ) ;


		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $cashPayment ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $cashAccountId ;
			$financeTransfer -> to_id		 = $vendorAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $cashPayment ;

			$financeTransfer -> save () ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;

			$cashAccount -> account_balance -= $cashPayment ;
			$vendorAccount -> account_balance += $cashPayment ;

			$cashAccount -> update () ;
			$vendorAccount -> update () ;
		}

		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $chequePayment ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $chequeAccountId ;
			$financeTransfer -> to_id		 = $vendorAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $chequePayment ;

			$financeTransfer -> save () ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;

			$chequeAccount -> account_balance -= $chequePayment ;
			$vendorAccount -> account_balance += $chequePayment ;

			$chequeAccount -> update () ;
			$vendorAccount -> update () ;
		}
	}

	private function validateSavePayment ( $vendorId , $dateTime , $cashPayment , $chequePayment )
	{
		$dateTime = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTime ) ;

		$data = compact ( [
			'vendorId' ,
			'dateTime' ,
			'cashPayment' ,
			'chequePayment'
		] ) ;

		$rules = [
			'vendorId'		 => [
				'required'
			] ,
			'dateTime'		 => [
				'required' ,
				'date' ,
				'date_format:Y-m-d H:i:s'
			] ,
			'cashPayment'	 => [
				'required_without:chequePayment' ,
				'numeric'
			] ,
			'chequePayment'	 => [
				'required_without:cashPayment' ,
				'numeric'
			]
		] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
