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

	public function edit ( $id )
	{
		$data = [ ] ;

		$financeAccount	 = \Models\FinanceAccount::findOrFail ( $id ) ;
		$bankSelectBox	 = \Models\Bank::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'None' ] ) ;

		$data = compact ( [
			'financeAccount' ,
			'id' ,
			'bankSelectBox'
		] ) ;
		return \View::make ( 'web.finances.accounts.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			$name		 = \Input::get ( 'name' ) ;
			$bankId		 = \Input::get ( 'bank_id' ) ;
			$isInHouse	 = \Input::get ( 'is_in_house' ) ;
			$isActive	 = \Input::get ( 'is_active' ) ;

			$financeAccounts = \Models\FinanceAccount::findOrFail ( $id ) ;

			$financeAccounts -> name			 = $name ;
			$financeAccounts -> bank_id			 = \NullHelper::nullIfEmpty ( $bankId ) ;
			$financeAccounts -> account_balance	 = 0 ;
			$financeAccounts -> is_in_house		 = \NullHelper::zeroIfNull ( $isInHouse ) ;
			$financeAccounts -> is_active		 = \NullHelper::zeroIfNull ( $isActive ) ;

			$financeAccounts -> update () ;

			return \Redirect::action ( 'finances.accounts.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
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

	public function home ()
	{
		$data = [ ] ;

		$filterValues	 = \Input::all () ;
		$financeAccounts = \Models\FinanceAccount::filter ( $filterValues ) ;

		$name					 = \Input::get ( 'name' ) ;
		$bankId					 = \Input::get ( 'bank_id' ) ;
		$isInHouse				 = \Input::get ( 'is_in_house' ) ;
		$isActive				 = \Input::get ( 'is_active' ) ;
		$financeAccountsDetails	 = \Models\FinanceAccount::get () ;
		$banks					 = \Models\FinanceAccount::distinct () -> lists ( 'bank_id' ) ;
		$bankSelectBox			 = \Models\Bank::getArrayForHtmlSelectByIds ( 'id' , 'name' , $banks , [
			''		 => 'Any' ,
			'none'	 => 'None'
		] ) ;
		$data					 = compact ( [
			'financeAccounts' ,
			'name' ,
			'bankId' ,
			'isInHouse' ,
			'isActive' ,
			'financeAccountsDetails' ,
			'bankSelectBox'
		] ) ;

		return \View::make ( 'web.finances.accounts.home' , $data ) ;
	}

}
