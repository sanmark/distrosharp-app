<?php

class ItemButler
{

	public static function getMinimumAvailableItemCode ()
	{
		$requestObject			 = new \Models\Item() ;
		$allCodes				 = $requestObject -> lists ( 'code' ) ;
		$minimumAvailableNumber	 = \NumberHelper::getMinimumAvailableNumberFromArray ( $allCodes ) ;

		return $minimumAvailableNumber ;
	}

	public static function filterItemSalesDetails ( $filterValues )
	{
		$requestObject = new \Models\SellingItem() ;


		if ( count ( $filterValues ) > 0 )
		{
			$item		 = $filterValues[ 'item' ] ;
			$rep		 = $filterValues[ 'rep' ] ;
			$route		 = $filterValues[ 'route' ] ;
			$customer	 = $filterValues[ 'customer' ] ;
			$from_date	 = $filterValues[ 'from_date' ] ;
			$to_date	 = $filterValues[ 'to_date' ] ;

			if ( strlen ( $item ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'item_id' , '=' , $item ) ;
			}
		}

		$itemDetails = $requestObject -> get () ;


		if ( strlen ( $rep ) > 0 )
		{
			$new_result = $itemDetails -> filter ( function($collection)use($rep)
			{
				if ( $collection -> sellingInvoice -> rep -> id == $rep )
				{
					return true ;
				}
			} ) ;

			$itemDetails = $new_result ;
		}

		if ( strlen ( $route ) > 0 )
		{
			$new_result = $itemDetails -> filter ( function($collection)use($route)
			{
				if ( $collection -> sellingInvoice -> customer -> route -> id == $route )
				{
					return true ;
				}
			} ) ;

			$itemDetails = $new_result ;
		}

		if ( strlen ( $customer ) > 0 )
		{
			$new_result = $itemDetails -> filter ( function($collection)use($customer)
			{
				if ( $collection -> sellingInvoice -> customer -> id == $customer )
				{
					return true ;
				}
			} ) ;

			$itemDetails = $new_result ;
		}

		if ( strlen ( $from_date ) > 0 && strlen ( $to_date ) > 0 )
		{
			$from_time	 = $from_date . " 00:00:00" ;
			$to_time	 = $to_date . " 23:59:59" ;

			$new_result = $itemDetails -> filter ( function($collection)use($from_time , $to_time)
			{
				if ( strtotime ( $collection -> sellingInvoice -> date_time ) > strtotime ( $from_time ) && strtotime ( $collection -> sellingInvoice -> date_time ) < strtotime ( $to_time ) )
				{
					return true ;
				}
			} ) ;

			$itemDetails = $new_result ;
		}

		return $itemDetails ;
	}

	public static function getMinBuyingInvoiceOrder ()
	{
		$buying_invoice_id	 = 0 ;
		$requestObject		 = new Models\Item();

		$counter = 1 ;
		while ( $counter != 0 )
		{
			$result = $requestObject -> where ( 'buying_invoice_order' , '=' , $counter ) -> get () ;

			if ( count ( $result ) == 0 )
			{
				$buying_invoice_id	 = $counter ;
				$counter			 = 0 ;
			} else
			{
				$counter ++ ;
			}
		}
		return $buying_invoice_id ;
	}

	public static function getMinSellingInvoiceOrder ()
	{
		$selling_invoice_id	 = 0 ;
		$requestObject		 = new Models\Item();

		$counter = 1 ;
		while ( $counter != 0 )
		{
			$result = $requestObject -> where ( 'selling_invoice_order' , '=' , $counter ) -> get () ;

			if ( count ( $result ) == 0 )
			{
				$selling_invoice_id	 = $counter ;
				$counter			 = 0 ;
			} else
			{
				$counter ++ ;
			}
		}
		return $selling_invoice_id ;
	}

}
