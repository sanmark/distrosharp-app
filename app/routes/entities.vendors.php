<?php

Route::group ( [
	'prefix' => 'entities/vendors' ,
	'before' => 'auth'
] , function()
{
	Route::get ( '' , [
		'as'	 => 'entities.vendors.view' ,
		'before' => ['hasAbilities:view_vendors' ] ,
		'uses'	 => 'Controllers\Entities\VendorController@home'
	] ) ;
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
