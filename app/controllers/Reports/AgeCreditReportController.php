<?php

namespace Controllers\Reports ;

class AgeCreditReportController extends \Controller
{

	public function view ()
	{
		$filterValues = \Input::all () ;

		$repId		 = \InputButler::get ( 'rep' ) ;
		$customerId	 = \InputButler::get ( 'customer' ) ;
		$routeId	 = \InputButler::get ( 'route_id' ) ;
		$ageDays	 = \NullHelper::ifNullEmptyOrWhitespace ( \InputButler::get ( 'age_by_days' ) , 45 ) ;
		$sellingData = \Models\SellingInvoice::ageCreditFilter ( $filterValues ) ;
		$repsList	 = \Models\SellingInvoice::distinct ( 'rep_id' )
			-> where ( 'is_completely_paid' , '=' , FALSE )
			-> lists ( 'rep_id' ) ;

		$routeSelectBox = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [ '' => 'Select' ] ) ;

		$repSelectBox = \User::getArrayForHtmlSelectByIds ( 'id' , 'username' , $repsList , ['' => 'Select Rep' ] ) ;

		$customerSelectBox = ['' => 'Select Route First' ] ;

		$invoiceBalanceTotal = [ ] ;

		$total = 0 ;

		for ( $i = 0 ; $i < count ( $sellingData ) ; $i ++ )
		{
			$invoiceBalanceTotal[ $sellingData[ $i ][ 'id' ] ] = \Models\SellingInvoice::find ( $sellingData[ $i ][ 'id' ] ) -> getInvoiceBalance () ;

			$total = $total + $invoiceBalanceTotal[ $sellingData[ $i ][ 'id' ] ] ;
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
			'routeId' ,
			'total'
			] ) ;
		return \View::make ( 'web.reports.ageCreditReport.view' , $data ) ;
	}

}
