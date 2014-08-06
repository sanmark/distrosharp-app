<?php

namespace Models ;

class Vendor extends \Eloquent
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
			'name'		 => ['required' ] ,
			'details'	 => ['required' ]
		] ;

		$validator = \Validator::make ( $data , $rules ) ;
		if ( $validator -> fails () )
		{
			$iie				 = new InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
