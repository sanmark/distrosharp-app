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
		SessionButler::setOrganization ( $organization ) ;
		ConfigButler::setTenantDb ( $organization ) ;

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

		$menu = MenuButler::getMenu ( Auth::user () ) ;
		SessionButler::setMenu ( $menu ) ;

		return Redirect::to ( '/' ) ;
	}

	public function logout ()
	{
		Auth::logout () ;
		Session::clear () ;
		return Redirect::to ( '/' ) ;
	}

}
