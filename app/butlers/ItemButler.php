<?php

class ItemButler
{

	public static function getMinimumAvailableItemCode ()
	{
		$requestObject			 = new \Models\Item() ;
		$allCodes				 = $requestObject -> lists ( 'code' ) ;
		$minimumAvailableNumber	 = \NumberHelper::getMinimumAvailableNumberFromArray ( $allCodes ) ;

		return $minimumAvailableNumber ;
	}

}
