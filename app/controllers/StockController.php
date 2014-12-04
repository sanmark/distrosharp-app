<?php

namespace Controllers ;

class StockController extends \Controller
{

	public function all ()
	{
		$data = [ ] ;

		$stocks = \Models\Stock::with ( ['incharge' , 'stockType' ] )
			-> get () ;

		$data[ 'stocks' ] = $stocks ;

		return \View::make ( 'web.stocks.home' , $data ) ;
	}

	public function view ( $stockId )
	{
		$data = [ ] ;

		$stock = \Models\Stock::with ( 'incharge' , 'stockType' )
			-> findOrFail ( $stockId ) ;

		$lastConfirmedDate = $stock -> getLastConfirmDate () ;

		$lastConfirmedDate = \DateTimeHelper::convertTextToFormattedDateTime ( $lastConfirmedDate , "Y-m-d h:i A" ) ;

		$stockDetails = $stock -> stockDetails () -> activeItems () -> get () ;

		$data = compact ( [
			'stock' ,
			'stockDetails' ,
			'lastConfirmedDate'
			] ) ;

		return \View::make ( 'web.stocks.view' , $data ) ;
	}

	public function edit ( $stockId )
	{
		$data = [ ] ;

		$stock		 = \Models\Stock::with ( ['incharge' , 'stockType' ] )
			-> findOrFail ( $stockId ) ;
		$users		 = \User::getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'None' ] ) ;
		$stockTypes	 = \Models\StockType::getArrayForHtmlSelect ( 'id' , 'label' ) ;

		$data[ 'stock' ]		 = $stock ;
		$data[ 'users' ]		 = $users ;
		$data[ 'stockTypes' ]	 = $stockTypes ;

		return \View::make ( 'web.stocks.edit' , $data ) ;
	}

	public function update ( $stockId )
	{
		try
		{
			$stock = \Models\Stock::findOrFail ( $stockId ) ;

			$stock -> incharge_id	 = \NullHelper::ifNullEmptyOrWhitespace ( \InputButler::get ( 'incharge_id' ) , NULL ) ;
			$stock -> stock_type_id	 = \InputButler::get ( 'stock_type_id' ) ;

			$stock -> update () ;

			\MessageButler::setSuccess ( 'Successfully updated stock details.' ) ;

			\ActivityLogButler::add ( "Update Stock " . $stockId ) ;

			return \Redirect::action ( 'stocks.all' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function create ()
	{
		$usersList	 = \User::getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'None' ] ) ;
		$stockTypes	 = \Models\StockType::getArrayForHtmlSelect ( 'id' , 'label' , ['' => 'Select Stock type' ] ) ;
		$data		 = compact ( [
			'usersList' ,
			'stockTypes'
			] ) ;
		return \View::make ( 'web.stocks.create' , $data ) ;
	}

	public function save ()
	{
		try
		{
			$stock					 = new \Models\Stock() ;
			$stock -> name			 = \InputButler::get ( 'stock_name' ) ;
			$stock -> incharge_id	 = \NullHelper::ifNullEmptyOrWhitespace ( \InputButler::get ( 'incharge_id' ) , NULL ) ;
			$stock -> stock_type_id	 = \InputButler::get ( 'stock_type_id' ) ;

			$stock -> save () ;

			\StockDetailButler::createStockItems ( $stock -> id ) ;

			\MessageButler::setSuccess ( 'New stock was created successfully' ) ;
			\ActivityLogButler::add ( "Create Stock " . $stock -> id ) ;
			return \Redirect::action ( 'stocks.all' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function getAvailableQuantity ()
	{
		$itemId	 = \Input::get ( 'itemId' ) ;
		$repId	 = \Input::get ( 'rep_id' ) ;

		$stock = \Models\Stock::where ( 'incharge_id' , '=' , $repId ) -> firstOrFail () ;

		$stockDetail = \Models\StockDetail::where ( 'stock_id' , '=' , $stock -> id )
			-> where ( 'item_id' , '=' , $itemId )
			-> firstOrFail () ;
		return \Response::json ( $stockDetail -> good_quantity ) ;
	}

}
