<?php

class AbilityButler
{

	public static function checkAbilities ( $allowedAbilities )
	{
		if ( is_string ( $allowedAbilities ) )
		{
			$allowedAbilities = explode ( ',' , $allowedAbilities ) ;
		}

		$userAbilities = Auth::user () -> getAbilityCodes () ;

		if ( self::hasCommonAbilities ( $allowedAbilities , $userAbilities ) )
		{
			return TRUE ;
		} elseif ( in_array ( 'super_admin' , $allowedAbilities ) && SessionButler::isSuperAdminLoggedIn () )
		{
			return TRUE ;
		}

		return FALSE ;
	}

	private static function hasCommonAbilities ( $allowedAbilities , $userAbilities )
	{
		$commonAbilities = array_intersect ( $allowedAbilities , $userAbilities ) ;

		if ( count ( $commonAbilities ) == 0 )
		{
			return FALSE ;
		}

		return TRUE ;
	}

}
