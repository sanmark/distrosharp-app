<?php

namespace Models ;

class Item extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

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
				'required',
				'no_spaces_in_string' ,
				'unique:items'
			] ,
			'name'					 => [
				'required' ,
				'unique:items'
			] ,
			'reorder_level'			 => ['required' ] ,
			'current_buying_price'	 => ['required' ] ,
			'current_selling_price'	 => ['required' ] ,
			'buying_invoice_order'	 => [
				'required' ,
				'unique:items'
			] ,
			'selling_invoice_order'	 => [
				'required' ,
				'unique:items'
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

			'code'	 => [
				'required' ,
				'unique:items,code,' . $this -> id
			] ,
			'name'	 => [
				'required' ,
				'unique:items,name,' . $this -> id
			] ,
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
		$filePath	 = \public_path () . '\images\product-images\/' ;

		if ( file_exists ( $filePath . $code ) )
		{
			return $baseUrl . '/images/product-images/' . $code ;
		} else
		{
			return FALSE ;
		}
	}

	public static function getMinBuyingInvoiceOrder ()
	{
		$buying_invoice_id	 = 0 ;
		$requestObject		 = new Item() ;

		$counter = 1 ;
		while ( $counter != 0 )
		{
			$result = $requestObject -> where ( 'buying_invoice_order' , '=' , $counter ) -> get () ;

			if ( count ( $result ) == 0 )
			{
				$buying_invoice_id	 = $counter ;
				$counter			 = 0 ;
			} else
			{
				$counter ++ ;
			}
		}
		return $buying_invoice_id ;
	}

	public static function getMinSellingInvoiceOrder ()
	{
		$selling_invoice_id	 = 0 ;
		$requestObject		 = new Item() ;

		$counter = 1 ;
		while ( $counter != 0 )
		{
			$result = $requestObject -> where ( 'selling_invoice_order' , '=' , $counter ) -> get () ;

			if ( count ( $result ) == 0 )
			{
				$selling_invoice_id	 = $counter ;
				$counter			 = 0 ;
			} else
			{
				$counter ++ ;
			}
		}
		return $selling_invoice_id ;
	}

}
