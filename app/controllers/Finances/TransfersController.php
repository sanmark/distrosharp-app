<?php

namespace Controllers\Finances ;

class TransfersController extends \Controller
{

	public function viewAll ()
	{
		$filterValues	 = \Input::all () ;
		$financeData	 = \Models\FinanceTransfer::viewAllFilter ( $filterValues ) ;
		$fromDate		 = \InputButler::get ( 'from_date' ) ;
		$toDate			 = \InputButler::get ( 'to_date' ) ;
		$compareSign	 = \InputButler::get ( 'compare_sign' ) ;
		$amount			 = \InputButler::get ( 'amount' ) ;
		$fromAccount	 = \InputButler::get ( 'from_account' ) ;
		$toAccount		 = \InputButler::get ( 'to_account' ) ;

		$compareSignSelectBox	 = ['' => 'Compare' , '>' => 'Greater Than' , '<' => 'Smaller Than' , '=' => 'Equals to' ] ;
		$fromAccountsId			 = \Models\FinanceTransfer::distinct ( 'from_id' ) -> lists ( 'from_id' ) ;
		if ( count ( $fromAccountsId ) == 0 )
		{
			$fromAccountsIds = [NULL => 'Select Account' ] ;
		} else
		{
			$fromAccountsIds = [NULL => 'Select Account' ] + \Models\FinanceAccount::whereIn ( 'id' , $fromAccountsId )
					-> getArrayForHtmlSelect ( 'id' , 'name' ) ;
		}

		$toAccountsId = \Models\FinanceTransfer::distinct ( 'to_id' ) -> lists ( 'to_id' ) ;
		if ( count ( $fromAccountsId ) == 0 )
		{
			$toAccountsIds = [NULL => 'Select Account' ] ;
		} else
		{
			$toAccountsIds = [NULL => 'Select Account' ] + \Models\FinanceAccount::whereIn ( 'id' , $toAccountsId )
					-> getArrayForHtmlSelect ( 'id' , 'name' ) ;
		}

		$data = compact ( [
			'financeData' ,
			'compareSignSelectBox' ,
			'fromAccountsIds' ,
			'toAccountsIds' ,
			'compareSign' ,
			'fromAccount' ,
			'toAccount' ,
			'fromDate' ,
			'toDate' ,
			'amount'
			] ) ;
		return \View::make ( 'web.finances.transfers.viewAll' , $data ) ;
	}

	public function selectAccountsInvolved ()
	{
		$requestObject		 = \Models\FinanceAccount::where ( 'is_active' , '=' , TRUE )
			-> where ( 'is_in_house' , '=' , TRUE ) ;
		$accountSelectBox	 = \Models\FinanceAccount::getArrayForHtmlSelectByRequestObject ( 'id' , 'name' , $requestObject , [NULL => 'Select Account' ] ) ;
		$data				 = compact ( [
			'accountSelectBox'
			] ) ;

		return \View::make ( 'web.finances.transfers.selectAccountsInvolved' , $data ) ;
	}

	public function pSelectAccountsInvolved ()
	{
		try
		{
			$from	 = \InputButler::get ( 'from' ) ;
			$to		 = \InputButler::get ( 'to' ) ;

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
		$currentDate = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-dTH:i:s' ) ) ;
		$data		 = compact ( [
			'fromAccount' ,
			'toAccount' ,
			'currentDate'
			] ) ;
		return \View::make ( 'web.finances.transfers.add' , $data ) ;
	}

	public function save ( $fromAccountId , $toAccountId )
	{
		try
		{
			$dateTime	 = \InputButler::get ( 'date_time' ) ;
			$amount		 = \InputButler::get ( 'amount' ) ;
			$description = \InputButler::get ( 'description' ) ;

			$financeTransfer				 = new \Models\FinanceTransfer() ;
			$financeTransfer -> from_id		 = $fromAccountId ;
			$financeTransfer -> to_id		 = $toAccountId ;
			$financeTransfer -> date_time	 = $dateTime ;
			$financeTransfer -> amount		 = $amount ;
			$financeTransfer -> description	 = $description ;

			$financeTransfer -> save () ;

			\ActivityLogButler::add ( "Add Finance Transfer " . $financeTransfer -> id ) ;

			\MessageButler::setSuccess ( "Finance transfer was added successfully" ) ;

			return \Redirect::action ( 'finances.transfers.viewAll' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function home ( $accountId )
	{

		$filterValues		 = \Input::all () + ['id' => $accountId ] ;
		$accountTransfers	 = \Models\FinanceTransfer::filter ( $filterValues ) ;

		$fromDate				 = \InputButler::get ( 'from_date' ) ;
		$toDate					 = \InputButler::get ( 'to_date' ) ;
		$inOrOut				 = \InputButler::get ( 'in_or_out' ) ;
		$compareSign			 = \InputButler::get ( 'compare_sign' ) ;
		$amount					 = \InputButler::get ( 'amount' ) ;
		$accountRefill			 = \InputButler::get ( 'transfer_account' ) ;
		$compareSignSelectBox	 = ['' => 'Compare' , '>' => 'Greater Than' , '<' => 'Smaller Than' , '=' => 'Equals to' ] ;

		$inOrOutSelectBox	 = ['' => 'Any' , 'in' => 'In' , 'out' => 'Out' ] ;
		$accountName		 = \Models\FinanceAccount::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'Transfer Account' ] ) ;

		$account = \Models\FinanceAccount::findOrFail ( $accountId ) ;

		$data = compact ( [
			'accountTransfers' ,
			'compareSignSelectBox' ,
			'account' ,
			'inOrOutSelectBox' ,
			'fromDate' ,
			'toDate' ,
			'compareSign' ,
			'inOrOut' ,
			'amount' ,
			'accountName' ,
			'accountRefill'
			] ) ;

		return \View::make ( 'web.finances.transfers.home' , $data ) ;
	}

	public function edit ( $transferId )
	{
		$financeTransfer	 = \Models\FinanceTransfer::with ( 'chequeDetail.bank' )
			-> findOrFail ( $transferId ) ;
		$accountSelectBox	 = \Models\FinanceAccount::getArrayForHtmlSelect ( 'id' , 'name' ) ;
		$dateTime			 = \DateTimeHelper::dateTimeRefill ( $financeTransfer -> date_time ) ;
		$banksList			 = \Models\Bank::where ( 'is_active' , '=' , TRUE ) -> getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

		$data = compact ( [
			'financeTransfer' ,
			'accountSelectBox' ,
			'dateTime' ,
			'banksList'
			] ) ;

		return \View::make ( 'web.finances.transfers.edit' , $data ) ;
	}

	public function update ( $transferId )
	{
		try
		{
			$financeTransferUpdateRow = \Models\FinanceTransfer::with ( 'chequeDetail' )
				-> findOrFail ( $transferId ) ;

			$dateTime			 = \InputButler::get ( 'date_time' ) ;
			$amount				 = \InputButler::get ( 'amount' ) ;
			$description		 = \InputButler::get ( 'description' ) ;
			$fromId				 = \InputButler::get ( 'from_id' ) ;
			$toId				 = \InputButler::get ( 'to_id' ) ;
			$chequeBankId		 = \InputButler::get ( 'cheque_bank_id' ) ;
			$chequeNumber		 = \InputButler::get ( 'cheque_number' ) ;
			$chequeIssuedDate	 = \InputButler::get ( 'cheque_issued_date' ) ;
			$chequePayableDate	 = \InputButler::get ( 'cheque_payable_date' ) ;

			$this -> validateChequeDetails ( $chequeBankId , $chequeNumber , $chequeIssuedDate , $chequePayableDate ) ;

			$financeTransferUpdateRow -> date_time	 = $dateTime ;
			$financeTransferUpdateRow -> amount		 = $amount ;
			$financeTransferUpdateRow -> description = $description ;
			$financeTransferUpdateRow -> from_id	 = $fromId ;
			$financeTransferUpdateRow -> to_id		 = $toId ;

			$financeTransferUpdateRow -> update () ;

			$chequeDetail					 = $financeTransferUpdateRow -> chequeDetail ;
			$chequeDetail -> bank_id		 = $chequeBankId ;
			$chequeDetail -> cheque_number	 = $chequeNumber ;
			$chequeDetail -> issued_date	 = $chequeIssuedDate ;
			$chequeDetail -> payable_date	 = $chequePayableDate ;

			$chequeDetail -> update () ;

			\ActivityLogButler::add ( "Edit Finance Transfer " . $financeTransfer -> id ) ;

			\MessageButler::setSuccess ( "Finance transfer was updated successfully" ) ;

			return \Redirect::action ( 'finances.transfers.viewAll' ) ;
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

	public function validateChequeDetails ( $chequeBankId , $chequeNumber , $chequeIssuedDate , $chequePayableDate )
	{
		$chequeIssuedDate	 = \DateTimeHelper::convertTextToFormattedDateTime ( $chequeIssuedDate , 'Y-m-d' ) ;
		$chequePayableDate	 = \DateTimeHelper::convertTextToFormattedDateTime ( $chequePayableDate , 'Y-m-d' ) ;

		$data = compact ( [
			'chequeBankId' ,
			'chequeNumber' ,
			'chequeIssuedDate' ,
			'chequePayableDate'
			] ) ;

		$rules = [
			'chequeBankId'		 => [
				'required'
			] ,
			'chequeNumber'		 => [
				'required'
			] ,
			'chequeIssuedDate'	 => [
				'required' ,
				'date' ,
				'date_format:Y-m-d'
			] ,
			'chequePayableDate'	 => [
				'required' ,
				'date' ,
				'date_format:Y-m-d'
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
