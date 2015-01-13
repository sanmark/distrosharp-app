<?php

namespace Controllers\Reports ;

class SalesSummaryController extends \Controller
{

	public function all ()
	{

		$routes		 = \Models\Route::getArrayForHtmlSelect ( 'id' , 'name' , [NULL => 'All Routes' ] ) ;
		$customers	 = [NULL => 'Select Route First' ] ;

		$reps_v_routes	 = \Models\Route::lists ( 'rep_id' ) ;
		$reps			 = \User::whereIn ( 'id' , $reps_v_routes )
			-> getArrayForHtmlSelect ( 'id' , 'username' , [NULL => 'All Reps' ] ) ;


		$dateFrom = date ( 'Y-m-d' , mktime ( 0 , 0 , 0 , date ( 'm' ) , date ( 'd' ) - 3 , date ( 'Y' ) ) ) ;

		$dateTo = date ( 'Y-m-d' ) ;

		$data = compact ( [
			'routes' ,
			'customers' ,
			'reps' ,
			'dateFrom' ,
			'dateTo'
			] ) ;

		return \View::make ( 'web.reports.sales.home' , $data ) ;
	}

	public function createSalesSummaryReport ()
	{
		$filterValues	 = \Input::all () ;
		$resuls			 = \Models\SellingInvoice::filterForSalesSummary ( $filterValues ) ;

		$sellingInvoices = array () ;


		foreach ( $resuls as $key => $sellingInvoice )
		{
			$sellingInvoices [ $key ][ 'route' ]	 = $sellingInvoice -> customer -> route -> name ;
			$sellingInvoices [ $key ][ 'customer' ]	 = $sellingInvoice -> customer -> name ;

			$sellingInvoices [ $key ][ 'invoiceNumber' ] = $sellingInvoice -> id ;

			$sellingInvoices [ $key ][ 'discount' ] = $sellingInvoice -> discount ;

			$sellingInvoices [ $key ][ 'paymentValueByCash' ]	 = $sellingInvoice -> getPaymentValueByCash () ;
			
			$sellingInvoices [ $key ][ 'paymentValueByCheque' ]	 = $sellingInvoice -> getPaymentValueByCheque () ;
			
			$sellingInvoices	[$key]['lateCreditInvoices'] = $sellingInvoice->getLateCreditInvoices();		
			$sellingInvoices [ $key ][ 'lateCreditPayments' ]	 = $sellingInvoice -> getLateCreditPayments () ;
			$sellingInvoices [ $key ][ 'invoiceTotal' ]			 = $sellingInvoice -> getInvoiceTotal () ;
		}

		$data = compact ( [
			'sellingInvoices'
			] ) ;

		echo json_encode ( $data ) ;
	}

}
