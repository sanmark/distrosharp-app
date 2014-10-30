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
		return \ArrayHelper::areAllElementsHasValue( $value ) ;
	}
	
	public function aVehicleStock ( $field , $value , $parameters )
	{
		$fromStock			 = \Models\Stock::findOrFail ( $value ) ;
		$vehicleStockType	 = \StockTypeButler::getVehicleStockType () ;
		if ( $fromStock -> stock_type_id == $vehicleStockType -> id )
		{
			return TRUE ;
		}
		return FALSE ;
	}

	public function aNormalStock ( $field , $value , $parameters )
	{
		$toStock		 = \Models\Stock::findOrFail ( $value ) ;
		$normalStockType = \StockTypeButler::getNormalStockType () ;
		if ( $toStock -> stock_type_id == $normalStockType -> id )
		{
			return TRUE ;
		}
		return FALSE ;
	}

}
