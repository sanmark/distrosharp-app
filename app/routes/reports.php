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
		'uses'	 => 'Controllers\Reports\StockController@view'
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

	Route::get ( 'sales-summary' , [
		'as'	 => 'reports.salesSummary' ,
		'before' => ['hasAbilities:view_sales_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\SalesSummaryController@all'
	] ) ;

	Route::post ( 'sales-summary' , [
		'as'	 => 'reports.salesSummary' ,
		'before' => ['hasAbilities:view_sales_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\SalesSummaryController@all'
	] ) ;

	Route::get ( 'profit-and-loss' , [
		'as'	 => 'reports.profitAndLossReport' ,
		'before' => ['hasAbilities:view_profit_and_loss_report' ] ,
		'uses'	 => 'Controllers\Reports\ProfitAndLossReportController@home'
	] ) ;

	Route::post ( 'profit-and-loss' , [
		'as'	 => 'reports.profitAndLossReport' ,
		'before' => ['hasAbilities:view_profit_and_loss_report' ] ,
		'uses'	 => 'Controllers\Reports\ProfitAndLossReportController@filter'
	] ) ;

	Route::get ( 'incoming-cheques' , [
		'as'	 => 'reports.incomingChequesReport' ,
		'before' => ['hasAbilities:view_incoming_cheques_report' ] ,
		'uses'	 => 'Controllers\Reports\IncomingChequesReportController@home'
	] ) ;
	Route::post ( 'incoming-cheques' , [
		'as'	 => 'reports.incomingChequesReport' ,
		'before' => ['hasAbilities:view_incoming_cheques_report' ] ,
		'uses'	 => 'Controllers\Reports\IncomingChequesReportController@view'
	] ) ;
	Route::get ( 'item-sales-summary' , [
		'as'	 => 'report.itemSalesSummary' ,
		'before' => ['hasAbilities:view_item_sales_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\ItemSalesSummaryController@home'
	] ) ;

	Route::post ( 'item-sales-summary' , [
		'as'	 => 'report.itemSalesSummary' ,
		'before' => ['hasAbilities:view_item_sales_summary_report' ] ,
		'uses'	 => 'Controllers\Reports\ItemSalesSummaryController@filter'
	] ) ;

	Route::get ( 'item-sales-details' , [
		'as'	 => 'reports.itemSalesDetails' ,
		'before' => ['hasAbilities:view_item_sales_details_report' ] ,
		'uses'	 => 'Controllers\Reports\ItemSalesDetailsController@home'
	] ) ;

	Route::post ( 'item-sales-details' , [
		'as'	 => 'reports.itemSalesDetails' ,
		'before' => ['hasAbilities:view_item_sales_details_report' ] ,
		'uses'	 => 'Controllers\Reports\ItemSalesDetailsController@view'
	] ) ;

	Route::get ( 'item-return' , [
		'as'	 => 'reports.itemReturnReport' ,
		'before' => ['hasAbilities:view_item_return_report' ] ,
		'uses'	 => 'Controllers\Reports\ItemReturnReportController@home'
	] ) ;

	Route::post ( 'item-return' , [
		'as'	 => 'reports.itemReturnReport' ,
		'before' => ['hasAbilities:view_item_return_report' ] ,
		'uses'	 => 'Controllers\Reports\ItemReturnReportController@view'
	] ) ;
	Route::get ( 'credit-summary' , [
		'as'	 => 'reports.creditSummary' ,
		'before' => ['hasAbilities:view_credit_summery_report' ] ,
		'uses'	 => 'Controllers\Reports\CreditSummaryReportController@home'
	] ) ;

	Route::post ( 'credit-summary' , [
		'as'	 => 'reports.creditSummary' ,
		'before' => ['hasAbilities:view_credit_summery_report' ] ,
		'uses'	 => 'Controllers\Reports\CreditSummaryReportController@home'
	] ) ;

	Route::get ( 'credit-summary/{id}' , [
		'as'	 => 'reports.creditSummary.view' ,
		'before' => [ 'hasAbilities:view_credit_summery_report' ] ,
		'uses'	 => 'Controllers\Reports\CreditSummaryReportController@view'
	] ) ;

	Route::get ( 'rep-finance' , [
		'as'	 => 'reports.repFinanceReport' ,
		'before' => ['hasAbilities:view_rep_finance_report' ] ,
		'uses'	 => 'Controllers\Reports\RepFinanceReportController@filter'
	] ) ;

	Route::post ( 'rep-finance' , [
		'as'	 => 'reports.repFinanceReport' ,
		'before' => ['hasAbilities:view_rep_finance_report' ] ,
		'uses'	 => 'Controllers\Reports\RepFinanceReportController@filter'
	] ) ;
	Route::get ( 'timely-stock' , [
		'as'	 => 'reports.timelyStockReport' ,
		'before' => ['hasAbilities:view_timely_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\TimelyStockReportController@home'
	] ) ;

	Route::post ( 'timely-stock' , [
		'as'	 => 'reports.timelyStockReport' ,
		'before' => ['hasAbilities:view_timely_stock_report' ] ,
		'uses'	 => 'Controllers\Reports\TimelyStockReportController@filter'
	] ) ;
} ) ;
