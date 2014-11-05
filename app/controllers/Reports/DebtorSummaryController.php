<?php

namespace Controllers\Reports ;

class DebtorSummaryController extends \Controller
{

	public function home ()
	{
		$routes		 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
		$customers	 = [NULL => 'Select Route First' ] ;
		$fromDate	 = \DateTimeHelper::dateRefill ( date ( 'Y-m-d' , strtotime ( 'first day of this month' ) ) ) ;
		$toDate		 = \DateTimeHelper::dateRefill ( date ( 'Y-m-d' , strtotime ( 'today' ) ) ) ;

		$data = compact ( [
			'routes' ,
			'customers' ,
			'fromDate' ,
			'toDate' ,
			] ) ;

		return \View::make ( 'web.reports.debtorSummary.home' , $data ) ;
	}

	public function filter ()
	{
		try
		{
			$routes		 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
			$customers	 = [NULL => 'Select Route First' ] ;
			$fromDate	 = \InputButler::get ( 'from_date' ) ;
			$toDate		 = \InputButler::get ( 'to_date' ) ;
			$routeId	 = \InputButler::get ( 'route_id' ) ;
			$customerId	 = \InputButler::get ( 'customer_id' ) ;

			$fromTime	 = $fromDate . ' 00:00:00' ;
			$toTime		 = $toDate . ' 23:59:59' ;

			$customer = \Models\Customer::find ( $customerId ) ;

			$filterValues = \Input::all () ;

			$this -> validateFilterValues ( $filterValues ) ;

			$requestObjet = $this -> prepareRequestObjectForFiltering ( $filterValues ) ;

			$sellingInvoices = $requestObjet -> get () ;
			$balanceBefore	 = $customer -> getSellingInvoiceBalanceBefore ( $fromTime ) ;
			$endingBalance	 = 0 ;

			$endingBalance = + $balanceBefore ;
			foreach ( $sellingInvoices as $sellingInvoice )
			{
				$endingBalance += $sellingInvoice -> getInvoiceBalance () ;
			}

			$data = compact ( [
				'routes' ,
				'customers' ,
				'sellingInvoices' ,
				'fromDate' ,
				'toDate' ,
				'routeId' ,
				'customerId' ,
				'balanceBefore' ,
				'endingBalance'
				] ) ;

			return \View::make ( 'web.reports.debtorSummary.filter' , $data ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	private function prepareRequestObjectForFiltering ( $filterValues )
	{
		$requestObjet = new \Models\SellingInvoice() ;

		$fromDate	 = $filterValues[ 'from_date' ] ;
		$toDate		 = $filterValues[ 'to_date' ] ;
		$customerId	 = $filterValues[ 'customer_id' ] ;

		if ( count ( $filterValues ) > 0 )
		{
			if ( strlen ( $fromDate ) > 0 && strlen ( $toDate ) > 0 )
			{
				$fromTime	 = $fromDate . ' 00:00:00' ;
				$toTime		 = $toDate . ' 23:59:59' ;

				$fromTime	 = \DateTimeHelper::convertTextToFormattedDateTime ( $fromTime ) ;
				$toTime		 = \DateTimeHelper::convertTextToFormattedDateTime ( $toTime ) ;
				$timeRange	 = [$fromTime , $toTime ] ;

				$requestObjet = $requestObjet -> whereBetween ( 'date_time' , $timeRange ) ;
			}

			if ( strlen ( $customerId ) > 0 )
			{
				$requestObjet = $requestObjet -> where ( 'customer_id' , '=' , $customerId ) ;
			}
		}

		$requestObjet = $requestObjet -> where ( 'is_completely_paid' , '=' , FALSE ) ;

		$sellingInvoices = $requestObjet -> with ( 'financeTransfers' ) ;

		return $requestObjet ;
	}

	private function validateFilterValues ( $data )
	{
		$data[ 'from_date' ] = \DateTimeHelper::convertTextToFormattedDateTime ( $data[ 'from_date' ] ) ;
		$data[ 'to_date' ]	 = \DateTimeHelper::convertTextToFormattedDateTime ( $data[ 'to_date' ] ) ;

		$rules = [
			'from_date'		 => [
				'required' ,
				'date' ,
				'date_format:Y-m-d H:i:s'
			] ,
			'to_date'		 => [
				'required' ,
				'date' ,
				'date_format:Y-m-d H:i:s'
			] ,
			'route_id'		 => [
				'required' ,
				'numeric'
			] ,
			'customer_id'	 => [
				'required' ,
				'numeric'
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
