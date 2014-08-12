<?php

namespace Models ;

class Stock_detail extends \Eloquent
{

	public $timestamps = FALSE ;
	
	public function update ( array $attributes = array () )
	{
		parent::update ( $attributes ) ;
	}
}