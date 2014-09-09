<?php

namespace Controllers\Reports ;

class StockController extends \Controller
{

	public function show ()
	{
		$data		 = [ ] ;
		$stockSelect = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' ) ;

		$data [ 'stockSelect' ]	 = $stockSelect ;
		array_unshift ( $stockSelect , '--' ) ;
		$data [ 'stockSelect' ]	 = $stockSelect ;
		$data [ 'stockId' ]		 = 0 ;

		return \View::make ( 'web.reports.stock.home' , $data ) ;
	}

	public function update ()
	{
		$data					 = [ ] ;
		$stockSelect			 = \Models\Stock::getArrayForHtmlSelect ( 'id' , 'name' ) ;
		array_unshift ( $stockSelect , '--' ) ;
		$data [ 'stockSelect' ]	 = $stockSelect ;

		$stockId = \Input::get ( 'stock_id' ) ;
		if ( isset ( $stockId ) )
		{

			$stock					 = \Models\StockDetail::where ( 'stock_id' , '=' , $stockId ) -> get () ;
			$calculatedStockValues	 = $this -> calculateDerivedValues ( $stock ) ;

			$data [ 'calculatedStockValues' ] = $calculatedStockValues ;

			$totals = $this -> getTotals ( $calculatedStockValues ) ;

			$data [ 'good_quantity_value_total' ]	 = $totals[ 'good_quantity_value_total' ] ;
			$data [ 'return_quantity_value_total' ]	 = $totals[ 'return_quantity_value_total' ] ;
			$data [ 'grandTotal' ]					 = $totals[ 'grandTotal' ] ;
			$data [ 'stockId' ]						 = $stockId ;
		}


		return \View::make ( 'web.reports.stock.home' , $data ) ;
	}

	public function calculateDerivedValues ( $stock )
	{
		$calculatedStock = [ ] ;
		foreach ( $stock as $item )
		{
			$item -> good_quantity_value	 = $item -> good_quantity * $item -> item -> current_buying_price ;
			$item -> return_quantity_value	 = $item -> return_quantity * $item -> item -> current_buying_price ;
			$item -> total_value			 = $item -> good_quantity_value + $item -> return_quantity_value ;
			$calculatedStock[ $item -> id ]	 = $item ;
		}

		$calculatedStockValues = new \Illuminate\Support\Collection ( $calculatedStock ) ;
		return $calculatedStockValues ;
	}

	private function getTotals ( $calculatedStockValues )
	{
		$totals						 = [ ] ;
		$good_quantity_value_total	 = 0 ;
		$return_quantity_value_total = 0 ;
		$grandTotal					 = 0 ;
		foreach ( $calculatedStockValues as $item )
		{
			$good_quantity_value_total	 = $good_quantity_value_total + $item -> good_quantity_value ;
			$return_quantity_value_total = $return_quantity_value_total + $item -> return_quantity_value ;
			$grandTotal					 = $grandTotal + $item -> total_value ;
		}
		$totals[ 'good_quantity_value_total' ]	 = $good_quantity_value_total ;
		$totals[ 'return_quantity_value_total' ] = $return_quantity_value_total ;
		$totals[ 'grandTotal' ]					 = $grandTotal ;
		return $totals ;
	}

}
