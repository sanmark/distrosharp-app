<?php

namespace Models ;

class SellingItem extends BaseEntity implements \Interfaces\iEntity
{

	public function sellingInvoice ()
	{
		return $this -> belongsTo ( 'Models\SellingInvoice' ) ;
	}

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' ) ;
	}

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public static function getArrayForHtmlSelect ( $key , $value , array $firstElement = NULL )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

	public function getSalesLineTotal ()
	{
		$lineTotal = $this -> price * $this -> paid_quantity ;

		return $lineTotal ;
	}

	public function getReturnLineTotal ()
	{
		$lineTotal = ($this -> good_return_price * $this -> good_return_quantity) + ($this -> company_return_price * $this -> company_return_quantity);

		return $lineTotal ;
	}

}
