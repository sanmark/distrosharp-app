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

}
