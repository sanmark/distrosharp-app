<?php

namespace Models ;

class FinanceTransfer extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	public function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'	 => [
				'required'
			] ,
			'amount'	 => [
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
