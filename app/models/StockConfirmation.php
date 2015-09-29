<?php

namespace Models ;

class StockConfirmation extends BaseEntity implements \Interfaces\iEntity
{

	public function stock ()
	{
		return $this -> belongsTo ( 'Models\Stock' ) ;
	}

	public function stockConfirmationFilter ( $filterValues )
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
