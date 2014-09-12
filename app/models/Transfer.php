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
		$requestObject = self::prepareRequestObjectForFiltering ( $filterValues ) ;

		$requestObject = $requestObject -> with ( [
			'fromStock' ,
			'toStock'
		] ) ;

		return $requestObject -> get () ;
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

	private static function prepareRequestObjectForFiltering ( $filterValues )
	{
		$requestObject = new Transfer() ;

		if ( count ( $filterValues ) > 0 )
		{
			$fromStockId	 = $filterValues[ 'from_stock_id' ] ;
			$toStockId		 = $filterValues[ 'to_stock_id' ] ;
			$dateTimeFrom	 = $filterValues[ 'date_time_from' ] ;
			$dateTimeTo		 = $filterValues[ 'date_time_to' ] ;

			if ( strlen ( $fromStockId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'from_stock_id' , '=' , $fromStockId ) ;
			}

			if ( strlen ( $toStockId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'to_stock_id' , '=' , $toStockId ) ;
			}

			if ( strlen ( $dateTimeFrom ) > 0 && strlen ( $dateTimeTo ) > 0 )
			{
				$dateTimeFrom	 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeFrom ) ;
				$dateTimeTo		 = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeTo ) ;

				$datesAndTimes = [$dateTimeFrom , $dateTimeTo ] ;

				$requestObject = $requestObject -> whereBetween ( 'date_time' , $datesAndTimes ) ;
			} elseif ( strlen ( $dateTimeFrom ) > 0 )
			{
				$dateTimeFrom = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeFrom ) ;

				$requestObject = $requestObject -> where ( 'date_time' , '=' , $dateTimeFrom ) ;
			} elseif ( strlen ( $dateTimeTo ) > 0 )
			{
				$dateTimeTo = \DateTimeHelper::convertTextToFormattedDateTime ( $dateTimeTo ) ;

				$requestObject = $requestObject -> where ( 'date_time' , '=' , $dateTimeTo ) ;
			}
		}

		return $requestObject ;
	}

}
