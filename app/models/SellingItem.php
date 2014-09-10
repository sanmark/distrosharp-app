<?php

namespace Models ;

class SellingItem extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

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

}
