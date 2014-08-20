<?php

class StockDetailsSeeder extends Seeder
{

	public function run ()
	{
		$stockDetails = [
			[
				'id'				 => 1 ,
				'stock_id'			 => 1 ,
				'item_id'			 => 1 ,
				'good_quantity'		 => 0 ,
				'return_quantity'	 => 0 ,
			] ,
			[
				'id'				 => 2 ,
				'stock_id'			 => 1 ,
				'item_id'			 => 2 ,
				'good_quantity'		 => 0 ,
				'return_quantity'	 => 0 ,
			] ,
			[
				'id'				 => 3 ,
				'stock_id'			 => 1 ,
				'item_id'			 => 3 ,
				'good_quantity'		 => 0 ,
				'return_quantity'	 => 0 ,
			]
//			[
//				'id'				 =>  ,
//				'stock_id'			 =>  ,
//				'item_id'			 =>  ,
//				'good_quantity'		 =>  ,
//				'return_quantity'	 =>  ,
//			] ,
		] ;

		foreach ( $stockDetails as $stockDetail )
		{
			$stockDetailsO = new \Models\StockDetail() ;

			$stockDetailsO -> id				 = $stockDetail[ 'id' ] ;
			$stockDetailsO -> stock_id			 = $stockDetail[ 'stock_id' ] ;
			$stockDetailsO -> item_id			 = $stockDetail[ 'item_id' ] ;
			$stockDetailsO -> good_quantity		 = $stockDetail[ 'good_quantity' ] ;
			$stockDetailsO -> return_quantity	 = $stockDetail[ 'return_quantity' ] ;
			
			$stockDetailsO->save();
		}
	}

}
