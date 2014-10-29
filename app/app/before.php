<?php

App::before ( function($request)
{
	if ( Session::has ( SESSION_ORGANIZATION ) )
	{
		Config::set ( CONFIG_DATABASE_CONNECTIONS_TENANTDB_DATABASE , Config::get ( CONFIG_CONFIG_TENANTDB_PREFIX ) . Session::get ( SESSION_ORGANIZATION ) ) ;
	}
} ) ;
