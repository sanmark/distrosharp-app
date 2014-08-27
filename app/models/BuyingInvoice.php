<?php

namespace Models ;

class BuyingInvoice extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function stock ()
	{
		return $this -> belongsTo ( 'Models\Stock' ) ;
	}

	public function vendor ()
	{
		return $this -> belongsTo ( 'Models\Vendor' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date'					 => [
				'required'
			] ,
			'vendor_id'				 => [
				'required' ,
			] ,
			'printed_invoice_num'	 => [
				'required' ,
			] ,
			'stock_id'				 => [
				'required' ,
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

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;
		parent::update ( $attributes ) ;
	}

	public function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date'					 => [
				'required' ,
			] ,
			'vendor_id'				 => [
				'required' ,
			] ,
			'printed_invoice_num'	 => [
				'required' ,
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
		$requestObject = new BuyingInvoice() ;

		if ( count ( $filterValues ) > 0 )
		{
			$id			 = $filterValues[ 'id' ] ;
			$vendorId	 = $filterValues[ 'vendor_id' ] ;
			$date		 = $filterValues[ 'date' ] ;
			$isPaid		 = $filterValues[ 'is_paid' ] ;
			$sortBy		 = $filterValues[ 'sort_by' ] ;
			$sortOrder	 = $filterValues[ 'sort_order' ] ;
			$stockId	 = $filterValues[ 'stock_id' ] ;

			if ( strlen ( $id ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'id' , '=' , $id ) ;
			}
			if ( strlen ( $vendorId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'vendor_id' , '=' , $vendorId ) ;
			}
			if ( ! empty ( $date ) )
			{
				$requestObject = $requestObject -> where ( 'date' , '=' , $date ) ;
			}
			if ( $stockId != '' )
			{
				$requestObject = $requestObject -> where ( 'stock_id' , '=' , $stockId ) ;
			}
			if ( $isPaid == TRUE )
			{
				$requestObject = $requestObject -> where ( 'completely_paid' , '=' , 1 ) ;
			}
			if ( strlen ( $sortBy ) > 0 && strlen ( $sortOrder ) > 0 )
			{
				$requestObject = $requestObject -> orderBy ( $sortBy , $sortOrder ) ;
			}
		}
		return $requestObject -> get () ;
	}

	public static function getArray ( $key , $value )
	{
		throw new \Exceptions\NotImplementedException () ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		throw new \Exceptions\NotImplementedException () ;
	}

}
