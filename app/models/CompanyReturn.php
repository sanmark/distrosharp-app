<?php

namespace Models ;

class CompanyReturn extends BaseEntity implements \Interfaces\iEntity
{

	public function stock ()
	{
		return $this -> belongsTo ( 'Models\Stock' , 'from_stock_id' ) ;
	}

	public function vendor ()
	{
		return $this -> belongsTo ( 'Models\Vendor' , 'vendor_id' ) ;
	}

	public function companyReturnfilter ( $filterValues )
	{
		$requestObject = new \Models\CompanyReturn() ;

		if ( count ( $filterValues ) == 0 )
		{
			return $requestObject -> whereBetween ( 'date_time' , [date ( 'Y-m-d H:i:s' , strtotime ( '-7 days midnight' ) ) , date ( 'Y-m-d H:i:s' ) ] )
					-> get () ;
		}
		if ( count ( $filterValues ) > 0 )
		{
			$vendorId	 = $filterValues[ 'vendor_id' ] ;
			$fromStockId = $filterValues[ 'from_stock_id' ] ;
			$fromDate	 = $filterValues[ 'from_date' ] ;
			$toDate		 = $filterValues[ 'to_date' ] ;
			$minDate	 = $requestObject -> min ( 'date_time' ) ;
			$maxDate	 = $requestObject -> max ( 'date_time' ) ;

			if ( strlen ( $fromDate ) > 0 && strlen ( $toDate ) > 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$fromDate , $toDate ] ) ;
			}
			if ( strlen ( $fromDate ) > 0 && strlen ( $toDate ) == 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$fromDate , $maxDate ] ) ;
			}
			if ( strlen ( $fromDate ) == 0 && strlen ( $toDate ) > 0 )
			{
				$requestObject = $requestObject -> whereBetween ( 'date_time' , [$minDate , $toDate ] ) ;
			}
			if ( strlen ( $vendorId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'vendor_id' , '=' , $vendorId ) ;
			}
			if ( strlen ( $fromStockId ) > 0 )
			{
				$requestObject = $requestObject -> where ( 'from_stock_id' , '=' , $fromStockId ) ;
			}
			return $requestObject -> get () ;
		}
	}

}
