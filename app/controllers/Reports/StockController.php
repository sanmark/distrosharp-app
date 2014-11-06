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
		$data			 = compact ( [
			'stockSelect' ,
			'stockId' ,
			'stockDetails' ,
			'viewData' ,
			'totals'
			] ) ;

		return \View::make ( 'web.reports.stock.home' , $data ) ;
	}

	public function view ()
	{
		$stockSelect	 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' , [0 => 'All' ] ) ;
		$stockId		 = \InputButler::get ( 'stock_id' ) ;
		$stockDetails	 = $this -> filterStock ( $stockId ) ;
		$totals			 = $this -> getTotal ( $stockDetails ) ;
		$viewData		 = TRUE ;
		$data			 = compact ( [
			'stockSelect' ,
			'stockId' ,
			'stockDetails' ,
			'viewData' ,
			'totals'
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
