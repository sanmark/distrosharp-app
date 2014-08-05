<?php

namespace Controllers\Entities ;

class CustomerController extends \Controller
{

	public function add ()
	{
		return \View::make ( 'web.entities.customers.add' ) ;
	}

	public function save ()
	{
		try
		{
			$customer				 = new \Customer() ;
			$customer -> name		 = \Input::get ( 'name' ) ;
			$customer -> route_id	 = \Input::get ( 'route_id' ) ;
			$customer -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$customer -> details	 = \Input::get ( 'details' ) ;

			$customer -> save () ;
		} catch ( \InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
