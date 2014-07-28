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

Route::group ( [
	'before' => 'auth'
] , function()
{
	Route::get ( '' , function()
	{
		echo ':)' ;
	} ) ;
} ) ;

foreach ( glob ( app_path () . '/routes/*.php' ) as $routeFile )
{
	include $routeFile ;
}