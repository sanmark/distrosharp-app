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

	public static function getArrayForHtmlSelectByIds ( $key , $value , $by , array $firstElement = NULL )
	{
		$array = self::getArrayByIds ( $key , $value , $by ) ;

		if ( ! is_null ( $firstElement ) )
		{
			$array = $firstElement + $array ;
		}

		return $array ;
	}

	public static function getArrayForHtmlSelectByRequestObject ( $key , $value , \Illuminate\Database\Eloquent\Builder $requestObject , array $firstElement = NULL )
	{
		$requestObject	 = $requestObject -> select ( $key , \DB::raw ( 'CONCAT (' . $value . ') as `value`' ) ) ;
		$array			 = $requestObject -> lists ( 'value' , $key ) ;

		if ( ! is_null ( $firstElement ) )
		{
			$array = $firstElement + $array ;
		}

		return $array ;
	}

	public function scopeGetArrayForHtmlSelect ( $query , $key , $value , array $firstElements = NULL )
	{
		$query	 = $query -> select ( $key , \DB::raw ( 'CONCAT (' . $value . ') as `value`' ) ) ;
		$array	 = $query -> lists ( 'value' , $key ) ;

		if ( ! is_null ( $firstElements ) )
		{
			$array = $firstElements + $array ;
		}

		return $array ;
	}

}
