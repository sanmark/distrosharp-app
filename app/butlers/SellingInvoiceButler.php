<?php

class SellingInvoiceButler
{

	public static function getAllRepsForHtmlSelect ( array $firstElements = [NULL => 'Any' ] )
	{
		$repIds = \Models\Stock::distinct ()
			-> lists ( 'incharge_id' ) ;

		$reps = User::getArrayForHtmlSelectByIds ( 'id' , 'username' , $repIds , $firstElements ) ;

		return $reps ;
	}

	public static function getNextId ()
	{
		$lastSellingInvoice = Models\SellingInvoice::orderBy ( 'id' , 'desc' ) -> first () ;

		if ( is_null ( $lastSellingInvoice ) )
		{
			return 1 ;
		}

		$lastId	 = $lastSellingInvoice -> id ;
		$nextId	 = $lastId + 1 ;

		return $nextId ;
	}

	public static function profitAndLossFilter ( $filterValues )
	{
		$requestObject = new Models\SellingInvoice() ;

		if ( count ( $filterValues ) > 0 )
		{
			$date_from	 = $filterValues[ 'from_date' ] ;
			$date_to	 = $filterValues[ 'to_date' ] ;

			if ( strlen ( $date_from ) > 0 && strlen ( $date_to ) > 0 )
			{
				$date_from_start = $date_from . " 00:00:00" ;
				$date_to_end	 = $date_to . " 23:59:59" ;

				$requestObject = $requestObject -> whereBetween ( 'date_time' , array ( $date_from_start , $date_to_end ) ) ;
			} elseif ( strlen ( $date_from ) > 0 )
			{
				$date_from_start = $date_from . " 00:00:00" ;
				$date_from_end	 = $date_from . " 23:59:59" ;

				$requestObject = $requestObject -> whereBetween ( 'date_time' , array ( $date_from_start , $date_from_end ) ) ;
			}
		}

		return $requestObject -> get () ;
	}

	public static function getFirstSellingInvoiceDate ()
	{
		$firstDateTime = self::getFirstSellingInvoiceDateTime () ;

		$firstDate = DateTimeHelper::convertTextToFormattedDateTime ( $firstDateTime , 'Y-m-d' ) ;

		return $firstDate ;
	}

	public static function getFirstSellingInvoiceDateTime ()
	{
		return Models\SellingInvoice::min ( 'date_time' ) ;
	}

	public static function getLastSellingInvoiceDate ()
	{
		$lastDateTime = self::getLastSellingInvoiceDateTime () ;

		$lastDate = DateTimeHelper::convertTextToFormattedDateTime ( $lastDateTime , 'Y-m-d' ) ;

		return $lastDate ;
	}

	public static function getLastSellingInvoiceDateTime ()
	{
		return Models\SellingInvoice::max ( 'date_time' ) ;
	}

	public static function getSellingInvoicesForDates ( $filterValues )
	{
		$sellingInvoices = \Models\SellingInvoice::filter ( $filterValues )
			-> sortBy ( 'date_time' ) ;

		$sellingInvoicesForDates = [ ] ;

		foreach ( $sellingInvoices as $sellingInvoice )
		{
			$sellingInvoicesForDates[ DateTimeHelper::convertTextToFormattedDateTime ( $sellingInvoice -> date_time , 'Y-m-d' ) ][] = $sellingInvoice ;
		}

		return $sellingInvoicesForDates ;
	}

	public static function getLateCreditInvoices ( $id )
	{
		$result = array () ;

		$requestObject = new \Models\SellingInvoice() ;

		$requestObject = $requestObject -> where ( 'id' , '=' , $id ) ;

		$requestObject = $requestObject -> with ( 'financeTransfersPaidByThis' ) -> get () ;

		if ( count ( $requestObject ) )
		{
			foreach ( $requestObject[ 0 ] -> financeTransfersPaidByThis as $finance_transfer )
			{ 
				if ( $finance_transfer -> pivot -> selling_invoice_id != $id )
				{
					array_push ( $result , $finance_transfer -> pivot -> selling_invoice_id ) ;
				}
			}
		}

		return array_unique ( $result ) ;
	}

	public static function getLateCreditPayments ( $id )
	{
		$amount_cash	 = 0 ;
		$amount_cheque	 = 0 ;

		$requestObject = new \Models\SellingInvoice() ;

		$requestObject = $requestObject -> where ( 'id' , '=' , $id ) ;

		$requestObject = $requestObject -> with ( 'financeTransfersPaidByThis' ) -> get () ;

		if ( count ( $requestObject ) )
		{

			foreach ( $requestObject[ 0 ] -> financeTransfersPaidByThis as $finance_transfer )
			{
				if ( $finance_transfer -> pivot -> selling_invoice_id != $id )
				{ 
					if ( $finance_transfer -> isCash () )
					{
						$amount_cash = $amount_cash + $finance_transfer -> amount ;
					}

					if ( $finance_transfer -> isCheque () )
					{
						$amount_cheque = $amount_cheque + $finance_transfer -> amount ;
					}
				}
			}
		}

		return array ( "amount_cash" => $amount_cash , "amount_cheque" => $amount_cheque ) ;
	}

}
