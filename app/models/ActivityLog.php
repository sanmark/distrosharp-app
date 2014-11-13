<?php

namespace Models ;

class ActivityLog extends BaseEntity implements \Interfaces\iEntity
{

	public function user ()
	{
		return $this -> belongsTo ( 'User' , 'user_id' ) ;
	}

}
