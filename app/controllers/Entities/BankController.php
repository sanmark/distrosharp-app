<?php

namespace Controllers\Entities ;

class BankController extends \Controller
{

	public function home ()
	{
		$data = [ ] ;

		$banks = \Models\Bank::all () ;

		$data[ 'banks' ] = $banks ;

		return \View::make ( 'web.entities.banks.home' , $data ) ;
	}

	public function add ()
	{
		return \View::make ( 'web.entities.banks.add' ) ;
	}

	public function save ()
	{
		try
		{
			$bank				 = new \Models\Bank() ;
			$bank -> name		 = \Input::get ( 'name' ) ;
			$bank -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;

			$bank -> save () ;
		} catch ( \InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
