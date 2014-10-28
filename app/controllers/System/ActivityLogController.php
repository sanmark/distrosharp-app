<?php

namespace Controllers\System ;

class ActivityLogController extends \Controller
{

	public function home ()
	{
		$users			 = \User::getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Users' ] ) ;
		$from_time		 = NULL ;
		$to_time		 = NULL ;
		$user			 = NULL ; 
		$filterValues	 = [
			"today" => date ( "Y-m-d" ) ,
			"from_time"	 => "" ,
			"to_time"	 => "" ,
			"user"		 => 0
			] ;

		$activityLogs = $this -> filterActivitiLogs ( $filterValues ) ;

		$data = compact ( [
			'users' ,
			'from_time' ,
			'to_time' ,
			'view_data' ,
			'user' ,
			'activityLogs'
			] ) ;

		return \View::make ( 'web.system.activityLog.view' , $data ) ;
	}

	public function view ()
	{
		try
		{
			$users			 = \User::getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Users' ] ) ;
			$from_time		 = \Input::get ( 'from_time' ) ;
			$to_time		 = \Input::get ( 'to_time' ) ;
			$user			 = \Input::get ( 'user' ) ;
			$filterValues	 = [
				"from_time"	 => $from_time ,
				"to_time"	 => $to_time ,
				"user"		 => $user
				] ;

			$this -> validateFilterValues ( \Input::all () ) ;

			$activityLogs = $this -> filterActivitiLogs ( $filterValues ) ;

			$data = compact ( [
				'users' ,
				'from_time' ,
				'to_time' ,
				'view_data' ,
				'user' ,
				'activityLogs'
				] ) ;

			return \View::make ( 'web.system.activityLog.view' , $data ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function filterActivitiLogs ( $filterValues )
	{
		$requestObject = new \Models\ActivityLog() ;

		if ( isset ( $filterValues[ 'today' ] ) )
		{
			$today_start = $filterValues[ 'today' ] . " 00:00:00" ;
			$today_end	 = $filterValues[ 'today' ] . " 23:59:59" ;

			$requestObject = $requestObject -> whereBetween ( 'date_time' , array ( $today_start , $today_end ) ) ;
		}
		if ( $filterValues[ 'user' ] != 0 )
		{
			$requestObject = $requestObject -> where ( 'user_id' , '=' , $filterValues[ 'user' ] ) ;
		}

		if ( ! empty ( $filterValues[ 'from_time' ] ) && ! empty ( $filterValues[ 'to_time' ] ) )
		{
			$time_start	 = date ( "Y-m-d H:i:s", strtotime($filterValues[ 'from_time' ] ) );
			$time_end	 = date ( "Y-m-d H:i:s", strtotime($filterValues[ 'to_time' ] ) );
   
			$requestObject = $requestObject -> whereBetween ( 'date_time' , array ( $time_start , $time_end ) ) ;
		}

		return $requestObject->  orderBy ('date_time', 'DESC') -> get () ;
	}

	private function validateFilterValues ( $data )
	{
		$rules = [
			'from_time'	 => [
				'required'
			] ,
			'to_time'	 => [
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
