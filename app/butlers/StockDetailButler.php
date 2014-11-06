<?php

class StockDetailButler
{

	public static function decreaseGoodQuantity ( $stockId , $itemId , $amount )
	{
		$stockDetail = self::getStockDetailByStocIdkAndItemId ( $stockId , $itemId ) ;
		$stockDetail -> good_quantity -= $amount ;
		$stockDetail -> save () ;
	}

	public static function increaseGoodQuantity ( $stockId , $itemId , $amount )
	{
		$stockDetail = self::getStockDetailByStocIdkAndItemId ( $stockId , $itemId ) ;
		$stockDetail -> good_quantity += $amount ;
		$stockDetail -> save () ;
	}

	public static function decreaseReturnQuantity ( $stockId , $itemId , $amount )
	{
		$stockDetail = self::getStockDetailByStocIdkAndItemId ( $stockId , $itemId ) ;
		$stockDetail -> return_quantity -= $amount ;
		$stockDetail -> save () ;
	}

	public static function increaseReturnQuantity ( $stockId , $itemId , $amount )
	{
		$stockDetail = self::getStockDetailByStocIdkAndItemId ( $stockId , $itemId ) ;
		$stockDetail -> return_quantity += $amount ;
		$stockDetail -> save () ;
	}

	public static function getStockDetailByStocIdkAndItemId ( $stockId , $itemId )
	{
		$stockDetail = Models\StockDetail::where ( 'stock_id' , '=' , $stockId )
			-> where ( 'item_id' , '=' , $itemId )
			-> first () ;

		return $stockDetail ;
	}

	public static function createStockItems ($stockId)
	{
		$allItems = \Models\Item::where ( 'is_active' , '=' , TRUE ) -> get () ;

		foreach ( $allItems as $item )
		{
			$stockDetails				 = new \Models\StockDetail() ;
			$stockDetails -> stock_id	 = $stockId ;
			$stockDetails -> item_id	 = $item -> id ;

			$stockDetails -> save () ;
		}
	}

}
