<?php

namespace Models ;

class Customer extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function route ()
	{
		return $this -> belongsTo ( 'Models\Route' ) ;
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
		$requestObject = new Customer() ;

		if ( count ( $filterValues ) > 0 )
		{
			$name		 = $filterValues[ 'name' ] ;
			$routeId	 = $filterValues[ 'route' ] ;
			$isActive	 = $filterValues[ 'is_active' ] ;

			$requestObject = $requestObject -> where ( 'name' , 'LIKE' , '%' . $name . '%' ) ;

			if ( $routeId > 0 )
			{
				$requestObject = $requestObject -> whereHas ( 'route' , function($r) use($routeId)
				{
					$r -> where ( 'id' , '=' , $routeId ) ;
				} ) ;
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
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name'		 => [
				'required' ,
				'unique:customers'
			] ,
			'route_id'	 => [ 'required' ] ,
			'is_active'	 => [ 'required' ] ,
		] ;
		$validator	 = \Validator::make ( $data , $rules ) ;

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
			'name'		 => [
				'required' ,
				'unique:customers,name,' . $this -> id
			] ,
			'route_id'	 => [ 'required' ] ,
			'is_active'	 => [ 'required' ] ,
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
		return new \Exceptions\NotImplementedException() ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		return new \Exceptions\NotImplementedException() ;
	}

}
