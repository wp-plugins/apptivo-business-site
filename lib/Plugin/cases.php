<?php
/**
 * Apptivo Cases Apps Plugin
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';
class AWP_Cases extends AWP_Base
{
function &instance()
{
  static $instances = array();
  if (!isset($instances[0])) {
    $class = __CLASS__;
    $instances[0] = & new $class();
   }        
    return $instances[0];
    
}

function __construct()
{
$this->_plugin_activated = false;
$settings=array();
    	$this->_plugin_activated=false;
    	$settings=get_option("awp_plugins");
    	if(get_option("awp_plugins")!=="false"){
    		if($settings["cases"])
    			$this->_plugin_activated=true;
    	}
    	
$this->fields = array(
			array('fieldid' => 'subject','fieldname' => 'Subject','defaulttext' => 'Subject','must_require'=>1,'must'=>1,'showorder' => '1','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'description','fieldname' => 'Description','defaulttext' => 'Description','must_require'=>0,'must'=>0,'showorder' => '2','validation' => 'textarea','fieldtype' => 'textarea'),
			array('fieldid' => 'priority','fieldname' => 'Priority','defaulttext' => 'Priority','must_require'=>1,'must'=>1,'showorder' => '3','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'firstname','fieldname' => 'First Name','defaulttext' => 'First Name','must_require'=>1,'must'=>1,'showorder' => '4','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'lastname','fieldname' => 'Last Name','defaulttext' => 'Last Name','must_require'=>1,'must'=>1,'showorder' => '5','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '6','must_require'=>1,'must'=>1,'validation' => 'email','fieldtype' => 'text'),
			array('fieldid' => 'phone','fieldname' => 'Telephone Number','defaulttext' => 'Telephone Number','must_require'=>0,'must'=>0,'showorder' => '7','validation' => 'phonenumber','fieldtype' => 'text'),
			array('fieldid' => 'type','fieldname' => 'Type','defaulttext' => 'Type','must_require'=>1,'must'=>1,'showorder' => '8','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'captcha','fieldname' => 'Captcha','defaulttext' => 'Captcha','must_require'=>1,'showorder' => '9','validation' => 'text','fieldtype' => 'captcha'),
			array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '10','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '11','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '12','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '13','validation' => '','fieldtype' => 'radio'),
			array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '14','validation' => '','fieldtype' => 'checkbox')          
		);

$this->validations = array(
			array('validationLabel' => 'None','validation' => 'none'),
			array('validationLabel' => 'Email ID','validation' => 'email'),
			array('validationLabel' => 'Number','validation' => 'number')
			);

$this->fieldtypes = array(
			array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
			array('fieldtypeLabel' => 'Radio Option','fieldtype' => 'radio'),
			array('fieldtypeLabel' => 'Select','fieldtype' => 'select'),
			array('fieldtypeLabel' => 'Textbox','fieldtype' => 'text'),
			array('fieldtypeLabel' => 'Textarea','fieldtype' => 'textarea')
			);

}

 /**
     * Runs plugin
     */
function run()
{
  if($this->_plugin_activated){
	    add_shortcode('apptivo_cases','apptivo_business_cases');				
  }
}
    
function settings(){
//Theme Templates
$themetemplates = get_awpTemplates(TEMPLATEPATH.'/cases','Plugin');
$plugintemplates=$this->get_plugin_templates();
arsort($plugintemplates);
	
if(isset($_POST['awp_cases_settings'])):

//Cases Form Propertieds.

//template Type& Template Layout
if($_POST['awp_cases_templatetype']=="awp_plugin_template"):
	$templatelayout=$_POST['awp_cases_plugintemplatelayout'];
else:
	$templatelayout=$_POST['awp_cases_themetemplatelayout'];
endif;

$casesform_properties=array( 'tmpltype' =>$_POST['awp_cases_templatetype'],
                             'layout' =>$templatelayout, 
	                         'confmsg' => stripslashes($_POST['awp_cases_confirmationmsg']),
			                 'confirm_msg_page' => $_POST['awp_cases_confirm_msg_page'],
							 'confirm_msg_pageid' => $_POST['awp_cases_confirmmsg_pageid'],
							 'css' => stripslashes($_POST['awp_cases_customcss']),
                             'submit_button_type' => $_POST['awp_cases_submit_type'],
                             'submit_button_val' => $_POST['awp_cases_submit_value'] );

//New Custom fields 
			$stack = array();
			$addtional_custom = array();
			$addtional_order = 15;
			for($i=6;$i<20;$i++)
			{  
				if(isset($_POST['customfield'.$i.'_newest']) )
				{
					$addtional_custom = array('fieldid' => 'customfield'.$i.'','fieldname' => 'Custom Field '.$i.'',
					                     'defaulttext' => 'Custom Field'.$i.'','showorder' => $addtional_order,'validation' => '',
					                     'fieldtype' => 'select');
					$addtional_order++;
					array_push($stack, $addtional_custom);
				}else {
					break;
				}
			}
			
			if(!empty($stack)) :
			 update_option('awp_addtional_custom_cases',$stack);
			endif;
			
       //General Cases form fields

		//For Additional custom fields.
		$addtional_custom = get_option('awp_addtional_custom_cases');
		$master_field = array();
		if(!empty($addtional_custom)):
		$master_field = array_merge($this->fields,$addtional_custom);
		else:
		$master_field = $this->fields;
		endif;
		
		
			$casesformfields=array();
			foreach( $master_field as $fieldsmasterproperties )
			{
				$enabled=0;
				$contactformfield=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				
                                if(!empty ($_POST[$fieldid.'_order'])){
                                    $displayorder = $_POST[$fieldid.'_order'];
                                }
                                else{
                                    $displayorder = $fieldsmasterproperties['showorder'];
                                }
                                 if(!empty ($_POST[$fieldid.'_text'])){
                                    $displaytext = $_POST[$fieldid.'_text'];
                                }
                                else{
                                    $displaytext = $fieldsmasterproperties['defaulttext'];
                                }
                                 
			                   if($fieldsmasterproperties['must'])
                                {
                                    $enabled = 1;
                                    $required = 1;
                                }
                                else if($fieldid=='captcha')
                                {
                                    $enabled = $_POST[$fieldid.'_show'];
                                    $required = 1;
                                }
                                else
                                {
                                    $enabled = $_POST[$fieldid.'_show'];
                                    $required = $_POST[$fieldid.'_require'];
                                }
				if($enabled){
					$casefield=$this->createformfield_array($fieldid,$displaytext,$required,$_POST[$fieldid.'_type'],$_POST[$fieldid.'_validation'],$_POST[$fieldid.'_options'],$displayorder);
					array_push($casesformfields, $casefield);
				}
			}
			
			$cases_fields_properties = array('properties'=>$casesform_properties,'fields'=>$casesformfields);
			update_option('absp_cases_form_fields',$cases_fields_properties);			
endif;


$absp_cases_fields_properties = get_option('absp_cases_form_fields');
$fields=$absp_cases_fields_properties['fields']; //Cases Fields
$formproperties=$absp_cases_fields_properties['properties'];//Case Properties
	
echo '<div class="wrap"><h2>Apptivo Cases Form</h2></div>';
?>
<?php 
if(!$this->_plugin_activated)
{
	echo "Cases form is currently <span style='color:red'>disabled</span>. Please enable this in <a href='/wp-admin/admin.php?page=awp_general&tab=plugins'>Apptivo General Settings</a>.";
}
echo awp_flow_diagram('cases',1);
?>
<form name="awp_cases_settings_form" method="post" action="">
<table class="form-table">
<tr valign="top">

<?php if(!empty($formproperties[tmpltype])) :?>
				<tr valign="top">
					<th valign="top"><label for="cases_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the cases form.','apptivo-businesssite'); ?></span>
					</th>
					<td valign="top"><span id="awp_cases_shortcode" name="awp_cases_shortcode">
					<input style="width:300px;" type="text" id="cases_shortcode" name="cases_shortcode"  readonly="true" value='[apptivo_cases]' />
					</span>
					</td>
				</tr>
<?php endif; ?>
				
					<th valign="top"><label for="awp_cases_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
					</th>
					<td valign="top">
					<input type="hidden" id="awp_cases_name" name="awp_cases_name" value="<?php echo $selectedcontactform;?>"> 
					
						<select name="awp_cases_templatetype" id="awp_cases_templatetype" onchange="cases_change_template();">
							<option value="awp_plugin_template"  <?php selected($formproperties[tmpltype],'awp_plugin_template'); ?> >Plugin Templates</option>
							<?php if(!empty($themetemplates)) : ?>
							<option value="theme_template"  <?php selected($formproperties[tmpltype],'theme_template'); ?> >Templates from Current Theme</option>
							<?php endif; ?>
						</select>
					
					</td>
				</tr>
				<tr valign="top">
					<th valign="top"><label for="awp_cases_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>					
					</th>
					<td valign="top">
					<?php  if( sizeof($plugintemplates) > 0 ) : ?>
					<select name="awp_cases_plugintemplatelayout" id="awp_cases_plugintemplatelayout" <?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
						<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
							<option value="<?php echo $plugintemplates[$template]?>" <?php selected($formproperties[layout],$plugintemplates[$template]); ?> >
							<?php echo $template?>
							</option>
							<?php }  ?>
					</select> 
					<?php else : echo 'No templates available'; endif;?>
					<select name="awp_cases_themetemplatelayout" id="awp_cases_themetemplatelayout" <?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
						<?php foreach (array_keys( $themetemplates ) as $template ) : ?>
							<option value="<?php echo $themetemplates[$template]?>" <?php selected($formproperties['layout'],$themetemplates[$template]);?> >
							<?php echo $template?>
							</option>
							<?php endforeach;?>
					</select>
					
					</td>
				</tr>
				<tr valign="top">
					<th><label for="awp_cases_customcss"><?php _e("Confirmation message page", 'apptivo-businesssite' ); ?>:</label>
					</th>
					<td valign="top">
                          <input type="radio" value="same"  id="same_page" name="awp_cases_confirm_msg_page" <?php checked('same',$formproperties[confirm_msg_page]); ?> checked="checked" /><label for="same_page"> Same Page</label>
                          <input type="radio" value="other" id="other_page" name="awp_cases_confirm_msg_page" <?php checked('other',$formproperties[confirm_msg_page]); ?>/> <label for="other_page"> Other page</label>
                          <br />
                           <br />
                           <select id="awp_cases_confirmmsg_pageid" name="awp_cases_confirmmsg_pageid" <?php if($formproperties[confirm_msg_page] != 'other') echo 'style="display:none;"';?> >                      
							 <?php
							  $pages = get_pages(); 
							  foreach ($pages as $pagg) {
							  	?>
							  	<option value="<?php echo $pagg->ID; ?>"  <?php selected($pagg->ID, $formproperties[confirm_msg_pageid]); ?> >
								<?php echo $pagg->post_title; ?>
								</option>
							  	<?php 
							  }
							 ?>	
							 </select>
							 
					</td>
					</td>					
				</tr>
				<tr valign="top" id="awp_cases_confirmationmsg_tr" <?php if($formproperties[confirm_msg_page] == 'other') echo 'style="display:none;"';?> >
					<th valign="top"><label for="awp_cases_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description">This message will shown in your website page, once cases form submitted.</span>
					</th>
					<td valign="top">
					<div style="width:620px;">
					<?php the_editor($formproperties[confmsg],'awp_cases_confirmationmsg','',FALSE);  ?>
					</div>
					</td>
				</tr>
				<tr valign="top">
					<th><label for="awp_cases_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description">Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.</span>
					</th>
					<td valign="top"><textarea name="awp_cases_customcss"
							id="awp_cases_customcss" size="100" cols="40" rows="10"><?php echo $formproperties[css];?></textarea>
					</td>
					
				</tr>
                    <tr valign="top">
					<th><label id="awp_cases_submit_type" for="awp_cases_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>

                    <td valign="top">
                       <input type="radio" value="submit" id="submit_button" name="awp_cases_submit_type" <?php checked('submit',$formproperties[submit_button_type]); ?> checked="checked" /> <label for="submit_button">Button</label>
                       <input type="radio" value="image" id="submit_image" name="awp_cases_submit_type"<?php checked('image',$formproperties[submit_button_type]); ?>/><label for="submit_image">Image</label>
					</td>
				</tr>
                <tr valign="top">
					<th><label for="awp_cases_submit_val"  id="awp_cases_submit_val" ><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>
                    <td valign="top"><input type="text" name="awp_cases_submit_value" id="awp_cases_submit_value" value="<?php echo $formproperties[submit_button_val];?>" size="52"/>
                    <span id="upload_img_button" style="display:none;">
                    <input id="cases_upload_image" type="button" value="Upload Image" />
					<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
					</span>
					</td>
				</tr>
</table>

<?php
       //For Additional custom fields.
		$addtional_custom = get_option('awp_addtional_custom_cases');
		$master_field = array();
		if(!empty($addtional_custom)):
		$master_field = array_merge($this->fields,$addtional_custom);
		else:
		$master_field = $this->fields;
		endif;	
?>
<table width="900" cellspacing="0" cellpadding="0" id="cases_form_fields" name="cases_form_fields" style="border-collapse: collapse;">
<br /><h3>Cases Form Fields</h3>
<div style="margin: 10px;">Select and configure list of fields from below table to show in your cases form.</div>
			<tbody>
				<tr>
					<th></th>
				</tr>
				<tr align="center"
					style="background-color: rgb(223, 223, 223); font-weight: bold;"
					class="widefat">
	
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Require','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Order','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite'); ?></td>
					<td align="center" style="width:100px;border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite'); ?></td>
				</tr>
				<tr>
					<th></th>
				</tr>
				<?php
				
				$pos = 0;
	            $index_key = 0;
				foreach( $master_field as $fieldsmasterproperties )
				{   
					$enabled=0;$required=0;
					$fieldExists=array();
					$fieldid=$fieldsmasterproperties['fieldid'];
					
					 if($fieldsmasterproperties['must']) :
					 $enabled =1;
                     $required =1;
                     endif;
                     
                     if($fieldid == 'captcha') :
                     $required = 1;                      
                     endif;

                    if(!empty($fields))
					{
						$fieldExistFlag= awp_recursive_array_search($fields, $fieldid, 'fieldid');	
					}
					
					if(trim($fieldExistFlag)!=="")
					{
						$enabled=1;
						$fieldData=array("fieldid"=>$fieldid,
										 "fieldname"=>$fieldsmasterproperties['fieldname'],
										 "show"=>$enabled,
										 "required"=>$fields[$fieldExistFlag]['required'],
										 "showtext"=>$fields[$fieldExistFlag]['showtext'],
										 "type"=>$fields[$fieldExistFlag]['type'],
						                 "must_require"=> $fieldsmasterproperties['must_require'],
										 "validation"=>$fields[$fieldExistFlag]['validation'],
										 "options"=>$fields[$fieldExistFlag]['options'],
										 "order"=>$fields[$fieldExistFlag]['order']);
					}else{					
						$fieldData=array("fieldid"=>$fieldid,
										 "fieldname"=>$fieldsmasterproperties['fieldname'],
										 "show"=>$enabled,
										 "required"=>$required,
										 "showtext"=>$fieldsmasterproperties['defaulttext'],
										 "type"=> $fieldsmasterproperties['fieldtype'],
						                 "must_require"=> $fieldsmasterproperties['must_require'],
										 "validation"=>"",
										 "options"=>"",
										 "order"=>"");
				
					}
				 $pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
				?>
				<tr >
				    <!--  Field Name -->
					<td
						style="border: 1px solid rgb(204, 204, 204); padding-left: 10px;width:150px;"><?php echo $fieldData['fieldname']?>
						
						<?php if($index_key > 13 ) : ?>
					<input type="hidden" id="<?php echo $fieldData['fieldid']?>_newest" name="<?php echo $fieldData['fieldid']?>_newest" value="dd" />
					<?php endif; $index_key++; ?>	
					
					</td>
					
						
					 <!--  Field To Show -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input
					<?php  if($enabled) { ?> checked="checked"  <?php }  if($fieldsmasterproperties['must']) { ?>  disabled="disabled" <?php }  ?> type="checkbox"  id="<?php echo $fieldData['fieldid']?>_show" name="<?php echo $fieldData['fieldid']?>_show" size="30"
					onclick="casesform_enablefield('<?php echo $fieldData['fieldid']?>')">
					</td> 
					
					 <!--  Field To Require -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input <?php if($fieldData['required'] ) { ?>checked="checked" <?php }?> <?php if(!$enabled || ($fieldData['must_require'])) { ?> disabled="disabled" <?php } ?> type="checkbox"                                        
						id="<?php echo $fieldData['fieldid']?>_require"
						name="<?php echo $fieldData['fieldid']?>_require" size="30"></td>
						
					 <!--  Display Order -->	
<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input type="text" style="text-align:center;" onkeypress="return isNumberKey(event)"  id="<?php echo $fieldData['fieldid']?>_order"
						name="<?php echo $fieldData['fieldid']?>_order"
						value="<?php echo $fieldData['order']; ?>" size="3"
						maxlength="2" <?php if(!$enabled) { ?> disabled="disabled" <?php } ?>></td>
						
					 <!--  Display Text -->		
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					<?php if(!$enabled) { ?> disabled="disabled" <?php } ?>
						type="text" id="<?php echo $fieldData['fieldid']?>_text"
						name="<?php echo $fieldData['fieldid']?>_text"
						value="<?php echo $fieldData['showtext']; ?>"></td>
					
					 <!--  Field Type -->		
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<?php
					$name_postfix="type";
					if($pos===false){
						?>
						<input 
						type="hidden"
						id="<?php echo $fieldData['fieldid']?>_type"
						name="<?php echo $fieldData['fieldid']?>_type"
						value="<?php echo $fieldData['type']; ?>" >
						<input 
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly"
						type="text" id="<?php echo $fieldData['fieldid']?>_typehiddentext"
						name="<?php echo $fieldData['fieldid']?>_typehiddentext"
						value="<?php echo $fieldData['type']; ?>" ><?php
						$name_postfix="type_select";	
					}else{
						?>
					<select name="<?php echo $fieldData['fieldid']?>_type" id="<?php echo $fieldData['fieldid']?>_type" 
					<?php
						
						if($pos===false) {?>readonly="readonly"<?php }
						if(!$enabled || ($pos===false)) { ?> disabled="disabled" <?php } ?>
						onChange="casesform_showoptionstextarea('<?php echo $fieldData['fieldid']?>');"
					>
					<?php foreach( $this->fieldtypes as $masterfieldtypes )
				{ ?>
					
					<option value="<?php echo $masterfieldtypes['fieldtype'];?>" 
					<?php if($masterfieldtypes['fieldtype']==$fieldData['type']){?>
					
					selected="selected"<?php }?>><?php echo $masterfieldtypes[fieldtypeLabel];?></option>
					<?php }?>
					
					</select>
					<?php }
					?>
					</td>
					
					<!-- Validation Type -->
					<td align="center" style="width:100px;border: 1px solid rgb(204, 204, 204);">
					<?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
                                         ?>
                                        <?php if($pos===false){
                                        ?><input
						type="hidden"
						id="<?php echo $fieldData['fieldid']?>_validation"
						name="<?php echo $fieldData['fieldid']?>_validation"
						<?php if($fieldid=="email"){
							?>value="email"
							<?php }else if($fieldid=="phone"){ ?>
							value="phonenumber"
							<?php }else{ ?>
							value="none"
							<?php }?> >
						<input style="width:100px;"
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly"
						type="text" id="<?php echo $fieldData['fieldid']?>_validationhidden"
						name="<?php echo $fieldData['fieldid']?>_validationhidden"
						<?php if($fieldid=="email"){
							?>value="Email Id"
							<?php }else if($fieldid=="phone"){ ?>
							value="Phone Number"
							<?php }else{ ?>
							value="None"
							<?php }?> > <?php
                                        }
                                        else{
                                        	
                                        ?>
                                        <select name="<?php echo $fieldData['fieldid']?>_validation" id="<?php echo $fieldData['fieldid']?>_validation"
					<?php if(!$enabled ) { ?> disabled="disabled" <?php }
						if( ($fieldData['type'] != 'text' && (strtolower($fieldData['validation']) == 'none' || strtolower($fieldData['validation']) == ''))) {?>disabled="disabled"<?php }?>
					>
					<?php foreach( $this->validations as $masterfieldtypes )
                                        { ?>
					<option value="<?php echo $masterfieldtypes['validation'];?>" 
					<?php if($masterfieldtypes['validation']==$fieldData['validation']){?>
					selected="selected"<?php }?>><?php echo $masterfieldtypes[validationLabel];?></option>
					<?php }?>
					</select>
                                         <?php }  ?>
					</td>
					
					<!-- Options Values -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<?php
					if($pos===false){
						echo "N/A";
						//Not a custom field. Dont show any thing
					}else if( $enabled && ( ($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")) ){?>
					<textarea style="width:190px;"
					<?php if(!$enabled){ ?> disabled="disabled" <?php } ?>
						id="<?php echo $fieldData['fieldid']?>_options"
						name="<?php echo $fieldData['fieldid']?>_options" ><?php echo $fieldData['options']; ?></textarea>
					<?php }else {?>
					<textarea
					disabled="disabled" style="display:none;width:190px;"
						id="<?php echo $fieldData['fieldid']?>_options"
						name="<?php echo $fieldData['fieldid']?>_options"  ></textarea>
					<?php }?>
						</td>
				</tr>
				<?php  } ?>
	
			</tbody>
		</table>
		<?php 
		$addtional_custom = get_option('awp_addtional_custom_cases');
		if(empty($addtional_custom))
		{
			$cnt_custom_filed = 6;
		}else {
			$cnt_custom_filed = 6 + count($addtional_custom);
		}
		?>
		<p> <a rel="<?php echo $cnt_custom_filed; ?>" href="javascript:void(0);" id="cases_addcustom_field" name="cases_addcustom_field"  >+Add Another Custom Field</a> </p>
		<p class="submit">
			<input <?php if(!$this->_plugin_activated): echo 'disabled="disabled"'; endif; ?>   type="submit" name="awp_cases_settings" id="awp_cases_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration','apptivo business site') ?>" />
		</p>
		</form>
		
<?php 
}

//GEt Plugin Templates.
function get_plugin_templates()
	{  
		$default_headers = array(
		'Template Name' => 'Template Name'		
	    );
	    $templates = array();	 
		$dir_contact = AWP_CASES_TEMPLATEPATH;
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir_contact)) {
		    if ($dh = opendir($dir_contact)) {
		        while (($file = readdir($dh)) !== false) {
		        	if ( substr( $file, -4 ) == '.php' )
		        	{		        		        	
					$plugin_data = get_file_data( $dir_contact."/".$file, $default_headers, '' );
					if(strlen(trim($plugin_data['Template Name'])) != 0 )
					{
						$templates[$plugin_data['Template Name']] = $file;						
					}
		        	}
		        }
		        
		        closedir($dh);
		    }
		}
		return $templates;                        
	 
}

/**
 * Create field array
*/

function createformfield_array($fieldid,$showtext,$required,$type,$validation,$options,$displayorder){
        
	    $displayorder = (trim($displayorder)=="")?0:trim($displayorder);
				
		$options = (is_array($options))?$options:stripslashes(str_replace( array('"'), '', strip_tags($options)));

		if( trim($type) != 'text' && trim($type) != 'textarea')
		{
			$pos = strpos(trim($fieldid), 'customfield');
			if( $pos !== false )
			{
				if( !is_array($options) && trim($options) == '')
				{	
					return '';
				}
			}
		}   
		$contactformfield= array(
	            'fieldid'=>$fieldid,
                'showtext' => stripslashes(str_replace( array('"'), '', strip_tags($showtext))),
	            'required' => $required,
				'type' => $type,
				'validation' => $validation,
				'options' => $options,
	   			'order' => $displayorder
		);
		return $contactformfield;
}
	
}