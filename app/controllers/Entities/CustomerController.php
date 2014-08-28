<?php

namespace Controllers\Entities ;

class CustomerController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$filterValues = \Input::all () ;

		$customers		 = \Models\Customer::filter ( $filterValues ) ;
		$routeSelectBox	 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'Any' ] ) ;
		$name			 = \Input::get ( 'name' ) ;
		$routeId		 = \Input::get ( 'route' ) ;
		$isActive		 = \Input::get ( 'is_active' ) ;

		$data[ 'customers' ]		 = $customers ;
		$data[ 'routeSelectBox' ]	 = $routeSelectBox ;
		$data[ 'name' ]				 = $name ;
		$data[ 'routeId' ]			 = $routeId ;
		$data[ 'isActive' ]			 = $isActive ;

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

			return \Redirect::action ( 'entities.customers.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	public function edit ( $id )
	{

		$customer		 = \Models\Customer::findOrFail ( $id ) ;
		$routeSelectBox	 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'Any' ] ) ;

		$data = [ ] ;

		$data[ 'customer' ]			 = $customer ;
		$data[ 'routeSelectBox' ]	 = $routeSelectBox ;

		return \View::make ( 'web.entities.customers.edit' , $data ) ;
	}

	public function update ( $id )
	{

		try
		{
			$customer = \Models\Customer::findOrFail ( $id ) ;

			$customer -> name		 = \Input::get ( 'name' ) ;
			$customer -> route_id	 = \Input::get ( 'route_id' ) ;
			$customer -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$customer -> details	 = \Input::get ( 'details' ) ;

			$customer -> update () ;

			return \Redirect::action ( 'entities.customers.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	private function getFilterValues ()
	{
		$fieldsToRequest = [
			'name'
		] ;

		$filterValues = \Input::all () ;

		if ( count ( $filterValues ) )
		{

			$filterValuesForRequestedFields = \ArrayHelper::getValuesIfKeysExist ( $filterValues , $fieldsToRequest ) ;

			$filterValuesForRequestedFields = \ArrayHelper::pruneEmptyElements ( $filterValuesForRequestedFields ) ;

			return $filterValuesForRequestedFields ;
		}

		return NULL ;
	}

}
