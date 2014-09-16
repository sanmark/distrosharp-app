<?php

namespace Models ;

class Customer extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function route ()
	{
		return $this -> belongsTo ( 'Models\Route' ) ;
	}

	public function financeAccount ()
	{
		return $this -> belongsTo ( 'Models\FinanceAccount' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;

		$financeAccount					 = new FinanceAccount() ;
		$financeAccount -> name			 = $this -> name ;
		$financeAccount -> is_active	 = $this -> is_active ;
		$financeAccount -> is_in_house	 = FALSE ;
		$financeAccount -> save () ;

		$this -> finance_account_id = $financeAccount -> id ;

		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		$financeAccount					 = FinanceAccount::findOrFail ( $this -> finance_account_id ) ;
		$financeAccount -> name			 = $this -> name ;
		$financeAccount -> is_active	 = $this -> is_active ;
		$financeAccount -> is_in_house	 = FALSE ;
		$financeAccount -> update () ;

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

}
