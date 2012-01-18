<?php
/**
 * AWP_Datachae(Apptivo Wordpress Datacache).
 *
 */
require_once 'common-util.php';
class AWP_Mcache_Util extends AWP_Common_Util
{
	
   /**
     * Memcache object
     *
     * @var Memcache
     */
    var $_memcache = null;
    
    /**
     * PHP5 constructor
     */
    function __construct()
    {   
    	if(class_exists('Memcache'))
    	{
    	$this->_memcache = & new Memcache();
    	} else 
    	{
    		trigger_error("PHP Class 'Memcache' does not exist!", E_USER_ERROR);
    	}
    }
    
	/**
	 * To check if the memcahe isconnect or not.
	 *
	 * @param unknown_type $host
	 * @param unknown_type $port
	 */	
	function connectmcache($hostname_portno = '')
	{  	
		if(isset($hostname_portno) && $hostname_portno != '')
	{
		$host_port = explode(":",$hostname_portno);
		$hostname = trim($host_port[0]);
		$port = trim($host_port[1]);
		$awp_memcache_connect = $this->_memcache->connect($hostname,$portno);
	}else {
		
		if(!defined('AWP_MEMCACHED_HOST') && !defined('AWP_MEMCACHED_PORT'))
		{
		$mcache = $this->mcacheinfo();
		$host_port = explode(":",$mcache);
		$hostname = trim($host_port[0]);
		$port = trim($host_port[1]);		
		$awp_memcache_connect = $this->_memcache->connect($hostname,$port);
		}
		else { 
			$awp_memcache_connect = $this->_memcache->connect(AWP_MEMCACHED_HOST,AWP_MEMCACHED_PORT);
		}
	}
    return  $awp_memcache_connect;
	}
	/**
	 * Test MemCache connect.
	 *
	 * @param unknown_type $hostname
	 * @param unknown_type $port
	 */
	function testmcacheconnect($hostname,$port)
	{
		$awp_memcache_connect = $this->_memcache->connect($hostname,$port);		
		return  $awp_memcache_connect;
	}
	/**
	 * To store Datas to Memcache
	 *
	 * @param unknown_type $key
	 * @param unknown_type $data
	 */
	function storedata($key,$data)
	{   
		if(!defined('APPTIVO_SITE_KEY'))
		{
		$site_Key = $this->getsiteinfo();
		}else {
		$site_Key = APPTIVO_SITE_KEY;			
		}
		$key = $site_Key.$key;
		return @$this->_memcache->set($key, $data);   
	}
	/**
	 * To get the Datas from Memcahe
	 *
	 * @param unknown_type $key
	 */
	function getdata($key)
	{   
	    if(!defined('APPTIVO_SITE_KEY'))
		{
		$site_Key = $this->getsiteinfo();
		}else {
		$site_Key = APPTIVO_SITE_KEY;			
		}	
		$key = $site_Key.$key;		
		return @$this->_memcache->get($key);
	}
    
    /**
     * Deletes data
     *
     * @param string $key
     * @return boolean
     */
    function delete($key)
    {   
        if(!defined('APPTIVO_SITE_KEY'))
		{
		$site_Key = $this->getsiteinfo();
		}else {
		$site_Key = APPTIVO_SITE_KEY;			
		}
		$key = $site_Key.$key;
        return @$this->_memcache->delete($key);
    }
    
    /**
     * Flushes all data
     *
     * @return boolean
     */
    function flush()
    {
        return @$this->_memcache->flush();
    }
}
?>