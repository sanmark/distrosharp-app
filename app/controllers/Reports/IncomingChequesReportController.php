<?php

namespace Controllers\Reports ;

class IncomingChequesReportController extends \Controller
{

	public function home ()
	{
		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes ) -> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;
		$routes			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
		$customers		 = [NULL => 'Select Route First' ] ;
		$banks			 = \Models\Bank::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'All Banks' ] ) ;

		$data = compact ( [
			'reps' ,
			'customers' ,
			'routes' ,
			'banks'
			] ) ;

		return \View::make ( 'web.reports.cheques.home' , $data ) ;
	}

	public function view ()
	{
		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes ) -> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;
		$routes			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
		$customers		 = [NULL => 'Select Route First' ] ;
		$banks			 = \Models\Bank::getArrayForHtmlSelect ( 'id' , 'name' , [ NULL => 'All Banks' ] ) ;

		$filterValues	 = \Input::all () ;
		$cheques		 = \ChequeButler::filterForIncomingChequesReports ( $filterValues ) ;

		$repId		 = \InputButler::get ( 'rep' ) ;
		$customerId	 = \InputButler::get ( 'customer' ) ;
		$bankId		 = \InputButler::get ( 'bank' ) ;
		$date_from	 = \InputButler::get ( 'date_from' ) ;
		$date_to	 = \InputButler::get ( 'date_to' ) ;
		$cheque_num	 = \InputButler::get ( 'cheque_num' ) ;
		$route		 = \InputButler::get ( 'route' ) ;

		$data = compact ( [
			'reps' ,
			'customers' ,
			'banks' ,
			'repId' ,
			'routes' ,
			'route' ,
			'customerId' ,
			'bankId' ,
			'date_from' ,
			'date_to' ,
			'cheque_num' ,
			'cheques'
			] ) ;

		return \View::make ( 'web.reports.cheques.view' , $data ) ;
	}

}
