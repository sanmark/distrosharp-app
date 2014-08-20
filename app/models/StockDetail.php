<?php

namespace Models ;

class StockDetail extends \Eloquent
{

	public $timestamps = FALSE ;
	
	public function update ( array $attributes = array () )
	{
		parent::update ( $attributes ) ;
	}
}