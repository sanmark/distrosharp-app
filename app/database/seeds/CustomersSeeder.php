<?php

class CustomersSeeder extends Seeder
{

	public function run ()
	{
		$customers = [
			[
				'id'		 => 1 ,
				'name'		 => 'Supun' ,
				'route_id'	 => 1 ,
				'is_active'	 => 1 ,
				'details'	 => 'Customer since 1890' ,
			] ,
			[
				'id'		 => 2 ,
				'name'		 => 'Mahesh' ,
				'route_id'	 => 1 ,
				'is_active'	 => 1 ,
				'details'	 => 'Customer since 1790' ,
			] ,
			[
				'id'		 => 3 ,
				'name'		 => 'Randika' ,
				'route_id'	 => 1 ,
				'is_active'	 => 1 ,
				'details'	 => 'Customer since 1690' ,
			] ,
//			[
//				'id'=>,
//				'name'=>'',
//				'route_id'=>,
//				'is_active'=>,
//				'details'=>''
//			],
			] ;

		foreach ( $customers as $customer )
		{
			$customerO = new \Models\Customer() ;

			$customerO -> id		 = $customer[ 'id' ] ;
			$customerO -> name		 = $customer[ 'name' ] ;
			$customerO -> route_id	 = $customer[ 'route_id' ] ;
			$customerO -> is_active	 = $customer[ 'is_active' ] ;
			$customerO -> details	 = $customer[ 'details' ] ;

			$customerO -> save () ;
		}
	}

}
