<?php

namespace Models ;

class Customer extends \Eloquent
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> validateForSave () ;
		parent::save ( $options ) ;
	}

	public function update ( array $attributes = array () )
	{
		$this -> validateForUpdate () ;

		parent::save ( $attributes ) ;
	}

	private function validateForSave ()
	{
		$data		 = $this -> toArray () ;
		$rules		 = [
			'name'		 => [
				'required' ,
				'unique:customers'
			] ,
			'route_id'	 => ['required' ] ,
			'is_active'	 => ['required' ] ,
		] ;
		$validator	 = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

	private function validateForUpdate ()
	{

		$data = $this -> toArray () ;

		$rules = [
			'name'		 => [
				'required' ,
				'unique:customers,name,' . $this -> id
			] ,
			'route_id'	 => ['required' ] ,
			'is_active'	 => ['required' ] ,
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
