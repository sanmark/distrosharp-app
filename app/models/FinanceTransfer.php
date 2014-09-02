<?php

namespace Models ;

class FinanceTransfer extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function toAccount ()
	{
		return $this -> belongsTo ( 'Models\FinanceAccount' , 'to_id' ) ;
	}

	public function fromAccount ()
	{
		return $this -> belongsTo ( 'Models\FinanceAccount' , 'from_id' ) ;
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

	public function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'	 => [
				'required'
			] ,
			'amount'	 => [
				'required' ,
				'numeric'
			] ,
			'from_id'	 => [
				'different:to_id'
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
