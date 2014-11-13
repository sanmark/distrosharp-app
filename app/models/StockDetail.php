<?php

namespace Models ;

class StockDetail extends BaseEntity implements \Interfaces\iEntity
{

	public function stock ()
	{
		return $this -> belongsTo ( 'Models\Stock' ) ;
	}

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

	public function scopeActiveItems ( $query )
	{
		return $query -> whereHas ( 'item' , function($item)
			{
				$item -> where ( 'is_active' , '=' , TRUE ) ;
			} ) ;
	}

}
