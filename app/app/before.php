<?php

App::before ( function($request)
{
	if ( Session::has ( SESSION_TENANTDB ) )
	{
		Config::set ( CONFIG_DATABASE_CONNECTIONS_TENANTDB_DATABASE , Config::get ( CONFIG_CONFIG_TENANTDB_PREFIX ) . Session::get ( SESSION_TENANTDB ) ) ;
	}
} ) ;
