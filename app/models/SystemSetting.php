<?php

namespace Models ;

class SystemSetting extends \Eloquent
{

	public $timestamps = FALSE ;

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::save ( $attributes ) ;
	}

	private function validateForUpdate ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'value' => [
				'required'
			]
		] ;

		$messages = [
			'value.required' => 'Please make sure all the values are filled.'
		] ;

		$validator = \Validator::make ( $data , $rules , $messages ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
