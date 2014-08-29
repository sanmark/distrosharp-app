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

			$data	 = compact ( [
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

			$financeTransfer = new \Models\FinanceTransfer() ;

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
