<?php

/**
 * Apptivo Main Controller
 */
require_once AWP_LIB_DIR . '/Plugin.php';
/**
 * Class AWP_MainController
 */
class AWP_MainController extends AWP_Base
{
    /**
     * Current page
     * @var string
     */
    var $_page = 'awp_general';
    //aip_
    /**
     * Notes
     * @var array
     */
    var $_notes = array();
    
    /**
     * Errors
     * @var array
     */
    var $_errors = array();
    
    /**
     * Runs plugin
     */
    function run()
    {
        add_action('admin_menu', array(&$this,'admin_menu'));
               
        add_action('init', array(&$this,'init'));
        
        /**
         * Run Contact Forms Plugin
         */
        require_once AWP_PLUGINS_DIR . '/ContactForms.php';
        $awp_contactforms = & AWP_ContactForms::instance();
        $awp_contactforms->run();
        
         /**
         * Run Forms Plugin
         */
        require_once AWP_PLUGINS_DIR . '/jobs.php';
        $awp_jobsforms = & AWP_Jobs::instance();
        $awp_jobsforms->run();
        
         /**
         * Run Newsletter Plugin
         */
        require_once AWP_PLUGINS_DIR . '/Newsletter.php';
        $awp_testimonials = & AWP_Newsletter::instance();
        $awp_testimonials->run();
        
         /**
         * Run Testimonials Plugin
         */
        require_once AWP_PLUGINS_DIR . '/Testimonials.php';
        $awp_testimonials = & AWP_Testimonials::instance();
        $awp_testimonials->run();
         /**
         * Run News Plugin
         */
        require_once AWP_PLUGINS_DIR . '/News.php';
        $awp_testimonials = & AWP_News::instance();
        $awp_testimonials->run();
             /**
         * Run Events Plugin
         */
        require_once AWP_PLUGINS_DIR . '/Events.php';
        $awp_testimonials = & AWP_Events::instance();
        $awp_testimonials->run();


    }
    
    /**
     * Returns plugin instance
     *
     * @return AWP_Controller
     */
    function &instance()
    {
        static $instances = array();
        
        if (!isset($instances[0])) {
            $class = __CLASS__;
            $instances[0] = & new $class();
        }
        
        return $instances[0];
    }
    
    /**
     * Init action
     */
    function init()
    {
        
    }
    
    /**
     * Load action
     */
    function load()
    {
        require_once AWP_LIB_DIR . '/Request.php';
        
        $this->_page = AWP_Request::get_string('page');
        
        switch (true) {
        	case ($this->_page == 'awp_general'):
            case ($this->_page == 'awp_contactforms'):
            case ($this->_page == 'awp_newsletter'):
            case ($this->_page == 'awp_testimonials'):
            case ($this->_page == 'awp_news'):
            case ($this->_page == 'awp_events'):
            case ($this->_page == 'awp_jobs'):          
            		           
                break;
            
            default:
                $this->_page = 'awp_general';
        }
        
        /**
         * Save config
         */
        if (isset($_REQUEST['awp_generalform_commonplugin_settings'])) {
        	$this->save_generalsettings();
        }

       
    }

    /**
     * Save General Settings form
     * 
     */
    function save_generalsettings(){
    	$settings=array();
    	$settings["contactforms"]=AWP_Request::get_boolean("contactforms_enable");
    	$settings["newsletters"]=AWP_Request::get_boolean("newsletters_enable");
    	$settings["testimonials"]=AWP_Request::get_boolean("testimonials_enable");
    	$settings["news"]=AWP_Request::get_boolean("news_enable");
    	$settings["events"]=AWP_Request::get_boolean("events_enable");
        $settings["jobs"]=AWP_Request::get_boolean("jobs_enable");
    	if(get_option("awp_plugins")!=="false"){
        
    		update_option("awp_plugins", $settings);
    	}else{
    		add_option("awp_plugins", $settings);
    	}
    }
    
    /**
     * Admin menu
     */
    function admin_menu()
    {
        /*$pages = array(
            'awp_general' => array('General Settings',  'General Settings' ), 
            'awp_contactforms' => array('Contact Forms','Contact Forms'),
            'awp_newsletter' => array('Newsletter', 'Newsletter' ),            
            'awp_events' => array('Events','Events'),
            'awp_news' => array('News','News'),           
            'awp_testimonials' => array('Testimonials', 'Testimonials'),
            'awp_jobs' => array('Jobs', 'Jobs')
                );*/
          $pages = array('awp_general' => array('General Settings',  'General Settings' ));
          $awp_pluginsettings = get_option('awp_plugins');
          
   		 if(!defined('AWP_CONTACTFORM_DISABLE') || !AWP_CONTACTFORM_DISABLE)
          {
          	$contact_pages = array('awp_contactforms' => array('Contact Forms','Contact Forms'));
          	$pages = array_merge($pages, $contact_pages); 
          }
   		 if(!defined('AWP_NEWSLETTER_DISABLE') || !AWP_NEWSLETTER_DISABLE)
          {
          	$newsletter_pages = array('awp_newsletter' => array('Newsletter', 'Newsletter' ));
          	$pages = array_merge($pages, $newsletter_pages); 
          }
   		 if(!defined('AWP_TESTIMONIALS_DISABLE') || !AWP_TESTIMONIALS_DISABLE)
          {
          	$testimonials_pages = array('awp_testimonials' => array('Testimonials', 'Testimonials'));
          	$pages = array_merge($pages, $testimonials_pages); 
          }
     	if(!defined('AWP_NEWS_DISABLE') || !AWP_NEWS_DISABLE)
          {
          	$news_pages = array('awp_news' => array('News','News'));
          	$pages = array_merge($pages, $news_pages); 
          }
    	if(!defined('AWP_EVENTS_DISABLE') || !AWP_EVENTS_DISABLE)
          {
          	$events_pages = array('awp_events' => array('Events','Events'));
          	$pages = array_merge($pages, $events_pages); 
          }
    	if(!defined('AWP_JOBS_DISABLE') || !AWP_JOBS_DISABLE)
          { 
          
          	$jobs_pages = array('awp_jobs' => array('Jobs',  'Jobs' ));
          	$pages = array_merge($pages, $jobs_pages); 
          }
      
         
        add_menu_page('Apptivo', 'Apptivo', 'manage_options', 'awp_general', '',"http://d3piu9okvoz5ps.cloudfront.net/awp-content_1/12377wp10031/uploads/2011/07/apptivo-1.png");
        $submenu_pages = array();
        
		foreach ($pages as $slug => $titles) {
				$submenu_pages[] = add_submenu_page('awp_general', $titles[0] . ' | Apptivo', $titles[1], 'manage_options', $slug, array(
                &$this, 
                'options'
            ));
           }
         
        if (current_user_can('manage_options')) {
            /**
             * Only admin can modify AIP settings
             */
            foreach ($submenu_pages as $submenu_page) {
                add_action('load-' . $submenu_page, array(&$this,'load'));
            }
        }
    }

    /**
     * Options page
     */
    function options()
    {
 
        /**
         * Show tab
         */
        switch ($this->_page) {
            case 'awp_general':
                $this->options_general();
                break;
            
            case 'awp_contactforms':
                $this->options_contactforms();
                break;
            
            case 'awp_newsletter':
                $this->options_newsletter();
                break;
            
            case 'awp_testimonials':
                $this->options_testimonials();
                break;
            
            case 'awp_news':
                $this->options_news();
                break;
            
            case 'awp_events':
                $this->options_events();
                break;
                
           case 'awp_jobs':
                $this->options_jobs();
                break;
         

        }
    }
    

    /**
     * General tab
     */
    function options_general(){
    	$this->show_general_settings();
    }
    /**
     * Contact Forms tab
     */
    function options_contactforms(){
    	require_once AWP_PLUGINS_DIR . '/ContactForms.php';
        $awp_contactforms = & AWP_ContactForms::instance();
        $awp_contactforms->options();
    }
   /**
     * Jobs Form tab
     */
    function options_jobs(){
    	require_once AWP_PLUGINS_DIR . '/jobs.php';
        $awp_jobsforms = & AWP_Jobs::instance();
        $page = $_GET['keys'];
        switch ($page){
        	case jobcreation:
        		$awp_jobsforms->createJobsoptions();
        		break;
        	case configuration:
        		$awp_jobsforms->jobconfiguration();
        		break;
        	case jobsearch:
        		$awp_jobsforms->jobsearch();
        		break;		
        	default :
        		$awp_jobsforms->createJobsoptions();
        }   
    }
    

    
    /**
     * Testimonials tab
     */
    function options_testimonials(){
    	require_once AWP_PLUGINS_DIR . '/Testimonials.php';
        $awp_testimonials = & AWP_Testimonials::instance();
        $awp_testimonials->options();
    }
    
    /**
     * Newsletter tab
     */
    function options_newsletter(){
    	require_once AWP_PLUGINS_DIR . '/Newsletter.php';
        $awp_testimonials = & AWP_Newsletter::instance();
        $awp_testimonials->options();
    }
    
    /**
     * News tab
     */
    function options_news(){
    	require_once AWP_PLUGINS_DIR . '/News.php';
        $awp_testimonials = & AWP_News::instance();
        $awp_testimonials->options();
    }
    
    /**
     * Events tab
     */
    function options_events(){
    	require_once AWP_PLUGINS_DIR . '/Events.php';
        $awp_testimonials = & AWP_Events::instance();
        $awp_testimonials->options();
    }
    /**
     * Mem cache Settings.
     *
     * @param unknown_type $memcachesettings
     * @param unknown_type $test_m_cacheconnect
     */
 
    
    function memCacheSettings($memcachesettings,$test_m_cacheconnect)
    {
    	 echo "<h3>" . __( 'Memcache Settings', 'apptivo-businesssite' ) . "</h3>";
    	 ?>
    	 <form name="awp_memcache_settings" method="post" action="">
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
					<th valign="top"><label for="memcache_enable"><?php _e("Enable Memcache", 'apptivo-businesssite' ); ?>:</label>
						<!-- <br><span class="description">Enable the memcache to store the data which is coming for apptivo web services</span> -->
					</th>
					<td valign="top">
						<input type="checkbox" name="memcache_enable" id="memcache_enable" onclick="genralform_enablefield('memcache_enable')" class="enabled" <?php checked(1, $memcachesettings['memcache_enable']);checked('Test',$_POST['awp_memcache_test']); ?>/>
					    
					</td>
				</tr>
                
                
				<tr>
        		<th><label for="memcached_servers">Memcached hostname:port / <acronym title="Internet Protocol">IP</acronym>:port:</label></th>
        		<td>
        			<input <?php  if(!$memcachesettings['memcache_enable'] && $_POST['awp_memcache_test'] != 'Test' ){ echo 'disabled="disabled"'; } ?>  type="text" size="30" value="<?php if($_POST['awp_memcache_test']) { echo $_POST['hostname_portno']; } else { echo $memcachesettings['hostname_portno']; }  ?>" name="hostname_portno" id="hostname_portno">
        			<input <?php  if(!$memcachesettings['memcache_enable'] && $_POST['awp_memcache_test'] != 'Test' ){ echo 'disabled="disabled"'; } ?>  type="submit" name="awp_memcache_test" id="awp_memcache_test" class="button-primary" value="Test">
        			<?php 
                         if($_POST['awp_memcache_test'] && $test_m_cacheconnect )
                         {
                         echo '<span class="message" style="display:inline-block;padding: 5px;background-color: #BBFFBB ;" id="memcached_test_status">Test passed.</span>';
                         }else if($_POST['awp_memcache_test'])
                         {
                          echo '<span class="message" style="display:inline-block;padding: 5px;background-color: #FF9999 ;" id="memcached_test_status">Test Failed.</span>';	
                         }                     	
                        ?>
        			<br><span class="description">e.g. domain.com:22122 (or) 192.168.1.100:11211 </span>
        		</td>
        	</tr>
                      		<tr>
					<td>
						&nbsp;
						
					</td>
                                        <td>
    						
							<input type="submit" name="awp_memcache_settings"
								id="awp_memcache_settings" class="button-primary"
								value="<?php esc_attr_e('Save Configuration') ?>" />
						
					</td>
				</tr>
                        </tbody>
                    </table>
                </form>
                <?php 
    }
 /**
     * Site Information.
     *
     * @param unknown_type $apptivo_site_key
     */
    function siteInformation($apptivo_site_key,$apptivo_access_key)
    {
    	echo "<h3>" . __( 'Site Information', 'apptivo-businesssite' ) . "</h3>";
    	if(isset($_POST['awp_siteinfo_form']))
    	{ 
    		if(strlen(trim($_POST['site_key'])) != 0 )
    	{
    		echo '<div class="updated below-h2" id="message"> <p> Updated Site Information Settings.</p></div>';
    	}else{
    		echo '<div class="updated below-h2" id="message"><p>Site key cannot be empty.</p></div>'; 
    	}
    	}
    	?>
    	            <form name="awp_siteinfo_form" method="post" action="">
                    <table class="form-table">
                        <tbody>
                <tr valign="top">
					<th valign="top" style="width:425px;"><label for="site_key"><?php _e("Site key", 'apptivo-businesssite' ); ?> (You must <a href="<?php echo awp_developerguide('purchase-sitekey'); ?>" target="_blank" >purchase a site key</a> before using this Plugin) :</label>
						<br><span class="description">Site key generated in Apptivo</span>
					</th>
					<td valign="top">
                     <input style="width:250px;" type="text" name="site_key" id="site_key" class="enabled" value="<?php echo $apptivo_site_key; ?>"/>
                     <input type="hidden" name="prev_site_key" id="prev_site_key" value="<?php echo $apptivo_site_key; ?>"/>
                     <input type="hidden" name="update_site_inf" id="update_site_inf" value="yes"/>
					</td>
				</tr>
				
				<tr valign="top">
					<th valign="top"><label for="site_key"><?php _e("Access key", 'apptivo-businesssite' ); ?> </label>
						<br><span class="description">Access key generated in Apptivo</span>
					</th>
					<td valign="top">
                     <input style="width:250px;" type="text" name="access_key" id="access_key" class="enabled" value="<?php echo $apptivo_access_key; ?>"/>
                    </td>
				</tr>
				
				<tr>
					<td colspan="2">
						<p class="submit">
							<input type="submit" name="awp_siteinfo_form"
								id="awp_siteinfo_form" class="button-primary"
								value="<?php esc_attr_e('Save Configuration') ?>" onclick="return cmp_sitekey();" />
						</p>
					</td>
				</tr>
                        </tbody>
                    </table>
                </form>
            <script type="text/javascript" language="javascript" > 
			function cmp_sitekey()
            {   
				
				var prev_siteKey = jQuery.trim( jQuery('#prev_site_key').val() );
				var current_sitekey = jQuery.trim( jQuery('#site_key').val() );
				var accessKey = jQuery.trim( jQuery('#access_key').val() );

				if( current_sitekey == '' || accessKey == '') //To chk site key is empty
				{
					if(current_sitekey == '' && accessKey == '') {
						jQuery('#site_key').css('border', '1px solid #f00');
						jQuery('#access_key').css('border','1px solid #f00');
						alert("Site Key and Access Key can not be empty.");
					}else if(current_sitekey == '' && accessKey != '') {
					jQuery('#site_key').css('border', '1px solid #f00');
					jQuery('#access_key').css('border', '1px solid #dfdfdf');
					alert("Site Key can not be empty.");
					}else if(current_sitekey != '' && accessKey == '') {
					jQuery('#site_key').css('border', '1px solid #dfdfdf');
					jQuery('#access_key').css('border', '1px solid #f00');
					alert("Access Key can not be empty.");
						}
					return false;
				}else if( prev_siteKey == '' ) //To chk Previous site key is empty
				{
					return true;
				}else if( current_sitekey != prev_siteKey ) 
				{
					var answer = confirm('Are you sure  change the Site Key? \n Changing site key will reset all apptivo business site plugin settings.');
					if (answer){ 
						return true;
					}
					else{
						jQuery('#site_key').val(prev_siteKey);
						jQuery('#site_key').css('border', '1px solid #dfdfdf');
						jQuery('#access_key').css('border', '1px solid #dfdfdf');  
						return false;
					}
				}else   //To chk both site keys are equal or not.
				{
					//alert("This Site Key already configured.");
					jQuery('#update_site_inf').val('no');   // update_site_inf value is set 'no'
					return true; 
					}
				
             }
	        </script>
    	<?php 
    	
    	
    }
    
	/**
     * Plugins Settings Options.
     *
     * @param unknown_type $disable_pluginsSettings
     * @param unknown_type $generalsettings
     */
    function pluginsSettings($generalsettings)
    {
    	echo "<h3>" . __( 'Plugins Settings', 'apptivo-businesssite' ) . "</h3>";
	    if(isset($_POST['awp_generalform_commonplugin_settings']))
	    	{ 
	    		echo '<div class="updated below-h2" id="message"> <p>Updated Plugins Settings.</p></div>';	    
	    	}
    	$apptivo_site_key = get_option('apptivo_sitekey');
    	if (defined("APPTIVO_SITE_KEY") ){
			$disable_plugin=false;
    	}
    	else if (!empty($apptivo_site_key) || strlen(trim($apptivo_site_key)) > 0 )
    	{	
    		$disable_plugin=false;
    	}
		else 
		{	
			$disable_plugin=true;
		}
	    /* if($disable_plugin) 
	     {
		 	 echo '<span style="color:#f00;">Save Site Key to update settings.</span>';
		 } */ 
     	 ?>
	  <form name="awp_generalsettings_form" method="post" action="">
		<table class="form-table">
			<tbody>
			    <tr valign="top">
			    <?php if(!defined('AWP_CONTACTFORM_DISABLE') || !AWP_CONTACTFORM_DISABLE) {  ?>
					<th valign="top"><label for="contactforms_enable"><?php _e("Contact Forms", 'apptivo-businesssite' ); ?>:</label>
						<br><span class="description">Contact Forms to collect lead from your website.</span>
					</th>
					<td valign="top">
						<input type="checkbox" name="contactforms_enable" id="contactforms_enable" class="enabled" 
						<?php if($disable_plugin) {?>
						disabled="disabled"
						<?php } if($generalsettings["contactforms"]){?>
						checked="checked"
						<?php }?>
						>
						<strong>Enable</strong>			
					</td>
				    <?php } if(!defined('AWP_NEWSLETTER_DISABLE') || !AWP_NEWSLETTER_DISABLE) {?>
					<th valign="top"><label for="newsletters_enable"><?php _e("Newsletters", 'apptivo-businesssite' ); ?>:</label>
						<br><span class="description">Subscribe your users to newsletters using Apptivo Campaigns.</span>
					</th>
					<td valign="top">
						<input type="checkbox" name="newsletters_enable" id="newsletters_enable" class="enabled"
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php  } if($generalsettings["newsletters"]){?>
						checked="checked"
						<?php }?>
						>
						<strong>Enable</strong>			
					</td>
					<?php } ?>
				</tr>
				<tr valign="top">
				<?php if(!defined('AWP_TESTIMONIALS_DISABLE') || !AWP_TESTIMONIALS_DISABLE) {?>
					<th valign="top"><label for="testimonials_enable"><?php _e("Testimonials", 'apptivo-businesssite' ); ?>:</label>
						<br><span class="description">Collect and show in your website, what your clients tell about you.</span>
					</th>
					<td valign="top">
						<input type="checkbox" name="testimonials_enable" id="testimonials_enable" class="enabled"
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php } if($generalsettings["testimonials"]){?>
						checked="checked"
						<?php }?>
						>
						<strong>Enable</strong>			
					</td>
					<?php } if(!defined('AWP_NEWS_DISABLE') || !AWP_NEWS_DISABLE) { ?>
				
					<th valign="top"><label for="news_enable"><?php _e("News", 'apptivo-businesssite' ); ?>:</label>
						<br><span class="description">Show what's News about your company in your websites.</span>
					</th>
					<td valign="top">
						<input type="checkbox" name="news_enable" id="news_enable" class="enabled"
						<?php if($disable_plugin) {?>
						disabled="disabled"
						<?php } if($generalsettings["news"]){?>
						checked="checked"
						<?php }?>
						>
						<strong>Enable</strong>			
					</td>
					<?php } ?>
				</tr>
				<tr valign="top">
				<?php if(!defined('AWP_EVENTS_DISABLE') || !AWP_EVENTS_DISABLE) { ?>
					<th valign="top"><label for="events_enable"><?php _e("Events", 'apptivo-businesssite' ); ?>:</label>
						<br><span class="description">Show various events organized by you in your website.</span>
					</th>

					<td valign="top">
						<input type="checkbox" name="events_enable" id="events_enable" class="enabled"
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php }
						if($generalsettings["events"]){?>
						checked="checked"
						<?php }?>
						>
						<strong>Enable</strong>			
					</td>
					<?php } if(!defined('AWP_JOBS_DISABLE') || !AWP_JOBS_DISABLE ) { ?>
					
					<th valign="top"><label for="jobs_enable"><?php _e("Jobs", 'apptivo-businesssite' ); ?>:</label>
						<br><span class="description">Jobs</span>
					</th>
					<td valign="top">
						<input type="checkbox" name="jobs_enable" id="jobs_enable" class="enabled" 
						<?php if($disable_plugin){?>
						disabled="disabled"
						<?php } if($generalsettings["jobs"]){?>
						checked="checked"
						<?php }?>
						>
						<strong>Enable</strong>			
					</td>
					<?php } ?>
					
				</tr>
				<tr>
					<td colspan="2">
						<p class="submit">
							<input <?php if($disable_plugin) { echo 'disabled="disabled"'; } ?>type="submit" name="awp_generalform_commonplugin_settings"
								id="awp_generalform_commonplugin_settings" class="button-primary"
								value="<?php esc_attr_e('Save Configuration') ?>" />
						</p>
						
					</td>
				</tr>
	   		</tbody>
		</table>
		</form>
	<?php 
    }
/**
	 * Memcache Configuration and test Memcache...
	 *
	 */    
	function configure_memcache()
	{       
		    if(class_exists('Memcache'))
		    { 
		    $awp_datacache = new AWP_Cache_Util(); //Create Object in AWP_DataCache clss]
		    }
		    else { 
		    	echo '<span style="color:#f00;">PHP Memcache is not detected in this system. Install PHP Memcache and configure for better performance.</span>';
		    //trigger_error("PHP Class 'Memcache' does not exist!", E_USER_ERROR);	
		    }
		    $memcachesettings = array();
	        $memcachesettings['memcache_enable']= AWP_Request::get_boolean("memcache_enable");
	        $memcachesettings['hostname_portno']= AWP_Request::get_string("hostname_portno");
	        
	        if(isset($_POST['awp_memcache_settings'])){
	            if(get_option("awp_memcache_settings")!=="false"){
	               update_option('awp_memcache_settings',$memcachesettings);
	            }
	            else{
	               add_option('awp_memcache_settings', $memcachesettings);
	            }
	            if(!$memcachesettings['memcache_enable'])
	            {
	            	update_option('awp_memcache_settings','');
	            }
	            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Memcache configuration successfully saved.
	                </p></div>';
	        } 
	}
	
	function  test_mcacheconnect()
	{       
		if($_POST['awp_memcache_test'])
		{   
		  if(class_exists('Memcache'))
		    { 
		    $awp_datacache = new AWP_Cache_Util(); //Create Object in AWP_DataCache clss]
		    $memcachesettings = array();
	        $memcachesettings['memcache_enable']= AWP_Request::get_boolean("memcache_enable");
	        $memcachesettings['hostname_portno']= AWP_Request::get_string("hostname_portno");  
	        $test_connect = $awp_datacache->connectmcache( $memcachesettings['hostname_portno']);
	        return $test_connect;
		    }else{
		    	//echo '<span style="color:#f00;">PHP Memcache is not detected in this system. Install PHP Memcache and configure to Test Memcache connection.</span>';
		    	return false;
		    //trigger_error("PHP Class 'Memcache' does not exist!", E_USER_ERROR);	
		    }
		    
	         
		}
	          
	}
	
	
	
	
 /**
     * Render Apptivo General Settings page
     */
    function show_general_settings(){
       /* Stored Site Information */	            	
       if(isset($_POST['awp_siteinfo_form'])){
            $apptivo_site_key= AWP_Request::get_string("site_key");
            $apptivo_access_key= AWP_Request::get_string("access_key");
            $apptivo_update_site_inf= AWP_Request::get_string("update_site_inf"); // To check options or delete or not.
            if ((!empty($apptivo_site_key) || strlen(trim($apptivo_site_key)) > 0) && (strtolower($apptivo_update_site_inf) != 'no'))
            {
            //apptivo site key.	
              if(get_option("apptivo_sitekey")!=="false"){        //options are deleted if changinging the sitekey.  So check apptivo-update_site_inf is 'yes' or 'no',in default apptivo-update_site_inf is 'yes'
	                update_option('apptivo_sitekey',$apptivo_site_key);
					delete_option('awp_contactforms');				//Contactform configuration
					delete_option('awp_events_settings');			//Events Full view configuration.
					delete_option('awp_events_inline_settings');	//Events InlineView configurayion.
					delete_option('awp_jobsforms');					//job forms configuraion.
					delete_option('awp_jobs_settings');				//Job settings page confiduration.
					delete_option('awp_jobsearchforms');			//Job search form configuration.
					delete_option('awp_news_inline_settings');		//News Inline View configuration.
					delete_option('awp_news_settings');				//News Full View Configuration.
					delete_option('awp_newsletterforms');			//Newsletter Form Configuration.
					delete_option('awp_testimonials_inline_settings');//Testimonials Inline view Configuration.
					delete_option('awp_testimonials_settings');		//Testimonials Full View Configuration.
	           }
	           else{
	               add_option('apptivo_sitekey', $apptivo_site_key);
	           }
            }
            
            //apptivo access Key
             if(get_option('apptivo_accesskey')!=="false") :
             	update_option('apptivo_accesskey',$apptivo_access_key);
             else:
               add_option('apptivo_accesskey', $apptivo_access_key);
             endif;
               
       }
       
       $apptivo_site_key = get_option('apptivo_sitekey');
       $apptivo_access_key = get_option('apptivo_accesskey');
    	// Now display the settings editing screen
		echo '<div class="wrap">';
		echo "<h2>" . __( 'Apptivo General Settings', 'apptivo-businesssite' ) . "</h2>";
		if(trim($updatemessage)!=""){
		?>
		<div id="message" class="updated">
	        <p>
	        <?php echo $updatemessage;?>
	        </p>
	    </div>
	    <?php }
	    
	    //$apptivo_site_key= AWP_Request::get_string("site_key");
            if(!defined("APPTIVO_SITE_KEY") )
            	$this->siteInformation($apptivo_site_key,$apptivo_access_key);
            else if (!empty($apptivo_site_key)){
            	$this->siteInformation($apptivo_site_key,$apptivo_access_key);
            }
            $general_plugins_settings=get_option("awp_plugins");
            
            if($general_plugins_settings == '' || empty($general_plugins_settings)) :
	            if( basename(dirname(AWP_LIB_DIR))  == 'apptivo-businesssite-crm') :
	            $general_plugins_settings = array(
	            'contactforms' => 1,
	            'newsletters' => 1,
	            'testimonials' => 1,
	            'news'=>1,
	            'events'=> 1
	            );
                    if(get_option("apptivo_sitekey") != '') :
	            	update_option("awp_plugins", $general_plugins_settings);
	            	endif;
	            endif;
	            if( basename(dirname(AWP_LIB_DIR))  == 'apptivo-businesssite-hrms') :
	            $general_plugins_settings = array(            
	            'newsletters' => 1, 
	            'jobs'=> 1
	            );
                   if(get_option("apptivo_sitekey") != '') :
	            	update_option("awp_plugins", $general_plugins_settings);
	            	endif;
	            endif;
            endif;
            
            $this->pluginsSettings($general_plugins_settings);
            if(!defined("AWP_MEMCACHED_HOST") && !defined("AWP_MEMCACHED_PORT") )
            {
            	$this->configure_memcache();
                $memcachesettings = get_option('awp_memcache_settings');
                $test_m_cacheconnect = $this->test_mcacheconnect();
                $this->memCacheSettings($memcachesettings,$test_m_cacheconnect);
	        }
	     ?>
			<script language="javascript" type="text/javascript">
			function genralform_enablefield(fld)
			{
				var checked=document.getElementById(fld).checked;
				if(checked){
					document.getElementById('hostname_portno').disabled=!checked;
					document.getElementById('awp_memcache_test').disabled=!checked;
				}
				else {
					document.getElementById('hostname_portno').disabled="disabled";
					document.getElementById('awp_memcache_test').disabled="disabled";
				}
			}
			</script>
<?php 
	     echo "</div>";
    }
	
}