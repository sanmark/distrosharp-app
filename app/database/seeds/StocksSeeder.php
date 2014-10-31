<?php

class StocksSeeder extends Seeder
{

	public function run ()
	{
		$stocks = [
			[
				'id'			 => 1 ,
				'name'			 => 'Main' ,
				'incharge_id'	 => 3 ,
				'stock_type_id'	 => 1 ,
			] ,
			[
				'id'			 => 2 ,
				'name'			 => 'Imbalance' ,
				'incharge_id'	 => 2 ,
				'stock_type_id'	 => 1 ,
			] ,
			[
				'id'			 => 3 ,
				'name'			 => 'SP-1234' ,
				'incharge_id'	 => 1 ,
				'stock_type_id'	 => 2 ,
			] ,
			[
				'id'			 => 4 ,
				'name'			 => 'BBD-2545' ,
				'incharge_id'	 => 4 ,
				'stock_type_id'	 => 2 ,
			] ,
//			[
//				'id'			 =>  ,
//				'name'			 => '' ,
//				'incharge_id'	 =>  ,
//				'stock_type_id'	 =>  ,
//			] ,
			] ;
		foreach ( $stocks as $stock )
		{
			$stockO					 = new \Models\Stock() ;
			$stockO -> id			 = $stock[ 'id' ] ;
			$stockO -> name			 = $stock[ 'name' ] ;
			$stockO -> incharge_id	 = $stock[ 'incharge_id' ] ;
			$stockO -> stock_type_id = $stock[ 'stock_type_id' ] ;

			$stockO -> save () ;
		}
	}

}
