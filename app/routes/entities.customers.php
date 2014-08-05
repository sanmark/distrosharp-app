<?php

Route::group([
	'prefix'=>'entities/customers',
	'before'=>'auth'
],  function ()
{
	Route::get('add',[
		'as'=>'add-customer',
		'uses'=>'Controllers\Entities\CustomerController@add'
	]);
	Route::post('add',[
		'as'=>'save-customer',
		'uses'=>'Controllers\Entities\CustomerController@save'
	]);
});
