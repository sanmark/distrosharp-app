<?php

namespace Models ;

class Route extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function rep ()
	{
		return $this -> belongsTo ( '\User' ) ;
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

	public static function filter ( $filterValues )
	{
		$requestObject = new Route() ;

		if ( count ( $filterValues ) > 0 )
		{
			$name		 = $filterValues[ 'name' ] ;
			$isActive	 = $filterValues[ 'is_active' ] ;
			$repId		 = $filterValues[ 'rep_id' ] ;

			$requestObject = $requestObject -> where ( 'name' , 'LIKE' , '%' . $name . '%' ) ;
			if ( $repId != 0 )
			{
				$requestObject = $requestObject -> where ( 'rep_id' , '=' , $repId ) ;
			}

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
			'name'	 => [
				'required' ,
				'unique:routes'
			] ,
			'rep_id' => [ 'required' ]
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
			'rep_id' => ['required' ]
		] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	public static function getArray ( $key , $value )
	{
		$array = self::select ( $key , \DB::raw ( 'CONCAT (' . $value . ') as `value`' ) )
		-> lists ( 'value' , $key ) ;

		return $array ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		$array = self::getArray ( $key , $value ) ;

		if ( ! is_null ( $firstElement ) )
		{
			$array = $firstElement + $array ;
		}

		return $array ;
	}

}
