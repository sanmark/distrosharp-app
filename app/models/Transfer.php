<?php

namespace Models ;

class Transfer extends BaseEntity implements \Interfaces\iEntity
{

	public function fromStock ()
	{
		return $this -> belongsTo ( 'Models\Stock' , 'from_stock_id' ) ;
	}

	public function toStock ()
	{
		return $this -> belongsTo ( 'Models\Stock' , 'to_stock_id' ) ;
	}

	public function transferDetails ()
	{
		return $this -> hasMany ( 'Models\TransferDetail' ) ;
	}

	public function save ( array $options = array () )
	{
		$this -> date_time = \DateTimeHelper::convertTextToFormattedDateTime ( $this -> date_time ) ;

		$this -> validateForSave () ;

		parent::save ( $options ) ;
	}

	public static function unloadReportFilter ( $filterValues )
	{
		$requestObject	 = new Transfer() ;
		$vehicleIds		 = \Models\Stock::where ( 'stock_type_id' , '=' , 2 ) -> lists ( 'id' ) ;

		if ( count ( $filterValues ) > 0 )
		{
			$fromDate	 = \InputButler::get ( 'from_date_time' ) ;
			$toDate		 = \InputButler::get ( 'to_date_time' ) ;
			$fromStock	 = \InputButler::get ( 'from_stock' ) ;
			$toStock	 = \InputButler::get ( 'to_stock' ) ;
			$minDate	 = $requestObject -> min ( 'date_time' ) ;
			$maxDate	 = $requestObject -> max ( 'date_time' ) ;

			if ( strlen ( $fromDate ) > 0 && strlen ( $toDate ) > 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$fromDate , $toDate ] ) ;
			}
			if ( strlen ( $fromDate ) > 0 && strlen ( $toDate ) == 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$fromDate , $maxDate ] ) ;
			}
			if ( strlen ( $fromDate ) == 0 && strlen ( $toDate ) > 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$minDate , $toDate ] ) ;
			}
			if ( strlen ( $fromStock ) > 0 )
			{
				$requestObject = $requestObject
					-> where ( 'from_stock_id' , '=' , $fromStock ) ;
			}
			if ( strlen ( $toStock ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'to_stock_id' , '=' , $toStock )
					-> whereNotIn ( 'to_stock_id' , $vehicleIds ) ;
			}
		}
		return $requestObject -> whereIn ( 'from_stock_id' , $vehicleIds )
				-> whereNotIn ( 'to_stock_id' , $vehicleIds )
				-> get () ;
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

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

}
