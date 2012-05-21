<?php
/**
 * AWP_Common_Util Class.
 *
 */
class AWP_Common_Util
{
	/**
	 * Get site Key information.
	 *
	 * @return unknown
	 */
	function getsiteinfo()
	{
		$apptivo_site_key= get_option('apptivo_sitekey');
		return $apptivo_site_key;
	}
	/**
	 * Get Memcache Information.
	 *
	 * @return unknown
	 */
	function mcacheinfo()
	{
		$machchesettings = get_option('awp_memcache_settings');
		return $machchesettings['hostname_portno'];
		
	}
}
?>