<?php

namespace Models ;

class StockDetail extends \Eloquent
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

}
