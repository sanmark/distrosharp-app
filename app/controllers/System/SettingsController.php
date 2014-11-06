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
			$paymentSourceCash	 = \InputButler::get ( 'payment_source_cash' ) ;
			$paymentSourceCheque = \InputButler::get ( 'payment_source_cheque' ) ;

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
			$timezone = \InputButler::get ( 'time_zone' ) ;
			\SystemSettingButler::setValue ( 'time_zone' , $timezone ) ;

			\MessageButler::setSuccess ( 'Timezone was saved successfully.' ) ;
			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function showPaymentTargetAccounts ()
	{
		$inHouseAccounts	 = \Models\FinanceAccount::where ( 'is_in_house' , '=' , TRUE )
			-> getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;
		$paymentTargetCash	 = \SystemSettingButler::getValue ( 'payment_target_cash' ) ;
		$paymentTargetCheque = \SystemSettingButler::getValue ( 'payment_target_cheque' ) ;

		$data = compact ( [
			'inHouseAccounts' ,
			'paymentTargetCash' ,
			'paymentTargetCheque'
			] ) ;

		return \View::make ( 'web.system.settings.paymentTargetAccounts' , $data ) ;
	}

	public function updatePaymentTargetAccounts ()
	{
		try
		{
			$paymentTargetCash	 = \InputButler::get ( 'payment_target_cash' ) ;
			$paymentTargetCheque = \InputButler::get ( 'payment_target_cheque' ) ;

			\SystemSettingButler::setValue ( 'payment_target_cash' , $paymentTargetCash ) ;
			\SystemSettingButler::setValue ( 'payment_target_cheque' , $paymentTargetCheque ) ;

			\MessageButler::setSuccess ( 'Payment targets were saved successfully.' ) ;
			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function showImbalanceStock ()
	{
		$stocksForHtmlSelect = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

		$imbalanceStockId = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;

		$data = compact ( [
			'stocksForHtmlSelect' ,
			'imbalanceStockId'
			] ) ;

		return \View::make ( 'web.system.settings.imbalanceStock' , $data ) ;
	}

	public function updateImbalanceStock ()
	{
		try
		{
			$imbalanceStockId = \InputButler::get ( 'imbalance_stock' ) ;

			\SystemSettingButler::setValue ( 'imbalance_stock' , $imbalanceStockId ) ;

			\MessageButler::setSuccess ( 'Imbalance Stock was updated successfully.' ) ;

			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function showMainStock ()
	{
		$stocksForHtmlSelect = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

		$mainStockId = \SystemSettingButler::getValue ( 'main_stock' ) ;

		$data = compact ( [
			'stocksForHtmlSelect' ,
			'mainStockId'
			] ) ;

		return \View::make ( 'web.system.settings.mainStock' , $data ) ;
	}

	public function updateMainStock ()
	{
		try
		{
			$mainStockId = \InputButler::get ( 'main_stock' ) ;

			\SystemSettingButler::setValue ( 'main_stock' , $mainStockId ) ;

			\MessageButler::setSuccess ( 'Main Stock was updated successfully.' ) ;

			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function showFinanceAccounts ()
	{
		$inHouseAccounts = \Models\FinanceAccount::where ( 'is_in_house' , '=' , TRUE )
			-> getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'Select' ] ) ;

		$incomeAccount	 = \SystemSettingButler::getValue ( 'income_account' ) ;
		$expenseAccount	 = \SystemSettingButler::getValue ( 'expense_account' ) ;

		$data = compact ( [
			'inHouseAccounts' ,
			'incomeAccount' ,
			'expenseAccount' ,
			] ) ;

		return \View::make ( 'web.system.settings.financeAccounts' , $data ) ;
	}

	public function updateFinanceAccounts ()
	{
		try
		{
			$incomeAccount	 = \InputButler::get ( 'income_account' ) ;
			$expenseAccount	 = \InputButler::get ( 'expense_account' ) ;

			\SystemSettingButler::setValue ( 'income_account' , $incomeAccount ) ;
			\SystemSettingButler::setValue ( 'expense_account' , $expenseAccount ) ;

			\MessageButler::setSuccess ( 'Finance Accounts were updated successfully.' ) ;

			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function selectOrganizationName ()
	{
		$currentOrganizationName = \SystemSettingButler::getValue ( 'organization_name' ) ;
		$organizationName		 = NULL ;
		$data					 = compact ( [
			'currentOrganizationName' ,
			'organizationName'
			] ) ;

		return \View::make ( 'web.system.settings.organizationName' , $data ) ;
	}

	public function updateOrganizationName ()
	{
		try
		{
			$organizationName = \InputButler::get ( 'organization_name' ) ;
			\SystemSettingButler::setValue ( 'organization_name' , $organizationName ) ;

			\MessageButler::setSuccess ( 'Organization name was saved successfully.' ) ;
			return \Redirect::back () ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function addUser ()
	{
		return \View::make ( 'web.system.addUser.add' ) ;
	}

	public function saveUser ()
	{
		try
		{
			$this -> validateAddUser () ;

			$user = new \User() ;

			$user -> username	 = \InputButler::get ( 'username' ) ;
			$user -> email		 = \InputButler::get ( 'email' ) ;
			$user -> password	 = \Hash::make ( \InputButler::get ( 'password' ) ) ;
			$user -> first_name	 = \InputButler::get ( 'first_name' ) ;
			$user -> last_name	 = \InputButler::get ( 'last_name' ) ;

			$user -> save () ;

			\MessageButler::setSuccess ( "New User was added successfully" ) ;
			return \View::make ( 'web.system.addUser.add' ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	public function validateAddUser ()
	{
		$data = [
			'username'				 => \InputButler::get ( 'username' ) ,
			'email'					 => \InputButler::get ( 'email' ) ,
			'password'				 => \InputButler::get ( 'password' ) ,
			'password_confirmation'	 => \InputButler::get ( 'password_confirmation' ) ,
			'first_name'			 => \InputButler::get ( 'first_name' ) ,
			'last_name'				 => \InputButler::get ( 'last_name' ) ,
			] ;

		$rules = [
			'username'				 => [
				'required' ,
				'unique:users,username' ] ,
			'email'					 => [
				'required' ,
				'email' ,
				'min:5' ,
				'unique:users,email'
			] ,
			'password'				 => [
				'required' ,
				'min:5' ,
				'confirmed'
			] ,
			'password_confirmation'	 => [
				'required' ,
				'min:5'
			] ,
			'first_name'			 => [
				'required'
			] ,
			'last_name'				 => [
				'required'
			] ,
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
