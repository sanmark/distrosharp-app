<?php

Route::group ( [
	'prefix' => 'entities/customers' ,
	'before' => 'auth'
] , function ()
{
	Route::get ( '' , [
		'as'	 => 'entities.customers.view' ,
		'before' => ['hasAbilities:view_customers' ] ,
		'uses'	 => 'Controllers\Entities\CustomerController@home'
	] ) ;

	Route::post ( '' , [
		'as'	 => 'entities.customers.view' ,
		'before' => ['hasAbilities:view_customers' ] ,
		'uses'	 => 'Controllers\Entities\CustomerController@home'
	] ) ;

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

	Route::get ( '{id}/edit' , [
		'as'	 => 'entities.customers.edit' ,
		'before' => ['hasAbilities:edit_customer' ] ,
		'uses'	 => 'Controllers\Entities\CustomerController@edit'
	] ) ;

	Route::post ( '{id}/edit' , [
		'as'	 => 'entities.customers.update' ,
		'before' => ['hasAbilities:edit_customer' ] ,
		'uses'	 => 'Controllers\Entities\CustomerController@update'
	] ) ;

	Route::group ( [
		'prefix' => 'ajax' ,
		'before' => 'csrf'
	] , function()
	{
		Route::post ( 'forRouteId' , [
			'as'	 => 'entities.customers.ajax.forRouteId' ,
			'uses'	 => 'Controllers\Entities\CustomerController@aForRouteId'
		] ) ;
	} ) ;
} ) ;
