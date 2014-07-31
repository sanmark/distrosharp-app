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

}
