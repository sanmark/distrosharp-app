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

		return self::hasCommonAbilities ( $allowedAbilities , $userAbilities ) ;
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
