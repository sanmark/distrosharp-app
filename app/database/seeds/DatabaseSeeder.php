<?php

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run ()
	{
		Eloquent::unguard () ;

		$this -> call ( 'UserSeeder' ) ;
		$this -> call ( 'AbilityUserSeeder' ) ;
		$this -> call ( 'ItemsSeeder' ) ;
		$this -> call ( 'BanksSeeder' ) ;
		$this -> call ( 'FinanceAccountsSeeder' ) ;
		$this -> call ( 'VendorsSeeder' ) ;
		$this -> call ( 'RoutesSeeder' ) ;
		$this -> call ( 'CustomersSeeder' ) ;
		$this -> call ( 'StocksSeeder' ) ;
		$this -> call ( 'StockDetailsSeeder' ) ;
		$this -> call ( 'SystemSettingsSeeder' ) ;
	}

}
