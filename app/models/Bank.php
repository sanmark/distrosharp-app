<?php

namespace Models ;

class Bank extends \Eloquent
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	public function validateForSave ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name' => ['required' ]
		] ;
		$validator	 = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new InvalidInputException() ;
			$iie -> validator	 = $validator ;
			throw $iie ;
		}
	}

}
