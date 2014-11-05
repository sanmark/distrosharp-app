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
		$name			 = \InputButler::get ( 'name' ) ;
		$routeId		 = \InputButler::get ( 'route' ) ;
		$isActive		 = \InputButler::get ( 'is_active' ) ;

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
			$customer -> name		 = \InputButler::get ( 'name' ) ;
			$customer -> route_id	 = \InputButler::get ( 'route_id' ) ;
			$customer -> is_active	 = \NullHelper::zeroIfNull ( \InputButler::get ( 'is_active' ) ) ;
			$customer -> details	 = \InputButler::get ( 'details' ) ;

			$customer -> save () ;

			\ActivityLogButler::add ( "Add Customer " . $customer -> id ) ;

			\MessageButler::setSuccess ( 'Customer "' . $customer -> name . '" was added successfuly.' ) ;

			return \Redirect::action ( 'entities.customers.add' ) ;
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

			$customer -> name		 = \InputButler::get ( 'name' ) ;
			$customer -> route_id	 = \InputButler::get ( 'route_id' ) ;
			$customer -> is_active	 = \NullHelper::zeroIfNull ( \InputButler::get ( 'is_active' ) ) ;
			$customer -> details	 = \InputButler::get ( 'details' ) ;

			$customer -> update () ;

			\ActivityLogButler::add ( "Edit Customer " . $customer -> id ) ;

			return \Redirect::action ( 'entities.customers.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function aForRouteId ()
	{
		$routeId	 = \InputButler::get ( 'routeId' ) ;
		$customers	 = \Models\Customer::where ( 'route_id' , '=' , $routeId )
			-> get () ;

		return \Response::json ( $customers ) ;
	}

	public function aCreditInvoices ()
	{
		$customerId = \InputButler::get ( 'customerId' ) ;

		$customer = \Models\Customer::findOrFail ( $customerId ) ;

		$creditInvoices = $customer -> creditInvoices ;

		$creditInvoicesWithBalance = [ ] ;

		foreach ( $creditInvoices as $creditInvoice )
		{
			$creditInvoice -> balance = $creditInvoice -> getInvoiceBalance () ;

			$creditInvoicesWithBalance[] = $creditInvoice ;
		}

		return \Response::json ( $creditInvoicesWithBalance ) ;
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
