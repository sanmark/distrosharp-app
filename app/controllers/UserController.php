<?php

class UserController extends Controller
{

	public function login ()
	{
		return View::make ( 'web.login.login' ) ;
	}

	public function pLogin ()
	{
		$organization = Input::get ( 'organization' ) ;

		Session::set ( 'tenant_db' , $organization ) ;
		Config::set ( 'database.connections.tenant_db.database' , Config::get ( 'config.tenant_db_prefix' ) . $organization ) ;

		$credentials[ 'username' ]	 = Input::get ( 'username' ) ;
		$credentials[ 'password' ]	 = Input::get ( 'password' ) ;

		try
		{
			if ( ! Auth::attempt ( $credentials ) )
			{
				MessageButler::setError ( 'Invalid login details.' ) ;
				return Redirect::back ()
				-> withInput () ;
			}
		} catch ( PDOException $exc )
		{
			MessageButler::setError ( 'Invalid login details.' ) ;
			return Redirect::back ()
			-> withInput () ;
		}

		return Redirect::to ( '/' ) ;
	}

}
