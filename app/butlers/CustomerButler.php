<?php

class CustomerButler
{

	public static function getSumsOfCreditsByCustomerIds ( $customerIds )
	{
		$creditBalanceWithCustomerId = [ ] ;
		foreach ( $customerIds as $customerId )
		{
			$customer													 = \Models\Customer::findOrFail ( $customerId -> customer_id ) ;
			$creditBalanceWithCustomerId[ $customerId -> customer_id ]	 = $customer -> getSumOfInvoiceCreditBalances () ;
		}
		return $creditBalanceWithCustomerId ;
	}

}
