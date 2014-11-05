<?php

class ActivityLogButler
{

	public static function add ( $message )
	{
		if ( ! SessionButler::isSuperAdminLoggedIn () )
		{
			$activityLog = new Models\ActivityLog() ;

			$activityLog -> date_time	 = date ( "Y-m-d H:i:s" ) ;
			$activityLog -> user_id		 = Auth::user () -> id ;
			$activityLog -> message		 = $message ;
			$activityLog -> url			 = Request::path () ;

			return $activityLog -> save () ;
		}
	}

}
