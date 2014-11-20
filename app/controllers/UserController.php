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
		$organization = InputButler::get ( 'organization' ) ;
		
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

		$credentials[ 'username' ]	 = InputButler::get ( 'username' ) ;
		$credentials[ 'password' ]	 = InputButler::get ( 'password' ) ;

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
		\ActivityLogButler::add ( "Logout User  " .  Auth::user ()->id .' ('.Auth::user ()->username.')'  ) ;
		Auth::logout () ;
		Session::clear () ;
		return Redirect::to ( '/' ) ;
	}

}
