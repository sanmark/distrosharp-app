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
			'minimumAvailableItemCode' ,
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
			$item -> buying_invoice_order	 = \Input::get ( 'buying_invoice_order' ) ;
			$item -> selling_invoice_order	 = \Input::get ( 'selling_invoice_order' ) ;
			$item -> is_active				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$item -> save () ;

			$this -> createStockDetailsByItem ( $item -> id ) ;

			return \Redirect::action ( 'entities.items.view' ) ;
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
			$item -> buying_invoice_order	 = \Input::get ( 'buying_invoice_order' ) ;
			$item -> selling_invoice_order	 = \Input::get ( 'selling_invoice_order' ) ;
			$item -> is_active				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;

			$item -> update () ;

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

}
