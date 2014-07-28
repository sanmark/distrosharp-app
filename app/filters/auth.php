<?php

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter ( 'auth' , function()
{
	if ( Auth::guest () )
	{
		if ( Request::ajax () )
		{
			return Response::make ( 'Unauthorized' , 401 ) ;
		} else
		{
			return Redirect::guest ( 'login' ) ;
		}
	}
} ) ;

Route::filter ( 'auth.basic' , function()
{
	return Auth::basic () ;
} ) ;