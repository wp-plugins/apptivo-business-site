<?php
    
function awp_messagelist($key='')
{   
	$awp_errormessage_list = array(
        "contactform-display-page" 				=> '<span class="error_message">Contact form is not configured.</span>',
		"newsletter-display-page" 				=> '<span class="error_message">Newsletter form is not configured.</span>',
		"events-display-page" 					=> '<span class="error_message">Events not found.</span>',
        "eventsconfigure-display-page" 			=> '<span class="error_message">Events is not configured.</span>',
        "newsconfigure-display-page"  			=> '<span class="error_message">News is not configured.</span>',
        "testimonialsconfigure-display-page"  	=> '<span class="error_message">Testimonials is not configured.</span>',
		"news-display-page" 					=> '<span class="error_message">News are not found.</span>',
		"testimonials-display-page" 			=> '<span class="error_message">Testimonials are not found.</span>',
        "jobapplicant-form-display-page"		=> '<span class="error_message">Job applicant form is not configured.</span>',
        "joblists-noresults-display-page"    	=> '<span class="error_message">No jobs are found</span>',
        "joblists-display-page"             	=> '<span class="error_message">Job List page is not configured.</span>',
        "jobdescription-display-page" 			=> '<span class="error_message">Selected jobs are not found.</span>',
        "jobsearch-form-display-page"    		=> '<span class="error_message">Job Search form is not configured.</span>',
		"contactlead-display-page"     			=> '<span class="error_message">Your message was not sent. Please try again after 10 mins</span>',
		"newslettertarget-display-page"     	=> '<span class="error_message">Your Subscription was not sent. Please try again after 10 mins</span>',
		"validate-searchJobsBySearchText"       => '<span class="error_message">Search Results are not found.Please try again after 10 mins.</span>',
		"validate-getAllJobsWithStatus"         => '<span class="error_message">Jobs are not found.Please try again after 10 mins.</span>',
		"jobapplicant-display-page"             => '<span class="error_message">Your request was not sent.Please try again after 10 mins.</span>',
		"validate-getJobsByNo"                  => '<span class="error_message">Selected jobs are not found.Please try again after 10 mins.</span>',
		"jobsearch-noresult"                    => '<span class="error_message">No jobs are found. Please modify your search and try again</span>'
    );
	
	return $awp_errormessage_list[$key];	
}

function awp_developerguide($key='')
{
	$developer_guide = array(
	"purchase-sitekey"                  => 'http://runapptivo.apptivo.com/purchasing-an-apptivo-wordpress-site-key-8272.html',
	"contactform" 				 		=> 'http://runapptivo.apptivo.com/contact-forms-overview-7942.html',
	"contactform-shortcode" 	 		=> 'http://runapptivo.apptivo.com/contact-forms-front-end-integration-7956.html',
	"contactform-template" 		 		=> 'http://runapptivo.apptivo.com/contact-forms-advanced-configuration-7959.html',
	"contactform-customcss"  	 		=> 'http://runapptivo.apptivo.com/contact-forms-advanced-configuration-7959.html',
	"contactform-basicconfig"   		=> 'http://runapptivo.apptivo.com/contact-forms-basic-setup-7945.html',
	
	"newsletter" 				 		=> 'http://runapptivo.apptivo.com/newsletter-overview-7989.html',
	"newsletter-shortcode"       		=> 'http://runapptivo.apptivo.com/newsletter-front-end-integration-7993.html',
	"newsletter-template"       		=> 'http://runapptivo.apptivo.com/newsletter-advanced-configuration-7997.html',
	"newsletter-customcss"       		=> 'http://runapptivo.apptivo.com/newsletter-advanced-configuration-7997.html',
	"newsletter-basicconfig"     		=> 'http://runapptivo.apptivo.com/newsletter-basic-setup-7991.html',
	
	"testimonilas"               		=> 'http://runapptivo.apptivo.com/testimonials-overview-8009.html',
	"testimonilas-fullview-shortcode"   => 'http://runapptivo.apptivo.com/testimonials-front-end-integration-8013.html',
	"testimonilas-fullview-customcss"   => 'http://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
	"testimonilas-fullview-template"   	=> 'http://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
	"testimonilas-inline-shortcode"   	=> 'http://runapptivo.apptivo.com/testimonials-front-end-integration-8013.html',
	"testimonilas-inline-customcss"   	=> 'http://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
	"testimonilas-inline-template"   	=> 'http://runapptivo.apptivo.com/testimonials-advanced-configuration-8017.html',
	"testimonials-basic-config"         => 'http://runapptivo.apptivo.com/testimonials-basic-setup-8011.html',
	
	"news"               				=> 'http://runapptivo.apptivo.com/news-overview-8288.html',
	"news-fullview-shortcode"   		=> 'http://runapptivo.apptivo.com/news-front-end-integration-8291.html',
	"news-fullview-customcss"   		=> 'http://runapptivo.apptivo.com/news-front-end-integration-8291.html',
	"news-fullview-template"   			=> 'http://runapptivo.apptivo.com/news-advanced-configuration-8293.html',
	"news-inline-shortcode"   			=> 'http://runapptivo.apptivo.com/news-front-end-integration-8291.html',
	"news-inline-customcss"   			=> 'http://runapptivo.apptivo.com/news-front-end-integration-8291.html',
	"news-inline-template"   			=> 'http://runapptivo.apptivo.com/news-advanced-configuration-8293.html',
	"news-basic-config"                 => 'http://runapptivo.apptivo.com/news-basic-setup-8290.html',
		
	"events"               				=> 'http://runapptivo.apptivo.com/events-overview-8031.html',
	"events-fullview-shortcode"   		=> 'http://runapptivo.apptivo.com/events-front-end-integration-8035.html',
	"events-fullview-customcss"   		=> 'http://runapptivo.apptivo.com/events-advanced-configuration-8039.html',
	"events-fullview-template"   		=> 'http://runapptivo.apptivo.com/events-advanced-configuration-8039.html',
	"events-inline-shortcode"   		=> 'http://runapptivo.apptivo.com/events-front-end-integration-8035.html',
	"events-inline-customcss"   		=> 'http://runapptivo.apptivo.com/events-advanced-configuration-8039.htmll',
	"events-inline-template"   			=> 'http://runapptivo.apptivo.com/events-advanced-configuration-8039.html',
	"events-basic-config"               => 'http://runapptivo.apptivo.com/events-overview-8031.html',
	
	"jobs"   							=> 'http://runapptivo.apptivo.com/jobs-overview-8041.html',
	"job-applicant-shortcode"           => 'http://runapptivo.apptivo.com/jobs-front-end-integration-8045.html',
	"job-applicant-template"            => 'http://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
	"job-applicant-customcss"           => 'http://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
	"job-applicant-basicconfig"         => 'http://runapptivo.apptivo.com/jobs-basic-setup-8043.html',
	"job-searchform-shortcode"          => 'http://runapptivo.apptivo.com/jobs-front-end-integration-8045.html',
	"job-searchform-template"           => 'http://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
	"job-searchform-customcss"          => 'http://runapptivo.apptivo.com/jobs-advanced-configuraton-8049.html',
	"job-searchform-basicconfig"        => 'http://runapptivo.apptivo.com/jobs-basic-setup-8043.html'
	
	
	);
	return $developer_guide[$key];
}


function awp_flow_diagram($key='')
{
	$flow_diagrams = array(
	"contactform" 	=> AWP_PLUGIN_BASEURL."/assets/images/contact.jpg",
	"newsletter" 	=> AWP_PLUGIN_BASEURL."/assets/images/newsletter.jpg",
	"news" 		 	=> AWP_PLUGIN_BASEURL."/assets/images/news.jpg",
	"events"  	 	=> AWP_PLUGIN_BASEURL."/assets/images/events.jpg",
	"testimonials"  => AWP_PLUGIN_BASEURL."/assets/images/testimonials.jpg",
	"jobs"   		=> AWP_PLUGIN_BASEURL."/assets/images/jobs.jpg"
	
	);
	return $flow_diagrams[$key];

}

function awp_image($key='')
{
	 $images = array(
	 "submit_button"       => AWP_PLUGIN_BASEURL."/assets/images/submit.jpeg",
	 "news_icon"           => AWP_PLUGIN_BASEURL."/assets/images/news_icon.gif",
	 "events_icon"         => AWP_PLUGIN_BASEURL."/assets/images/events_icon.gif",
	 "testimonials_icon"   => AWP_PLUGIN_BASEURL."/assets/images/testimonials_icon.gif",
	 "jobs_icon"           => AWP_PLUGIN_BASEURL."/assets/images/jobs_icon.jpeg",
	 "edit_icon"           => AWP_PLUGIN_BASEURL."/assets/images/edit.jpeg",
	 "delete_icon"         => AWP_PLUGIN_BASEURL."/assets/images/del.jpeg"
	 );
	 return $images[$key];	
}

?>