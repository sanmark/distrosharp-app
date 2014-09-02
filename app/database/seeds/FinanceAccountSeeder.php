<?php

class FinanceAccountSeeder extends Seeder
{

	public function run ()
	{
		$financeaccounts = [
			[
				'id'				 => 1 ,
				'name'				 => 'Personal' ,
				'bank_id'			 => 1 ,
				'is_active'			 => 1 ,
				'account_balance'	 => 0 ,
				'is_in_house'		 => 0
			] ,
			[
				'id'				 => 2 ,
				'name'				 => 'Family' ,
				'bank_id'			 => 1 ,
				'is_active'			 => 1 ,
				'account_balance'	 => 0 ,
				'is_in_house'		 => 1
			] ,
			[
				'id'				 => 3 ,
				'name'				 => 'Friends' ,
				'bank_id'			 => 1 ,
				'is_active'			 => 1 ,
				'account_balance'	 => 0 ,
				'is_in_house'		 => 0
			] ,
//			[
//				'id'			 => 1 ,
//				'name'			 => 'Personal' ,
//				'bank_id'			 => 1 ,
//				'is_active'			 => 1 ,
//				'account_balance'	 => 0 ,
//				'is_in_house'		 => 0 
//			] ,
		] ;
		foreach ( $financeaccounts as $financeaccount )
		{
			$accO = new \Models\FinanceAccount() ;

			$accO -> id				 = $financeaccount[ 'id' ] ;
			$accO -> name			 = $financeaccount[ 'name' ] ;
			$accO -> bank_id		 = $financeaccount[ 'bank_id' ] ;
			$accO -> is_active		 = $financeaccount[ 'is_active' ] ;
			$accO -> account_balance = $financeaccount[ 'account_balance' ] ;
			$accO -> is_in_house	 = $financeaccount[ 'is_in_house' ] ;

			$accO -> save () ;
		}
	}

}
