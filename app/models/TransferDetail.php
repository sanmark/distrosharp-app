<?php

namespace Models ;

class TransferDetail extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArray ( $key , $value )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

}
