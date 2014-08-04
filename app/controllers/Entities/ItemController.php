<?php

namespace Controllers\Entities ;

class ItemController extends \Controller
{

	public function add ()
	{
		return \View::make ( 'web.entities.items.add' ) ;
	}

	public function save ()
	{
		try
		{
			$item = new \Item() ;

			$item -> code					 = \Input::get ( 'code' ) ;
			$item -> name					 = \Input::get ( 'name' ) ;
			$item -> reorder_level			 = \Input::get ( 'reorder_level' ) ;
			$item -> current_buying_price	 = \Input::get ( 'current_buying_price' ) ;
			$item -> current_selling_price	 = \Input::get ( 'current_selling_price' ) ;
			$item -> buying_invoice_order	 = \Input::get ( 'buying_invoice_order' ) ;
			$item -> selling_invoice_order	 = \Input::get ( 'selling_invoice_order' ) ;
			$item -> is_active				 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;

			$item -> save () ;
		} catch ( \InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}