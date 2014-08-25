<?php

namespace Models ;

class Stock extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function stockDetails ()
	{
		return $this -> hasMany ( 'Models\StockDetail' ) ;
	}

	public function totalItemQuantities ()
	{
		$goodQuantity	 = $this -> goodQuantities () ;
		$returnQuantity	 = $this -> returnQuantities () ;

		$totalItemQuantity = \ArrayHelper::AddSameKeyElements ( $goodQuantity , $returnQuantity ) ;

		return $totalItemQuantity ;
	}

	public function goodQuantities ()
	{
		$this -> load ( 'stockDetails' ) ;
		$stockDetails = $this -> stockDetails ;

		$goodQuantity = $stockDetails -> lists ( 'good_quantity' , 'item_id' ) ;

		return $goodQuantity ;
	}

	public function returnQuantities ()
	{
		$this -> load ( 'stockDetails' ) ;
		$stockDetails = $this -> stockDetails ;

		$returnQuantity = $stockDetails -> lists ( 'return_quantity' , 'item_id' ) ;

		return $returnQuantity ;
	}

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArray ( $key , $value )
	{
		$array = self::select ( $key , \DB::raw ( 'CONCAT (' . $value . ') AS `value`' ) )
		-> lists ( 'value' , $key ) ;

		return $array ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		$array = self::getArray ( $key , $value ) ;

		if ( ! is_null ( $firstElement ) )
		{
			$array = $firstElement + $array ;
		}

		return $array ;
	}

}
