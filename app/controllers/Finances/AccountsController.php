<?php

namespace Controllers\Finances ;

class AccountsController extends \Controller
{

	public function add ()
	{
		$data = [ ] ;

		$bankSelectBox = \Models\Bank::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'None' ] ) ;

		$data[ 'bankSelectBox' ] = $bankSelectBox ;

		return \View::make ( 'web.finances.accounts.add' , $data ) ;
	}

	public function save ()
	{

		try
		{

			$name		 = \Input::get ( 'name' ) ;
			$bankId		 = \Input::get ( 'bank_id' ) ;
			$isInHouse	 = \Input::get ( 'is_in_house' ) ;
			$isActive	 = \Input::get ( 'is_active' ) ;

			$financeAccounts = new \Models\FinanceAccount() ;

			$financeAccounts -> name			 = $name ;
			$financeAccounts -> bank_id			 = \NullHelper::nullIfEmpty ( $bankId ) ;
			$financeAccounts -> account_balance	 = 0 ;
			$financeAccounts -> is_in_house		 = \NullHelper::zeroIfNull ( $isInHouse ) ;
			$financeAccounts -> is_active		 = \NullHelper::zeroIfNull ( $isActive ) ;

			$financeAccounts -> save () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
