<?php

namespace Controllers\Entities ;

class RouteController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$filterValues = \Input::all () ;

		$routes							 = \Models\Route::filter ( $filterValues ) ;
		$name							 = \Input::get ( 'name' ) ;
		$isActive						 = \Input::get ( 'is_active' ) ;
		$repId							 = \Input::get ( 'rep_id' ) ;
		$reps							 = \Models\Route::distinct () -> lists ( 'rep_id' ) ;
		$repSelectBoxContent			 = \User::getArrayForHtmlSelectByIds ( 'id' , 'username' , $reps , [NULL => 'Any' ] ) ;
		$data[ 'repSelectBoxContent' ]	 = $repSelectBoxContent ;
		$data[ 'routes' ]				 = $routes ;
		$data[ 'name' ]					 = $name ;
		$data[ 'isActive' ]				 = $isActive ;
		$data[ 'repId' ]				 = $repId ;
		return \View::make ( 'web.entities.routes.home' , $data ) ;
	}

	public function add ()
	{
		$data					 = [ ] ;
		$repSelectBox			 = \User::getArrayForHtmlSelect ( 'id' , 'username' , ['' => 'Select Rep' ] ) ;
		$data[ 'repSelectBox' ]	 = $repSelectBox ;
		return \View::make ( 'web.entities.routes.add' , $data ) ;
	}

	public function save ()
	{
		try
		{
			$route				 = new \Models\Route() ;
			$route -> name		 = \Input::get ( 'name' ) ;
			$route -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$route -> rep_id	 = \Input::get ( 'rep_id' ) ;

			$route -> save () ;

			\ActivityLogButler::add ( "Add Route " . $route -> id ) ;

			\MessageButler::setSuccess ( 'Route "' . $route -> name . '" was added successfully.' ) ;

			return \Redirect::action ( 'entities.routes.add' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function edit ( $id )
	{
		$route			 = \Models\Route::findOrFail ( $id ) ;
		$repSelectBox	 = \User::getArrayForHtmlSelect ( 'id' , 'username' , ['' => 'Select Rep' ] ) ;

		$data					 = [ ] ;
		$data[ 'route' ]		 = $route ;
		$data[ 'repSelectBox' ]	 = $repSelectBox ;

		return \View::make ( 'web.entities.routes.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			$route = \Models\Route::findOrFail ( $id ) ;

			$route -> name		 = \Input::get ( 'name' ) ;
			$route -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$route -> rep_id	 = \Input::get ( 'rep_id' ) ;

			$route -> update () ;

			\ActivityLogButler::add ( "Edit Route " . $route -> id ) ;

			return \Redirect::action ( 'entities.routes.view' ) ;
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
