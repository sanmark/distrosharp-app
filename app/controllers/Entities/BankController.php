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

	public function edit ( $id )
	{
		$data = [ ] ;

		$bank = \Models\Bank::findOrFail ( $id ) ;

		$data[ 'bank' ] = $bank ;

		return \View::make ( 'web.entities.banks.edit' , $data ) ;
	}

	public function update ( $id )
	{
		try
		{
			$bank				 = \Models\Bank::findOrFail ( $id ) ;
			
			$bank -> name		 = \Input::get('name' ) ;
			
			$bank -> is_active	 = \NullHelper::zeroIfNull ( \Input::get ( 'is_active' ) ) ;

			$bank -> update () ;

			return \Redirect::action( 'entities.banks.view' ) ;
		} catch ( \InvalidInputException $ex )
		{
			return \Redirect::back ()
			-> withErrors ( $ex -> validator )
			-> withInput () ;
		}
	}

}
