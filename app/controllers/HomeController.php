<?php

class HomeController extends BaseController
{

	public function showHome ()
	{
		$data = $this -> updateDailyWorkFlow () ;
		return \View::make ( 'web.home' , $data ) ;
	}

	public function refreshHome ()
	{
		$theSubmitedForm = \Input::get ( 'submitedForm' ) ;
		if ( $theSubmitedForm == 'dailyWorkFlow' )
		{
			$data = $this -> updateDailyWorkFlow () ;
			return \View::make ( 'web.home' , $data ) ;
		}
	}

	private function updateDailyWorkFlow ()
	{
		if ( \Input::get ( 'the_date' ) !== NULL )
		{
			$theDate = DateTimeHelper::convertTextToFormattedDateTime ( \Input::get ( 'the_date' ) , 'Y-m-d' ) ;
		} else
		{
			$theDate = date ( "Y-m-d" ) ;
		}


		if ( ! empty ( $theDate ) )
		{
			$data						 = [ ] ;
			$buyingInvoices				 = Models\BuyingInvoice::whereBetween ( 'date_time' , [$theDate . ' 00:00:00' , $theDate . ' 23:59:59' ] ) -> count () ;
			$data[ 'buyingInvoices' ]	 = $buyingInvoices ;
			$data[ 'today' ]			 = $theDate ;


			$transfers = Models\Transfer::with ( 'fromStock.incharge' , 'toStock.incharge' )
			-> where ( 'date_time' , '=' , $theDate )
			-> get () ;

			$data[ 'transferDetails' ] = $transfers ;


			$repIds = Models\SellingInvoice::where ( 'date_time' , '=' , $theDate )
			-> distinct ()
			-> lists ( 'rep_id' ) ;
			if ( $repIds != NULL )
			{
				$reps			 = User::with ( 'sellingInvoices' ) -> whereIn ( 'id' , $repIds ) -> get () ;
				$data[ 'reps' ]	 = $reps ;
			}
			return $data ;
		}
	}

}
