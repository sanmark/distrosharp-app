<?php

class ConfigButler
{

	public static function setTenantDb ( $organization )
	{
		$tenantDbPrefix = self::getTenantDbPrefix () ;

		Config::set ( CONFIG_DATABASE_CONNECTIONS_TENANTDB_DATABASE , $tenantDbPrefix . $organization ) ;
	}

	public static function getTenantDb ()
	{
		return Config::get ( CONFIG_DATABASE_CONNECTIONS_TENANTDB_DATABASE ) ;
	}

	public static function getTenantDbPrefix ()
	{
		return Config::get ( CONFIG_CONFIG_TENANTDB_PREFIX ) ;
	}

}
