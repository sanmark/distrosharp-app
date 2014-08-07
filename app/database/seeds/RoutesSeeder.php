<?php

class RoutesSeeder extends Seeder
{

	public function run ()
	{
		$routes = [
			[
				'id'		 => 1 ,
				'name'		 => 'Galle' ,
				'is_active'	 => 1 ,
				'rep'		 => 1 ,
			] ,
			[
				'id'		 => 2 ,
				'name'		 => 'Batapola' ,
				'is_active'	 => 1 ,
				'rep'		 => 1 ,
			] ,
			[
				'id'		 => 3 ,
				'name'		 => 'Elpitiya' ,
				'is_active'	 => 1 ,
				'rep'		 => 1 ,
			] ,
			[
				'id'		 => 4 ,
				'name'		 => 'Ambalangoda' ,
				'is_active'	 => 1 ,
				'rep'		 => 1 ,
			] ,
			[
				'id'		 => 5 ,
				'name'		 => 'Hikaduwa' ,
				'is_active'	 => 1 ,
				'rep'		 => 1 ,
			] ,
//			[
//				'id'		 => ,
//				'name'		 => '' ,
//				'is_active'	 => ,
//				'rep'		 => ,
//			],
		] ;

		foreach ( $routes as $route )
		{

			$routeO = new \Models\Route() ;

			$routeO -> id = $route[ 'id' ] ;
			$routeO -> name = $route['name'];
			$routeO -> is_active = $route['is_active'];
			$routeO -> rep = $route['rep'];
			
			$routeO -> save () ;
 		}
	}

}
