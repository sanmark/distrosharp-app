<?php

$currentTimeZone = \SystemSettingButler::getValue ( 'time_zone' ) ;
date_default_timezone_set ( $currentTimeZone ) ;
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
	dd ( DatabaseHelper::hasDatabase ( 'd-081-1' ) ) ;
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
