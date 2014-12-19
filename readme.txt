=== Apptivo Business Site CRM ===
Contributors: apptivo, rmohanasundaram, prabhuganapathy
Tags: apptivo, contact forms, crm, newsletters, testimonials
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 1.2.4

Create contact forms, newsletter signups, and customer testimonials, integrated with Apptivo.

== Description ==

The Apptivo Business Site CRM makes it simple to create effective business websites with features that integrate with Apptivo.  Create customized contact forms that work with Apptivo CRM tools, create newsletter signup forms, and manage customer testimonials.

All information is synced directly with your Apptivo small business management account, making it simple to keep your website in sync with your business.

= Plugin's Official Site =

Apptivo Wordpress Plugins ([http://www.apptivo.com/apptivo-business-site-wordpress-plug-in/](http://www.apptivo.com/apptivo-business-site-wordpress-plug-in/))


== Installation ==

1. Upload the extracted archive to `wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Purchase a API key from Apptivo. http://www.apptivo.com/where-to-find-your-apptivo-api-key-apptivo-access-key/
4. Go to the "General Settings" and enter your API Key and Access Key
5. Enjoy!

== Frequently Asked Questions ==

= Is this plugin free? =
No, you'll need to purchase a API key from Apptivo to use this plugin.

= Where do I get a API key? =

You'll need to register for an account at www.apptivo.com. http://www.apptivo.com/where-to-find-your-apptivo-api-key-apptivo-access-key/

= Where is the data stored? =

The plugin settings will be stored in your local Wordpress database, but content will be stored in Apptivo.

= Where to get help =

You can find the complete developer's guide here: http://runapptivo.apptivo.com/apptivo-business-site-developer-guide-7937.html

== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg

== Changelog ==

= 1.2.4 =
* Disabled SSLv3 Support.
* API connection error with PHP Soap Client

= 1.2.3.1 =
* Added new contact form teamplate with Placeholder.

= 1.2.3 =
* Added Lead assignee (employee or team), lead source, rank, status, type for contact forms.
* Added customer and contact association for cases by creating new or existing customer/contact.
* Added Case assignee (employee or team), priority, status, type for Cases. 
* Added double column layout and multiple custom fields for cases
* Added customer association to lead for Contact forms by creating new or existing customer. 
* Bug Fixes on Jobs and Newsletter plugin.

= 1.2.2 =
* added simple captcha
* fixed validation and backend exception

= 1.2.1.2 =
* reCAPTCHA js issue fixed

= 1.2.1.1 =
* Jobs country list issue fixed
* cases type and priority updated based on enable/disable status

= 1.2.1 =
* REST API updated for contact forms and Cases forms
* Google ReCaptcha added
* Added Responsive support forms
* Added web Testimonial forms

= 1.1.2.1 =
* Soap Client values passed as stream context for some php versions throw Fatal exception on accessing webservice. 

= 1.1.2 =
* Fixes for forms validation
* Soft handling of some PHP warnings

= 1.1.1 =
* Fix - Faulty html in custom field options.
* Added hooks for contact form, newsletter, cases and job applicant form.

= 1.1 =
* Updated Plugin to use Apptivo new API Key and Access Key in Firm Business settings.
* Old Plugin users using Site key / Access key from Website App, has to update the keys in Plugin with Business settings API Key / Access Key.
* For more information please check http://www.apptivo.com/where-to-find-your-apptivo-api-key-apptivo-access-key/
* Improved performance of jobs plugin.

= 1.0.1 =
* Jobs Upload functionality Bug fixes

= 1.0 =
* Hot Fix to Jobs Upload features. Older version of plugin has to be updated to this release to continue uploading resumes on Jobs.
* Cases feature added to Plugin. It enables customers to log a case from your website and you can manage it using Apptivo Cases App.
* Other bug fixes.

= 0.7.2 =
* UI bug fixes for IE

= 0.7.1 =
* Security Bug fixes related to Uploadify plugin used for Jobs.

= 0.7 =
* Form Submission IP Restriction
* Custom template API's
* Added Powered by Apptivo options
* Enabled Image Upload functionality
* Bug Fixes

= 0.6.1 =
* Fixes

= 0.6 =
* Disk cache implementation

= 0.5 =
* Plugin released!
