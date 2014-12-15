<?php

class MenuButler
{

	/**
	 * Builds the menu for the system.
	 * ['File Manager' , 'fileManager.home' , [ 'manage_files' ] ]
	 * In the array first is the menu lable, second is the route name thre is
	 * an array containing permissons.
	 *
	 * @var type
	 */
	private static $menuTemplate = [
		[ 'Home' , 'home' ] ,
		[ 'Entities' , [
				[ 'Items' , [
						[ 'View Items' , 'entities.items.view' , [ 'view_items' ] ] ,
						[ 'Add Item' , 'entities.items.add' , [ 'add_item' ] ] ,
					]
				] ,
				[ 'Vendors' , [
						[ 'View Vendors' , 'entities.vendors.view' , [ 'view_vendors' ] ] ,
						[ 'Add Vendor' , 'entities.vendors.add' , [ 'add_vendor' ] ]
					]
				] ,
				[ 'Routes' , [
						[ 'View Routes' , 'entities.routes.view' , [ 'view_routes' ] ] ,
						[ 'Add Route' , 'entities.routes.add' , [ 'add_route' ] ]
					]
				] ,
				[ 'Customers' , [
						[ 'View Customers' , 'entities.customers.view' , [ 'view_customers' ] ] ,
						[ 'Add Customer' , 'entities.customers.add' , [ 'add_customer' ] ]
					]
				] ,
				[ 'Banks' , [
						[ 'View Banks' , 'entities.banks.view' , [ 'view_banks' ] ] ,
						[ 'Add Bank' , 'entities.banks.add' , [ 'add_bank' ] ]
					]
				]
			]
		] ,
		[ 'Processes' , [
				[ 'Purchases' , [
						[ 'View Purchases' , 'processes.purchases.view' , [ 'view_purchases' ] ] ,
						[ 'Add Purchase' , 'processes.purchases.add' , [ 'add_purchase' ] ]
					]
				] ,
				['Transfers' , [
						['Add Transfer' , 'processes.transfers.selectStocksInvolved' , ['add_transfer' ] ] ,
						[ 'View Transfers' , 'processes.transfers.all' , ['view_transfers' ] ]
					]
				] ,
				['Sales' , [
						['View Sales' , 'processes.sales.all' , ['view_sales' ] ] ,
						['Add Sale' , 'processes.sales.add' , ['add_sale' ] ] ,
					]
				] ,
				['Company Returns' , [
						['Add Company Returns' , 'processes.companyReturns.add' , ['add_company_returns' ] ] ,
						['View Company Returns' , 'processes.companyReturns.view' , ['view_company_returns' ] ] ,
					]
				]
			]
		] ,
		['Stocks' , [
				['View Stocks' , 'stocks.all' , ['super_admin' ] ] ,
				['Add New Stock' , 'stocks.add' , ['super_admin' ] ]
			]
		] ,
		['Finance' , [
				['Account' , [
						['Add Account' , 'finances.accounts.add' , ['super_admin' ] ] ,
						['View Account' , 'finances.accounts.view' , ['super_admin' ] ] ,
						['Confirm Account Balance' , 'finances.accounts.confirmAccountBalance' , ['super_admin' ] ]
					]
				] ,
				['Transfers' , [
						['Add Transfer' , 'finances.transfers.selectAccountsInvolved' , ['super_admin' ] ] ,
						['View Transfers' , 'finances.transfers.viewAll' , ['super_admin' ] ]
					]
				]
			] ,
		] ,
		['System' , [
				['Settings' , 'system.settings' , ['super_admin' ] ] ,
				[ 'User Permissions' , 'system.userPermissions' , [ 'change_user_permissions' ] ] ,
				[ 'Add New User' , 'system.addNewUser' , ['super_admin' ] ] ,
				['Activity Log' , 'system.activityLog' , ['view_activity_log' ] ]
			]
		] ,
		['File Manager' , 'fileManager.home' , [ 'manage_files' ] ] ,
		['Reports' , [
				['Stock' , [
						['Stock Report' , 'reports.stocks' , [ 'view_stock_report' ] ] ,
						['Unload Report' , 'reports.unloadComparison' , ['view_unload_comparison_report' ] ] ,
						['Item Return Report' , 'reports.itemReturnReport' , ['view_item_return_report' ] ] ,
						['Timely Stock Report' , 'reports.timelyStockReport' , ['view_timely_stock_report' ] ] ,
						['Stock Confirm Report' , 'reports.stockConfirmReport' , ['view_stock_confirm_report' ] ] ,
					]
				] ,
				['Finance' , [
						['Debtor Summary' , 'reports.debtorSummary' , ['view_debtor_summary_report' ] ] ,
						['Profit and Loss Report' , 'reports.profitAndLossReport' , ['view_profit_and_loss_report' ] ] ,
						['Age Credit Report' , 'reports.ageCreditReport' , ['view_age_credit_report' ] ] ,
						['Incoming Cheques Report' , 'reports.incomingChequesReport' , ['view_incoming_cheques_report' ] ] ,
						['Credit Summary Report' , 'reports.creditSummary' , ['view_credit_summery_report' ] ] ,
						[ 'Rep Finance' , 'reports.repFinanceReport' , ['view_rep_finance_report' ] ] ,
					]
				] ,
				['Sales' , [
						['View Sales' , 'reports.viewSales' , ['view_sales_report' ] ] ,
						['Sales Summary Report' , 'reports.salesSummary' , ['view_sales_summary_report' ] ] ,
						['Item Sales Details' , 'reports.itemSalesDetails' , ['view_item_sales_details_report' ] ] ,
						['Item Sales Summary' , 'report.itemSalesSummary' , ['view_item_sales_summary_report' ] ] ,
					]
				]
			]
		] ,
		['Tools' , [
				['Weight Calculator' , 'tools.weightCalculator' , ['view_weight_calculator' ] ]  
			]
		] ,
		['Account' , [
				['Logout' , 'account.logout' , NULL ] ,
				['Settings' , 'account.settings' , NULL ]
			]
		] ,
		] ;

	public static function getMenu ( User $user )
	{
		$userPermissions = $user -> getAbilityCodes () ;

		$userMenu = self::makeUserMenu ( $userPermissions , self::$menuTemplate ) ;

		return $userMenu ;
	}

	private static function makeUserMenu ( $userPermissions , $menuItems )
	{
		$new = NULL ;

		foreach ( $menuItems as $menuItem )
		{
			$elementCount = count ( $menuItem ) ;

			if ( $elementCount == 2 && is_array ( $menuItem[ 1 ] ) )
			{
//Mid Point
				$subArray = self::makeUserMenu ( $userPermissions , $menuItem[ 1 ] ) ;

				if ( is_array ( $subArray ) )
				{
					$newItem		 = [ ] ;
					$newItem[ 0 ]	 = $menuItem[ 0 ] ;
					$newItem[ 1 ]	 = $subArray ;
					$new[]			 = $newItem ;
				}
			} elseif ( $elementCount == 2 && is_string ( $menuItem[ 1 ] ) )
			{
				$newItem		 = [ ] ;
				$newItem[ 0 ]	 = $menuItem[ 0 ] ;
				$newItem[ 1 ]	 = $menuItem[ 1 ] ;

				$new[] = $newItem ;
			} elseif ( $elementCount == 3 )
			{
//End Point
				if ( is_null ( $menuItem[ 2 ] ) )
				{
//No permission required for this menu option.

					$newItem		 = [ ] ;
					$newItem[ 0 ]	 = $menuItem[ 0 ] ;
					$newItem[ 1 ]	 = $menuItem[ 1 ] ;

					$new[] = $newItem ;
				} else
				{
					$hasPermissions = [ ] ;

					if ( is_array ( $userPermissions ) && is_array ( $menuItem[ 2 ] ) )
					{
						$hasPermissions = array_intersect ( $userPermissions , $menuItem[ 2 ] ) ;
					}

					if ( SessionButler::isSuperAdminLoggedIn () || count ( $hasPermissions ) > 0 )
					{
						$newItem		 = [ ] ;
						$newItem[ 0 ]	 = $menuItem[ 0 ] ;
						$newItem[ 1 ]	 = $menuItem[ 1 ] ;

						$new[] = $newItem ;
					}
				}
			}
		}

		return $new ;
	}

}
