<?php

class SessionButler
{

	public static function setOrganization ( $organization )
	{
		return Session::set ( SESSION_ORGANIZATION , $organization ) ;
	}

	public static function getOrganization ()
	{
		return Session::get ( SESSION_ORGANIZATION ) ;
	}

	public static function setMenu ( $menu )
	{
		return Session::set ( SESSION_MENU , $menu ) ;
	}

	public static function setRepId ( $repId )
	{
		return Session::set ( SESSION_REP , $repId ) ;
	}

	public static function getRepId ()
	{
		return Session::get ( SESSION_REP ) ;
	}

	public static function logSuperAdminIn ()
	{
		return Session::set ( SESSION_IS_SUPER_ADMIN_LOGGED_IN , TRUE ) ;
	}

	public static function isSuperAdminLoggedIn ()
	{
		return Session::get ( SESSION_IS_SUPER_ADMIN_LOGGED_IN ) ;
	}

}
