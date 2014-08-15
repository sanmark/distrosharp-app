<?php

namespace Models ;

class Bank extends \Eloquent implements \Interfaces\iEntity
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

	public function validateForSave ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name' => [
				'required' ,
				'unique:banks'
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

	public function validateForUpdate ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name' => [
				'required' ,
				'unique:banks,name,' . $this -> id
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
		return new \Exceptions\NotImplementedException();
	}

}
