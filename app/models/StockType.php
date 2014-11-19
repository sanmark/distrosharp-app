<?php

namespace Models ;

class StockType extends BaseEntity implements \Interfaces\iEntity
{

	protected $connection = 'central_db' ;

	public function stocks ()
	{
		return $this -> hasMany ( 'Models\Stock' ) ;
	}

	public function stockIds ()
	{
		$this -> load ( 'stocks' ) ;

		$stocks = $this -> stocks ;

		$stockIds = [ ] ;

		foreach ( $stocks as $stock )
		{
			$stockIds[] = $stock -> id ;
		}

		return $stockIds ;
	}

	public function stockIdsExceptImbalance ()
	{
		$imbalanceStockId = \SystemSettingButler::getValue ( 'imbalance_stock' ) ;

		$stockIds = $this -> stockIds () ;

		$indexOfImbalabnceStockId = array_search ( $imbalanceStockId , $this -> stockIds () ) ;

		unset ( $stockIds[ $indexOfImbalabnceStockId ] ) ;

		return $stockIds ;
	}

}
