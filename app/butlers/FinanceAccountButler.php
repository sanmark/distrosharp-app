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

}
