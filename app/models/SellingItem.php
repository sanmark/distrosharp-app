<?php

namespace Models ;

class SellingItem extends BaseEntity implements \Interfaces\iEntity
{

	public function sellingInvoice ()
	{
		return $this -> belongsTo ( 'Models\SellingInvoice' ) ;
	}

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' ) ;
	}

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public function getSoldQuantities ( $firstDate , $secondDate )
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

	public function getGoodReturnQuantities ( $firstDate , $secondDate )
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

	public function getSalesLineTotal ()
	{
		$lineTotal = $this -> price * $this -> paid_quantity ;

		return $lineTotal ;
	}

	public function getReturnLineTotal ()
	{
		$lineTotal = ($this -> good_return_price * $this -> good_return_quantity) + ($this -> company_return_price * $this -> company_return_quantity);

		return $lineTotal ;
	}

}
