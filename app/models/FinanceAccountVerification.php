<?php

namespace Models ;

class FinanceAccountVerification extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'			 => [
				'required'
			] ,
			'amount'			 => [
				'required' ,
				'numeric'
			] ,
			'finance_account_id' => [
				'required' ,
				'numeric'
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

}
