<?php

namespace Controllers\Processes ;

class SaleController extends \Controller
{

	public function add ()
	{
		$customers	 = \Models\Customer::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
		$items		 = \Models\Item::all () ;

		$user			 = \Auth::user () ;
		$user			 = $user -> load ( 'abilities' , 'stock.stockDetails' ) ;
		$stockDetails	 = \CollectionHelper::toArrayAndSetSpecificIndex ( $user -> stock -> stockDetails , 'item_id' ) ;

		$data = compact ( [
			'customers' ,
			'items' ,
			'stockDetails'
		] ) ;

		return \View::make ( 'web.processes.sales.add' , $data ) ;
	}

	public function save ()
	{
		try
		{
			$items = \Input::get ( 'items' ) ;

			$this -> validateAtLeastOneItemIsFilled ( $items ) ;
			$this -> validateSaleItems ( $items ) ;

			$sellingInvoice = new \Models\SellingInvoice() ;

			$sellingInvoice -> date_time				 = \Input::get ( 'date_time' ) ;
			$sellingInvoice -> customer_id				 = \Input::get ( 'customer_id' ) ;
			$sellingInvoice -> rep_id					 = \Auth::user () -> id ;
			$sellingInvoice -> printed_invoice_number	 = \Input::get ( 'printed_invoice_number' ) ;
			$sellingInvoice -> discount					 = \Input::get ( 'discount' ) ;
			$sellingInvoice -> is_completely_paid		 = \Input::get ( 'is_completely_paid' ) ;

			$sellingInvoice -> save () ;
			$sellingInvoiceId = $sellingInvoice -> id ;

			foreach ( $items as $itemId => $item )
			{
				if ( \ArrayHelper::hasAtLeastOneElementWithValue ( $item , ['price' ] ) )
				{
					$sellingItem = new \Models\SellingItem() ;

					$sellingItem -> selling_invoice_id		 = $sellingInvoiceId ;
					$sellingItem -> item_id					 = $itemId ;
					$sellingItem -> price					 = $item[ 'price' ] ;
					$sellingItem -> paid_quantity			 = $item[ 'paid_quantity' ] ;
					$sellingItem -> free_quantity			 = $item[ 'free_quantity' ] ;
					$sellingItem -> good_return_price		 = $item[ 'good_return_price' ] ;
					$sellingItem -> good_return_quantity	 = $item[ 'good_return_quantity' ] ;
					$sellingItem -> company_return_price	 = $item[ 'company_return_price' ] ;
					$sellingItem -> company_return_quantity	 = $item[ 'company_return_quantity' ] ;

					$sellingItem -> save () ;

					$stockId = \Auth::user () -> stock -> id ;

					$this -> updateStock ( $stockId , $itemId , $item[ 'paid_quantity' ] , $item[ 'free_quantity' ] , $item[ 'good_return_quantity' ] , $item[ 'company_return_quantity' ] ) ;
				}
			}
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
		$customerSelectBox		 = \Models\Customer::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Any' ] ) ;
		$repSelectBox			 = \SellingInvoiceButler::getAllRepsForHtmlSelect () ;
		$isActiveSelectBox		 = \ViewButler::htmlSelectAnyYesNo () ;
		$id						 = \Input::get ( 'id' ) ;
		$printedInvoiceNumber	 = \Input::get ( 'printed_invoice_number' ) ;
		$dateTimeFrom			 = \Input::get ( 'date_time_from' ) ;
		$dateTimeTo				 = \Input::get ( 'date_time_to' ) ;
		$customerId				 = \Input::get ( 'customer_id' ) ;
		$repId					 = \Input::get ( 'rep_id' ) ;
		$isCompletelyPaid		 = \Input::get ( 'is_completely_paid' ) ;


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
		] ) ;
		return \View::make ( 'web.processes.sales.all' , $data ) ;
	}

	private function validateSaleItems ( $items )
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

	private function validateAtLeastOneItemIsFilled ( $items )
	{
		$itemsWithoutPriceAndAvailableQuantity = \ArrayHelper::withoutRecursive ( $items , ['price' , 'available_quantity' ] ) ;

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

	private function updateStock ( $stockId , $itemId , $paidQuantity , $freeQuantity , $goodReturnQuantity , $companyReturnQuantity )
	{
		$totalSoldQuantity = $paidQuantity + $freeQuantity ;

		\StockDetailButler::decreaseGoodQuantity ( $stockId , $itemId , $totalSoldQuantity ) ;
		\StockDetailButler::increaseGoodQuantity ( $stockId , $itemId , $goodReturnQuantity ) ;
		\StockDetailButler::increaseReturnQuantity ( $stockId , $itemId , $companyReturnQuantity ) ;
	}

}
