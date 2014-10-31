<?php

namespace Controllers\Entities ;

class ItemController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$filterValues	 = \Input::all () ;
		$items			 = \Models\Item::filter ( $filterValues ) ;
		$code			 = \Input::get ( 'code' ) ;
		$name			 = \Input::get ( 'name' ) ;
		$isActive		 = \Input::get ( 'is_active' ) ;
		$sortBy			 = \Input::get ( 'sort_by' ) ;
		$sortOrder		 = \Input::get ( 'sort_order' ) ;

		$data[ 'items' ]	 = $items ;
		$data[ 'code' ]		 = $code ;
		$data[ 'name' ]		 = $name ;
		$data[ 'isActive' ]	 = $isActive ;
		$data[ 'sortBy' ]	 = $sortBy ;
		$data[ 'sortOrder' ] = $sortOrder ;

		return \View::make ( 'web.entities.items.home' , $data ) ;
	}

	public function add ()
	{
		$minimumAvailableItemCode = \ItemButler::getMinimumAvailableItemCode () ;

		$data = compact ( [
			'minimumAvailableItemCode'
			] ) ;

		return \View::make ( 'web.entities.items.add' , $data ) ;
	}

	public function save ()
	{

		try
		{
			$item = new \Models\Item() ;

			$item -> code					 = \Input::get ( 'code' ) ;
			$item -> name					 = \Input::get ( 'name' ) ;
			$item -> reorder_level			 = \Input::get ( 'reorder_level' ) ;
			$item -> current_buying_price	 = \Input::get ( 'current_buying_price' ) ;
			$item -> current_selling_price	 = \Input::get ( 'current_selling_price' ) ;
			$item -> buying_invoice_order	 = \ItemButler::getMinBuyingInvoiceOrder () ;
			$item -> selling_invoice_order	 = \ItemButler::getMinSellingInvoiceOrder () ;
			$item -> is_active				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$item -> weight					 = \Input::get ( 'weight' ) ;
			$item -> save () ;

			$this -> createStockDetailsByItem ( $item -> id ) ;

			\ActivityLogButler::add ( "Add Item " . $item -> id . " (" . $item -> name . ") " ) ;

			\MessageButler::setSuccess ( 'Item "' . $item -> name . '" was added successfully.' ) ;

			return \Redirect::action ( 'entities.items.add' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function edit ( $id )
	{
		$item = \Models\Item::findOrFail ( $id ) ;

		$data			 = [ ] ;
		$data[ 'item' ]	 = $item ;

		return \View::make ( 'web.entities.items.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			$item = \Models\Item::findOrFail ( $id ) ;

			$item -> code					 = \Input::get ( 'code' ) ;
			$item -> name					 = \Input::get ( 'name' ) ;
			$item -> reorder_level			 = \Input::get ( 'reorder_level' ) ;
			$item -> current_buying_price	 = \Input::get ( 'current_buying_price' ) ;
			$item -> current_selling_price	 = \Input::get ( 'current_selling_price' ) ;
			$item -> is_active				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$item -> weight					 = \Input::get ( 'weight' ) ;

			$item -> update () ;

			\ActivityLogButler::add ( "Edit Item " . $item -> id . " (" . $item -> name . ") " ) ;

			return \Redirect::action ( 'entities.items.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function createStockDetailsByItem ( $id )
	{

		$stocks = \Models\Stock::lists ( 'id' ) ;
		foreach ( $stocks as $stockId )
		{
			$stockDetails					 = new \Models\StockDetail() ;
			$stockDetails -> stock_id		 = $stockId ;
			$stockDetails -> item_id		 = $id ;
			$stockDetails -> good_quantity	 = 0 ;
			$stockDetails -> return_quantity = 0 ;
			$stockDetails -> save () ;
		}
	}

	public function order ()
	{
		$items	 = \Models\Item::all () ;
		$data	 = compact ( [ 'items' ] ) ;

		return \View::make ( 'web.entities.items.order' , $data ) ;
	}

	public function updateOrder ()
	{
		$items = \Input::all () ;

		$result = $this -> validateForSaveOrder ( $items ) ;

		if ( ! $result )
		{
			foreach ( $items[ 'sellingOrder' ] as $key => $sellingOrder )
			{
				$item							 = \Models\Item::findOrFail ( $items[ 'itemId' ][ $key ] ) ;
				$item -> buying_invoice_order	 = $items[ 'buyingOrder' ][ $key ] ;
				$item -> selling_invoice_order	 = $sellingOrder ;
				$item -> update () ;
			}

			\MessageButler::setSuccess ( 'Item Ordered successfully.' ) ;

			\ActivityLogButler::add ( "Ordere Item" ) ;

			return \Redirect::back () ;
		} else
		{
			return \Redirect::back () ;
		}
	}

	public function validateForSaveOrder ( $items )
	{
		$result = TRUE ;

		if ( array_unique ( $items[ 'sellingOrder' ] ) != $items[ 'sellingOrder' ] )
		{
			\MessageButler::setError ( 'The Selling Invoice Order can not be duplicated.' ) ;
		} elseif ( array_unique ( $items[ 'buyingOrder' ] ) != $items[ 'buyingOrder' ] )
		{
			\MessageButler::setError ( 'The Buying Invoice Order can not be duplicated.' ) ;
		} elseif ( ! \ArrayHelper::areAllElementsFilled ( $items[ 'sellingOrder' ] ) )
		{
			\MessageButler::setError ( 'The Selling Invoice Order can not be empty.' ) ;
		} elseif ( ! \ArrayHelper::areAllElementsFilled ( $items[ 'buyingOrder' ] ) )
		{
			\MessageButler::setError ( 'The Buying Invoice Order can not be empty.' ) ;
		} else
		{
			$result = FALSE ;
		}

		return $result ;
	}

}
