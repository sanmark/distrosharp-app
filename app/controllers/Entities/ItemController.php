<?php

namespace Controllers\Entities ;

class ItemController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$filterValues	 = \Input::all () ;
		$items			 = \Models\Item::filter ( $filterValues ) ;
		$code			 = \InputButler::get ( 'code' ) ;
		$name			 = \InputButler::get ( 'name' ) ;
		$isActive		 = \InputButler::get ( 'is_active' ) ;
		$sortBy			 = \InputButler::get ( 'sort_by' ) ;
		$sortOrder		 = \InputButler::get ( 'sort_order' ) ;

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

			$item -> code					 = \InputButler::get ( 'code' ) ;
			$item -> name					 = \InputButler::get ( 'name' ) ;
			$item -> reorder_level			 = \InputButler::get ( 'reorder_level' ) ;
			$item -> current_buying_price	 = \InputButler::get ( 'current_buying_price' ) ;
			$item -> current_selling_price	 = \InputButler::get ( 'current_selling_price' ) ;
			$item -> is_active				 = \NullHelper::zeroIfNull ( \InputButler::get ( 'is_active' ) ) ;
			$item -> weight					 = \InputButler::get ( 'weight' ) ;
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

			$item -> code					 = \InputButler::get ( 'code' ) ;
			$item -> name					 = \InputButler::get ( 'name' ) ;
			$item -> reorder_level			 = \InputButler::get ( 'reorder_level' ) ;
			$item -> current_buying_price	 = \InputButler::get ( 'current_buying_price' ) ;
			$item -> current_selling_price	 = \InputButler::get ( 'current_selling_price' ) ;
			$item -> is_active				 = \NullHelper::zeroIfNull ( \InputButler::get ( 'is_active' ) ) ;
			$item -> weight					 = \InputButler::get ( 'weight' ) ;

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

	public function getItemByCode ()
	{
		$itemCode	 = \Input::get ( 'itemCode' ) ;
		$item		 = \Models\Item::where ( 'code' , '=' , $itemCode )
			-> get () ;
		return \Response::json ( $item ) ;
	}

	public function getItemByName ()
	{
		$itemName = \Input::get ( 'itemName' ) ;

		$item = \Models\Item::take ( 10 ) -> where ( 'name' , 'LIKE' , $itemName . '%' )
				-> orWhere ( 'name' , 'LIKE' , '% ' . $itemName . '%' ) -> get () ;

		return \Response::json ( $item ) ;
	}

	public function getItemById ()
	{
		$itemId	 = \Input::get ( 'itemId' ) ;
		$item	 = \Models\Item::where ( 'id' , '=' , $itemId )
			-> get () ;
		return \Response::json ( $item ) ;
	}

}
