<?php

namespace Controllers\Reports ;

class ProfitAndLossReportController extends \Controller
{

	public function home ()
	{

		$date_from	 = NULL ;
		$date_to	 = NULL ;
		$viwe_data	 = FALSE ;

		$data = compact ( [
			'date_from' ,
			'date_to' ,
			'viwe_data'
			] ) ;

		return \View::make ( 'web.reports.profitAndLoss.view' , $data ) ;
	}

	public function filter ()
	{
		try
		{

			$date_from	 = \InputButler::get ( 'from_date' ) ;
			$date_to	 = \InputButler::get ( 'to_date' ) ;

			$filterValues = \Input::all () ;

			$this -> validateFilterValues ( $filterValues ) ;

			$sellingInvoices = \SellingInvoiceButler::profitAndLossFilter ( $filterValues ) ;

			$sales			 = 0 ;
			$discounts		 = 0 ;
			$costOfSoldGoods = 0 ;

			foreach ( $sellingInvoices as $sellingInvoice )
			{
				$discounts += $sellingInvoice -> discount ;
				$sales += $sellingInvoice -> getInvoiceTotal () + $discounts ;
				$costOfSoldGoods += $sellingInvoice -> getCostofSoldGoods () ;
			}
			$netSales = $sales - $discounts ;

			$viwe_data = TRUE ;

			$data = compact ( [
				'date_from' ,
				'date_to' ,
				'sales' ,
				'discounts' ,
				'netSales' ,
				'costOfSoldGoods' ,
				'viwe_data'
				] ) ;

			return \View::make ( 'web.reports.profitAndLoss.view' , $data ) ;
		} catch ( \Exceptions\InvalidInputException $ex )
		{
			return \Redirect::back ()
					-> withErrors ( $ex -> validator )
					-> withInput () ;
		}
	}

	private function validateFilterValues ( $data )
	{
		$rules = [
			'from_date'	 => [
				'required' ,
				'date'
			] ,
			'to_date'	 => [
				'required' ,
				'date'
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
