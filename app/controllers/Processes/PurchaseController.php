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
			$notVehicleList		 = \Models\Stock::whereNotIn ( 'stock_type_id' , ['2' ] ) ;
			$stocks				 = \Models\Stock::getArrayForHtmlSelectByRequestObject ( 'id' , 'name' , $notVehicleList , [ '' => 'Select Stock' ] ) ;
			$activeVendors		 = \Models\Vendor::where ( 'is_active' , '=' , 1 ) -> lists ( 'id' ) ;
			$vendorList			 = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $activeVendors , ['' => 'Select Vendor' ] ) ;
			$currentDateTime	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-dTH:i:s' ) ) ;
			$banksList			 = \Models\Bank::where ( 'is_active' , '=' , TRUE ) -> getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

			$data = compact ( [
				'itemRows' ,
				'itemRowsForTotal' ,
				'stocks' ,
				'currentDateTime' ,
				'vendorList' ,
				'banksList' ,
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

		$id				 = \InputButler::get ( 'id' ) ;
		$vendorId		 = \InputButler::get ( 'vendor_id' ) ;
		$fromDate		 = \InputButler::get ( 'from_date_time' ) ;
		$toDate			 = \InputButler::get ( 'to_date_time' ) ;
		$isPaid			 = \InputButler::get ( 'is_paid' ) ;
		$sortBy			 = \InputButler::get ( 'sort_by' ) ;
		$sortOrder		 = \InputButler::get ( 'sort_order' ) ;
		$stockId		 = \InputButler::get ( 'stock_id' ) ;
		$vendors		 = \Models\BuyingInvoice::distinct () -> lists ( 'vendor_id' ) ;
		$vendorSelectBox = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $vendors , [ NULL => 'Any' ] ) ;
		$stockSelectBox	 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [ '' => 'Any' ] ) ;

		$lineTotalArray = [ ] ;
		foreach ( $buyingInvoiceRows as $key )
		{
			$price			 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $key -> id )
				-> lists ( 'price' ) ;
			$quantity		 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $key -> id )
				-> lists ( 'quantity' ) ;
			$finalLineTotal	 = 0 ;
			for ( $i = 0 ; $i < count ( $quantity ) ; $i ++ )
			{
				$sum[ $key -> id ]	 = $price[ $i ] * $quantity[ $i ] ;
				$finalLineTotal		 = $finalLineTotal + $sum[ $key -> id ] ;
			}
			$lineTotalArray[ $key -> id ] = $finalLineTotal ;
		}
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
		$data[ 'lineTotalArray' ]	 = $lineTotalArray ;
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

		$vendors		 = \Models\Vendor::where ( 'is_active' , '=' , 1 ) -> lists ( 'id' ) ;
		$vendorSelectBox = \Models\Vendor::getArrayForHtmlSelectByIds ( 'id' , 'name' , $vendors , [NULL => 'Any' ] ) ;
		$purchaseRows	 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
			-> lists ( 'item_id' ) ;

		$purchaseDateRefill	 = \DateTimeHelper::dateTimeRefill ( $purchaseInvoiceDate -> date_time ) ;
		$banksList			 = \Models\Bank::where ( 'is_active' , '=' , TRUE ) -> getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

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
		$data[ 'banksList' ]				 = $banksList ;

		return \View::make ( 'web.processes.purchases.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			$purchaseItem = \Models\BuyingInvoice::findOrFail ( $id ) ;

			$purchaseItem -> date_time				 = \InputButler::get ( 'date_time' ) ;
			$purchaseItem -> vendor_id				 = \InputButler::get ( 'vendor_id' ) ;
			$purchaseItem -> printed_invoice_num	 = \InputButler::get ( 'printed_invoice_num' ) ;
			$purchaseItem -> completely_paid		 = \NullHelper::zeroIfNull ( \InputButler::get ( 'completely_paid' ) ) ;
			$purchaseItem -> other_expenses_amount	 = \InputButler::get ( 'other_expenses_amount' ) ;
			$purchaseItem -> other_expenses_details	 = \InputButler::get ( 'other_expenses_details' ) ;
			$cashPayment							 = \InputButler::get ( 'new_cash_payment' ) ;
			$chequePayment							 = \InputButler::get ( 'new_cheque_payment' ) ;
			$chequePaymentBankId					 = \InputButler::get ( 'cheque_payment_bank_id' ) ;
			$chequePaymentChequeNumber				 = \InputButler::get ( 'cheque_payment_cheque_number' ) ;
			$chequePaymentIssuedDate				 = \InputButler::get ( 'cheque_payment_issued_date' ) ;
			$chequePaymentPayableDate				 = \InputButler::get ( 'cheque_payment_payable_date' ) ;

			$this -> validateSaveNewPaymentBefore ( $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;
			$purchaseItem -> update () ;
			$this -> saveNewPayments ( $purchaseItem , $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;

			$countRows = \Models\Item::all () ;

			foreach ( $countRows as $rows )
			{
				$itemId			 = \InputButler::get ( 'item_id_' . $rows -> id ) ;
				$price			 = \InputButler::get ( 'buying_price_' . $rows -> id ) ;
				$quantity		 = \InputButler::get ( 'quantity_' . $rows -> id ) ;
				$freeQuantity	 = \InputButler::get ( 'free_quantity_' . $rows -> id ) ;

				if ( \InputButler::get ( 'exp_date_' . $rows -> id ) == '' )
				{
					$expDate = '0000-00-00' ;
				} else
				{
					$expDate = \InputButler::get ( 'exp_date_' . $rows -> id ) ;
				}
				$batchNumber = \InputButler::get ( 'batch_number_' . $rows -> id ) ;

				if ( strlen ( \InputButler::get ( 'quantity_' . $rows -> id ) ) > 0 )
				{
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
						$buyingItems -> free_quantity	 = \NullHelper::nullIfEmpty ( $freeQuantity ) ;
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
						$buyingItems -> free_quantity	 = \NullHelper::nullIfEmpty ( $freeQuantity ) ;
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
					}
				} elseif ( strlen ( \InputButler::get ( 'quantity_' . $rows -> id ) ) == 0 )
				{
					if ( in_array ( $itemId , \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
								-> lists ( 'item_id' ) ) )
					{
						if ( strlen ( \InputButler::get ( 'free_quantity_' . $rows -> id ) ) == 0 )
						{

							$stockDetails = new \Models\StockDetail() ;

							$stockRow = \Models\StockDetail::where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
								-> where ( 'item_id' , '=' , $itemId )
								-> lists ( 'good_quantity' ) ;

							$previousPurchaseFreeQuantity	 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
									-> where ( 'item_id' , '=' , $itemId ) -> lists ( 'free_quantity' ) ;
							$previousPurchaseQuantity		 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
									-> where ( 'item_id' , '=' , $itemId ) -> lists ( 'quantity' ) ;

							$preQuantity = $previousPurchaseFreeQuantity[ 0 ] + $previousPurchaseQuantity[ 0 ] ;
							$newQuantity = $stockRow[ 0 ] - $preQuantity ;

							$delete = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
								-> where ( 'item_id' , '=' , $itemId )
								-> delete () ;

							$stockDetails -> where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
								-> where ( 'item_id' , '=' , $itemId )
								-> update ( [ 'good_quantity' => $newQuantity ] ) ;
						} elseif ( strlen ( \InputButler::get ( 'free_quantity_' . $rows -> id ) ) > 0 )
						{

							$stockDetails = new \Models\StockDetail() ;

							$stockRow = \Models\StockDetail::where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
								-> where ( 'item_id' , '=' , $itemId )
								-> lists ( 'good_quantity' ) ;

							$previousPurchaseFreeQuantity	 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
									-> where ( 'item_id' , '=' , $itemId ) -> lists ( 'free_quantity' ) ;
							$previousPurchaseQuantity		 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
									-> where ( 'item_id' , '=' , $itemId ) -> lists ( 'quantity' ) ;

							$preQuantity = $previousPurchaseFreeQuantity[ 0 ] + $previousPurchaseQuantity[ 0 ] ;
							$newQuantity = ($stockRow[ 0 ] - $preQuantity) + $freeQuantity ;

							$buyingItems = \Models\BuyingItem::where ( 'invoice_id' , '=' , $id )
								-> where ( 'item_id' , '=' , $rows -> id )
								-> first () ;

							$buyingItems -> item_id = $itemId ;

							$buyingItems -> price			 = $price ;
							$buyingItems -> quantity		 = \NullHelper::nullIfEmpty ( $quantity ) ;
							$buyingItems -> free_quantity	 = $freeQuantity ;
							$buyingItems -> exp_date		 = $expDate ;
							$buyingItems -> batch_number	 = $batchNumber ;
							$buyingItems -> update () ;

							$stockDetails -> where ( 'stock_id' , '=' , $purchaseItem -> stock_id )
								-> where ( 'item_id' , '=' , $itemId )
								-> update ( [ 'good_quantity' => $newQuantity ] ) ;
						}
					}
				}
			}
			\MessageButler::setSuccess ( 'Purchase invoice was updated successfully.' ) ;

			\ActivityLogButler::add ( "Edit Purchase invoice " . $purchaseItem -> id ) ;

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
			$items						 = \Models\Item::where ( 'is_active' , '=' , 1 ) -> get () ;
			$toStockId					 = \InputButler::get ( 'stock_id' ) ;
			$purchaseDate				 = \InputButler::get ( 'date_time' ) ;
			$vendorId					 = \InputButler::get ( 'vendor_id' ) ;
			$printedInvoiceNum			 = \InputButler::get ( 'printed_invoice_num' ) ;
			$isPaid						 = \NullHelper::zeroIfNull ( \InputButler::get ( 'is_paid' ) ) ;
			$cashPayment				 = \InputButler::get ( 'cash_payment' ) ;
			$chequePayment				 = \InputButler::get ( 'cheque_payment' ) ;
			$chequePaymentBankId		 = \InputButler::get ( 'cheque_payment_bank_id' ) ;
			$chequePaymentChequeNumber	 = \InputButler::get ( 'cheque_payment_cheque_number' ) ;
			$chequePaymentIssuedDate	 = \InputButler::get ( 'cheque_payment_issued_date' ) ;
			$chequePaymentPayableDate	 = \InputButler::get ( 'cheque_payment_payable_date' ) ;

			if ( empty ( \InputButler::get ( 'other_expense_amount' ) ) )
			{
				$otherExpensesAmount = 0 ;
			} else
			{
				$otherExpensesAmount = \InputButler::get ( 'other_expense_amount' ) ;
			}
			if ( empty ( \InputButler::get ( 'other_expenses_details' ) ) )
			{
				$otherExpensesDetail = '' ;
			} else
			{
				$otherExpensesDetail = \InputButler::get ( 'other_expenses_details' ) ;
			}

			$buyingInvoice							 = new \Models\BuyingInvoice() ;
			$buyingInvoice -> date_time				 = $purchaseDate ;
			$buyingInvoice -> vendor_id				 = $vendorId ;
			$buyingInvoice -> printed_invoice_num	 = $printedInvoiceNum ;
			$buyingInvoice -> completely_paid		 = $isPaid ;
			$buyingInvoice -> other_expenses_amount	 = $otherExpensesAmount ;
			$buyingInvoice -> other_expenses_details = $otherExpensesDetail ;
			$buyingInvoice -> stock_id				 = $toStockId ;

			$this -> validateSavePaymentBefore ( $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;
			$buyingInvoice -> save () ;
			$this -> savePayments ( $buyingInvoice , $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;

			$countRows = \Models\Item::all () ;

			foreach ( $countRows as $rows )
			{
				$itemId			 = \InputButler::get ( 'item_id_' . $rows -> id ) ;
				$price			 = \InputButler::get ( 'buying_price_' . $rows -> id ) ;
				$quantity		 = \InputButler::get ( 'quantity_' . $rows -> id ) ;
				$freeQuantity	 = \InputButler::get ( 'free_quantity_' . $rows -> id ) ;
				if ( \InputButler::get ( 'exp_date_' . $rows -> id ) == '' )
				{
					$expDate = '0000-00-00' ;
				} else
				{
					$expDate = \InputButler::get ( 'exp_date_' . $rows -> id ) ;
				}
				$batchNumber = \InputButler::get ( 'batch_number_' . $rows -> id ) ;

				if ( strlen ( \InputButler::get ( 'quantity_' . $rows -> id ) ) > 0 )
				{
					$buyingItems = new \Models\BuyingItem() ;

					$buyingItems -> invoice_id		 = $buyingInvoice -> id ;
					$buyingItems -> item_id			 = $itemId ;
					$buyingItems -> price			 = $price ;
					$buyingItems -> quantity		 = $quantity ;
					$buyingItems -> free_quantity	 = \NullHelper::nullIfEmpty ( $freeQuantity ) ;
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
				} elseif ( strlen ( \InputButler::get ( 'quantity_' . $rows -> id ) ) == 0 )
				{
					if ( strlen ( \InputButler::get ( 'free_quantity_' . $rows -> id ) ) > 0 )
					{
						$buyingItems = new \Models\BuyingItem() ;

						$buyingItems -> invoice_id		 = $buyingInvoice -> id ;
						$buyingItems -> item_id			 = $itemId ;
						$buyingItems -> price			 = $price ;
						$buyingItems -> quantity		 = \NullHelper::nullIfEmpty ( $quantity ) ;
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
			}

			\ActivityLogButler::add ( "Add Purchase invoice " . $buyingInvoice -> id ) ;

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

	private function savePayments ( $buyingInvoice , $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$vendorId	 = $buyingInvoice -> vendor_id ;
		$dateTime	 = $buyingInvoice -> date_time ;
		$dateTime	 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTime ) ;

		$this -> validateSavePayment ( $vendorId , $dateTime , $cashPayment , $chequePayment ) ;

		$vendor = \Models\Vendor::findOrFail ( $vendorId ) ;

		$vendorAccountId = $vendor -> finance_account_id ;
		$cashAccountId	 = \SystemSettingButler::getValue ( 'payment_source_cash' ) ;
		$chequeAccountId = \SystemSettingButler::getValue ( 'payment_source_cheque' ) ;

		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $cashPayment ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $cashAccountId ;
			$financeTransfer -> to_id		 = $vendorAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $cashPayment ;

			$financeTransfer -> save () ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;
		}

		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $chequePayment ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $chequeAccountId ;
			$financeTransfer -> to_id		 = $vendorAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $chequePayment ;

			$financeTransfer -> save () ;

			$this -> saveChequeDetail ( $financeTransfer , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;
		}
	}

	private function saveNewPayments ( $buyingInvoice , $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$vendorId	 = $buyingInvoice -> vendor_id ;
		$dateTime	 = $buyingInvoice -> date_time ;
		$dateTime	 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTime ) ;

		$this -> validateSaveNewPayment ( $vendorId , $dateTime , $cashPayment , $chequePayment ) ;

		$vendor = \Models\Vendor::findOrFail ( $vendorId ) ;

		$vendorAccountId = $vendor -> finance_account_id ;
		$cashAccountId	 = \SystemSettingButler::getValue ( 'payment_source_cash' ) ;
		$chequeAccountId = \SystemSettingButler::getValue ( 'payment_source_cheque' ) ;

		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $cashPayment ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $cashAccountId ;
			$financeTransfer -> to_id		 = $vendorAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $cashPayment ;

			$financeTransfer -> save () ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;
		}

		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $chequePayment ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $chequeAccountId ;
			$financeTransfer -> to_id		 = $vendorAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $chequePayment ;

			$financeTransfer -> save () ;

			$this -> saveChequeDetail ( $financeTransfer , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;

			$buyingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id ) ;
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
				'numeric'
			] ,
			'chequePayment'	 => [
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

	private function validateSaveNewPayment ( $vendorId , $dateTime , $cashPayment , $chequePayment )
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
				'numeric'
			] ,
			'chequePayment'	 => [
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

	private function validateSavePaymentBefore ( $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$data = compact ( [
			'cashPayment' ,
			'chequePayment' ,
			'chequePaymentBankId' ,
			'chequePaymentChequeNumber' ,
			'chequePaymentIssuedDate' ,
			'chequePaymentPayableDate'
			] ) ;

		$rules = [
			'cashPayment'				 => [
				'numeric'
			] ,
			'chequePayment'				 => [
				'numeric'
			] ,
			'chequePaymentBankId'		 => [
				'required_with:chequePayment' ,
				'numeric'
			] ,
			'chequePaymentChequeNumber'	 => [
				'required_with:chequePayment'
			] ,
			'chequePaymentIssuedDate'	 => [
				'required_with:chequePayment' ,
				'date' ,
				'date_format:Y-m-d'
			] ,
			'chequePaymentPayableDate'	 => [
				'required_with:chequePayment' ,
				'date' ,
				'date_format:Y-m-d'
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

	private function validateSaveNewPaymentBefore ( $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$data = compact ( [
			'cashPayment' ,
			'chequePayment' ,
			'chequePaymentBankId' ,
			'chequePaymentChequeNumber' ,
			'chequePaymentIssuedDate' ,
			'chequePaymentPayableDate'
			] ) ;

		$rules = [
			'cashPayment'				 => [
				'numeric'
			] ,
			'chequePayment'				 => [
				'numeric'
			] ,
			'chequePaymentBankId'		 => [
				'required_with:chequePayment' ,
				'numeric'
			] ,
			'chequePaymentChequeNumber'	 => [
				'required_with:chequePayment'
			] ,
			'chequePaymentIssuedDate'	 => [
				'required_with:chequePayment' ,
				'date' ,
				'date_format:Y-m-d'
			] ,
			'chequePaymentPayableDate'	 => [
				'required_with:chequePayment' ,
				'date' ,
				'date_format:Y-m-d'
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

	private function saveChequeDetail ( \Models\FinanceTransfer $financeTransfer , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$chequePaymentIssuedDate	 = \DateTimeHelper::convertTextToFormattedDateTime ( $chequePaymentIssuedDate , 'Y-m-d' ) ;
		$chequePaymentPayableDate	 = \DateTimeHelper::convertTextToFormattedDateTime ( $chequePaymentPayableDate , 'Y-m-d' ) ;

		$chequeDetail = new \Models\ChequeDetail() ;

		$chequeDetail -> finance_transfer_id = $financeTransfer -> id ;
		$chequeDetail -> bank_id			 = $chequePaymentBankId ;
		$chequeDetail -> cheque_number		 = $chequePaymentChequeNumber ;
		$chequeDetail -> issued_date		 = $chequePaymentIssuedDate ;
		$chequeDetail -> payable_date		 = $chequePaymentPayableDate ;

		return $chequeDetail -> save () ;
	}

}
