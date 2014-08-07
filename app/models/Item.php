<?php

namespace Models ;

class Item extends \Eloquent
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
			'code'					 => ['required' ] ,
			'name'					 => ['required' ] ,
			'reorder_level'			 => ['required' ] ,
			'current_buying_price'	 => ['required' ] ,
			'current_selling_price'	 => ['required' ] ,
			'buying_invoice_order'	 => ['required' ] ,
			'selling_invoice_order'	 => ['required' ]
		] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
