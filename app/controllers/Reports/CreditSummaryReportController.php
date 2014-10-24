<?php

namespace Controllers\Reports ;

class CreditSummaryReportController extends \Controller
{

	public function home ()
	{

		$routeId			 = \Input::get ( 'route_id' ) ;
		$route				 = \Models\Route::find ( $routeId ) ;
		$routeList			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'All Routes' ] ) ;
		$customersInRoute	 = \Models\Customer::lists ( 'id' ) ;
		if ( $route != NULL )
		{
			$customersInRoute = $route -> getCustomersIds () ;
		}

		if ( count ( $customersInRoute ) == 0 )
		{
			$customersIds				 = [ ] ;
			$creditBalanceWithCustomerId = [ ] ;
		} else
		{
			$customersIds = \Models\SellingInvoice::distinct ( 'customer_id' )
				-> whereIn ( 'customer_id' , $customersInRoute )
				-> where ( 'is_completely_paid' , '=' , FALSE )
				-> get ( ['customer_id' ] ) ;

			$creditBalanceWithCustomerId = \CustomerButler::getSumsOfCreditsByCustomerIds ( $customersIds ) ;
		}
		$data = compact ( [
			'routeList' ,
			'routeId' ,
			'creditBalanceWithCustomerId' ,
			'customersIds'
			] ) ;
		return \View::make ( 'web.reports.creditSummary.home' , $data ) ;
	}

	public function view ( $id )
	{
		$customer					 = \Models\Customer::findOrFail ( $id ) ;
		$currentDate				 = date ( 'Y-m-d h:i A' ) ;
		$creditBalanceForCustomer	 = $customer -> getSumOfInvoiceCreditBalances () ;
		$customerCreditInvoices		 = $customer -> creditInvoices ;

		$creditAge = [ ] ;

		foreach ( $customerCreditInvoices as $sellingInvoice )
		{

			$creditAge[ $sellingInvoice -> id ] = \DateTimeHelper::dateDifferenceUntilToday ( $sellingInvoice -> date_time ) ;
		}
		$data = compact ( [
			'customer' ,
			'currentDate' ,
			'creditAge' ,
			'customerCreditInvoices' ,
			'creditBalanceForCustomer'
			] ) ;
		return \View::make ( 'web.reports.creditSummary.view' , $data ) ;
	}

}
