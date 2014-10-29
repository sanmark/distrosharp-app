<?php

class StockTypeButler
{

	public static function getVehicleStockType ()
	{
		$vehicleStockType = \Models\StockType::where ( 'name' , '=' , STOCK_VEHICLE ) -> first () ;
		return $vehicleStockType ;
	}

	public static function getNormalStockType ()
	{
		$normalStockType = \Models\StockType::where ( 'name' , '=' , STOCK_NORMAL ) -> first () ;
		return $normalStockType ;
	}

}
