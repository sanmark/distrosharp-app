<?php

namespace Controllers\Entities ;

class RouteController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$routes = \Models\Route::all () ;

		$data[ 'routes' ] = $routes ;

		return \View::make ( 'web.entities.routes.home' , $data ) ;
	}

	public function add ()
	{
		return \View::make ( 'web.entities.routes.add' ) ;
	}

	public function save ()
	{
		try
		{
			$route				 = new \Models\Route() ;
			$route -> name		 = \Input::get ( 'name' ) ;
			$route -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$route -> rep		 = \Input::get ( 'rep' ) ;

			$route -> save () ;
			
			return \Redirect::action( 'entities.routes.view' ) ;
		} catch ( \InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	public function edit ( $id )
	{
		$route = \Models\Route::findOrFail ( $id ) ;

		$data			 = [ ] ;
		$data[ 'route' ] = $route ;

		return \View::make ( 'web.entities.routes.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			$route = \Models\Route::findOrFail ( $id ) ;

			$route -> name		 = \Input::get ( 'name' ) ;
			$route -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;
			$route -> rep		 = \Input::get ( 'rep' ) ;

			$route -> update () ;

			return \Redirect::action ( 'entities.routes.view' ) ;
		} catch ( \InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
