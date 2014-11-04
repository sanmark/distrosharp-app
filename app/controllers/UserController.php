<?php

class UserController extends Controller
{

	public function login ( $superAdminLoginToken = NULL )
	{
		if ( ( ! NullHelper::isNullEmptyOrWhitespace ( $superAdminLoginToken )) && ($superAdminLoginToken != Config::get ( 'superAdminLogin.token' ) ) )
		{
			return View::make ( 'web.404' ) ;
		}

		return View::make ( 'web.login.login' ) ;
	}

	public function pLogin ( $superAdminLoginToken = NULL )
	{
		$organization = Input::get ( 'organization' ) ;

		if ( NullHelper::isNullEmptyOrWhitespace ( $organization ) )
		{
			MessageButler::setError ( 'Please enter organization code.' ) ;

			return Redirect::back ()
					-> withInput () ;
		}

		SessionButler::setOrganization ( $organization ) ;
		ConfigButler::setTenantDb ( $organization ) ;
		$tenantDbName = ConfigButler::getTenantDb () ;

		if ( ! DatabaseHelper::hasDatabase ( $tenantDbName ) )
		{
			MessageButler::setError ( 'Invalid organization code.' ) ;

			return Redirect::back ()
					-> withInput () ;
		}

		$credentials[ 'username' ]	 = Input::get ( 'username' ) ;
		$credentials[ 'password' ]	 = Input::get ( 'password' ) ;

		try
		{
			if ( NullHelper::isNullEmptyOrWhitespace ( $superAdminLoginToken ) )
			{
				if ( ! Auth::attempt ( $credentials ) )
				{
					MessageButler::setError ( 'Your login details are incorrect. Please contact your admin.' ) ;
					return Redirect::back ()
							-> withInput () ;
				}
			} else
			{
				if ( ! UserButler::logSuperAdminIn ( $credentials ) )
				{
					MessageButler::setError ( 'Your login details are incorrect. Please contact your admin.' ) ;
					return Redirect::back ()
							-> withInput () ;
				}
			}
		} catch ( PDOException $exc )
		{
			MessageButler::setError ( 'Your login details are incorrect. Please contact your admin.' ) ;
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
