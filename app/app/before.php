<?php

App::before ( function($request)
{
	if ( Session::has ( 'tenant_db' ) )
	{
		Config::set ( 'database.connections.tenant_db.database' , Config::get ( 'config.tenant_db_prefix' ) . Session::get ( 'tenant_db' ) ) ;
	}
} ) ;
