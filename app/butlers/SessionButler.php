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

}
