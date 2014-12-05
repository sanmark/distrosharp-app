<?php

class StockButler
{

	public static function getItemsForUnload ( $fromStockId )
	{

		$lastUnloadTime = \Models\Transfer::where ( 'from_stock_id' , '=' , $fromStockId ) -> max ( 'date_time' ) ;
		if ( $lastUnloadTime == null )
		{
			$lastLoadingsAfterUnload = \Models\Transfer::where ( 'to_stock_id' , '=' , $fromStockId )
				-> get () ;
		} else
		{
			$lastLoadingsAfterUnload = \Models\Transfer::where ( 'to_stock_id' , '=' , $fromStockId )
				-> where ( 'date_time' , '>' , $lastUnloadTime )
				-> get () ;
		}

		$itemIdArray = [ ] ;
		foreach ( $lastLoadingsAfterUnload as $loading )
		{
			$itemIds = \Models\TransferDetail::where ( 'transfer_id' , '=' , $loading -> id ) -> get () ;
			foreach ( $itemIds as $id )
			{
				array_push ( $itemIdArray , $id -> item_id ) ;
			}
		}
		$notZeroItemIds	 = \Models\StockDetail::where ( 'good_quantity' , '>' , 0 ) -> where ( 'stock_id' , '=' , $fromStockId )
			-> lists ( 'item_id' ) ;
		$LoadedItems	 = array_values ( array_unique ( array_merge ( $itemIdArray , $notZeroItemIds ) ) ) ;
		return $LoadedItems ;
	}

}
