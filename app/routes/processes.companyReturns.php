<?php

Route::group ( [
	'prefix' => 'processes/company-returns' ,
	'before' => 'auth'
	] , function ()
{
	Route::get ( 'add' , [
		'as'	 => 'processes.companyReturns.add' ,
		'before' => ['hasAbilities:add_company_returns' ] ,
		'uses'	 => 'Controllers\Processes\CompanyReturnsController@add'
	] ) ;
	Route::post ( 'add' , [
		'as'	 => 'processes.companyReturns.add' ,
		'before' => ['hasAbilities:add_company_returns' ] ,
		'uses'	 => 'Controllers\Processes\CompanyReturnsController@save'
	] ) ;
	Route::get ( 'view' , [
		'as'	 => 'processes.companyReturns.view' ,
		'before' => ['hasAbilities:view_company_returns' ] ,
		'uses'	 => 'Controllers\Processes\CompanyReturnsController@view'
	] ) ;
	Route::get ( '{id}/view' , [
		'as'	 => 'processes.companyReturns.viewItems' ,
		'before' => ['hasAbilities:view_company_returns' ] ,
		'uses'	 => 'Controllers\Processes\CompanyReturnsController@viewItems'
	] ) ;
	Route::post ( 'view' , [
		'as'	 => 'processes.companyReturns.view' ,
		'before' => ['hasAbilities:view_company_returns' ] ,
		'uses'	 => 'Controllers\Processes\CompanyReturnsController@view'
	] ) ;
	Route::group ( [
		'prefix' => 'ajax' ,
		'before' => 'csrf'
		] , function()
	{
		Route::post ( 'getQuantity' , [
			'as'	 => 'processes.companyReturns.ajax.getQuantity' ,
			'uses'	 => 'Controllers\Processes\CompanyReturnsController@getQuantity'
		] ) ;
	} ) ;
} ) ;
