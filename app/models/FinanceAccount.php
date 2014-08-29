<?php

namespace Models ;

class FinanceAccount extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function bank ()
	{

		return $this -> belongsTo ( 'Models\Bank' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;
		parent::update ( $attributes ) ;
	}

	public function validateForUpdate ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name' => [
				'required'
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

	public function validateForSave ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name' => [
				'required'
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
		$requestObject = new FinanceAccount() ;

		if ( count ( $filterValues ) > 0 )
		{
			$name		 = $filterValues [ 'name' ] ;
			$bankId		 = $filterValues [ 'bank_id' ] ;
			$isInHouse	 = $filterValues [ 'is_in_house' ] ;
			$isActive	 = $filterValues [ 'is_active' ] ;

			if ( strlen ( $name ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'name' , 'LIKE' , "%$name%" ) ;
			}
			if ( ! empty ( $bankId ) )
			{
				if ( $bankId == 'none' )
				{
					$requestObject = $requestObject -> whereNull ( 'bank_id' ) ;
				} else
				{
					$requestObject = $requestObject -> where ( 'bank_id' , '=' , $bankId ) ;
				}
			}
			if ( $isInHouse != '' )
			{
				$requestObject = $requestObject -> where ( 'is_in_house' , '=' , $isInHouse ) ;
			}
			if ( $isActive != '' )
			{
				$requestObject = $requestObject -> where ( 'is_active' , '=' , $isActive ) ;
			}
		}
		return $requestObject -> get () ;
	}

	public static function getArray ( $key , $value )
	{
		throw \Exceptions\NotImplementedException () ;
	}

	public static function getArrayForHtmlSelect ( $key , $value )
	{
		throw \Exceptions\NotImplementedException () ;
	}

}
