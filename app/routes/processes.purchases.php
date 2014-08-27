<?php

Route::group ( [
	'prefix' => 'processes/purchases' ,
	'before' => 'auth'
] , function ()
{
	Route::get('add',[
		'as'=>'processes.purchases.add',
		'before'=>['hasAbilities:add_purchase'],
		'uses'=>'Controllers\Processes\PurchaseController@add'
	]);
	Route::post('add',[
		'as'=>'processes.purchases.add',
		'before'=>['hasAbilities:add_purchase'],
		'uses'=>'Controllers\Processes\PurchaseController@save'
	]);
	
	Route::get('',[
		'as'=>'processes.purchases.view',
		'before'=>['hasAbilities:view_purchases'],
		'uses'=>'Controllers\Processes\PurchaseController@home',
	]);
	Route::post('',[
		'as'=>'processes.purchases.view',
		'before'=>['hasAbilities:view_purchases'],
		'uses'=>'Controllers\Processes\PurchaseController@home',
	]);
	Route::get('{id}/edit',[
		'as'=>'processes.purchases.edit',
		'before'=>['hasAbilities:view_purchases'],
		'uses'=>'Controllers\Processes\PurchaseController@edit'
	]);
	Route::post('{id}/edit',[
		'as'=>'processes.purchases.update',
		'before'=>['hasAbilities:edit_purchase'],
		'uses'=>'Controllers\Processes\PurchaseController@update'
	]);
} ) ;
