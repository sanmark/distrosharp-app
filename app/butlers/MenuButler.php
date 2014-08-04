<?php

class MenuButler
{

	private static $menuTemplate = [
		['Home' , 'home' ] ,
		['Entities' , [
				['Items' , [
						['Add Item' , 'add-item' , ['add_item' ] ]
					]
				],
				['Vendors' , [
						['Add Vendor' , 'add-vendor' , ['add_vendor' ] ]
					]
				]
			]
		]
	] ;

	public static function getMenu ( User $user )
	{
		$userPermissions = $user -> getAbilityCodes () ;

		$userMenu = self::makeUserMenu ( $userPermissions , self::$menuTemplate ) ;

		return $userMenu ;
	}

	private static function makeUserMenu ( $userPermissions , $menuItems )
	{
		$new = NULL ;

		foreach ( $menuItems as $menuItem )
		{
			$elementCount = count ( $menuItem ) ;

			if ( $elementCount == 2 && is_array ( $menuItem[ 1 ] ) )
			{
				//Mid Point
				$subArray = self::makeUserMenu ( $userPermissions , $menuItem[ 1 ] ) ;

				if ( is_array ( $subArray ) )
				{
					$newItem		 = [ ] ;
					$newItem[ 0 ]	 = $menuItem[ 0 ] ;
					$newItem[ 1 ]	 = $subArray ;
					$new[]			 = $newItem ;
				}
			} elseif ( $elementCount == 2 && is_string ( $menuItem[ 1 ] ) )
			{
				$newItem		 = [ ] ;
				$newItem[ 0 ]	 = $menuItem[ 0 ] ;
				$newItem[ 1 ]	 = $menuItem[ 1 ] ;

				$new[] = $newItem ;
			} elseif ( $elementCount == 3 )
			{
				//End Point
				$hasPermission = [ ] ;

				if ( is_array ( $userPermissions ) && is_array ( $menuItem[ 2 ] ) )
				{
					$hasPermissions = array_intersect ( $userPermissions , $menuItem[ 2 ] ) ;
				}

				if ( count ( $hasPermissions ) > 0 )
				{
					$newItem		 = [ ] ;
					$newItem[ 0 ]	 = $menuItem[ 0 ] ;
					$newItem[ 1 ]	 = $menuItem[ 1 ] ;

					$new[] = $newItem ;
				}
			}
		}

		return $new ;
	}

}
