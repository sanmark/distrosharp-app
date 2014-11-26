<?php

class ItemsSeeder extends Seeder
{

	public function run ()
	{
		$items = [
			[
				'id'					 => 1 ,
				'code'					 => 'c1' ,
				'name'					 => 'P One' ,
				'reorder_level'			 => 50 ,
				'current_buying_price'	 => 98 ,
				'current_selling_price'	 => 100 ,
				'is_active'				 => 1 ,
				'weight'				 => 230.50 ,
			] ,
			[
				'id'					 => 2 ,
				'code'					 => 'c2' ,
				'name'					 => 'P Two' ,
				'reorder_level'			 => 40 ,
				'current_buying_price'	 => 96 ,
				'current_selling_price'	 => 98 ,
				'is_active'				 => 1 ,
				'weight'				 => 200.50 ,
			] ,
			[
				'id'					 => 3 ,
				'code'					 => 'c3' ,
				'name'					 => 'P Three' ,
				'reorder_level'			 => 30 ,
				'current_buying_price'	 => 94 ,
				'current_selling_price'	 => 96 ,
				'is_active'				 => 1 ,
				'weight'				 => 500.00 ,
			] ,
//			[
//				'id'					 =>  ,
//				'code'					 => '' ,
//				'name'					 => '' ,
//				'reorder_level'			 =>  ,
//				'current_buying_price'	 =>  ,
//				'current_selling_price'	 =>  ,
//				'is_active'				 =>  ,
//			] ,
			] ;

		foreach ( $items as $item )
		{
			$itemO = new \Models\Item() ;

			$itemO -> id					 = $item[ 'id' ] ;
			$itemO -> code					 = $item[ 'code' ] ;
			$itemO -> name					 = $item[ 'name' ] ;
			$itemO -> reorder_level			 = $item[ 'reorder_level' ] ;
			$itemO -> current_buying_price	 = $item[ 'current_buying_price' ] ;
			$itemO -> current_selling_price	 = $item[ 'current_selling_price' ] ;
			$itemO -> is_active				 = $item[ 'is_active' ] ;
			$itemO -> weight				 = $item[ 'weight' ] ;

			$itemO -> save () ;
		}
	}

}
