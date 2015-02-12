<?php

class ReturnItemButler
{

	public static function getGoodReturnQuantitiesOfAllItemsInGivenPeriod ( $firstDate , $secondDate )
	{
		$imbalanceStock		 = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;
		$sellingInvoiceIds	 = \Models\SellingInvoice::where ( 'stock_id' , '!=' , $imbalanceStock )
			-> whereBetween ( 'date_time' , [$firstDate , $secondDate ] )
			-> get () ;
		$goodReturnQuantity	 = [ ] ;
		foreach ( $sellingInvoiceIds as $sellingInvoice )
		{
			$items = \Models\SellingItem::where ( 'selling_invoice_id' , '=' , $sellingInvoice -> id ) -> get () ;
			foreach ( $items as $item )
			{
				if ( ! isset ( $goodReturnQuantity[ $item -> item_id ] ) )
				{
					$goodReturnQuantity[ $item -> item_id ] = 0 ;
				}
				$returnItemQuantity						 = \Models\SellingItem::where ( 'selling_invoice_id' , '=' , $sellingInvoice -> id )
					-> where ( 'item_id' , '=' , $item -> item_id )
					-> first () ;
				$goodReturnQuantity[ $item -> item_id ]	 = $goodReturnQuantity[ $item -> item_id ] + $returnItemQuantity -> good_return_quantity ;
			}
		}
		return $goodReturnQuantity ;
	}

}
