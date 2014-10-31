<?php

namespace Controllers\Reports ;

class ItemReturnReportController extends \Controller
{

	public function home ()
	{
		$items			 = \Models\Item::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Items' ] ) ;
		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes ) -> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;
		$routes			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Routes' ] ) ;
		$customers		 = [NULL => 'Select Route First' ] ;
		$view_data		 = FALSE ;
		$item			 = NULL ;
		$rep			 = NULL ;
		$route			 = NULL ;
		$customer		 = NULL ;
		$from_date		 = NULL ;
		$to_date		 = NULL ;

		$data = compact ( [
			'items' ,
			'reps' ,
			'routes' ,
			'customers' ,
			'view_data' ,
			'item' ,
			'rep' ,
			'route' ,
			'customer' ,
			'from_date' ,
			'to_date'
			] ) ;

		return \View::make ( 'web.reports.itemReturn.view' , $data ) ;
	}

	public function view ()
	{
		$items			 = \Models\Item::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Items' ] ) ;
		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes ) -> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;
		$routes			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Routes' ] ) ;
		$customers		 = [NULL => 'Select Route First' ] ;

		$item		 = \Input::get ( 'item' ) ;
		$rep		 = \Input::get ( 'rep' ) ;
		$route		 = \Input::get ( 'route' ) ;
		$customer	 = \Input::get ( 'customer' ) ;
		$from_date	 = \Input::get ( 'from_date' ) ;
		$to_date	 = \Input::get ( 'to_date' ) ;

		$filterValues = \Input::all () ;

		$itemDetails = \ItemButler::filterReturnItem ( $filterValues ) ;
		$view_data	 = TRUE ;


		$data = compact ( [
			'items' ,
			'reps' ,
			'routes' ,
			'customers' ,
			'item' ,
			'rep' ,
			'route' ,
			'customer' ,
			'from_date' ,
			'to_date' ,
			'itemDetails' ,
			'view_data'
			] ) ;

		return \View::make ( 'web.reports.itemReturn.view' , $data ) ;
	}

}
