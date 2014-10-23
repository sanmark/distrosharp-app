<?php

namespace ValidationRules ;

class CustomValidationRules extends \Validator
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

	public function atLeastOneElementOfOneArrayHasValue ( $field , $value , $parameters )
	{
		return \ArrayHelper::hasAtLeastOneElementWithValueInAtLeastOneChildArrayRecursive ( $value ) ;
	}

	public function greaterThanOrEqualTo ( $field , $value , $parameters )
	{
		$againstValue = ( float ) $parameters[ 0 ] ;
		return $value >= $againstValue ;
	}

	public function noSpacesInString ( $attribute , $value )
	{
		return ! preg_match ( '/ /' , $value ) ;
	}

	public function allFieldsFilled ( $field , $value , $parameters )
	{
		return \ArrayHelper::areAllElementsFilled($value);
	}

}
