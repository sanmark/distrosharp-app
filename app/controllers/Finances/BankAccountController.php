<?php

namespace Controllers\Finances ;

class BankAccountController extends \Controller
{

	public function home ()
	{
		$bankAccountSelectBox	 = \FinanceAccountButler::getBankAccountsInvolvedForTransfer () ;
		$bankAccountId			 = NULL ;
		$viewDateTime			 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
		$transferData			 = NULL ;

		$data = compact ( [
			'bankAccountSelectBox' ,
			'bankAccountId' ,
			'viewDateTime' ,
			'transferData'
		] ) ;

		return \View::make ( 'web.finances.accounts.confirm' , $data ) ;
	}

	public function filter ()
	{

		$filterValues			 = \Input::all () ;
		$bankAccountId			 = \Input::get ( 'account' ) ;
		$viewDateTime			 = \Input::get ( 'datetime' ) ;
		$bankAccountSelectBox	 = \FinanceAccountButler::getBankAccountsInvolvedForTransfer () ;

		if ( $bankAccountId == '' )
		{
			\MessageButler::setError ( "Please select a bank account" ) ;
			return \Redirect::back ()
			-> withInput () ;
		}

		$financeAccount = \Models\FinanceAccount::findOrFail ( $bankAccountId ) ;

		$viewDateTime = \NullHelper::ifNullEmptyOrWhitespace ( $viewDateTime , date ( 'Y-m-d H:i:s' ) ) ;

		$transferData = $financeAccount -> bankReportFilter ( $filterValues ) ;

		$lastConfirmDateTime = $financeAccount -> getLastConfirmDateBefore ( $viewDateTime ) ;


		if ( \Input::get ( 'confirm' ) )
		{
			$financeAccount -> verifyFinanceAccountBalance ( $viewDateTime ) ;
			$filterValues[ 'datetime' ]	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
			$viewDateTime				 = $filterValues[ 'datetime' ] ;

			$transferData = $financeAccount -> bankReportFilter ( $filterValues ) ;

			$lastConfirmDateTime = $financeAccount -> getLastConfirmDate () ;
		}

		$startingTotal	 = $financeAccount -> getFinanceAccountStartBalance ( $lastConfirmDateTime ) ;
		$endingTotal	 = $financeAccount -> getFinanceAccountEndBalance ( $lastConfirmDateTime , $transferData ) ;

		$data = compact ( [
			'bankAccountSelectBox' ,
			'bankAccountId' ,
			'viewDateTime' ,
			'transferData' ,
			'endingTotal' ,
			'startingTotal' ,
			'startingConfirmDateTime'
		] ) ;

		return \View::make ( 'web.finances.accounts.confirm' , $data ) ;
	}

}
