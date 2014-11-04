<?php

class UserButler
{

	public static function logSuperAdminIn ( $credentials )
	{
		$firstTenantUser = User::first () ;

		Config::set ( 'database.default' , 'central_db' ) ;

		$user = User::where ( 'username' , '=' , $credentials[ 'username' ] ) -> first () ;

		if ( Hash::check ( $credentials[ 'password' ] , $user[ 'password' ] ) )
		{
			Auth::login ( $firstTenantUser ) ;

			SessionButler::logSuperAdminIn () ;

			return TRUE ;
		}

		Config::set ( 'database.default' , 'tenant_db' ) ;

		return FALSE ;
	}

}
