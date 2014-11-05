<?php

class InputButler
{

	public static function get ( $key )
	{
		$value = Input::get ( $key ) ;

		if ( is_null ( $value ) )
		{
			return $value ;
		}

		if ( is_array ( $value ) )
		{
			$value = ArrayHelper::removeWhiteSpacesInValuesRecursive ( $value ) ;
		} else
		{
			$value = StringHelper::removeWhiteSpaces ( $value ) ;
		}

		return $value ;
	}

}
