<?php

namespace Models ;

class StockDetail extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function item ()
	{
		return $this -> belongsTo ( 'Models\Item' ) ;
	}

	public function update ( array $attributes = array () )
	{
		parent::update ( $attributes ) ;
	}

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

}
