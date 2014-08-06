<?php

Route::group ( [
	'prefix' => 'entities/customers' ,
	'before' => 'auth'
] , function ()
{
	Route::get ( 'add' , [
		'as'	 => 'entities.customers.add' ,
		'before' => ['hasAbilities:add_customer' ] ,
		'uses'	 => 'Controllers\Entities\CustomerController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'entities.customers.save' ,
		'before' => ['hasAbilities:add_customer' ] ,
		'uses'	 => 'Controllers\Entities\CustomerController@save'
	] ) ;
} ) ;
