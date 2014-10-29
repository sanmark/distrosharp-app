<?php

namespace Controllers\Entities ;

class VendorController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$filterValues = \Input::all () ;

		$vendors	 = \Models\Vendor::filter ( $filterValues ) ;
		$name		 = \Input::get ( 'name' ) ;
		$isActive	 = \Input::get ( 'is_active' ) ;

		$data[ 'vendors' ]	 = $vendors ;
		$data[ 'name' ]		 = $name ;
		$data[ 'isActive' ]	 = $isActive ;

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
			$vendor				 = new \Models\Vendor() ;
			$vendor -> name		 = \Input::get ( 'name' ) ;
			$vendor -> details	 = \Input::get ( 'details' ) ;
			$vendor -> is_active = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$vendor -> save () ;
			
			\ActivityLogButler::add ("Add Vendor ". $vendor->id) ;

			return \Redirect::action ( 'entities.vendors.view' ) ;
		} catch ( \Exceptions\InvalidInputException $exc )
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

			\ActivityLogButler::add ("Edit Vendor ". $vendor->id) ;

			return \Redirect::action ( 'entities.vendors.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
