<?php

Route::group ( [
	'prefix' => 'entities/customers' ,
	'before' => 'auth'
] , function ()
{
	Route::get ( 'add' , [
		'as'	 => 'entities.customers.add' ,
		'uses'	 => 'Controllers\Entities\CustomerController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'entities.customers.save' ,
		'uses'	 => 'Controllers\Entities\CustomerController@save'
	] ) ;
} ) ;
