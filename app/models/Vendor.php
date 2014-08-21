<?php

namespace Models ;

class Vendor extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}
	
	public static function getVendorsForHtmlSelect()
	{
		
		
		$vendors= Vendor::lists('name','id');
		$vendors=[0=>'Select Vendor']+$vendors;
		return $vendors;
		
	}
	
	
	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::save ( $attributes ) ;
	}
	
		public static function filter ( $filterValues )
	{
		$requestObject = new Vendor() ;

		if ( count ( $filterValues ) > 0 )
		{
			$name		 = $filterValues[ 'name' ] ;
			$isActive	 = $filterValues[ 'is_active' ] ;

			$requestObject = $requestObject -> where ( 'name' , 'LIKE' , '%' . $name . '%' ) ;


			if ( $isActive != '' )
			{
				$requestObject = $requestObject -> where ( 'is_active' , '=' , $isActive ) ;
			}
		}

		return $requestObject -> get () ;
	}
	
	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'name' => [
				'required' ,
				'unique:vendors'
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

	private function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules		 = [
			'name' => [
				'required' ,
				'unique:vendors,name,' . $this -> id
			]
		] ;
		$validator	 = \Validator::make ( $data , $rules ) ;
		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;
			throw $iie ;
		}
	}
	public static function getArray ( $key , $value )
	{
		return new \Exceptions\NotImplementedException() ;
	}

	public static function getArrayForHtmlSelect ( $key , $value, array $firstElement = NULL )
	{
		return new \Exceptions\NotImplementedException() ;
	}

}
