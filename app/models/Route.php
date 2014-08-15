<?php

namespace Models ;

class Route extends \Eloquent implements \Interfaces\iEntity
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
			'name'	 => [
				'required' ,
				'unique:routes'
			] ,
			'rep'	 => [ 'required' ]
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

		$rules = [
			'name'	 => [
				'required' ,
				'unique:routes,name,' . $this -> id
			] ,
			'rep'	 => ['required' ]
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
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArray ( $key , $value )
	{
		$array = self::select ( $key , \DB::raw ( 'CONCAT (' . $value . ') as `value`' ) )
		-> lists ( 'value' , $key ) ;

		return $array ;
	}

	public static function getArrayForHtmlSelect ( $key , $value )
	{
		$array = self::getArray ( $key , $value ) ;

		$anyElemet = [
			'0' => 'Any'
		] ;

		$array = $anyElemet + $array ;

		return $array ;
	}

}
