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

		$stock = \Models\Stock::with ( 'stockDetails.item', 'incharge', 'stockType' )
		-> findOrFail ( $stockId ) ;

		$data[ 'stock' ] = $stock ;

		return \View::make ( 'web.stocks.view' , $data ) ;
	}

	public function edit ( $stockId )
	{
		$data = [ ] ;

		$stock		 = \Models\Stock::with ( ['incharge' , 'stockType' ] )
		-> findOrFail ( $stockId ) ;
		$users		 = \User::getArrayForHtmlSelect ( 'id' , 'username' ) ;
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

			$stock -> incharge_id	 = \Input::get ( 'incharge_id' ) ;
			$stock -> stock_type_id	 = \Input::get ( 'stock_type_id' ) ;

			$stock -> update () ;

			\MessageButler::setSuccess ( 'Successfully updated stock details.' ) ;
			return \Redirect::action ( 'stocks.all' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
