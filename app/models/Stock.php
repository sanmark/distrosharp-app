<?php

namespace Models ;

class Stock extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function stockDetails ()
	{
		return $this -> hasMany ( 'Models\StockDetail' ) ;
	}

	public function incharge ()
	{
		return $this -> belongsTo ( 'User' , 'incharge_id' ) ;
	}

	public function stockType ()
	{
		return $this -> belongsTo ( 'Models\StockType' ) ;
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

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::update ( $attributes ) ;
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

	private function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'id'			 => [
				'required' ,
				'unique:stocks,id,' . $this -> id
			] ,
			'name'			 => [
				'required' ,
				'unique:stocks,name,' . $this -> id
			] ,
			'incharge_id'	 => [
				'required' ,
				'unique:stocks,incharge_id,' . $this -> id
			] ,
			'stock_type_id'	 => [
				'required'
			]
		] ;

		$messages = [
			'incharge_id.unique' => 'This user has already been assigned to another stock.'
		] ;

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
