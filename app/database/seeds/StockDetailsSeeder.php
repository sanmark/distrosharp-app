<?php

class StockDetailsSeeder extends Seeder
{

	public function run ()
	{
		$stockDetails = [ ] ;

		$stocks	 = Models\Stock::all () ;
		$items	 = Models\Item::all () ;

		$stockItemId = 0 ;

		foreach ( $stocks as $stock )
		{
			foreach ( $items as $item )
			{
				$stockItemId ++ ;

				$newStockItem = [
					'id'		 => $stockItemId ,
					'stock_id'	 => $stock -> id ,
					'item_id'	 => $item -> id
					] ;

				$newStockItem[ 'good_quantity' ]	 = 100 ;
				$newStockItem[ 'return_quantity' ]	 = 0 ;

				$stockDetails[] = $newStockItem ;
			}
		}

		foreach ( $stockDetails as $stockDetail )
		{
			$stockDetailsO = new \Models\StockDetail() ;

			$stockDetailsO -> id				 = $stockDetail[ 'id' ] ;
			$stockDetailsO -> stock_id			 = $stockDetail[ 'stock_id' ] ;
			$stockDetailsO -> item_id			 = $stockDetail[ 'item_id' ] ;
			$stockDetailsO -> good_quantity		 = $stockDetail[ 'good_quantity' ] ;
			$stockDetailsO -> return_quantity	 = $stockDetail[ 'return_quantity' ] ;

			$stockDetailsO -> save () ;
		}
	}

}
