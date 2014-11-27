<?php 
/**
 * Apptivo Business Site CRM Configuration
 * @package Apptivo Business Site CRM
*/
define('AWP_DEFAULT_ITEM_SHOW',5);
define('AWP_DEFAULT_MORE_TEXT','More..');
define("AWP_SAVE_CONTACT",1);
//Disable Plugins
//define('AWP_CONTACTFORM_DISABLE',1);
//define('AWP_NEWSLETTER_DISABLE',1);
//define('AWP_NEWS_DISABLE',1);
//define('AWP_EVENTS_DISABLE',1);
//define('AWP_TESTIMONIALS_DISABLE',1);
//define('AWP_JOBS_DISABLE',1);
//define('AWP_CASES_DISABLE',1);
/*
 User updateable define statements ends here..
 Changing define statements below will make plugin to not work properly.
 * */
// Site Url
define('SITE_URL', site_url());
//Plugin Version
define('AWP_VERSION', '1.2.4');

//Plugin folders
define('AWP_LIB_DIR', AWP_PLUGIN_BASEPATH . '/lib');
define('AWP_ASSETS_DIR', AWP_PLUGIN_BASEPATH . '/assets');
define('AWP_INC_DIR', AWP_PLUGIN_BASEPATH . '/inc');
define('AWP_PLUGINS_DIR', AWP_LIB_DIR . '/Plugin');
define('AWP_WIDGETS_DIR', AWP_LIB_DIR . '/widgets');

//plugin template folder
define('AWP_CONTACTFORM_TEMPLATEPATH',AWP_INC_DIR.'/contact-forms/templates');
define('AWP_CASES_TEMPLATEPATH',AWP_INC_DIR.'/cases/templates');
define('AWP_NEWSLETTER_TEMPLATEPATH',AWP_INC_DIR.'/newsletter/templates');
define('AWP_NEWS_TEMPLATEPATH',AWP_INC_DIR.'/news/templates');
define('AWP_EVENTS_TEMPLATEPATH',AWP_INC_DIR.'/events/templates');
define('AWP_TESTIMONIALS_TEMPLATEPATH',AWP_INC_DIR.'/testimonials/templates');
define('AWP_TESTIMONIALS_FORM_TEMPLATEPATH',AWP_INC_DIR.'/testimonials/templates/frontend');
define('AWP_JOBSFORM_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobapplicant');
define('AWP_JOBSEARCHFORM_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobsearch');
define('AWP_JOBDESCRIPTION_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobdescription');
define('AWP_JOBLISTS_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/joblists');

//Default Template
define('AWP_EVENTS_DEFAULT_TEMPLATE','default-events.php');
define('AWP_NEWS_DEFAULT_TEMPLATE','default-news.php');
define('AWP_TESTIMONIALS_DEFAULT_TEMPLATE','default-testimonials.php');
define('AWP_NEWSLETTER_WIDGET_DEFAULT_TEMPLATE','widget-default-template-usphone.php');
//Apptivo API URL's
//Dont change this unless specified, changing to incorrect values will make plugins to not work properly.

define('APPTIVO_API_URL','https://api.apptivo.com/');

define('APPTIVO_BUSINESS_SERVICES', APPTIVO_API_URL.'app/services/v1/BusinessSiteServices?wsdl');
define('APPTIVO_BUSINESS_INDEX', APPTIVO_API_URL.'ts/services/AppJobWebService?wsdl');

define('APPTIVO_LEAD_SOURCE_API',APPTIVO_API_URL.'app/dao/lead');
define('APPTIVO_LEAD_API', APPTIVO_API_URL.'app/dao/leads');
define('APPTIVO_CASES_API', APPTIVO_API_URL. 'app/dao/case');
define('APPTIVO_CUSTOMER_API', APPTIVO_API_URL. 'app/dao/customers');
define('APPTIVO_CONTACTS_API', APPTIVO_API_URL. 'app/dao/contacts');
define('APPTIVO_NOTES_API', APPTIVO_API_URL.'app/dao/note');
define('APPTIVO_TESTIMONIALS_STATUS_API', APPTIVO_API_URL. 'app/dao/testimonial');
define('APPTIVO_TARGETS_API',APPTIVO_API_URL.'app/dao/targets');
define('APPTIVO_SIGNUP_API',APPTIVO_API_URL.'app/dao/signup');

define('APPTIVO_LEAD_OBJECT_ID','4');
define('APPTIVO_CASES_OBJECT_ID','59');
define('APPTIVO_CUSTOMER_OBJECT_ID','3');
define('APPTIVO_CONTACT_OBJECT_ID','2');
define('APPTIVO_EMPLOYEE_OBJECT_ID','8');
define('APPTIVO_TEAM_OBJECT_ID','91');
define('APPTIVO_JOBS_OBJECT_ID','135');
define('APPTIVO_NOTE_OBJECT_ID','19');