<?php

class NumberHelper
{

	public static function getMinimumAvailableNumberFromArray ( $array )
	{
		$filteredArray = array_filter ( $array , 'is_numeric' ) ;

		if ( ! empty ( $filteredArray ) )
		{
			$maxNumber = max ( $filteredArray ) ;

			$range = range ( 1 , $maxNumber ) ;

			$array_diff = array_diff ( $range , $filteredArray ) ;

			if ( empty ( $array_diff ) )
			{
				$minNumber = $maxNumber + 1 ;
			} 
			else{
				$minNumber = min ( $array_diff ) ;
			}
		} else
		{
			$minNumber = 1 ;
		}


		return $minNumber ;
	}

}
