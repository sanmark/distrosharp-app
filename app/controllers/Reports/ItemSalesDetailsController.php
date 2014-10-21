<?php

namespace Controllers\Reports ;

class ItemSalesDetailsController extends \Controller
{

	public function home ()
	{
		$items			 = \Models\Item::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
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

		return \View::make ( 'web.reports.itemsDetails.view' , $data ) ;
	}

	public function view ()
	{
		try
		{
			$items			 = \Models\Item::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
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

			$this -> validateFilterValues ( $filterValues ) ;

			$itemDetails = \ItemButler::filterItemSalesDetails ( $filterValues ) ;
			$view_data	 = TRUE ;

			$selectedItem = \Models\Item::find ( $item ) ;

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
				'view_data' ,
				'selectedItem'
				] ) ;

			return \View::make ( 'web.reports.itemsDetails.view' , $data ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	private function validateFilterValues ( $data )
	{
		$rules = [
			'item' => [
				'required'
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
