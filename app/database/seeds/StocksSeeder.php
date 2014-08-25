<?php

class StocksSeeder extends Seeder
{

	public function run ()
	{
		$stocks = [
			[
				'id'			 => 1 ,
				'name'			 => 'Main' ,
				'incharge'		 => 1 ,
				'stock_type_id'	 => 2 ,
			] ,
			[
				'id'			 => 2 ,
				'name'			 => 'Backup' ,
				'incharge'		 => 2 ,
				'stock_type_id'	 => 2 ,
			] ,
//			[
//				'id'			 =>  ,
//				'name'			 => '' ,
//				'incharge'		 =>  ,
//				'stock_type_id'	 =>  ,
//			] ,
		] ;
		foreach ( $stocks as $stock )
		{
			$stockO					 = new \Models\Stock() ;
			$stockO -> id			 = $stock[ 'id' ] ;
			$stockO -> name			 = $stock[ 'name' ] ;
			$stockO -> incharge		 = $stock[ 'incharge' ] ;
			$stockO -> stock_type_id = $stock[ 'stock_type_id' ] ;

			$stockO -> save () ;
		}
	}

}
