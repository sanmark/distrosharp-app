<?php

namespace Models ;

class BuyingInvoice extends \Eloquent
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
			'date'					 => [
				'required'
			] ,
			'vendor_id'				 => [
				'required' ,
			] ,
			'printed_invoice_num'	 => [
				'required' ,
			] ,
		] ;
		
		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException();
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
