<?php

namespace Models ;

class Item extends \Eloquent implements \Interfaces\iEntity
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
				'required' ,
				'unique:items'
			] ,
			'name'					 => [
				'required' ,
				'unique:items'
			] ,
			'reorder_level'			 => ['required' ] ,
			'current_buying_price'	 => ['required' ] ,
			'current_selling_price'	 => ['required' ] ,
			'buying_invoice_order'	 => ['required' ] ,
			'selling_invoice_order'	 => ['required' ]
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
				'unique:items,code,' . $this -> id
			] ,
			'name'					 => [
				'required' ,
				'unique:items,name,' . $this -> id
			] ,
			'reorder_level'			 => ['required' ] ,
			'current_buying_price'	 => ['required' ] ,
			'current_selling_price'	 => ['required' ] ,
			'buying_invoice_order'	 => ['required' ] ,
			'selling_invoice_order'	 => ['required' ]
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
			$code			 = $filterValues[ 'code' ] ;
			$name			 = $filterValues[ 'name' ] ;
			$isActive		 = $filterValues[ 'is_active' ] ;
			$sortBy			 = $filterValues[ 'sort_by' ] ;
			$sortOrder		 = $filterValues[ 'sort_order' ] ;
			$requestObject	 = $requestObject -> where ( 'code' , 'LIKE' , '%' . $code . '%' )
			-> where ( 'name' , 'LIKE' , '%' . $name . '%' )
			-> orderBy ( $sortBy , $sortOrder ) ;

			if ( $isActive != '' )
			{
				$requestObject = $requestObject -> where ( 'is_active' , '=' , $isActive )
				-> orderBy ( $sortBy , $sortOrder ) ;
			}
		}
		return $requestObject -> get () ;
	}

	public static function getArray ( $key , $value )
	{
		return new \Exceptions\NotImplementedException() ;
	}

	public static function getArrayForHtmlSelect ( $key , $value )
	{
		return new \Exceptions\NotImplementedException() ;
	}

}
