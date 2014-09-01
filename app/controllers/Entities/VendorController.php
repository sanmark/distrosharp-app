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
			$financeAccount					 = new \Models\FinanceAccount() ;
			$financeAccount -> name			 = \Input::get ( 'name' ) ;
			$financeAccount -> is_active	 = TRUE ;
			$financeAccount -> is_in_house	 = FALSE ;
			$financeAccount -> save () ;

			$vendor							 = new \Models\Vendor() ;
			$vendor -> name					 = \Input::get ( 'name' ) ;
			$vendor -> details				 = \Input::get ( 'details' ) ;
			$vendor -> is_active			 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$vendor -> finance_account_id	 = $financeAccount -> id ;
			$vendor -> save () ;

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

			$financeAccount				 = \Models\FinanceAccount::findOrFail ( $vendor -> finance_account_id ) ;
			$financeAccount -> name		 = $vendor -> name ;
			$financeAccount -> is_active = $vendor -> is_active ;
			$financeAccount -> update () ;

			return \Redirect::action ( 'entities.vendors.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
