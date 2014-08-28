<?php

namespace Models ;

class SellingInvoice extends \Eloquent
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> date_time			 = \DateTimeHelper::convertTextToFormattedDateTime ( $this -> date_time , 'Y-m-d H:i:s' ) ;
		$this -> is_completely_paid	 = \NullHelper::zeroIfNull ( $this -> is_completely_paid ) ;

		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'date_time'				 => [
				'required' ,
				'date_format:Y-m-d H:i:s'
			] ,
			'customer_id'			 => [
				'required' ,
				'numeric'
			] ,
			'rep_id'				 => [
				'required' ,
				'numeric'
			] ,
			'printed_invoice_number' => [
				'required' ,
			] ,
			'discount'				 => [
				'numeric'
			] ,
			'is_completely_paid'	 => [
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
