<?php

class SellingItemButler
{

	public static function getSoldQuantitiesOfAllItemsInGivenPeriod ( $firstDate , $secondDate )
	{
		$imbalanceStock		 = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;
		$sellingInvoiceIds	 = \Models\SellingInvoice::where ( 'stock_id' , '!=' , $imbalanceStock )
			-> whereBetween ( 'date_time' , [$firstDate , $secondDate ] )
			-> get () ;

		$soldQuantity = [ ] ;
		foreach ( $sellingInvoiceIds as $sellingInvoice )
		{
			$items = \Models\SellingItem::where ( 'selling_invoice_id' , '=' , $sellingInvoice -> id ) -> get () ;
			foreach ( $items as $item )
			{

				if ( ! isset ( $soldQuantity[ $item -> item_id ] ) )
				{
					$soldQuantity[ $item -> item_id ] = 0 ;
				}
				$sold								 = \Models\SellingItem::where ( 'selling_invoice_id' , '=' , $sellingInvoice -> id )
					-> where ( 'item_id' , '=' , $item -> item_id )
					-> first () ;
				$soldQuantity[ $item -> item_id ]	 = $soldQuantity[ $item -> item_id ] + $sold -> paid_quantity ;
			}
		}
		return $soldQuantity ;
	}

}
