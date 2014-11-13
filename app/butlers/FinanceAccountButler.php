<?php

class FinanceAccountButler
{

	public static function getCashTargetAccount ()
	{
		$cashTargetAccountId = SystemSettingButler::getValue ( 'payment_target_cash' ) ;

		$cashTargetAccount = \Models\FinanceAccount::findOrFail ( $cashTargetAccountId ) ;

		return $cashTargetAccount ;
	}

	public static function getChequeTargetAccount ()
	{
		$chequeTargetAccountId = SystemSettingButler::getValue ( 'payment_target_cheque' ) ;

		$chequeTargetAccount = \Models\FinanceAccount::findOrFail ( $chequeTargetAccountId ) ;

		return $chequeTargetAccount ;
	}

	public static function getCashSourceAccount ()
	{
		$cashSourceAccountId = \SystemSettingButler::getValue ( 'payment_source_cash' ) ;

		$cashSourceAccount = Models\FinanceAccount::findOrFail ( $cashSourceAccountId ) ;

		return $cashSourceAccount ;
	}

	public static function getChequeSourceAccount ()
	{
		$chequeSourceAccountId = \SystemSettingButler::getValue ( 'payment_source_cheque' ) ;

		$chequeSourceAccount = Models\FinanceAccount::findOrFail ( $chequeSourceAccountId ) ;

		return $chequeSourceAccount ;
	}

	public static function getAccountsInvolvedForTransfer ()
	{
		$accountListFromId	 = \Models\FinanceTransfer::distinct ( 'from_id' ) -> lists ( 'from_id' ) ;
		$accountListToId	 = \Models\FinanceTransfer::distinct ( 'to_id' ) -> lists ( 'to_id' ) ;

		$accountListFromTransfers = array_unique ( array_merge ( $accountListFromId , $accountListToId ) ) ;

		$accountList = \Models\FinanceAccount::where ( 'is_in_house' , '=' , TRUE ) -> lists ( 'id' ) ;

		$accountList = array_intersect ( $accountListFromTransfers , $accountList ) ;

		$accountSelectBox = \Models\FinanceAccount::getArrayForHtmlSelectByIds ( 'id' , 'name' , $accountList , ['' => 'Select Account' ] ) ;

		return $accountSelectBox ;
		
	}

}
