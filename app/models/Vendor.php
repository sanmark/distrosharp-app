<?php

namespace Models ;

class Vendor extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::save ( $attributes ) ;
	}

	public static function filter ( $filterValues )
	{
		$requestObject = new Vendor() ;

		if ( count ( $filterValues ) > 0 )
		{
			$name		 = $filterValues[ 'name' ] ;
			$isActive	 = $filterValues[ 'is_active' ] ;

			$requestObject = $requestObject -> where ( 'name' , 'LIKE' , '%' . $name . '%' ) ;


			if ( $isActive != '' )
			{
				$requestObject = $requestObject -> where ( 'is_active' , '=' , $isActive ) ;
			}
		}

		return $requestObject -> get () ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'name' => [
				'required' ,
				'unique:vendors'
			]
		] ;

		$validator = \Validator::make ( $data , $rules ) ;
		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	private function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules		 = [
			'name' => [
				'required' ,
				'unique:vendors,name,' . $this -> id
			]
		] ;
		$validator	 = \Validator::make ( $data , $rules ) ;
		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;
			throw $iie ;
		}
	}

	public static function getArray ( $key , $value )
	{
		$array = self::select ( $key , \DB::raw ( 'CONCAT (' . $value . ') AS `value`' ) )
		-> lists ( 'value' , $key ) ;

		return $array ;
	}

	public static function getArrayByIds ( $key , $value , $ids )
	{


		if ( count ( $ids ) > 0 && ( $ids[ 0 ] > 0 || $ids[ 0 ] != null) )
		{
			$array = self::whereIn ( 'id' , $ids )
			-> select ( $key , \DB::raw ( 'CONCAT(' . $value . ')as `value`' ) )
			-> lists ( 'value' , $key ) ;

			return $array ;
		}
		return [ ] ;
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

	public static function getArrayForHtmlSelectByIds ( $key , $value , $by )
	{
		$array = self::getArrayByIds ( $key , $value , $by ) ;

		$anyElemet = [
			NULL => 'Any'
		] ;

		$array = $anyElemet + $array ;

		return $array ;
	}

}
