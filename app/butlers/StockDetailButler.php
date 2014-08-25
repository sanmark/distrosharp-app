<?php

class StockDetailButler
{

	public static function decreaseItemAmount ( $stockId , $itemId , $amount )
	{
		$stockDetail = self::getStockDetailByStocIdkAndItemId ( $stockId , $itemId ) ;
		$stockDetail -> good_quantity -= $amount ;
		$stockDetail -> save () ;
	}

	public static function increaseItemAmount ( $stockId , $itemId , $amount )
	{
		$stockDetail = self::getStockDetailByStocIdkAndItemId ( $stockId , $itemId ) ;
		$stockDetail -> good_quantity += $amount ;
		$stockDetail -> save () ;
	}

	public static function getStockDetailByStocIdkAndItemId ( $stockId , $itemId )
	{
		$stockDetail = Models\StockDetail::where ( 'stock_id' , '=' , $stockId )
		-> where ( 'item_id' , '=' , $itemId )
		-> first () ;

		return $stockDetail ;
	}

}
