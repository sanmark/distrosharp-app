<?php

class StockConfirmationButler
{

	public static function getQuantitiesWhenConfirm ( $lastConfirmDate )
	{
		$confirmationId			 = \Models\StockConfirmation::where ( 'date_time' , '=' , $lastConfirmDate ) -> first () ;
		$itemsOnLastConfirm		 = \Models\StockConfirmationDetail::where ( 'stock_confirmation_id' , '=' , $confirmationId -> id ) -> get () ;
		$quantityOnLastConfirm	 = [ ] ;
		foreach ( $itemsOnLastConfirm as $value )
		{
			$quantityOnLastConfirm[ $value -> item_id ] = $value -> good_item_quantity ;
		}

		return $quantityOnLastConfirm ;
	}

	public static function getLastConfirmDateBeforeToDate ( $stock , $toDate )
	{
		$lastConfirmDate = \Models\StockConfirmation::where ( 'stock_id' , '=' , $stock )
			-> where ( 'date_time' , '<' , $toDate )
			-> max ( 'date_time' ) ;

		return $lastConfirmDate ;
	}

	public static function stockConfirmationFilter ( $filterValues )
	{
		$requestObject = new \Models\StockConfirmation() ;

		if ( count ( $filterValues ) > 0 )
		{
			$stock			 = $filterValues[ 'stock' ] ;
			$fromDateTime	 = $filterValues[ 'from_date_time' ] ;
			$toDateTime		 = $filterValues[ 'to_date_time' ] ;

			if ( strlen ( $stock ) > 0 )
			{
				if ( $stock == 0 )
				{
					$requestObject = $requestObject ;
				} else
				{
					$requestObject = $requestObject -> where ( 'stock_id' , '=' , $stock ) ;
				}
			}
			if ( strlen ( $fromDateTime ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'date_time' , '>' , $fromDateTime ) ;
			}
			if ( strlen ( $toDateTime ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'date_time' , '<' , $toDateTime ) ;
			}
		}
		return $requestObject -> orderBy ( 'date_time' , 'DESC' ) -> get () ;
	}

}
