<?php

namespace Models ;

class Transfer extends \Eloquent implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function save ( array $options = array () )
	{
		$this -> date_time = \DateTimeHelper::convertTextToFormattedDateTime ( $this -> date_time , 'Y-m-d H:i:s' ) ;

		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArray ( $key , $value )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	private function validateForSave ()
	{
		$data = $this -> toArray () ;

		$rules = [
			'from_stock_id'	 => [
				'required' ,
				'different:to_stock_id'
			] ,
			'to_stock_id'	 => [
				'required'
			] ,
			'date_time'		 => [
				'required' ,
				'date' ,
				'date_format:Y-m-d H:i:s'
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