<?php

class StocksSeeder extends Seeder
{

	public function run ()
	{
		$stocks = [
			[
				'id'			 => 1 ,
				'name'			 => 'Main' ,
				'incharge_id'	 => 1 ,
				'stock_type_id'	 => 2 ,
			] ,
			[
				'id'			 => 2 ,
				'name'			 => 'SP AB-1234' ,
				'incharge_id'	 => 2 ,
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
