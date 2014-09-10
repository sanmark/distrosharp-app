<?php

namespace Models ;

class Transfer extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function fromStock ()
	{
		return $this -> belongsTo ( 'Models\Stock' , 'from_stock_id' ) ;
	}

	public function toStock ()
	{
		return $this -> belongsTo ( 'Models\Stock' , 'to_stock_id' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> date_time = \DateTimeHelper::convertTextToFormattedDateTime ( $this -> date_time ) ;

		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}

	public static function filter ( $filterValues )
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
