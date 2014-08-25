<?php

namespace ValidationRules ;

class CustomValidationRules
{

	public function hashMatch ( $field , $value , $parameters )
	{
		$originalPassword = $parameters[ 0 ] ;

		return \Hash::check ( $value , $originalPassword ) ;
	}

	public function atLeastOneArrayElementHasValue ( $field , $value , $parameters )
	{
		foreach ( $value as $element )
		{
			if ( ! \NullHelper::isNullEmptyOrWhitespace ( $element ) )
			{
				return TRUE ;
			}
		}

		return FALSE ;
	}

}
