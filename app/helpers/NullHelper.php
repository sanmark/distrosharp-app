<?php

class NullHelper
{

	public static function zeroIfNull ( $value )
	{
		if ( is_null ( $value ) )
		{
			return 0 ;
		}

		return $value ;
	}

	public static function zeroIfEmptyString ( $string )
	{
		if ( $string == "" )
		{
			return 0 ;
		}
		return $string ;
	}

	public static function isNullEmptyOrWhitespace ( $value )
	{
		if ( is_null ( $value ) )
		{
			return TRUE ;
		}

		if ( empty ( $value ) )
		{
			return TRUE ;
		}

		if ( strlen ( trim ( $value ) ) == 0 )
		{
			return TRUE ;
		}

		return FALSE ;
	}

	public static function nullIfEmpty ( $value )
	{
		if ( empty ( $value ) )
		{
			return NULL ;
		}

		return $value ;
	}

	public static function ifNullEmptyOrWhitespace ( $valueOne , $valueTwo )
	{
		if ( self::isNullEmptyOrWhitespace ( $valueOne ) )
		{
			return $valueTwo ;
		}

		return $valueOne ;
	}

}
