<?php

namespace Controllers\Reports ;

class TimelyStockReportController extends \Controller
{

	public function home ()
	{
		$stock		 = \SystemSettingButler::getValue ( 'main_stock' ) ;
		if($stock==''&&$stock==NULL)
		{
			\MessageButler::setError('Please set "Main Stock"');
			return \Redirect::action('system.settings.mainStock');
		}
		$fromDate	 = date ( 'Y-m-d H:i:s' , strtotime ( '-7 day' , strtotime ( 'today 00:00:00' ) ) ) ;
		$fromDate	 = \DateTimeHelper::dateTimeRefill ( $fromDate ) ;
		$toDate		 = date ( 'Y-m-d H:i:s' , strtotime ( 'today 23:59:59' ) ) ;
		$toDate		 = \DateTimeHelper::dateTimeRefill ( $toDate ) ;
		$items		 = [ ] ;
		$data		 = compact ( [
			'fromDate' ,
			'toDate' ,
			'items' ,
			] ) ;
		return \View::make ( 'web.reports.timelyStockReport.home' , $data ) ;
	}

	public function filter ()
	{
		$stock				 = \SystemSettingButler::getValue ( 'main_stock' ) ;
		$fromDate			 = \Input::get ( 'fromDate' ) ;
		$toDate				 = \Input::get ( 'toDate' ) ;
		$items				 = \Models\StockDetail::where ( 'stock_id' , '=' , $stock ) -> get () ;
		$item				 = new \Models\Item() ;
		$stockConfirmation	 = new \Models\StockConfirmation() ;
		$sellingInvoice		 = new \Models\SellingItem() ;
		$buyingInvoice		 = new \Models\BuyingItem() ;

		$stocksList = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , ['' => 'Select Stock' ] ) ;

		//add before from date max
		$lastConfirmDate = $stockConfirmation -> where ( 'stock_id' , '=' , $stock )
			-> where ( 'date_time' , '<' , $toDate )
			-> max ( 'date_time' ) ;

		if ( $lastConfirmDate == NULL )
		{
			\MessageButler::setInfo ( "Main Stock never confirmed yet.Please Confirm at least one time " . \HTML::link ( \URL::action ( 'stocks.view' , [$stock ] ) , 'Here' ) ) ;
			return \Redirect::action ( 'reports.timelyStockReport' )
					-> withInput () ;
		}

		$soldQuantities = $sellingInvoice -> getSoldQuantities ( $fromDate , $toDate ) ;

		$purchasedQuantities = $buyingInvoice -> getPurchasedQuantities ( $fromDate , $toDate ) ;

		$goodReturnQuantities = $sellingInvoice -> getGoodReturnQuantities ( $fromDate , $toDate ) ;

		$whenConfirmQuanities = $stockConfirmation -> getQuantitiesWhenConfirm ( $lastConfirmDate ) ;

		$whenConfirmCost = $item -> getCost ( $items , $whenConfirmQuanities , 'current_buying_price' ) ;

		$openingQuantities = $this -> getOpeningQuantities ( $fromDate , $lastConfirmDate , $items ) ;

		$endingQuantities = $this -> getEndingQuantities ( $fromDate , $toDate , $lastConfirmDate , $items ) ;

		$endingCost = $item -> getCost ( $items , $endingQuantities , 'current_buying_price' ) ;

		$endingTotal = $this -> getTotal ( $endingCost ) ;

		$openingCost = $item -> getCost ( $items , $openingQuantities , 'current_buying_price' ) ;

		$openingTotal = $this -> getTotal ( $openingCost ) ;

		$soldCost = $item -> getCost ( $items , $soldQuantities , 'current_selling_price' ) ;

		$soldTotal = $this -> getTotal ( $soldCost ) ;

		$purchasedCost = $item -> getCost ( $items , $purchasedQuantities , 'current_buying_price' ) ;

		$purchasedTotal = $this -> getTotal ( $purchasedCost ) ;

		$goodReturnCost = $item -> getCost ( $items , $goodReturnQuantities , 'current_buying_price' ) ;

		$goodReturnTotal = $this -> getTotal ( $goodReturnCost ) ;

		$data = compact ( [
			'stocksList' ,
			'stock' ,
			'fromDate' ,
			'toDate' ,
			'minFromDate' ,
			'items' ,
			'openingQuantities' ,
			'openingCost' ,
			'soldQuantities' ,
			'soldCost' ,
			'purchasedQuantities' ,
			'purchasedCost' ,
			'openingTotal' ,
			'soldTotal' ,
			'purchasedTotal' ,
			'goodReturnQuantities' ,
			'goodReturnCost' ,
			'goodReturnTotal' ,
			'endingQuantities' ,
			'endingCost' ,
			'endingTotal'
			] ) ;
		return \View::make ( 'web.reports.timelyStockReport.home' , $data ) ;
	}

	public function getOpeningQuantities ( $fromDate , $lastConfirmDate , $items )
	{

		$sellingInvoice		 = new \Models\SellingItem() ;
		$buyingInvoice		 = new \Models\BuyingItem() ;
		$stockConfirmation	 = new \Models\StockConfirmation() ;

		$soldQuantities = $sellingInvoice -> getSoldQuantities ( $lastConfirmDate , $fromDate ) ;

		$purchasedQuantities = $buyingInvoice -> getPurchasedQuantities ( $lastConfirmDate , $fromDate ) ;

		$goodReturnQuantities = $sellingInvoice -> getGoodReturnQuantities ( $lastConfirmDate , $fromDate ) ;

		$whenConfirmQuanities = $stockConfirmation -> getQuantitiesWhenConfirm ( $lastConfirmDate ) ;

		$openingQuantity = $this -> getQuantityArray ( $soldQuantities , $purchasedQuantities , $goodReturnQuantities , $whenConfirmQuanities , $items ) ;

		return $openingQuantity ;
	}

	public function getEndingQuantities ( $fromDate , $toDate , $lastConfirmDate , $items )
	{
		$sellingInvoice		 = new \Models\SellingItem() ;
		$buyingInvoice		 = new \Models\BuyingItem() ;
		$stockConfirmation	 = new \Models\StockConfirmation() ;

		$soldQuantities = $sellingInvoice -> getSoldQuantities ( $fromDate , $toDate ) ;

		$purchasedQuantities = $buyingInvoice -> getPurchasedQuantities ( $fromDate , $toDate ) ;

		$goodReturnQuantities = $sellingInvoice -> getGoodReturnQuantities ( $fromDate , $toDate ) ;

		$whenConfirmQuanities = $stockConfirmation -> getQuantitiesWhenConfirm ( $lastConfirmDate ) ;

		$endingQuantity = $this -> getQuantityArray ( $soldQuantities , $purchasedQuantities , $goodReturnQuantities , $whenConfirmQuanities , $items ) ;

		return $endingQuantity ;
	}

	public function getTotal ( $cost )
	{
		$total = 0 ;
		foreach ( $cost as $amount )
		{
			$total = $total + ( float ) str_replace ( ',' , '' , $amount ) ;
		}
		$total = number_format ( ( float ) $total , 2 ) ;
		return $total ;
	}

	public function getQuantityArray ( $soldQuantities , $purchasedQuantities , $goodReturnQuantities , $whenConfirmQuanities , $items )
	{
		$quantityArray = [ ] ;
		foreach ( $items as $item )
		{
			if ( ! isset ( $whenConfirmQuanities[ $item -> item_id ] ) )
			{
				$whenConfirmQuanities[ $item -> item_id ] = 0 ;
			}
			if ( ! isset ( $purchasedQuantities[ $item -> item_id ] ) )
			{
				$purchasedQuantities[ $item -> item_id ] = 0 ;
			}
			if ( ! isset ( $goodReturnQuantities[ $item -> item_id ] ) )
			{
				$goodReturnQuantities[ $item -> item_id ] = 0 ;
			}
			if ( ! isset ( $soldQuantities[ $item -> item_id ] ) )
			{
				$soldQuantities[ $item -> item_id ] = 0 ;
			}
			$quantityArray[ $item -> item_id ] = $whenConfirmQuanities[ $item -> item_id ] + $purchasedQuantities[ $item -> item_id ] + $goodReturnQuantities[ $item -> item_id ] - $soldQuantities[ $item -> item_id ] ;
		}

		return $quantityArray ;
	}

}
