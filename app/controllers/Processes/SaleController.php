<?php

namespace Controllers\Processes ;

class SaleController extends \Controller
{

	public function add ()
	{
		if ( \NullHelper::isNullEmptyOrWhitespace ( \SessionButler::getRepId () ) )
		{
			\MessageButler::setInfo ( 'Please select a rep before adding Selling Invoices.' ) ;

			return \Redirect::action ( 'processes.sales.setRep' ) ;
		}

		$rep = \User::with ( 'stock' )
			-> findOrFail ( \SessionButler::getRepId () ) ;

		if ( $rep -> stock -> isLoaded () )
		{
			$customers			 = [ NULL => 'Select Route First' ] ;
			$routes				 = \Models\Route::where ( 'rep_id' , '=' , $rep -> id ) -> getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Select' ] ) ;
			$items				 = \Models\Item::where ( 'is_active' , '=' , TRUE )
				-> orderBy ( 'selling_invoice_order' , 'ASC' )
				-> get () ;
			$rep				 = $rep -> load ( 'abilities' , 'stock.stockDetails' ) ;
			$stockDetails		 = \CollectionHelper::toArrayAndSetSpecificIndex ( $rep -> stock -> stockDetails , 'item_id' ) ;
			$guessedInvoiceId	 = \SellingInvoiceButler::getNextId () ;
			$currentDateTime	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
			$banksList			 = \Models\Bank::where ( 'is_active' , '=' , TRUE ) -> getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

			$data = compact ( [
				'customers' ,
				'routes' ,
				'items' ,
				'stockDetails' ,
				'guessedInvoiceId' ,
				'currentDateTime' ,
				'banksList' ,
				'rep'
				] ) ;

			return \View::make ( 'web.processes.sales.add' , $data ) ;
		} else
		{
			\MessageButler::setError ( 'Please load your stock before sale.' ) ;
			return \View::make ( 'web.processes.sales.not-loaded' ) ;
		}
	}

	public function save ()
	{
		try
		{
			$items						 = \Input::get ( 'items' ) ;
			$dateTime					 = \Input::get ( 'date_time' ) ;
			$customerId					 = \Input::get ( 'customer_id' ) ;
			$printedInvoiceNumber		 = \Input::get ( 'printed_invoice_number' ) ;
			$discount					 = \Input::get ( 'discount' ) ;
			$isCompletelyPaid			 = \Input::get ( 'is_completely_paid' ) ;
			$cashPaymentAmount			 = \Input::get ( 'cash_payment' ) ;
			$chequePaymentAmount		 = \Input::get ( 'cheque_payment' ) ;
			$chequePaymentBankId		 = \Input::get ( 'cheque_payment_bank_id' ) ;
			$chequePaymentChequeNumber	 = \Input::get ( 'cheque_payment_cheque_number' ) ;
			$chequePaymentIssuedDate	 = \Input::get ( 'cheque_payment_issued_date' ) ;
			$chequePaymentPayableDate	 = \Input::get ( 'cheque_payment_payable_date' ) ;
			$oldRouteId					 = \Input::get ( 'route_id' ) ;
			$creditPayments				 = \Input::get ( 'credit_payments' ) ;
			$rep						 = \User::with ( 'stock' )-> findOrFail ( \SessionButler::getRepId () ) ;
			$stockId					 = $rep -> stock -> id ;

			$this -> validateSaleItems ( $items ) ;
			$this -> validateSavePayments ( $cashPaymentAmount , $chequePaymentAmount , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;
			$this -> validateCreditPayments ( $creditPayments ) ;

			$sellingInvoice = new \Models\SellingInvoice() ;

			$sellingInvoice -> date_time				 = $dateTime ;
			$sellingInvoice -> customer_id				 = $customerId ;
			$sellingInvoice -> rep_id					 = $rep -> id ;
			$sellingInvoice -> printed_invoice_number	 = $printedInvoiceNumber ;
			$sellingInvoice -> discount					 = \NullHelper::zeroIfEmptyString ( $discount ) ;
			$sellingInvoice -> is_completely_paid		 = $isCompletelyPaid ;
			$sellingInvoice -> stock_id					 = $stockId[ 0 ] ;

			$sellingInvoice -> save () ;
			$this -> saveItems ( $sellingInvoice , $items ) ;
			$this -> savePayments ( $sellingInvoice , $cashPaymentAmount , $chequePaymentAmount , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;
			$this -> saveCreditPayments ( $sellingInvoice , $creditPayments ) ;

			\MessageButler::setSuccess ( 'Selling Invoice was saved successfully.' ) ;

			\ActivityLogButler::add ( "Add Selling Invoice " . $sellingInvoice -> id ) ;

			return \Redirect::action ( 'processes.sales.add' )
					-> with ( 'oldRouteId' , $oldRouteId ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function all ()
	{

		$filterValues			 = \Input::all () ;
		$sellingInvoices		 = \Models\SellingInvoice::filter ( $filterValues ) ;
		$customerSelectBox		 = \Models\Customer::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Any' ] ) ;
		$routeSelectBox			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Any' ] ) ;
		$repSelectBox			 = \SellingInvoiceButler::getAllRepsForHtmlSelect () ;
		$isActiveSelectBox		 = \ViewButler::htmlSelectAnyYesNo () ;
		$id						 = \Input::get ( 'id' ) ;
		$printedInvoiceNumber	 = \Input::get ( 'printed_invoice_number' ) ;
		$dateTimeFrom			 = \Input::get ( 'date_time_from' ) ;
		$dateTimeTo				 = \Input::get ( 'date_time_to' ) ;
		$customerId				 = \Input::get ( 'customer_id' ) ;
		$repId					 = \Input::get ( 'rep_id' ) ;
		$isCompletelyPaid		 = \Input::get ( 'is_completely_paid' ) ;
		$routeId				 = \Input::get ( 'route_id' ) ;


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

		if ( is_null ( $dateTimeFrom ) )
		{
			$dateTimeFrom = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( '-7 days midnight' ) ) ) ;
		}

		if ( is_null ( $dateTimeTo ) )
		{
			$dateTimeTo = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( 'today 23:59:59' ) ) ) ;
		}

		//var_dump($customerId);

		$data = compact ( [
			'sellingInvoices' ,
			'customerSelectBox' ,
			'repSelectBox' ,
			'isActiveSelectBox' ,
			'id' ,
			'printedInvoiceNumber' ,
			'dateTimeFrom' ,
			'dateTimeTo' ,
			'customerId' ,
			'repId' ,
			'isCompletelyPaid' ,
			'creditBalance' ,
			'totalPayment' ,
			'totalOfTotalPaid' ,
			'totalOfTotalCredit' ,
			'totalOfInvoiceSum' ,
			'totalOfDiscountSum' ,
			'routeSelectBox' ,
			'routeId'
			] ) ;
		return \View::make ( 'web.processes.sales.all' , $data ) ;
	}

	public function edit ( $id )
	{
		$sellingInvoice		 = \Models\SellingInvoice::with ( 'rep' , 'sellingItems' ) -> findOrFail ( $id ) ;
		$customerRO			 = \Models\Customer::where ( 'is_active' , '=' , TRUE ) ;
		$customerDropDown	 = \Models\Customer::getArrayForHtmlSelectByRequestObject ( 'id' , 'name' , $customerRO , [ NULL => 'Select' ] ) ;
		$items				 = \Models\Item::all () ;
		$banksList			 = \Models\Bank::where ( 'is_active' , '=' , TRUE ) -> getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

		$data = compact ( [
			'sellingInvoice' ,
			'customerDropDown' ,
			'items' ,
			'banksList'
			] ) ;

		return \View::make ( 'web.processes.sales.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			$items						 = \Input::get ( 'items' ) ;
			$cashPaymentAmount			 = \Input::get ( 'new_cash_payment' ) ;
			$chequePaymentAmount		 = \Input::get ( 'new_cheque_payment' ) ;
			$chequePaymentBankId		 = \Input::get ( 'cheque_payment_bank_id' ) ;
			$chequePaymentChequeNumber	 = \Input::get ( 'cheque_payment_cheque_number' ) ;
			$chequePaymentIssuedDate	 = \Input::get ( 'cheque_payment_issued_date' ) ;
			$chequePaymentPayableDate	 = \Input::get ( 'cheque_payment_payable_date' ) ;
			$this -> validateAtLeastOneItemIsFilled ( $items ) ;
			$this -> validateSaleItemsForUpdate ( $items ) ;
			$this -> validateSaveNewPayments ( $cashPaymentAmount , $chequePaymentAmount , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;

			$sellingInvoice = \Models\SellingInvoice::findOrFail ( $id ) ;

			$sellingInvoice -> date_time				 = \Input::get ( 'date_time' ) ;
			$sellingInvoice -> customer_id				 = \Input::get ( 'customer_id' ) ;
			$sellingInvoice -> printed_invoice_number	 = \Input::get ( 'printed_invoice_number' ) ;
			$sellingInvoice -> discount					 = \Input::get ( 'discount' ) ;
			$sellingInvoice -> is_completely_paid		 = \Input::get ( 'is_completely_paid' ) ;

			$sellingInvoice -> update () ;
			$this -> savePayments ( $sellingInvoice , $cashPaymentAmount , $chequePaymentAmount , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;

			$this -> updateSellingItems ( $id ) ;

			\MessageButler::setSuccess ( 'Selling invoice was updated successfully.' ) ;

			\ActivityLogButler::add ( "Edit Selling Invoice " . $sellingInvoice -> id ) ;

			return \Redirect::action ( 'processes.sales.all' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function selectRep ()
	{
		$repSelectBox	 = \SellingInvoiceButler::getAllRepsForHtmlSelect ( [NULL => 'Select' ] ) ;
		$currentRepId	 = \SessionButler::getRepId () ;

		$data = compact ( [
			'repSelectBox' ,
			'currentRepId'
			] ) ;

		return \View::make ( 'web.processes.sales.selectRep' , $data ) ;
	}

	public function setRep ()
	{
		try
		{
			$this -> validateSetRep () ;

			$repId = \Input::get ( 'rep_id' ) ;

			\SessionButler::setRepId ( $repId ) ;

			\MessageButler::setSuccess ( 'Rep was set successfully.' ) ;

			return \Redirect::action ( 'processes.sales.add' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	private function validateSaleItems ( $items )
	{
		foreach ( $items as $itemId => $item )
		{
			$itemO = \Models\Item::findOrFail ( $itemId ) ;

			if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $item , ['price' , 'available_quantity' , 'good_return_price' , 'company_return_price' ] ) )
			{
				$rules = [
					'price'						 => [
						'required_with:paid_quantity,free_quantity' ,
						'numeric'
					] ,
					'available_quantity'		 => [
						'required_with:paid_quantity,free_quantity' ,
						'greater_than_or_equal_to:' . ($item[ 'paid_quantity' ] + $item[ 'free_quantity' ])
					] ,
					'paid_quantity'				 => [
						'required_without_all:free_quantity,good_return_quantity,company_return_quantity' ,
						'numeric'
					] ,
					'free_quantity'				 => [
						'required_without_all:paid_quantity,good_return_quantity,company_return_quantity' ,
						'numeric'
					] ,
					'good_return_price'			 => [
						'required_with:good_return_quantity' ,
						'numeric'
					] ,
					'good_return_quantity'		 => [
						'required_with_all:paid_quantity,free_quantity,company_return_quantity' ,
						'numeric'
					] ,
					'company_return_price'		 => [
						'required_with:company_return_quantity' ,
						'numeric'
					] ,
					'company_return_quantity'	 => [
						'required_with_all:paid_quantity,free_quantity,good_return_quantity' ,
						'numeric'
					]
					] ;

				$messages = [
					'price.required_with'							 => $itemO -> name . ': Please enter the Price. It is require when Paid Quantity or Free Quantity is present.' ,
					'available_quantity.greater_than_or_equal_to'	 => $itemO -> name . ': The sum of Paid and Free Quantities are higher than the available amount.' ,
					'paid_quantity.required_without_all'			 => $itemO -> name . ': Paid Quantity is required when none of Free Quantity, Good Return Quantity, or Company Return Quantity are present.' ,
					'free_quantity.required_without_all'			 => $itemO -> name . ': Free Quantity is required when none of Paid Quantity, Good Return Quantity, or Company Return Quantity are present.' ,
					'good_return_price.required_with'				 => $itemO -> name . ': Good Return Price is required when Good Return Quantity is present.' ,
					'good_return_quantity.required_with_all'		 => $itemO -> name . ': Good Return Quantity is required when none of Paid Quantity, Free Quantity, or Company Return Quantity are present.' ,
					'company_return_price.required_with'			 => $itemO -> name . ': Company Return Price is required when Company Return Quantity is present.' ,
					'company_return_quantity.required_with_all'		 => $itemO -> name . ': Company Return Quantity is required when none of Paid Quantity, Free Quantity, or Good Return Quantity are present.'
					] ;

				$validator = \Validator::make ( $item , $rules , $messages ) ;

				if ( $validator -> fails () )
				{
					$iie				 = new \Exceptions\InvalidInputException() ;
					$iie -> validator	 = $validator ;

					throw $iie ;
				}
			}
		}
	}

	private function validateCreditPayments ( $creditPayments )
	{
		if ( count ( $creditPayments ) > 0 )
		{
			foreach ( $creditPayments as $creditPayment )
			{
				$rules = [
					'cash_amount'			 => [
						'numeric'
					] ,
					'cheque_amount'			 => [
						'numeric'
					] ,
					'cheque_bank_id'		 => [
						'required_with:cheque_amount'
					] ,
					'cheque_number'			 => [
						'required_with:cheque_amount'
					] ,
					'cheque_issued_date'	 => [
						'required_with:cheque_amount'
					] ,
					'cheque_payable_date'	 => [
						'required_with:cheque_amount'
					]
					] ;

				$validator = \Validator::make ( $creditPayment , $rules ) ;

				if ( $validator -> fails () )
				{
					$iie				 = new \Exceptions\InvalidInputException() ;
					$iie -> validator	 = $validator ;

					throw $iie ;
				}
			}
		}
	}

	private function validateSaleItemsForUpdate ( $items )
	{
		foreach ( $items as $itemId => $item )
		{
			$itemO = \Models\Item::findOrFail ( $itemId ) ;

			if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $item , ['price' , 'available_quantity' ] ) )
			{
				$rules = [
					'price'						 => [
						'required_with:paid_quantity,free_quantity' ,
						'numeric'
					] ,
					'paid_quantity'				 => [
						'required_without_all:free_quantity,good_return_quantity,company_return_quantity' ,
						'numeric'
					] ,
					'free_quantity'				 => [
						'required_without_all:paid_quantity,good_return_quantity,company_return_quantity' ,
						'numeric'
					] ,
					'good_return_price'			 => [
						'required_with:good_return_quantity' ,
						'numeric'
					] ,
					'good_return_quantity'		 => [
						'required_with_all:paid_quantity,free_quantity,company_return_quantity' ,
						'numeric'
					] ,
					'company_return_price'		 => [
						'required_with:company_return_quantity' ,
						'numeric'
					] ,
					'company_return_quantity'	 => [
						'required_with_all:paid_quantity,free_quantity,good_return_quantity' ,
						'numeric'
					]
					] ;

				$messages = [
					'price.required_with'						 => $itemO -> name . ': Please enter the Price. It is require when Paid Quantity or Free Quantity is present.' ,
					'paid_quantity.required_without_all'		 => $itemO -> name . ': Paid Quantity is required when none of Free Quantity, Good Return Quantity, or Company Return Quantity are present.' ,
					'free_quantity.required_without_all'		 => $itemO -> name . ': Free Quantity is required when none of Paid Quantity, Good Return Quantity, or Company Return Quantity are present.' ,
					'good_return_price.required_with'			 => $itemO -> name . ': Good Return Price is required when Good Return Quantity is present.' ,
					'good_return_quantity.required_with_all'	 => $itemO -> name . ': Good Return Quantity is required when none of Paid Quantity, Free Quantity, or Company Return Quantity are present.' ,
					'company_return_price.required_with'		 => $itemO -> name . ': Company Return Price is required when Company Return Quantity is present.' ,
					'company_return_quantity.required_with_all'	 => $itemO -> name . ': Company Return Quantity is required when none of Paid Quantity, Free Quantity, or Good Return Quantity are present.'
					] ;

				$validator = \Validator::make ( $item , $rules , $messages ) ;

				if ( $validator -> fails () )
				{
					$iie				 = new \Exceptions\InvalidInputException() ;
					$iie -> validator	 = $validator ;

					throw $iie ;
				}
			}
		}
	}

	private function validateAtLeastOneItemIsFilled ( $items )
	{
		$itemsWithoutPriceAndAvailableQuantity = \ArrayHelper::withoutRecursive ( $items , ['price' , 'available_quantity' , 'good_return_price' , 'company_return_price' ] ) ;

		$data = [
			'field' => $itemsWithoutPriceAndAvailableQuantity
			] ;

		$rules = [
			'field' => [
				'at_least_one_element_of_one_array_has_value'
			]
			] ;

		$messages = [
			'field.at_least_one_element_of_one_array_has_value' => 'Please enter sales data.'
			] ;

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	private function updateStockOnSave ( $stockId , $itemId , $paidQuantity , $freeQuantity , $goodReturnQuantity , $companyReturnQuantity )
	{
		$totalSoldQuantity = $paidQuantity + $freeQuantity ;

		\StockDetailButler::decreaseGoodQuantity ( $stockId , $itemId , $totalSoldQuantity ) ;
		\StockDetailButler::increaseGoodQuantity ( $stockId , $itemId , $goodReturnQuantity ) ;
		\StockDetailButler::increaseReturnQuantity ( $stockId , $itemId , $companyReturnQuantity ) ;
	}

	private function updatestockOnUpdate ( $stockId , $itemId , $paidQuantity , $freeQuantity , $goodReturnQuantity , $companyReturn )
	{
		$totalSoldQuantity = $paidQuantity + $freeQuantity ;

		\StockDetailButler::increaseGoodQuantity ( $stockId , $itemId , $totalSoldQuantity ) ;
		\StockDetailButler::decreaseGoodQuantity ( $stockId , $itemId , $goodReturnQuantity ) ;
		\StockDetailButler::decreaseReturnQuantity ( $stockId , $itemId , $companyReturn ) ;
	}

	private function updateSellingItems ( $sellingInvoiceId )
	{
		$sellingItemsArray = \Input::get ( 'items' ) ;

		$originalSellingItems	 = \Models\SellingItem::where ( 'selling_invoice_id' , '=' , $sellingInvoiceId ) -> get () ;
		$filledItems			 = $this -> getFilledItems ( $sellingInvoiceId , $sellingItemsArray ) ;
		$deletedItems			 = $originalSellingItems -> diff ( $filledItems ) ;

		$this -> updateFilledItems ( $filledItems ) ;
		$this -> updateDeletedItems ( $deletedItems ) ;
	}

	private function getFilledItems ( $sellingInvoiceId , $sellingItems )
	{
		$filledItemsArray = [ ] ;

		foreach ( $sellingItems as $itemId => $item )
		{
			if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $item , ['price' ] ) )
			{
				$sellingItem = \Models\SellingItem::where ( 'selling_invoice_id' , '=' , $sellingInvoiceId )
					-> where ( 'item_id' , '=' , $itemId )
					-> first () ;

				if ( is_null ( $sellingItem ) )
				{
					$sellingItem						 = new \Models\SellingItem() ;
					$sellingItem -> selling_invoice_id	 = $sellingInvoiceId ;
					$sellingItem -> item_id				 = $itemId ;
				}

				$sellingItem -> price					 = \NullHelper::nullIfEmpty ( $item[ 'price' ] ) ;
				$sellingItem -> paid_quantity			 = \NullHelper::nullIfEmpty ( $item[ 'paid_quantity' ] ) ;
				$sellingItem -> free_quantity			 = \NullHelper::nullIfEmpty ( $item[ 'free_quantity' ] ) ;
				$sellingItem -> good_return_price		 = \NullHelper::nullIfEmpty ( $item[ 'good_return_price' ] ) ;
				$sellingItem -> good_return_quantity	 = \NullHelper::nullIfEmpty ( $item[ 'good_return_quantity' ] ) ;
				$sellingItem -> company_return_price	 = \NullHelper::nullIfEmpty ( $item[ 'company_return_price' ] ) ;
				$sellingItem -> company_return_quantity	 = \NullHelper::nullIfEmpty ( $item[ 'company_return_quantity' ] ) ;

				$filledItemsArray[] = $sellingItem ;
			}
		}

		$filledItems = new \Illuminate\Database\Eloquent\Collection ( $filledItemsArray ) ;

		return $filledItems ;
	}

	private function updateFilledItems ( $filledItems )
	{
		foreach ( $filledItems as $filledItem )
		{
			$filledItem -> load ( 'sellingInvoice.rep.stock' , 'item' ) ;

			$sellingInvoice	 = $filledItem -> sellingInvoice ;
			$originalItem	 = $sellingInvoice -> sellingItemById ( $filledItem -> id ) ;
			$stockId		 = $sellingInvoice -> rep -> stock -> id ;
			$itemId			 = $filledItem -> item -> id ;

			$filledItem -> save () ;

			if ( ! is_null ( $originalItem ) )
			{
				$paidQuantityDifference	 = $originalItem -> paid_quantity - $filledItem -> paid_quantity ;
				$freeQuantityDifference	 = $originalItem -> free_quantity - $filledItem -> free_quantity ;
				$goodReturnDifference	 = $originalItem -> good_return_quantity - $filledItem -> good_return_quantity ;
				$companyReturnDifference = $originalItem -> company_return_quantity - $filledItem -> company_return_quantity ;

				$this -> updatestockOnUpdate ( $stockId , $itemId , $paidQuantityDifference , $freeQuantityDifference , $goodReturnDifference , $companyReturnDifference ) ;
			} else
			{
				$paidQuantity			 = $filledItem -> paid_quantity ;
				$freeQuantity			 = $filledItem -> free_quantity ;
				$goodReturnQuantity		 = $filledItem -> good_return_quantity ;
				$companyReturnQuantity	 = $filledItem -> company_return_quantity ;

				$this -> updateStockOnSave ( $stockId , $itemId , $paidQuantity , $freeQuantity , $goodReturnQuantity , $companyReturnQuantity ) ;
			}
		}
	}

	private function updateDeletedItems ( $deletedItems )
	{
		foreach ( $deletedItems as $deletedItem )
		{
			$deletedItem -> load ( 'sellingInvoice.rep.stock' , 'item' ) ;

			$deletedItem -> delete () ;
			$stockId = $deletedItem -> sellingInvoice -> rep -> stock -> id ;
			$itemId	 = $deletedItem -> item -> id ;

			$paidQuantity			 = $deletedItem -> paid_quantity ;
			$freeQuantity			 = $deletedItem -> free_quantity ;
			$goodReturnQuantity		 = $deletedItem -> good_return_quantity ;
			$companyReturnQuantity	 = $deletedItem -> company_return_quantity ;

			$this -> updatestockOnUpdate ( $stockId , $itemId , $paidQuantity , $freeQuantity , $goodReturnQuantity , $companyReturnQuantity ) ;
		}
	}

	private function savePayments ( $sellingInvoice , $cashPaymentAmount , $chequePaymentAmount , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$customerId	 = $sellingInvoice -> customer_id ;
		$dateTime	 = $sellingInvoice -> date_time ;

		$dateTime = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTime ) ;

		$customer = \Models\Customer::findOrFail ( $customerId ) ;

		$cashTargetAccount	 = \FinanceAccountButler::getCashTargetAccount () ;
		$chequeTargetAccount = \FinanceAccountButler::getChequeTargetAccount () ;

		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $cashPaymentAmount ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $customer -> finance_account_id ;
			$financeTransfer -> to_id		 = $cashTargetAccount -> id ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $cashPaymentAmount ;

			$financeTransfer -> save () ;

			$sellingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id , ['paid_invoice_id' => $sellingInvoice -> id ] ) ;
		}

		if ( ! \NullHelper::isNullEmptyOrWhitespace ( $chequePaymentAmount ) )
		{
			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $customer -> finance_account_id ;
			$financeTransfer -> to_id		 = $chequeTargetAccount -> id ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $chequePaymentAmount ;

			$financeTransfer -> save () ;

			$this -> saveChequeDetail ( $financeTransfer , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate ) ;

			$sellingInvoice -> financeTransfers () -> attach ( $financeTransfer -> id , ['paid_invoice_id' => $sellingInvoice -> id ] ) ;
		}
	}

	private function validateSavePayments ( $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$data = compact ( [
			'cashPayment' ,
			'chequePayment' ,
			'chequePaymentBankId' ,
			'chequePaymentChequeNumber' ,
			'chequePaymentIssuedDate' ,
			'chequePaymentPayableDate' ,
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

	private function validateSaveNewPayments ( $cashPayment , $chequePayment , $chequePaymentBankId , $chequePaymentChequeNumber , $chequePaymentIssuedDate , $chequePaymentPayableDate )
	{
		$data = compact ( [
			'cashPayment' ,
			'chequePayment' ,
			'chequePaymentBankId' ,
			'chequePaymentChequeNumber' ,
			'chequePaymentIssuedDate' ,
			'chequePaymentPayableDate' ,
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

	private function saveItems ( $sellingInvoice , $items )
	{
		$sellingInvoiceId = $sellingInvoice -> id ;

		foreach ( $items as $itemId => $item )
		{
			if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $item , [ 'price' , 'available_quantity' , 'good_return_price' , 'company_return_price' ] ) )
			{
				$rep = \User::with ( 'stock' )
					-> findOrFail ( \SessionButler::getRepId () ) ;

				$sellingItem = new \Models\SellingItem() ;

				$sellingItem -> selling_invoice_id		 = $sellingInvoiceId ;
				$sellingItem -> item_id					 = $itemId ;
				$sellingItem -> price					 = \NullHelper::nullIfEmpty ( $item[ 'price' ] ) ;
				$sellingItem -> paid_quantity			 = \NullHelper::nullIfEmpty ( $item[ 'paid_quantity' ] ) ;
				$sellingItem -> free_quantity			 = \NullHelper::nullIfEmpty ( $item[ 'free_quantity' ] ) ;
				$sellingItem -> good_return_price		 = \NullHelper::nullIfEmpty ( $item[ 'good_return_price' ] ) ;
				$sellingItem -> good_return_quantity	 = \NullHelper::nullIfEmpty ( $item[ 'good_return_quantity' ] ) ;
				$sellingItem -> company_return_price	 = \NullHelper::nullIfEmpty ( $item[ 'company_return_price' ] ) ;
				$sellingItem -> company_return_quantity	 = \NullHelper::nullIfEmpty ( $item[ 'company_return_quantity' ] ) ;

				$sellingItem -> save () ;

				$stockId = $rep -> stock -> id ;

				$this -> updateStockOnSave ( $stockId , $itemId , $item[ 'paid_quantity' ] , $item[ 'free_quantity' ] , $item[ 'good_return_quantity' ] , $item[ 'company_return_quantity' ] ) ;
			}
		}
	}

	private function saveCreditPayments ( \Models\SellingInvoice $sellingInvoice , $creditPayments )
	{
		if ( count ( $creditPayments ) > 0 )
		{
			foreach ( $creditPayments as $sellingInvoiceId => $creditPayment )
			{
				if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $creditPayment ) )
				{
					$sellingInvoiceBeingPaid = \Models\SellingInvoice::with ( 'customer' )
						-> findOrFail ( $sellingInvoiceId ) ;

					if ( ! \NullHelper::isNullEmptyOrWhitespace ( $creditPayment[ 'cash_amount' ] ) )
					{
						$cashTargetAccount = \FinanceAccountButler::getCashTargetAccount () ;

						$financeTransfer				 = new \Models\FinanceTransfer() ;
						$financeTransfer -> from_id		 = $sellingInvoiceBeingPaid -> customer -> finance_account_id ;
						$financeTransfer -> to_id		 = $cashTargetAccount -> id ;
						$financeTransfer -> date_time	 = $sellingInvoice -> date_time ;
						$financeTransfer -> amount		 = $creditPayment[ 'cash_amount' ] ;

						$financeTransfer -> save () ;

						$sellingInvoiceBeingPaid -> financeTransfers () -> attach ( $financeTransfer -> id , ['paid_invoice_id' => $sellingInvoice -> id ] ) ;
					}

					if ( ! \NullHelper::isNullEmptyOrWhitespace ( $creditPayment[ 'cheque_amount' ] ) )
					{
						$chequeAmount		 = $creditPayment[ 'cheque_amount' ] ;
						$chequeBankId		 = $creditPayment[ 'cheque_bank_id' ] ;
						$chequeNumber		 = $creditPayment[ 'cheque_number' ] ;
						$chequeIssuedDate	 = $creditPayment[ 'cheque_issued_date' ] ;
						$chequePayableDate	 = $creditPayment[ 'cheque_payable_date' ] ;

						$chequeTargetAccount = \FinanceAccountButler::getChequeTargetAccount () ;

						$financeTransfer				 = new \Models\FinanceTransfer() ;
						$financeTransfer -> from_id		 = $sellingInvoiceBeingPaid -> customer -> finance_account_id ;
						$financeTransfer -> to_id		 = $chequeTargetAccount -> id ;
						$financeTransfer -> date_time	 = $sellingInvoice -> date_time ;
						$financeTransfer -> amount		 = $creditPayment[ 'cheque_amount' ] ;

						$financeTransfer -> save () ;

						$this -> saveChequeDetail ( $financeTransfer , $chequeBankId , $chequeNumber , $chequeIssuedDate , $chequePayableDate ) ;

						$sellingInvoiceBeingPaid -> financeTransfers () -> attach ( $financeTransfer -> id , ['paid_invoice_id' => $sellingInvoice -> id ] ) ;
					}
				}
			}
		}
	}

	private function validateSetRep ()
	{
		$data = \Input::all () ;

		$rules = [
			'rep_id' => [
				'required' ,
				'numeric'
			]
			] ;

		$messages = [
			'rep_id.required' => 'Please select rep'
			] ;

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
