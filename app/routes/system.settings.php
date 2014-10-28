<?php

Route::group ( [
	'prefix' => 'system/settings' ,
	'before' => [
		'auth' ,
		'hasAbilities:edit_system_settings'
	]
] , function ()
{
	Route::get ( '' , [
		'as'	 => 'system.settings' ,
		'uses'	 => 'Controllers\System\SettingsController@showHome'
	] ) ;

	Route::get ( 'payment-source-accounts' , [
		'as'	 => 'system.settings.paymentSourceAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@showPaymentSourceAccounts'
	] ) ;

	Route::post ( 'payment-source-accounts' , [
		'as'	 => 'system.settings.paymentSourceAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@updatePaymentSourceAccounts'
	] ) ;

	Route::get ( 'timezone' , [
		'as'	 => 'system.settings.timezone' ,
		'uses'	 => 'Controllers\System\SettingsController@selectTimeZone'
	] ) ;

	Route::post ( 'timezone' , [
		'as'	 => 'system.settings.timezone' ,
		'uses'	 => 'Controllers\System\SettingsController@updateTimeZone'
	] ) ;

	Route::get ( 'payment-target-accounts' , [
		'as'	 => 'system.settings.paymentTargetAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@showPaymentTargetAccounts'
	] ) ;

	Route::post ( 'payment-target-accounts' , [
		'as'	 => 'system.settings.paymentTargetAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@updatePaymentTargetAccounts'
	] ) ;

	Route::get ( 'imbalance-stock' , [
		'as'	 => 'system.settings.imbalanceStock' ,
		'uses'	 => 'Controllers\System\SettingsController@showImbalanceStock'
	] ) ;

	Route::post ( 'imbalance-stock' , [
		'as'	 => 'system.settings.imbalanceStock' ,
		'uses'	 => 'Controllers\System\SettingsController@updateImbalanceStock'
	] ) ;

	Route::get ( 'finance-accounts' , [
		'as'	 => 'system.settings.financeAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@showFinanceAccounts'
	] ) ;

	Route::post ( 'finance-accounts' , [
		'as'	 => 'system.settings.financeAccounts' ,
		'uses'	 => 'Controllers\System\SettingsController@updateFinanceAccounts'
	] ) ;
	
	Route::get ( 'activityLog' , [
		'as'	 => 'system.activityLog' ,
		'uses'	 => 'Controllers\System\ActivityLogController@home'
	] ) ;

	Route::post ( 'activityLog' , [
		'as'	 => 'system.activityLog' ,
		'uses'	 => 'Controllers\System\ActivityLogController@view'
	] ) ;
} ) ;
