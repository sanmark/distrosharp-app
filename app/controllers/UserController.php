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

		Session::set ( SESSION_TENANTDB , $organization ) ;
		Config::set ( CONFIG_DATABASE_CONNECTIONS_TENANTDB_DATABASE , Config::get ( CONFIG_CONFIG_TENANTDB_PREFIX ) . $organization ) ;

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
