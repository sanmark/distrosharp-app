<?php

namespace Models ;

class BuyingItem extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;
		parent::update ( $attributes ) ;
	}

	private function validateForSave ()
	{
		$countRows = \Models\Item::all () ;
		foreach ( $countRows as $rows )
		{

			if ( strlen ( \Input::get ( 'quantity_' . $rows -> id ) ) > 0 )
			{
				$data = $this -> toArray () ;

				$rules = [
					'buying_price_' . $rows -> id	 => [
						'numeric'
					] ,
					'quantity_' . $rows -> id		 => [
						'numeric' ,
					] ,
					'free_quantity_' . $rows -> id	 => [
						'numeric' ,
					] ,
					] ;
			}
			if ( strlen ( \Input::get ( 'quantity_' . $rows -> id ) ) == 0 )
			{
				$data = $this -> toArray () ;

				$rules = [
					'buying_price_' . $rows -> id	 => [
						'numeric'
					] ,
					'quantity_' . $rows -> id		 => [
						'numeric' ,
					] ,
					'free_quantity_' . $rows -> id	 => [
						'numeric' ,
					] ,
					] ;
			}
		}
		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	private function validateForUpdate ()
	{
		$countRows = \Models\Item::all () ;
		foreach ( $countRows as $rows )
		{

			if ( strlen ( \Input::get ( 'quantity_' . $rows -> id ) ) > 0 )
			{
				$data = $this -> toArray () ;

				$rules = [
					'buying_price_' . $rows -> id	 => [
						'numeric'
					] ,
					'quantity_' . $rows -> id		 => [
						'numeric' ,
					] ,
					'free_quantity_' . $rows -> id	 => [
						'numeric' ,
					] ,
					] ;
			}
			if ( strlen ( \Input::get ( 'quantity_' . $rows -> id ) ) == 0 )
			{
				$data = $this -> toArray () ;

				$rules = [
					'buying_price_' . $rows -> id	 => [
						'numeric'
					] ,
					'quantity_' . $rows -> id		 => [
						'numeric' ,
					] ,
					'free_quantity_' . $rows -> id	 => [
						'numeric' ,
					] ,
					] ;
			}
		}
		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public function getPurchasedQuantities ( $firstDate , $secondDate )
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

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

}
