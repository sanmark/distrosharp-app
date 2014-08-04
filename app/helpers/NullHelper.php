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

}
