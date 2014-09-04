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

	public function selectTimeZone ()
	{
		$allTimeZones	 = timezone_identifiers_list () ;
		$all			 = [ ] ;

		foreach ( $allTimeZones as $timeZone )
		{
			$all[ $timeZone ] = $timeZone ;
		}
		$all	 = ['' => 'Select Time Zone' ] + $all ;
		$data	 = compact ( [
			'all'
		] ) ;

		return \View::make ( 'web.system.settings.timezone' , $data ) ;
	}

	public function updateTimeZone ()
	{
		try
		{
			$timezone = \Input::get ( 'time_zone' ) ;
			\SystemSettingButler::setValue ( 'time_zone' , $timezone ) ;

			\MessageButler::setSuccess ( 'Timezone were saved successfully.' ) ;
			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
