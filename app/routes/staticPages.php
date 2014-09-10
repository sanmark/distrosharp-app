<?php

Route::group ( [
	'before' => 'auth'
] , function()
{
	Route::get ( '' , [
		'as'	 => 'home' ,
		'uses'	 => 'HomeController@showHome'
	] ) ;

	Route::post ( '' , [
		'as'	 => 'home' ,
		'uses'	 => 'HomeController@refreshHome'
	] ) ;
} ) ;


