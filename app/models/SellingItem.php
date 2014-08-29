<?php

namespace Models ;

class SellingItem extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

}
