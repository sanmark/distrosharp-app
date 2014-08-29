<?php

namespace Models ;

class BaseEntity extends \Eloquent implements \Interfaces\iEntity
{

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArray ( $key , $value )
	{
		$array = self::select ( $key , \DB::raw ( 'CONCAT (' . $value . ') as `value`' ) )
		-> lists ( 'value' , $key ) ;

		return $array ;
	}

	public static function getArrayByIds ( $key , $value , $by )
	{
		$array = [ ] ;

		if ( count ( $by ) > 0 )
		{
			$array = self::whereIn ( 'id' , $by )
			-> select ( $key , \DB::raw ( 'CONCAT(' . $value . ')as `value`' ) )
			-> lists ( 'value' , $key ) ;
			return $array ;
		}

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

	public static function getArrayForHtmlSelectByIds ( $key , $value , $by , array $firstElement = NULL )
	{
		$array = self::getArrayByIds ( $key , $value , $by ) ;

		if ( ! is_null ( $firstElement ) )
		{
			$array = $firstElement + $array ;
		}

		return $array ;
	}

}
