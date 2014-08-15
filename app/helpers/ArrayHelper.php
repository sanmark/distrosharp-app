<?php

class ArrayHelper
{

	public static function getValueIfKeyExistsOrNull ( $array , $key )
	{
		if ( isset ( $array[ $key ] ) )
		{
			return $array[ $key ] ;
		}

		return NULL ;
	}

	public static function getValuesIfKeysExist ( $values , $keys )
	{
		$filteredValues = NULL ;

		foreach ( $keys as $key )
		{
			if ( array_key_exists ( $key , $values ) )
			{
				$filteredValues[ $key ] = $values[ $key ] ;
			}
		}

		return $filteredValues ;
	}

	public static function pruneEmptyElements ( $array )
	{
		$prunedArray = NULL ;

		foreach ( $array as $key => $value )
		{
			if ( ! is_null ( $value ) && strlen ( $value ) > 0 )
			{
				$prunedArray[ $key ] = $value ;
			}
		}

		return $prunedArray ;
	}

}
