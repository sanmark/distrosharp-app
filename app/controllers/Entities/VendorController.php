<?php

namespace Controllers\Entities ;

class VendorController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$vendors = \Models\Vendor::all () ;

		$data[ 'vendors' ] = $vendors ;

		return \View::make ( 'web.entities.vendors.home' , $data ) ;
	}

	public function add ()
	{
		return \View::make ( 'web.entities.vendors.add' ) ;
	}

	public function save ()
	{
		try
		{
			$vendor = new \Models\Vendor() ;

			$vendor -> name		 = \Input::get ( 'name' ) ;
			$vendor -> details	 = \Input::get ( 'details' ) ;
			$vendor -> is_active = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;

			$vendor -> save () ;

			return \Redirect::action( 'entities.vendors.view' ) ;
		} catch ( \InvalidInputException $exc )
		{

			return \Redirect::back ()
			-> withErrors ( $exc -> validator )
			-> withInput () ;
		}
	}

	public function edit ( $id )
	{
		$data = [ ] ;

		$vendor = \Models\Vendor::findOrFail ( $id ) ;

		$data[ 'vendor' ] = $vendor ;

		return \View::make ( 'web.entities.vendors.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			
			$vendor				 = \Models\Vendor::findOrFail ( $id ) ;
			$vendor -> name		 = \Input::get ( 'name' ) ;
			$vendor -> details	 = \Input::get ( 'details' ) ;
			$vendor -> is_active = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;

			$vendor -> update () ;
			return \Redirect::action ( 'entities.vendors.view' ) ;
		} catch ( \InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
