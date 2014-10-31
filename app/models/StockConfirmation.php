<?php

namespace Models ;

class StockConfirmation extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function getQuantitiesWhenConfirm ( $lastConfirmDate )
	{
		$confirmationId			 = $this -> where ( 'date_time' , '=' , $lastConfirmDate ) -> first () ;
		$itemsOnLastConfirm		 = \Models\StockConfirmationDetail::where ( 'stock_confirmation_id' , '=' , $confirmationId -> id ) -> get () ;
		$quantityOnLastConfirm	 = [ ] ;
		foreach ( $itemsOnLastConfirm as $value )
		{
			$quantityOnLastConfirm[ $value -> item_id ] = $value -> good_item_quantity ;
		}

		return $quantityOnLastConfirm ;
	}

}
