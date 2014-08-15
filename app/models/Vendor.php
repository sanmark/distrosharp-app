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

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::save ( $attributes ) ;
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

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
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
