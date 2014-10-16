<?php

Route::group ( [
	'prefix' => 'reports' ,
	'before' => 'auth'
] , function ()
{
	Route::get ( 'stocks' , [
		'as'	 => 'reports.stocks' ,
		'before' => ['hasAbilities:view_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\StockController@show'
	] ) ;

	Route::post ( 'stocks' , [
		'as'	 => 'reports.stocks' ,
		'before' => ['hasAbilities:view_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\StockController@update'
	] ) ;
	Route::get ( 'debtor-summary' , [
		'as'	 => 'reports.debtorSummary' ,
		'before' => ['hasAbilities:view_debtor_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\DebtorSummaryController@home'
	] ) ;

	Route::post ( 'debtor-summary' , [
		'as'	 => 'reports.debtorSummary' ,
		'before' => ['hasAbilities:view_debtor_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\DebtorSummaryController@filter'
	] ) ;
	Route::get ( 'unload-comparison' , [
		'as'	 => 'reports.unloadComparison' ,
		'before' => ['hasAbilities:view_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\UnloadComparisonController@show'
	] ) ;
	Route::post ( 'unload-comparison' , [
		'as'	 => 'reports.unloadComparison' ,
		'before' => ['hasAbilities:view_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\UnloadComparisonController@show'
	] ) ;
	Route::get ( 'unload-comparison/{id}/view' , [
		'as'	 => 'reports.unloadComparison.view' ,
		'before' => ['hasAbilities:view_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\UnloadComparisonController@view'
	] ) ;
	Route::get ( 'age-credit-report' , [
		'as'	 => 'reports.ageCreditReport' ,
		'before' => ['hasAbilities:view_age_credit_report' ] ,
		'uses'	 => 'Controllers\Reports\AgeCreditReportController@view'
	] ) ;
	Route::post ( 'age-credit-report' , [
		'as'	 => 'reports.ageCreditReport' ,
		'before' => ['hasAbilities:view_age_credit_report' ] ,
		'uses'	 => 'Controllers\Reports\AgeCreditReportController@view'
	] ) ;
	Route::get ( 'salesSummary' , [
		'as'	 => 'reports.salesSummary' ,
		'before' => ['hasAbilities:view_sales_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\SalesSummaryController@all'
	] ) ; 
	Route::post( 'salesSummary' , [
		'as'	 => 'reports.salesSummary' ,
		'before' => ['hasAbilities:view_sales_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\SalesSummaryController@all'
	] ) ;
} ) ;
