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

}
