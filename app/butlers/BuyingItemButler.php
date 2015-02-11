<?php

class BuyingItemButler
{

	public static function getPurchasedQuantitiesOfAllItemsInGivenPeriod ( $firstDate , $secondDate )
	{
		$imbalanceStock		 = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;
		$buyingInvoiceIds	 = \Models\BuyingInvoice::where ( 'stock_id' , '!=' , $imbalanceStock )
			-> whereBetween ( 'date_time' , [$firstDate , $secondDate ] )
			-> get () ;
		$purchasedQuantity	 = [ ] ;
		foreach ( $buyingInvoiceIds as $buyingInvoice )
		{
			$items = \Models\BuyingItem::where ( 'invoice_id' , '=' , $buyingInvoice -> id ) -> get () ;
			foreach ( $items as $item )
			{
				if ( ! isset ( $purchasedQuantity[ $item -> item_id ] ) )
				{
					$purchasedQuantity[ $item -> item_id ] = 0 ;
				}
				$purchase								 = \Models\BuyingItem::where ( 'invoice_id' , '=' , $buyingInvoice -> id )
					-> where ( 'item_id' , '=' , $item -> item_id )
					-> first () ;
				$purchasedQuantity[ $item -> item_id ]	 = $purchasedQuantity[ $item -> item_id ] + $purchase -> quantity ;
			}
		}
		return $purchasedQuantity ;
	}

}
