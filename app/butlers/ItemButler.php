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

	public static function filterReturnItem ( $filterValues )
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

			$requestObject = $requestObject -> where ( 'good_return_quantity' , '>' , 0 ) ;

			if ( strlen ( $item ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'item_id' , '=' , $item ) ;
			}

			$requestObject = $requestObject -> orWhere ( 'company_return_quantity' , '>' , 0 ) ;

			if ( strlen ( $item ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'item_id' , '=' , $item ) ;
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
		}

		return $itemDetails ;
	}

	public static function getCostForItems ( $items , $quantity , $column )
	{
		$itemCost = [ ] ;
		if ( count ( $quantity ) == 0 )
		{
			return $itemCost ;
		}
		foreach ( $items as $item )
		{
			$itemPrice = \Models\Item::find ( $item -> item_id ) ;
			if ( ! isset ( $quantity[ $item -> item_id ] ) )
			{
				$itemCost[ $item -> item_id ] = 'None' ;
			} else
			{
				$itemCost[ $item -> item_id ] = number_format ( ( float ) $quantity[ $item -> item_id ] * $itemPrice -> $column , 2 ) ;
			}
		}
		return $itemCost ;
	}

}
