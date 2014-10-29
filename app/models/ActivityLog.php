<?php

namespace Models ;

class ActivityLog extends BaseEntity implements \Interfaces\iEntity
{

	public $timestamps = FALSE ;

	public function user ()
	{
		return $this -> belongsTo ( 'User' , 'user_id' ) ;
	}

}
