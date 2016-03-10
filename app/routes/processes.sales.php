<?php

Route::group ( [
	'prefix' => 'processes/sales' ,
	'before' => 'auth'
	] , function()
{
	Route::get ( '' , [
		'as'	 => 'processes.sales.all' ,
		'before' => ['hasAbilities:view_sales' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@all'
	] ) ;

	Route::post ( '' , [
		'as'	 => 'processes.sales.all' ,
		'before' => ['hasAbilities:view_sales' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@all'
	] ) ;

	Route::get ( 'add' , [
		'as'	 => 'processes.sales.add' ,
		'before' => ['hasAbilities:add_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@add'
	] ) ;

	Route::post ( 'add' , [
		'as'	 => 'processes.sales.save' ,
		'before' => ['hasAbilities:add_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@save'
	] ) ;
	
	Route::get ( 'credit/invoices' , [
		'as'	 => 'processes.sales.addOldInvoice' ,
		'before' => ['hasAbilities:add_credit' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@addOldInvoice'
	] ) ;
	
	Route::post('credit/invoices' , [
		'as' => 'processes.sales.oldCreditSave',
		'before' => ['hasAbilities:add_credit'],
		'uses' => 'Controllers\Processes\SaleController@oldCreditSave'
	]);

	Route::get ( 'set-rep' , [
		'as'	 => 'processes.sales.setRep' ,
		'before' => ['hasAbilities:add_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@selectRep'
	] ) ;

	Route::post ( 'set-rep' , [
		'as'	 => 'processes.sales.setRep' ,
		'before' => ['hasAbilities:add_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@setRep'
	] ) ;

	Route::get ( '{id}' , [
		'as'	 => 'processes.sales.view' ,
		'before' => ['hasAbilities:view_sales' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@view'
	] ) ;

	Route::get ( '{id}/edit' , [
		'as'	 => 'processes.sales.edit' ,
		'before' => ['hasAbilities:edit_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@edit'
	] ) ;

	Route::post ( '{id}/edit' , [
		'as'	 => 'processes.sales.edit' ,
		'before' => ['hasAbilities:edit_sale' ] ,
		'uses'	 => 'Controllers\Processes\SaleController@update'
	] ) ;
	
	Route::group([
		'prefix' => 'ajax',
		'before' => 'csrf'
	] , function(){
		
		Route::post('oldcreditInvoices' ,[
			'as'	 => 'processes.sales.ajax.oldcreditInvoices' ,
			'uses'	 => 'Controllers\Processes\SaleController@oldCreditInvoices'
		]);	
	});
	
} ) ;
