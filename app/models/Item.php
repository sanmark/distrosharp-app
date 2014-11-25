<?php

namespace Models ;

class Item extends BaseEntity implements \Interfaces\iEntity
{

	public function stockDetail ()
	{
		return $this -> hasOne ( 'Models\StockDetail' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::save ( $attributes ) ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'code'					 => [
				'required' ,
				'no_spaces_in_string' ,
				'unique:items'
			] ,
			'name'					 => [
				'required' ,
				'unique:items'
			] ,
			'reorder_level'			 => [
				'numeric' ,
				'required'
			] ,
			'current_buying_price'	 => [
				'numeric' ,
				'required'
			] ,
			'current_selling_price'	 => [
				'numeric' ,
				'required'
			] ,
			'weight'				 => [
				'numeric'
			]
			] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [

			'code'					 => [
				'required' ,
				'no_spaces_in_string' ,
				'unique:items,code,' . $this -> id
			] ,
			'name'					 => [
				'required' ,
				'unique:items,name,' . $this -> id
			] ,
			'reorder_level'			 => [
				'numeric' ,
				'required'
			] ,
			'current_buying_price'	 => [
				'numeric' ,
				'required'
			] ,
			'current_selling_price'	 => [
				'numeric' ,
				'required'
			]
			] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public static function filter ( $filterValues )
	{
		$requestObject = new Item() ;
		if ( count ( $filterValues ) > 0 )
		{
			$code		 = $filterValues[ 'code' ] ;
			$name		 = $filterValues[ 'name' ] ;
			$isActive	 = $filterValues[ 'is_active' ] ;
			$sortBy		 = $filterValues[ 'sort_by' ] ;
			$sortOrder	 = $filterValues[ 'sort_order' ] ;

			if ( strlen ( $code ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'code' , 'LIKE' , '%' . $code . '%' ) ;
			}

			if ( strlen ( $name ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'name' , 'LIKE' , '%' . $name . '%' ) ;
			}

			if ( strlen ( $isActive ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'is_active' , '=' , $isActive ) ;
			}

			if ( strlen ( $sortBy ) > 0 && strlen ( $sortOrder ) > 0 )
			{
				$requestObject = $requestObject -> orderBy ( $sortBy , $sortOrder ) ;
			}
		}

		return $requestObject -> get () ;
	}

	/**
	 * Find the image for the item and return the url if have an image.
	 *
	 * @return string|boolean Image url if found else false.
	 */
	public function getImageUrl ()
	{
		$baseUrl	 = \URL::to ( '/' ) ;
		$code		 = $this -> code ;
		$filePath	 = \public_path () . '/tenants/' . \SessionButler::getOrganization () . '/product-images/' ;

		if ( file_exists ( $filePath . $code ) )
		{
			return $baseUrl . '/tenants/' . \SessionButler::getOrganization () . '/product-images/' . $code ;
		} else
		{
			return FALSE ;
		}
	}

	public function getTotalFreeAmountSoldForRepAndTimeRange ( $repId , $fromDate , $toDate )
	{
		return $this -> getTotalPaidOrFreeAmountSoldForRepAndTimeRange ( 'free_quantity' , $repId , $fromDate , $toDate ) ;
	}

	public function getTotalPaidAmountSoldForRepAndTimeRange ( $repId , $fromDate , $toDate )
	{
		return $this -> getTotalPaidOrFreeAmountSoldForRepAndTimeRange ( 'paid_quantity' , $repId , $fromDate , $toDate ) ;
	}

	private function getTotalPaidOrFreeAmountSoldForRepAndTimeRange ( $columnName , $repId , $fromDate , $toDate )
	{
		$firstDate	 = \SellingInvoiceButler::getFirstSellingInvoiceDate () ;
		$today		 = date ( 'Y-m-d' ) ;

		$fromDate	 = \NullHelper::ifNullEmptyOrWhitespace ( $fromDate , $firstDate ) ;
		$toDate		 = \NullHelper::ifNullEmptyOrWhitespace ( $toDate , $today ) ;

		$fromDateTime	 = \DateTimeHelper::convertTextToFormattedDateTime ( $fromDate . ' 00:00:00' ) ;
		$toDateTime		 = \DateTimeHelper::convertTextToFormattedDateTime ( $toDate . ' 23:59:59' ) ;
		$repIds			 = [ $repId ] ;

		if ( \NullHelper::isNullEmptyOrWhitespace ( $repId ) )
		{
			$repIds = \Models\SellingInvoice::distinct ()
				-> lists ( 'rep_id' ) ;
		}

		$sellingInvoices = SellingInvoice::whereIn ( 'rep_id' , $repIds )
			-> whereBetween ( 'date_time' , [$fromDateTime , $toDateTime ] )
			-> get () ;

		$totalAmountSold = 0 ;

		foreach ( $sellingInvoices as $sellingInvoice )
		{
			$sellingInvoice -> load ( 'sellingItems' ) ;

			foreach ( $sellingInvoice -> sellingItems as $sellingItem )
			{
				if ( $sellingItem -> item_id == $this -> id )
				{
					$totalAmountSold += $sellingItem[ $columnName ] ;
				}
			}
		}

		return $totalAmountSold ;
	}

	public function getCost ( $items , $quantity , $column )
	{
		$itemCost = [ ] ;
		if ( count ( $quantity ) == 0 )
		{
			return $itemCost ;
		}
		foreach ( $items as $item )
		{
			$itemPrice = \Models\Item::find ( $item -> item_id ) ;
			if ( ! isset ( $quantity[ $item -> item_id ] ) )
			{
				$itemCost[ $item -> item_id ] = 'None' ;
			} else
			{
				$itemCost[ $item -> item_id ] = number_format ( ( float ) $quantity[ $item -> item_id ] * $itemPrice -> $column , 2 ) ;
			}
		}
		return $itemCost ;
	}

}
