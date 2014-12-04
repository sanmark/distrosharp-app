<?php

namespace Controllers\Reports ;

class StockController extends \Controller
{

	public function show ()
	{

		$stockSelect	 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [0 => 'All' ] ) ;
		$stockId		 = NULL ;
		$stockDetails	 = $this -> filterStock ( $stockId ) ;
		$totals			 = $this -> getTotal ( $stockDetails ) ;
		$viewData		 = TRUE ;
		$confirmStock	 = \AbilityButler::checkAbilities ( ['confirm_stock' ] ) ;
		$data			 = compact ( [
			'stockSelect' ,
			'stockId' ,
			'stockDetails' ,
			'viewData' ,
			'totals' ,
			'confirmStock'
			] ) ;

		return \View::make ( 'web.reports.stock.home' , $data ) ;
	}

	public function confirmStock ()
	{
		$stockId = \Input::get ( 'stock_id' ) ;
		if ( $stockId == 0 )
		{
			\MessageButler::setError ( "Please select stock for confirm" ) ;
			return \Redirect::action ( 'reports.stocks' ) ;
		}
		$stockConfirm				 = new \Models\StockConfirmation() ;
		$stockConfirm -> stock_id	 = $stockId ;
		$stockConfirm -> date_time	 = \DateTimeHelper::dateTimeRefill ( date ( 'Y-m-d H:i:s' ) ) ;
		$stockConfirm -> save () ;

		$stockConfirmationId = $stockConfirm -> id ;

		$stockDetails = \Models\StockDetail::where ( 'stock_id' , '=' , $stockId ) -> get () ;

		foreach ( $stockDetails as $stockDetail )
		{
			$stockConfirmationDeatil							 = new \Models\StockConfirmationDetail() ;
			$stockConfirmationDeatil -> stock_confirmation_id	 = $stockConfirmationId ;
			$stockConfirmationDeatil -> item_id					 = $stockDetail -> item_id ;
			$stockConfirmationDeatil -> good_item_quantity		 = $stockDetail -> good_quantity ;
			$stockConfirmationDeatil -> return_item_quantity	 = $stockDetail -> return_quantity ;

			$stockConfirmationDeatil -> save () ;
		}
		\ActivityLogButler::add ( "Stock Details Confirme " . $stockId ) ;
		\MessageButler::setSuccess ( "Stock details confirmed successfully" ) ;
		return $this -> view () ;
	}

	public function view ()
	{
		$stockSelect	 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [0 => 'All' ] ) ;
		$stockId		 = \InputButler::get ( 'stock_id' ) ;
		$stockDetails	 = $this -> filterStock ( $stockId ) ;
		$totals			 = $this -> getTotal ( $stockDetails ) ;
		$confirmStock	 = \AbilityButler::checkAbilities ( ['confirm_stock' ] ) ;
		$viewData		 = TRUE ;
		$data			 = compact ( [
			'stockSelect' ,
			'stockId' ,
			'stockDetails' ,
			'viewData' ,
			'totals' ,
			'confirmStock'
			] ) ;
		return \View::make ( 'web.reports.stock.home' , $data ) ;
	}

	public function filterStock ( $stockId )
	{
		$allStock	 = [ ] ;
		$items		 = \Models\Item::all () ;

		foreach ( $items as $key => $item )
		{
			$good_quantity			 = 0 ;
			$return_quantity		 = 0 ;
			$current_buying_price	 = 0 ;
			$total_weight			 = 0 ;

			$stockDetails = \Models\StockDetail::where ( 'item_id' , '=' , $item -> id ) ;

			if ( $stockId != 0 )
			{
				$stockDetails = $stockDetails -> where ( 'stock_id' , '=' , $stockId ) ;
			}

			foreach ( $stockDetails -> get () as $stock )
			{
				$item					 = $stock -> item ;
				$good_quantity += $stock -> good_quantity ;
				$return_quantity += $stock -> return_quantity ;
				$current_buying_price	 = $stock -> item -> current_buying_price ;
				$total_weight			 = $stock -> item -> weight ;
			}

			$item_array								 = [ ] ;
			$item_array[ 'item' ]					 = $item ;
			$item_array[ 'good_quantity' ]			 = $good_quantity ;
			$item_array[ 'return_quantity' ]		 = $return_quantity ;
			$item_array[ 'total_weight' ]			 = ($total_weight * $good_quantity) / 1000 ;
			$good_quantity_value					 = $good_quantity * $current_buying_price ;
			$item_array[ 'good_quantity_value' ]	 = $good_quantity_value ;
			$return_quantity_value					 = $return_quantity * $current_buying_price ;
			$item_array[ 'return_quantity_value' ]	 = $return_quantity_value ;
			$item_array[ 'total_value' ]			 = $good_quantity_value + $return_quantity_value ;

			$allStock[ $key ] = $item_array ;
		}

		$result = new \Illuminate\Support\Collection ( $allStock ) ;

		return $result ;
	}

	public function getTotal ( $stockDetails )
	{
		$good_quantity_value_total	 = 0 ;
		$return_quantity_value_total = 0 ;
		$grandTotal					 = 0 ;
		$totalWeight				 = 0 ;
		$totals						 = [ ] ;

		foreach ( $stockDetails as $stock )
		{
			$good_quantity_value_total += $stock[ 'good_quantity_value' ] ;
			$return_quantity_value_total += $stock[ 'return_quantity_value' ] ;
			$grandTotal +=$stock[ 'total_value' ] ;
			$totalWeight += $stock[ 'total_weight' ] ;
		}

		$totals[ 'good_quantity_value_total' ]	 = $good_quantity_value_total ;
		$totals[ 'return_quantity_value_total' ] = $return_quantity_value_total ;
		$totals[ 'grandTotal' ]					 = $grandTotal ;
		$totals[ 'totalWeight' ]				 = $totalWeight ;

		return $totals ;
	}

}
