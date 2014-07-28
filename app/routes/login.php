<?php

Route::group ( [
	'before' => 'guest'
] , function()
{
	Route::get ( 'login' , [
		'as'	 => 'login' ,
		'uses'	 => 'UserController@login'
	] ) ;

	Route::post ( 'login' , [
		'as'	 => 'pLogin' ,
		'uses'	 => 'UserController@pLogin'
	] ) ;
} ) ;
