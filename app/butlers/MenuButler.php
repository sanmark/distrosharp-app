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
						[ 'Order Items','entities.items.order',[ 'order_items' ] ]
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
						['Add Sale' , 'processes.sales.add' , ['add_sale' ] ]
					]
				]
			]
		] ,
		['Stocks' , [
				['View Stocks' , 'stocks.all' , ['view_stocks' ] ]
			]
		] ,
		['Finance' , [
				['Account' , [
						['Add Account' , 'finances.accounts.add' , ['add_finance_account' ] ] ,
						['View Account' , 'finances.accounts.view' , ['view_finance_accounts' ] ] ,
						['Confirm Account Balance' , 'finances.accounts.confirmBankAccount' , ['confirm_bank_account_balance' ] ]
					]
				] ,
				['Transfers' , [
						['Add Transfer' , 'finances.transfers.selectAccountsInvolved' , ['add_finance_transfer' ] ] ,
						['View Transfers' , 'finances.transfers.viewAll' , ['view_finance_transfers_details' ] ]
					]
				]
			] ,
		] ,
		['System' , [
				['Settings' , 'system.settings' , ['edit_system_settings' ] ]
			]
		] ,
		['File Manager' , 'fileManager.home' , [ 'manage_files' ] ] ,
		['Reports' , [
				['Stock Report' , 'reports.stocks' , [ 'view_stock_report' ] ] ,
				['Debtor Summary' , 'reports.debtorSummary' , ['view_debtor_summary_report' ] ] ,
				['Unload Report' , 'reports.unloadComparison' , ['view_unload_comparison_report' ] ] ,
				['Sales Summary Report' , 'reports.salesSummary' , ['view_sales_summary_report' ] ] ,
				['Profit and Loss Report' , 'reports.profitAndLossReport' , ['view_profit_and_loss_report' ] ] ,
				['Age Credit Report' , 'reports.ageCreditReport' , ['view_age_credit_report' ] ] ,
				['Item Sales Details' , 'reports.itemSalesDetails' , ['view_item_sales_details_report' ] ] ,
				['Incoming Cheques Report' , 'reports.incomingChequesReport' , ['view_incoming_cheques_report' ] ] ,
				['Item Sales Summary' , 'report.itemSalesSummary' , ['view_item_sales_summary_report' ] ] ,
				['Item Return Report' , 'reports.itemReturnReport' , ['view_item_return_report' ] ]
			] ,
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

					if ( count ( $hasPermissions ) > 0 )
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
