<?php

class StringHelper
{

	public static function removeWhiteSpaces ( $string )
	{

		$leftRightTrim		 = ltrim ( $string , ' ' ) ;
		$leftRightTrim		 = rtrim ( $leftRightTrim , ' ' ) ;
		$spacesRemovedString = preg_replace ( '!\s+!' , ' ' , $leftRightTrim ) ;

		return $spacesRemovedString ;
	}

}
