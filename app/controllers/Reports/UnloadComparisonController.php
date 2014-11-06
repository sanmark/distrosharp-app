<?php

namespace Controllers\Reports ;

class UnloadComparisonController extends \Controller
{

	public function show ()
	{
		$filterValues	 = \Input::all () ;
		$transferRows	 = \Models\Transfer::unloadReportFilter ( $filterValues ) ;

		$fromDate	 = \InputButler::get ( 'from_date_time' ) ;
		$toDate		 = \InputButler::get ( 'to_date_time' ) ;
		$fromStock	 = \InputButler::get ( 'from_stock' ) ;
		$toStock	 = \InputButler::get ( 'to_stock' ) ;

		if ( is_null ( $fromDate ) )
		{
			$fromDate = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' , strtotime ( '-7 days midnight' ) ) ) ;
		}

		if ( is_null ( $toDate ) )
		{
			$toDate = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
		}

		$vehicleIds		 = \Models\Stock::where ( 'stock_type_id' , '=' , 2 ) -> lists ( 'id' ) ;
		$fromVehicleIds	 = \Models\Transfer::distinct ( 'from_stock_id' ) -> whereIn ( 'from_stock_id' , $vehicleIds )
			-> lists ( 'from_stock_id' ) ;

		if ( count ( $fromVehicleIds ) == 0 )
		{
			$fromVehicleSelect = ['' => 'Select Stock' ] ;
		} else
		{
			$fromVehicleIds = \Models\Transfer::distinct ( 'from_stock_id' ) -> whereIn ( 'from_stock_id' , $vehicleIds )
				-> lists ( 'from_stock_id' ) ;

			$fromVehicleSelect = ['' => 'Select Stock' ] + \Models\Stock::whereIn ( 'id' , $fromVehicleIds )
					-> getArrayForHtmlSelect ( 'id' , 'name' ) ;
		}

		$toVehicleIds = \Models\Transfer::distinct ( 'to_stock_id' )
			-> whereNotIn ( 'to_stock_id' , $vehicleIds )
			-> lists ( 'to_stock_id' ) ;

		if ( count ( $toVehicleIds ) == 0 )
		{
			$toVehicleSelect = ['' => 'Select Stock' ] ;
		} else
		{
			$toVehicleIds = \Models\Transfer::distinct ( 'to_stock_id' )
				-> whereNotIn ( 'to_stock_id' , $vehicleIds )
				-> lists ( 'to_stock_id' ) ;

			$toVehicleSelect = ['' => 'Select Stock' ] + \Models\Stock::whereIn ( 'id' , $toVehicleIds )
					-> getArrayForHtmlSelect ( 'id' , 'name' ) ;
		}

		$data = compact ( [
			'transferRows' ,
			'fromVehicleSelect' ,
			'toVehicleSelect' ,
			'fromDate' ,
			'toDate' ,
			'fromStock' ,
			'toStock'
			] ) ;
		return \View::make ( 'web.reports.unloadComparison.home' , $data ) ;
	}

	public function view ( $id )
	{
		$basicDetails	 = \Models\Transfer::findOrFail ( $id ) ;
		$transferData	 = \Models\TransferDetail::where ( 'transfer_id' , '=' , $id )
			-> get () ;
		$preUnloadIds	 = \Models\Transfer::where ( 'id' , '<' , $basicDetails -> id )
			-> where ( 'from_stock_id' , '=' , $basicDetails -> from_stock_id )
			-> where ( 'to_stock_id' , '=' , $basicDetails -> to_stock_id )
			-> lists ( 'id' ) ;


		if ( count ( $preUnloadIds ) == 0 )
		{
			$loadIdsBetweenUnload = \Models\Transfer::where ( 'id' , '<' , $basicDetails -> id )
				-> where ( 'from_stock_id' , '=' , $basicDetails -> to_stock_id )
				-> where ( 'to_stock_id' , '=' , $basicDetails -> from_stock_id )
				-> lists ( 'id' ) ;

			$unloadDate = $basicDetails -> date_time ;

			$sellingIds = \Models\SellingInvoice::where ( 'stock_id' , '=' , $basicDetails -> from_stock_id )
				-> where ( 'date_time' , '<' , $unloadDate )
				-> lists ( 'id' ) ;
		} else
		{
			$loadIdsBetweenUnload = \Models\Transfer::where ( 'id' , '>' , max ( $preUnloadIds ) )
				-> where ( 'id' , '<' , $basicDetails -> id )
				-> where ( 'from_stock_id' , '=' , $basicDetails -> to_stock_id )
				-> where ( 'to_stock_id' , '=' , $basicDetails -> from_stock_id )
				-> lists ( 'id' ) ;

			$unloadDate = \Models\Transfer::where ( 'id' , '=' , max ( $preUnloadIds ) )
				-> where ( 'from_stock_id' , '=' , $basicDetails -> from_stock_id )
				-> where ( 'to_stock_id' , '=' , $basicDetails -> to_stock_id )
				-> lists ( 'date_time' ) ;

			$sellingIds = \Models\SellingInvoice::where ( 'stock_id' , '=' , $basicDetails -> from_stock_id )
				-> where ( 'date_time' , '>' , $unloadDate )
				-> where ( 'date_time' , '<' , $basicDetails -> date_time )
				-> lists ( 'id' ) ;
		}

		if ( count ( $loadIdsBetweenUnload ) == 0 )
		{
			$loadData			 = \Models\TransferDetail::where ( 'transfer_id' , $basicDetails -> id )
				-> get () ;
			$loadingItemQuantity = [ ] ;
			foreach ( $loadData as $row )
			{
				$loadingItemQuantity[ $row -> item_id ] = 0 ;
			}
		} else
		{
			$loadData			 = \Models\TransferDetail::whereIn ( 'transfer_id' , $loadIdsBetweenUnload )
				-> get () ;
			$loadingItemQuantity = [ ] ;

			foreach ( $loadData as $row )
			{
				if ( ! isset ( $loadingItemQuantity[ $row -> item_id ] ) )
				{
					$loadingItemQuantity[ $row -> item_id ] = 0 ;
				}
				$loadingItemQuantity[ $row -> item_id ] += ($row -> quantity) ;
			}
		}

		if ( count ( $sellingIds ) == 0 )
		{
			$loadItemSellingData = \Models\TransferDetail::where ( 'transfer_id' , '=' , $id )
				-> get () ;

			$sellingItemQuantity = [ ] ;

			foreach ( $loadItemSellingData as $row )
			{
				$sellingItemQuantity[ $row -> item_id ] = 0 ;
			}
		} else
		{
			$loadItemSellingData = \Models\SellingItem::whereIn ( 'selling_invoice_id' , $sellingIds )
				-> get () ;

			$sellingItemQuantity = [ ] ;

			foreach ( $loadItemSellingData as $row )
			{
				if ( ! isset ( $sellingItemQuantity[ $row -> item_id ] ) )
				{
					$sellingItemQuantity[ $row -> item_id ] = 0 ;
				}
				$sellingItemQuantity[ $row -> item_id ] += ($row -> paid_quantity) ;
			}
		}
		$sysLoadDiff = [ ] ;
		foreach ( $transferData as $sysLoad )
		{
			if ( array_key_exists ( $sysLoad -> item_id , $sellingItemQuantity ) && array_key_exists ( $sysLoad -> item_id , $loadingItemQuantity ) )
			{

				$sysLoadDiff[ $sysLoad -> item_id ] = $loadingItemQuantity[ $sysLoad -> item_id ] - $sellingItemQuantity[ $sysLoad -> item_id ] ;
			} else
			{
				if ( empty ( $loadingItemQuantity[ $sysLoad -> item_id ] ) )
				{
					$loadingItem = 0 ;
				} else
				{
					$loadingItem = $loadingItemQuantity[ $sysLoad -> item_id ] ;
				}
				$sysLoadDiff[ $sysLoad -> item_id ] = $loadingItem ;
			}
		}

		$difictQuantity = [ ] ;
		foreach ( $transferData as $difictRow )
		{
			$difictQuantity[ $difictRow -> item_id ] = $sysLoadDiff[ $difictRow -> item_id ] - $difictRow -> quantity ;
		}

		$data = compact ( [
			'transferData' ,
			'basicDetails' ,
			'loadingItemQuantity' ,
			'sellingItemQuantity' ,
			'sysLoadDiff' ,
			'difictQuantity'
			] ) ;
		return \View::make ( 'web.reports.unloadComparison.view' , $data ) ;
	}

}
