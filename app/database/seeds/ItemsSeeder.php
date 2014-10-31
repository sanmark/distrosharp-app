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
				'buying_invoice_order'	 => 1 ,
				'selling_invoice_order'	 => 1 ,
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
				'buying_invoice_order'	 => 2 ,
				'selling_invoice_order'	 => 2 ,
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
				'buying_invoice_order'	 => 3 ,
				'selling_invoice_order'	 => 3 ,
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
//				'buying_invoice_order'	 =>  ,
//				'selling_invoice_order'	 =>  ,
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
			$itemO -> buying_invoice_order	 = $item[ 'buying_invoice_order' ] ;
			$itemO -> selling_invoice_order	 = $item[ 'selling_invoice_order' ] ;
			$itemO -> is_active				 = $item[ 'is_active' ] ;
			$itemO -> weight				 = $item[ 'weight' ] ;

			$itemO -> save () ;
		}
	}

}
