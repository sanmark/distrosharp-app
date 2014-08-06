<?php

Route::group ( [
	'prefix' => 'entities/vendors' ,
	'before' => 'auth'
] , function()
{
	Route::get ( 'add' , [
		'as'	 => 'entities.vendors.add' ,
		'before' => ['hasAbilities:add_vendor' ] ,
		'uses'	 => 'Controllers\Entities\VendorController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'entities.vendors.save' ,
		'before' => ['hasAbilities:add_vendor' ] ,
		'uses'	 => 'Controllers\Entities\VendorController@save'
	] ) ;
} ) ;
