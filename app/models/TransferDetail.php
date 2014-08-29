<?php

namespace Models ;

class TransferDetail extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public static function filter ( $filterValues )
	{
		throw new \Exceptions\NotImplementedException() ;
	}

}
