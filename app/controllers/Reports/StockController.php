<?php

namespace Controllers\Reports ;

class StockController extends \Controller
{

	public function show ()
	{
		$data		 = [ ] ;
		$stockSelect = \Models\Stock::getArrayForHtmlSelect(  'id', 'name',[0 => 'All']  ) ;

		$data [ 'stockSelect' ]	 = $stockSelect ;
		$item					 = \Models\Item::all();
		$calculatedStockValues	 = $this -> calculateDerivedValuesByProduct ( $item ) ;

		$data [ 'calculatedStockValues' ] = $calculatedStockValues ;			

		$totals = $this -> getTotals ( $calculatedStockValues ) ;

		$data [ 'good_quantity_value_total' ]	 = $totals[ 'good_quantity_value_total' ] ;
		$data [ 'return_quantity_value_total' ]	 = $totals[ 'return_quantity_value_total' ] ;
		$data [ 'grandTotal' ]					 = $totals[ 'grandTotal' ] ;
		$data [ 'stockId' ]						 = 0 ;

		return \View::make ( 'web.reports.stock.home' , $data ) ;
	}

	public function update ()
	{
		$data					 = [ ] ;
		$stockSelect = \Models\Stock::getArrayForHtmlSelect(  'id', 'name',[0 => 'All']  ) ;
		
		$data [ 'stockSelect' ]	 = $stockSelect ;

		$stockId = \Input::get ( 'stock_id' ) ;
		if ( isset ( $stockId )&& $stockId != 0 )
		{
			$item					 = \Models\StockDetail::where ( 'stock_id' , '=' , $stockId ) -> get () ;
			$calculatedStockValues	 = $this -> calculateDerivedValues ( $item ) ;

			$data [ 'calculatedStockValues' ] = $calculatedStockValues ;

			$totals = $this -> getTotals ( $calculatedStockValues ) ;

			$data [ 'good_quantity_value_total' ]	 = $totals[ 'good_quantity_value_total' ] ;
			$data [ 'return_quantity_value_total' ]	 = $totals[ 'return_quantity_value_total' ] ;
			$data [ 'grandTotal' ]					 = $totals[ 'grandTotal' ] ;
			$data [ 'stockId' ]						 = $stockId ;
		}elseif ( $stockId == 0 )
		{	
			$item					 = \Models\Item::all();
			$calculatedStockValues	 = $this -> calculateDerivedValuesByProduct ( $item ) ;

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

	public function calculateDerivedValuesByProduct ( $items )
	{
		$calculatedStock = [ ] ;
		
		foreach ( $items as $item )
		{
			$good_quantity = 0;
			$return_quantity = 0;
			
			$stocks = \Models\StockDetail::where ( 'item_id', '=', $item->id )->  get ();
			foreach ($stocks as $stock){
				$good_quantity = $stock -> good_quantity + $good_quantity;
				$return_quantity = $stock -> return_quantity + $return_quantity;
				$current_buying_price = $stock -> item -> current_buying_price;
				$calculatedStock[ $item -> id ]	 = $stock ;
			}
			$stock-> good_quantity = $good_quantity;
			$stock -> return_quantity = $return_quantity;
			$stock -> good_quantity_value = $good_quantity * $current_buying_price;
			$stock -> return_quantity_value = $return_quantity * $current_buying_price;
			$stock -> total_value = $stock -> good_quantity_value + $stock -> return_quantity_value;
		}
		$calculatedStockValues = new \Illuminate\Support\Collection ( $calculatedStock ) ;
		return $calculatedStockValues ;
	}
	
	
}
