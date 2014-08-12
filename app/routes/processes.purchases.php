<?php

Route::group ( [
	'prefix'=>'processes/purchases',
	'before'=>'auth'
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
} ) ;
