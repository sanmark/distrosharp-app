<?php

namespace Controllers\Finances ;

class AccountsController extends \Controller
{

	public function add ()
	{

		$bankSelectBox = \Models\Bank::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'None' ] ) ;

		$data = compact ( [
			'bankSelectBox'
			] ) ;

		return \View::make ( 'web.finances.accounts.add' , $data ) ;
	}

	public function edit ( $id )
	{
		$financeAccount	 = \Models\FinanceAccount::findOrFail ( $id ) ;
		$bankSelectBox	 = \Models\Bank::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'None' ] ) ;

		if ( $financeAccount -> is_in_house == FALSE )
		{
			\MessageButler::setError ( 'Can\'t edit accounts not in house.' ) ;

			return \Redirect::action ( 'finances.accounts.view' ) ;
		}

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
			$name		 = \InputButler::get ( 'name' ) ;
			$bankId		 = \InputButler::get ( 'bank_id' ) ;
			$isActive	 = \InputButler::get ( 'is_active' ) ;

			$financeAccounts = \Models\FinanceAccount::findOrFail ( $id ) ;

			$financeAccounts -> name			 = $name ;
			$financeAccounts -> bank_id			 = \NullHelper::nullIfEmpty ( $bankId ) ;
			$financeAccounts -> account_balance	 = 0 ;
			$financeAccounts -> is_active		 = \NullHelper::zeroIfNull ( $isActive ) ;

			$financeAccounts -> update () ;

			\MessageButler::setSuccess ( 'Finance account "' . $name . '"was updated successfully' ) ;
			\ActivityLogButler::add ( "Update finance Account " . $financeAccounts -> id ) ;

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

			$name			 = \InputButler::get ( 'name' ) ;
			$bankId			 = \InputButler::get ( 'bank_id' ) ;
			$isActive		 = \InputButler::get ( 'is_active' ) ;
			$financeAccounts = new \Models\FinanceAccount() ;

			$financeAccounts -> name			 = $name ;
			$financeAccounts -> bank_id			 = \NullHelper::nullIfEmpty ( $bankId ) ;
			$financeAccounts -> account_balance	 = 0 ;
			$financeAccounts -> is_in_house		 = 1 ;
			$financeAccounts -> is_active		 = \NullHelper::zeroIfNull ( $isActive ) ;

			$financeAccounts -> save () ;

			\ActivityLogButler::add ( "Add Finance Account " . $financeAccounts -> id ) ;

			\MessageButler::setSuccess ( 'Finance account "' . $name . '" was added successfully' ) ;
			return \Redirect::action ( 'finances.accounts.view' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function home ()
	{

		$filterValues	 = \Input::all () ;
		$financeAccounts = \Models\FinanceAccount::filter ( $filterValues ) ;

		$name					 = \InputButler::get ( 'name' ) ;
		$bankId					 = \InputButler::get ( 'bank_id' ) ;
		$isActive				 = \InputButler::get ( 'is_active' ) ;
		$financeAccountsDetails	 = \Models\FinanceAccount::where ( 'is_in_house' , '=' , TRUE ) -> get () ;
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

	public function confirmAccountHome ()
	{
		$accountSelectBox	 = \FinanceAccountButler::getAccountsInvolvedForTransfer () ;
		$accountId			 = NULL ;
		$viewDateTime		 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
		$transferData		 = NULL ;

		$data = compact ( [
			'accountSelectBox' ,
			'accountId' ,
			'viewDateTime' ,
			'transferData'
			] ) ;

		return \View::make ( 'web.finances.accounts.confirm' , $data ) ;
	}

	public function confirmAccountFilter ()
	{

		$filterValues		 = \Input::all () ;
		$accountId			 = \InputButler::get ( 'account' ) ;
		$viewDateTime		 = \InputButler::get ( 'datetime' ) ;
		$accountSelectBox	 = \FinanceAccountButler::getAccountsInvolvedForTransfer () ;

		if ( $accountId == '' )
		{
			\MessageButler::setError ( "Please select a finance account" ) ;
			return \Redirect::back ()
					-> withInput () ;
		}

		$financeAccount = \Models\FinanceAccount::findOrFail ( $accountId ) ;

		$viewDateTime = \NullHelper::ifNullEmptyOrWhitespace ( $viewDateTime , date ( 'Y-m-d H:i:s' ) ) ;

		$transferData = $financeAccount -> financeAccountReportFilter ( $filterValues ) ;

		$lastConfirmDateTime = $financeAccount -> getLastConfirmDateBefore ( $viewDateTime ) ;


		if ( \InputButler::get ( 'confirm' ) )
		{
			$financeAccount -> verifyFinanceAccountBalance ( $viewDateTime ) ;
			$filterValues[ 'datetime' ]	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
			$viewDateTime				 = $filterValues[ 'datetime' ] ;

			$transferData = $financeAccount -> financeAccountReportFilter ( $filterValues ) ;

			$lastConfirmDateTime = $financeAccount -> getLastConfirmDate () ;
		}

		$startingTotal	 = $financeAccount -> getFinanceAccountStartBalance ( $lastConfirmDateTime ) ;
		$endingTotal	 = $financeAccount -> getFinanceAccountEndBalance ( $lastConfirmDateTime , $transferData ) ;

		$data = compact ( [
			'accountSelectBox' ,
			'accountId' ,
			'viewDateTime' ,
			'transferData' ,
			'endingTotal' ,
			'startingTotal' ,
			'startingConfirmDateTime'
			] ) ;

		return \View::make ( 'web.finances.accounts.confirm' , $data ) ;
	}

}
