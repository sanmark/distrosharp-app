<?php

class FinanceAccountsSeeder extends Seeder
{

	public function run ()
	{
		$financeAccounts = [
			[
				'id'				 => 1 ,
				'name'				 => 'Cash' ,
				'bank_id'			 => NULL ,
				'is_active'			 => TRUE ,
				'account_balance'	 => 0 ,
				'is_in_house'		 => TRUE ,
			] ,
			[
				'id'				 => 2 ,
				'name'				 => 'Cheque' ,
				'bank_id'			 => NULL ,
				'is_active'			 => TRUE ,
				'account_balance'	 => 0 ,
				'is_in_house'		 => TRUE ,
			] ,
			[
				'id'				 => 3 ,
				'name'				 => 'Imbalance' ,
				'bank_id'			 => NULL ,
				'is_active'			 => TRUE ,
				'account_balance'	 => 0 ,
				'is_in_house'		 => TRUE ,
			] ,
//			[
//				'id'				 =>  ,
//				'name'				 =>  ,
//				'bank_id'			 =>  ,
//				'is_active'			 =>  ,
//				'account_balance'	 =>  ,
//				'is_in_house'		 =>  ,
//			] ,
		] ;

		foreach ( $financeAccounts as $financeAccount )
		{
			$financeAccountO = new Models\FinanceAccount() ;

			$financeAccountO -> id				 = $financeAccount[ 'id' ] ;
			$financeAccountO -> name			 = $financeAccount[ 'name' ] ;
			$financeAccountO -> bank_id			 = $financeAccount[ 'bank_id' ] ;
			$financeAccountO -> is_active		 = $financeAccount[ 'is_active' ] ;
			$financeAccountO -> account_balance	 = $financeAccount[ 'account_balance' ] ;
			$financeAccountO -> is_in_house		 = $financeAccount[ 'is_in_house' ] ;

			$financeAccountO -> save () ;
		}
	}

}
