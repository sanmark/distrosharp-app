<?php

namespace Models ;

class Vendor extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;

		$financeAccount					 = new \Models\FinanceAccount() ;
		$financeAccount -> name			 = $this -> name ;
		$financeAccount -> is_active	 = TRUE ;
		$financeAccount -> is_in_house	 = FALSE ;
		$financeAccount -> save () ;

		$this -> finance_account_id = $financeAccount -> id ;

		parent::save ( $options ) ;
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

}
