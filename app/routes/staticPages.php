<?php

Route::group ( [
	'before' => 'auth'
] , function()
{
	Route::get ( '' , [
		'as'	 => 'home' ,
		'uses'	 => 'StaticPagesController@home'
	] ) ;
} ) ;


