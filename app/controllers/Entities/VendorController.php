<?php

namespace Controllers\Entities ;

class VendorController extends \Controller
{

	public function add ()
	{
		return \View::make ( 'web.entities.vendors.add' ) ;
	}

	public function save ()
	{
		try
		{
			$vendor = new \Vendor() ;

			$vendor -> name		 = \Input::get ( 'name' ) ;
			$vendor -> details	 = \Input::get ( 'details' ) ;
			$vendor -> is_active = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;


			$vendor -> save () ;
		} catch ( \InvalidInputException $exc )
		{

			return \Redirect::back ()
			-> withErrors ( $exc -> validator )
			-> withInput () ;
		}
	}

}
