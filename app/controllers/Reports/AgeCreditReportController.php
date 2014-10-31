<?php

namespace Controllers\Reports ;

class AgeCreditReportController extends \Controller
{

	public function view ()
	{
		$filterValues = \Input::all () ;

		$repId		 = \Input::get ( 'rep' ) ;
		$customerId	 = \Input::get ( 'customer' ) ;
		$routeId	 = \Input::get ( 'route_id' ) ;
		$ageDays	 = \Input::get ( 'age_by_days' ) ;
		$sellingData = \Models\SellingInvoice::ageCreditFilter ( $filterValues ) ;
		$repsList	 = \Models\SellingInvoice::distinct ( 'rep_id' )
			-> where ( 'is_completely_paid' , '=' , FALSE )
			-> lists ( 'rep_id' ) ;

		$routeSelectBox = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'Select' ] ) ;

		$repSelectBox = \User::getArrayForHtmlSelectByIds ( 'id' , 'username' , $repsList , ['' => 'Select Rep' ] ) ;

		$customerSelectBox = ['' => 'Select Route First' ] ;

		$invoiceBalanceTotal = [ ] ;

		for ( $i = 0 ; $i < count ( $sellingData ) ; $i ++ )
		{
			$invoiceBalanceTotal[ $sellingData[ $i ][ 'id' ] ] = \Models\SellingInvoice::find ( $sellingData[ $i ][ 'id' ] ) -> getInvoiceBalance () ;
		}
		$data = compact ( [
			'sellingData' ,
			'repSelectBox' ,
			'customerSelectBox' ,
			'repId' ,
			'customerId' ,
			'ageDays' ,
			'now' ,
			'invoiceBalanceTotal' ,
			'routeSelectBox' ,
			'routeId'
			] ) ;
		return \View::make ( 'web.reports.ageCreditReport.view' , $data ) ;
	}

}
