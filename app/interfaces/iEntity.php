<?php

namespace Interfaces ;

interface iEntity
{

	public function save ( array $options = array () ) ;

	public function update ( array $attributes = array () ) ;

	public static function filter ( $filterValues ) ;

	public static function getArray ( $key , $value ) ;

	public static function getArrayByIds ( $key , $value , $by ) ;

	public static function getArrayForHtmlSelectByIds ( $key , $value , $by , array $firstElement = NULL ) ;

	public static function getArrayForHtmlSelectByRequestObject ( $key , $value , \Illuminate\Database\Eloquent\Builder $requestObject , array $firstElement = NULL ) ;

	public function scopeGetArrayForHtmlSelect ( $query , $key , $value , array $firstElements = NULL ) ;
}
