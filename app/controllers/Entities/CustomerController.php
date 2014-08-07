<?php

namespace Controllers\Entities ;

class CustomerController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$customers = \Models\Customer::all () ;

		$data[ 'customers' ] = $customers ;

		return \View::make ( 'web.entities.customers.home' , $data ) ;
	}

	public function add ()
	{
		return \View::make ( 'web.entities.customers.add' ) ;
	}

	public function save ()
	{
		try
		{
			$customer				 = new \Models\Customer() ;
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