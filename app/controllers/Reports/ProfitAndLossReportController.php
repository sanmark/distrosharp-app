<?php

namespace Controllers\Reports ;

class ProfitAndLossReportController extends \Controller
{

	public function home ()
	{
		$routes			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Routes' ] ) ;
		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes )
			-> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;


		$date_from	 = NULL ;
		$date_to	 = NULL ;
		$viwe_data	 = FALSE ;
		$route_id	 = NULL ;
		$rep_id		 = NULL ;

		$data = compact ( [
			'date_from' ,
			'date_to' ,
			'viwe_data' ,
			'routes' ,
			'reps' ,
			'route_id' ,
			'rep_id'
			] ) ;

		return \View::make ( 'web.reports.profitAndLoss.view' , $data ) ;
	}

	public function filter ()
	{
		$routes			 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Routes' ] ) ;
		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes )
			-> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;

		$route_id	 = \InputButler::get ( 'route_id' ) ;
		$rep_id		 = \InputButler::get ( 'rep_id' ) ;

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
				$sales += $sellingInvoice -> getInvoiceTotal () ;
				$costOfSoldGoods += $sellingInvoice -> getCostofSoldGoods () ;
			}
			
			$netSales = $sales - $discounts ;

			$viwe_data = TRUE ;


			$discountPercentage			 = 0 ;
			$netSalesPercentage			 = 0 ;
			$costOfSoldGoodsPercentage	 = 0 ;
			$grossProfit				 = 0 ;

			if ( $discounts > 0 && $sales > 0 )
			{
				$discountPercentage = ($discounts / $sales) * 100 ;
			}

			if ( $netSales > 0 && $sales > 0 )
			{
				$netSalesPercentage = ($netSales / $sales) * 100 ;
			}

			if ( $costOfSoldGoods > 0 && $sales > 0 )
			{
				$costOfSoldGoodsPercentage = ($costOfSoldGoods / $sales) * 100 ;
			}

			$grossProfitTot = $netSales - $costOfSoldGoods ;

			if ( $grossProfitTot != 0 && $sales != 0 )
			{
				$grossProfit = ($grossProfitTot / $sales) * 100 ;
			}

			
			$data = compact ( [
				'date_from' ,
				'date_to' ,
				'sales' ,
				'discounts' ,
				'netSales' ,
				'costOfSoldGoods' ,
				'viwe_data' ,
				'routes' ,
				'reps' ,
				'route_id' ,
				'rep_id' ,
				'discountPercentage' ,
				'netSalesPercentage' ,
				'costOfSoldGoodsPercentage' ,
				'grossProfit'
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
