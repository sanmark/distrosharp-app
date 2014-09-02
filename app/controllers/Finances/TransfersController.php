<?php

namespace Controllers\Finances ;

class TransfersController extends \Controller
{

	public function selectAccountsInvolved ()
	{

		$accountSelectBox = \Models\FinanceAccount::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'Select Account' ] ) ;

		$data = compact ( [
			'accountSelectBox'
		] ) ;

		return \View::make ( 'web.finances.transfers.selectAccountsInvolved' , $data ) ;
	}

	public function pSelectAccountsInvolved ()
	{
		try
		{
			$from	 = \Input::get ( 'from' ) ;
			$to		 = \Input::get ( 'to' ) ;

			$data = compact ( [
				'from' ,
				'to'
			] ) ;

			$this -> validateSelectedTransfers ( $from , $to ) ;

			return \Redirect::action ( 'finances.transfers.add' , $data ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	public function add ( $fromAccountId , $toAccountId )
	{

		$fromAccount = \Models\FinanceAccount::findOrFail ( $fromAccountId ) ;
		$toAccount	 = \Models\FinanceAccount::findOrFail ( $toAccountId ) ;

		$data = compact ( [
			'fromAccount' ,
			'toAccount'
		] ) ;
		return \View::make ( 'web.finances.transfers.add' , $data ) ;
	}

	public function save ( $fromAccountId , $toAccountId )
	{
		try
		{
			$dateTime	 = \Input::get ( 'date_time' ) ;
			$amount		 = \Input::get ( 'amount' ) ;
			$description = \Input::get ( 'description' ) ;

			$financeAccountFrom	 = \Models\FinanceAccount::findOrFail ( $fromAccountId ) ;
			$financeAccountTo	 = \Models\FinanceAccount::findOrFail ( $toAccountId ) ;

			$fromAccountBalance	 = ($financeAccountFrom -> account_balance - $amount) ;
			$toAccountBalance	 = ($financeAccountTo -> account_balance + $amount) ;

			$financeAccountFrom -> account_balance	 = $fromAccountBalance ;
			$financeAccountTo -> account_balance	 = $toAccountBalance ;

			$financeAccountFrom -> update () ;
			$financeAccountTo -> update () ;

			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $fromAccountId ;
			$financeTransfer -> to_id		 = $toAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $amount ;
			$financeTransfer -> description	 = $description ;

			$financeTransfer -> save () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	public function home ( $accountId )
	{

		$accountTransfers = \Models\FinanceTransfer::where ( 'from_id' , '=' , $accountId )
		-> orWhere ( 'to_id' , '=' , $accountId ) -> get () ;

		$account = \Models\FinanceAccount::findOrFail ( $accountId ) ;

		$data = compact ( [
			'accountTransfers' ,
			'account'
		] ) ;

		return \View::make ( 'web.finances.transfers.home' , $data ) ;
	}

	public function edit ( $transferId )
	{
		$financeTransfer	 = \Models\FinanceTransfer::findOrFail ( $transferId ) ;
		$accountSelectBox	 = \Models\FinanceAccount::getArrayForHtmlSelect ( 'id' , 'name' ) ;
		$dateTimeWithUTC	 = date ( 'Y-m-dTH:i:s' , strtotime ( $financeTransfer -> date_time ) ) ;
		$dateTime			 = str_replace ( 'UTC' , 'T' , $dateTimeWithUTC ) ;

		$data = compact ( [
			'financeTransfer' ,
			'accountSelectBox' ,
			'dateTime'
		] ) ;

		return \View::make ( 'web.finances.transfers.edit' , $data ) ;
	}

	public function update ( $transferId )
	{
		try
		{
			$financeTransferUpdateRow = \Models\FinanceTransfer::findOrFail ( $transferId ) ;

			$dateTime	 = \Input::get ( 'date_time' ) ;
			$amount		 = \Input::get ( 'amount' ) ;
			$description = \Input::get ( 'description' ) ;
			$fromId		 = \Input::get ( 'from_id' ) ;
			$toId		 = \Input::get ( 'to_id' ) ;

			$preFromFinanceAccount = \Models\FinanceAccount::findOrFail ( $financeTransferUpdateRow -> from_id ) ;

			$preToFinanceAccount = \Models\FinanceAccount::findOrFail ( $financeTransferUpdateRow -> to_id ) ;

			$preFromAccountBalance = ($preFromFinanceAccount -> account_balance) + ($financeTransferUpdateRow -> amount) ;
			
			$preToAccountBalance = ($preToFinanceAccount -> account_balance) - ($financeTransferUpdateRow -> amount) ;

			$preFromFinanceAccount -> account_balance	 = $preFromAccountBalance ;
			$preToFinanceAccount -> account_balance		 = $preToAccountBalance ;

			$preFromFinanceAccount -> update () ;
			$preToFinanceAccount -> update () ;

			$financeTransferUpdateRow -> date_time	 = $dateTime ;
			$financeTransferUpdateRow -> amount		 = $amount ;
			$financeTransferUpdateRow -> description = $description ;
			$financeTransferUpdateRow -> from_id	 = $fromId ;
			$financeTransferUpdateRow -> to_id		 = $toId ;

			$financeTransferUpdateRow -> update () ;
			
			$financeAccountFrom	 = \Models\FinanceAccount::findOrFail ( $fromId ) ;
			$financeAccountTo	 = \Models\FinanceAccount::findOrFail ( $toId ) ;

			$fromAccountBalance	 = ($preFromAccountBalance - $amount) ;
			$toAccountBalance	 = ($preToAccountBalance + $amount) ;

			$financeAccountFrom -> account_balance	 = $fromAccountBalance ;
			$financeAccountTo -> account_balance	 = $toAccountBalance ;

			$financeAccountFrom -> update () ;
			$financeAccountTo -> update () ;
			
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

	private function validateSelectedTransfers ( $from , $to )
	{
		$data = [
			'from'	 => $from ,
			'to'	 => $to
		] ;

		$rules = [
			'from'	 => [
				'required' ,
				'different:to'
			] ,
			'to'	 => [
				'required'
			]
		] ;

		$validator = \Validator::make ( $data , $rules ) ;

		if ( $validator -> fails () )
		{
			$iie				 = new \Exceptions\InvalidInputException() ;
			$iie -> validator	 = $validator ;

			throw $iie ;
		}
	}

}
