<?php

namespace Controllers\System ;

class SettingsController extends \Controller
{

	public function showHome ()
	{
		return \View::make ( 'web.system.settings.home' ) ;
	}

	public function showPaymentSourceAccounts ()
	{
		$inHouseAccountRO	 = \Models\FinanceAccount::where ( 'is_in_house' , '=' , TRUE ) ;
		$inHouseAccounts	 = \Models\FinanceAccount::getArrayForHtmlSelectByRequestObject ( 'id' , 'name' , $inHouseAccountRO , [ NULL => 'Select' ] ) ;

		$paymentSourceCash	 = \SystemSettingButler::getValue ( 'payment_source_cash' ) ;
		$paymentSourceCheque = \SystemSettingButler::getValue ( 'payment_source_cheque' ) ;

		$data = compact ( [
			'inHouseAccounts' ,
			'paymentSourceCash' ,
			'paymentSourceCheque'
		] ) ;

		return \View::make ( 'web.system.settings.paymentSourceAccounts' , $data ) ;
	}

	public function updatePaymentSourceAccounts ()
	{
		try
		{
			$paymentSourceCash	 = \Input::get ( 'payment_source_cash' ) ;
			$paymentSourceCheque = \Input::get ( 'payment_source_cheque' ) ;

			\SystemSettingButler::setValue ( 'payment_source_cash' , $paymentSourceCash ) ;
			\SystemSettingButler::setValue ( 'payment_source_cheque' , $paymentSourceCheque ) ;

			\MessageButler::setSuccess ( 'Payment source(s) were saved successfully.' ) ;
			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
