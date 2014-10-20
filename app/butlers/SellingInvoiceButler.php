<?php

class SellingInvoiceButler
{

	public static function getAllRepsForHtmlSelect ()
	{
		$repIds = \Models\SellingInvoice::distinct ()
		-> lists ( 'rep_id' ) ;

		$reps = User::getArrayForHtmlSelectByIds ( 'id' , 'username' , $repIds , [NULL => 'Any' ] ) ;

		return $reps ;
	}

	public static function getNextId ()
	{
		$lastSellingInvoice = Models\SellingInvoice::orderBy ( 'id' , 'desc' ) -> first () ;

		if ( is_null ( $lastSellingInvoice ) )
		{
			return 1 ;
		}

		$lastId	 = $lastSellingInvoice -> id ;
		$nextId	 = $lastId + 1 ;

		return $nextId ;
	}
	
	
	public static function profitAndLossFilter ( $filterValues )
	{
		$requestObject = new Models\SellingInvoice();

		if ( count ( $filterValues ) > 0 )
		{
			$date_from	 = $filterValues[ 'from_date' ] ;
			$date_to	 = $filterValues[ 'to_date' ] ;

			if ( strlen ( $date_from ) > 0 && strlen ( $date_to ) > 0 )
			{
				$date_from_start = $date_from . " 00:00:00" ;
				$date_to_end	 = $date_to . " 23:59:59" ;

				$requestObject = $requestObject -> whereBetween ( 'date_time' , array ( $date_from_start , $date_to_end ) ) ;
			} elseif ( strlen ( $date_from ) > 0 )
			{
				$date_from_start = $date_from . " 00:00:00" ;
				$date_from_end	 = $date_from . " 23:59:59" ;

				$requestObject = $requestObject -> whereBetween ( 'date_time' , array ( $date_from_start , $date_from_end ) ) ;
			}
		}

		return $requestObject -> get () ;
	}

}
