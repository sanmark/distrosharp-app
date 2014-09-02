<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get ( '/test' , function()
{
	//$rOne = \Models\FinanceTransfer::whereBetween ( 'date_time' , ["2014-09-11 00:00:00" , "2014-09-11 23:59:59" ] )
	//-> where ( 'to_id' , '=' , '2' ) ;

	$rO		 = new \Models\FinanceTransfer() ;
	$rO		 = $rO -> whereBetween ( 'date_time' , ["2014-09-11 00:00:00" , "2014-09-11 23:59:59" ] ) ;
	$rOne	 = $rO ;
	$rTwo	 = $rO ;
	$rOne	 = $rOne -> where ( 'from_id' , '=' , '2' ) ;
	$rTwo	 = $rTwo -> where ( 'to_id' , '=' , '2' ) ;

	dd ( $rTwo -> lists ( 'id' ) ) ;
} ) ;

Route::group ( [
	'before' => 'auth'
] , function()
{
	Route::get ( '' , function()
	{
		return View::make ( 'web.test' ) ;
	} ) ;
} ) ;

foreach ( glob ( app_path () . '/routes/*.php' ) as $filterFile )
{
	include $filterFile ;
}

include 'customValidationRules.php' ;
