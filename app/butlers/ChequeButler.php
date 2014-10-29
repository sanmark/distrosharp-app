<?php

class ChequeButler
{

	public static function filterForIncomingChequesReports ( $filterValues )
	{

		$requestObject = new Models\ChequeDetail() ;


		$rep		 = $filterValues[ 'rep' ] ;
		$customer	 = $filterValues[ 'customer' ] ;
		$bank		 = $filterValues[ 'bank' ] ;
		$date_from	 = $filterValues[ 'date_from' ] ;
		$date_to	 = $filterValues[ 'date_to' ] ;
		$cheque_num	 = $filterValues[ 'cheque_num' ] ;

		if ( count ( $filterValues ) > 0 )
		{
			if ( strlen ( $date_from ) > 0 && strlen ( $date_to ) > 0 )
			{
				$time_from		 = $date_from . " 00:00:00" ;
				$time_to		 = $date_to . " 23:59:59" ;
				$requestObject	 = $requestObject -> whereBetween ( 'issued_date' , array ( $time_from , $time_to ) ) ;
			}
			if ( strlen ( $cheque_num ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'cheque_number' , '=' , $cheque_num ) ;
			}
			if ( strlen ( $bank ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'bank_id' , '=' , $bank ) ;
			}
		}

		$result = $requestObject -> get () ;

		if ( strlen ( $rep ) > 0 )
		{
			$new_result = $result -> filter ( function($collection)use($rep)
			{
				if ( $collection -> financeTransfer -> getSellingInvoice ()[ 'rep' ][ 'id' ] == $rep )
				{
					return true ;
				}
			} ) ;

			$result = $new_result ;
		}


		if ( strlen ( $customer ) > 0 )
		{
			$new_result = $result -> filter ( function($collection)use($customer)
			{
				if ( $collection -> financeTransfer -> getSellingInvoice ()[ 'customer' ][ 'id' ] == $customer )
				{
					return true ;
				}
			} ) ;

			$result = $new_result ;
		}

		return $result ;
	}

}
