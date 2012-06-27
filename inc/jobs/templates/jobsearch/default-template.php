<?php
/*
 Template Name:Default Template
 Template Type: Widget
 */ 
echo $before_widget;
$form = $jobsearchform;
$form_Fields = $jobsearchform['fields'];
$targetUrl = $form['target_pageurl'];
echo '<style type="text/css">
.awp_searchform_submit {
margin-top:10px;
}
</style>';
?>

<?php if(!isset($JobSearchResults))
{ ?>
<?php if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title; ?>
<div class="search_main">
<div class="jobsearch_main">
<form id="<?php echo $jobsearchform[name].'_jobsearchforms '; ?>" name="<?php echo $jobsearchform[name].'_jobsearchforms '; ?>" action="<? echo get_permalink( $action );  ?>" method="post">
<input type="hidden" value="<?php echo $jobsearchform[name]; ?>" name="job_seachformname_widget" id="job_seachformname_widget">
<input type="hidden" value="<?php echo $maxcnt; ?>" name="maxcnt" id="maxcnt">


<?php 
foreach( $form_Fields as $formFields)
{
	?>
	<?php 
	if($formFields['type'] == 'text')
	{ 
		?>
		
		 <div class="awp_searchform_key">
                 <div class="awp_searchform_whole">
                   <div class="awp_searchform_heading"><span class="absp_jobsearch_label awp_searchform_span"><?php echo $formFields['showtext']; ?></span></div>
                     <div class="awp_searchform_type_fields">
                 <input class="absp_jobsearch_input_text" type="text" border="0" value="Keyword" onclick="if(this.value=='Keyword') this.value='';" onblur="if(this.value=='') this.value='Keyword';" name="<?php echo $formFields['fieldid'];?>" id="<?php echo $formFields['fieldid']; ?>">
                 </div>
                 </div>
                </div>
             <?php } ?>
     <?php 
       $optionvalues = $formFields['options'];
     if( $optionvalues != '' ) {
     if($formFields['fieldid'] == 'customfield1')
     {

     ?>
      <div class="awp_searchform_ind">
                 <div class="awp_searchform_whole">
                     <div class="awp_searchform_heading"><span class="absp_jobsearch_label awp_searchform_span"><?php echo $formFields['showtext']; ?> </span></div>
                      <div class="awp_searchform_type_fields">
                        <select class="absp_jobsearch_select" name="customfield1" id="customfield1">
                   <option selected="selected" value="All" style="">Select <?php echo $formFields['showtext'];?> </option>
                        <?php foreach($optionvalues as $opt_val)
                   { 
                   	$option_arr = explode('::',$opt_val);
                    ?>                       
                        <option value="<?php echo $option_arr[0]; ?>" style=""><?php echo $option_arr[1]; ?></option>
                    <?php } ?>
                        </select> 
                     </div>
                 </div>
             </div>
             
     <?php } else if($formFields['fieldid'] == 'customfield2')
     { 

     ?>        
    <?php  if( $formFields['type'] == 'select')
    {   
    
     ?>  
        <div class="awp_searchform_jobtype">
                 <div class="awp_searchform_whole">
                     <div class="awp_searchform_heading"><span class="absp_jobsearch_label awp_searchform_span"><?php echo $formFields['showtext']; ?> </span></div>
                     <div class="awp_searchform_type_fields">
                     <select  class="absp_jobsearch_select" name="<?php echo $formFields['fieldid']; ?>" id="<?php echo $formFields['fieldid']; ?>"  border="0" style="border: 0px none; width: 168px; height: 21px; margin-top: 2px;">
                   <option value="" >Select <?php echo $formFields['showtext'];?></option>
                   <?php foreach($optionvalues as $opt_val)
                   {   $opt_value = strtoupper(trim($opt_val)); 
                       $opt_value = str_replace(" ","_",$opt_value);
     				?>                       
                        <option value="<?php echo $opt_value; ?>" style=""><?php echo $opt_val; ?></option>
                    <?php } ?>
                        </select>
                     </div>
                 </div>
             </div>
             
    <?php } else if( $formFields['type'] == 'checkbox' ) {?> 
    
            <div class="awp_searchform_jobtype">
                 <div class="awp_searchform_whole">
                     <div class="awp_searchform_heading">
                     <span class="absp_jobsearch_label awp_searchform_span"><?php echo $formFields['showtext']; ?></span>
                     </div>
                    <?php foreach($optionvalues as $opt_val)
                   {   $opt_value = strtoupper(trim($opt_val)); 
                       $opt_value = str_replace(" ","_",$opt_value);?>                       
                 <div class="awp_searchform_type_fields">
                        <label class="awp_searchform_lbl"><?php echo $opt_val; ?>&nbsp;&nbsp; </label>
                        <input class="absp_jobsearch_input_checkbox" value="<?php echo $opt_value; ?>" type="checkbox" name="<?php echo $formFields['fieldid'].'[]';?>" />
                 </div>
                     <?php } ?>
                  </div>
             </div>

       <?php }  else if( $formFields['type'] == 'radio' ) {?> 
    
      <div class="awp_searchform_jobtype">
                 <div class="awp_searchform_whole">
                     <div class="awp_searchform_heading"><span class="absp_jobsearch_label awp_searchform_span"><?php echo $formFields['showtext']; ?> </span></div>
                   <?php foreach($optionvalues as $opt_val)
                   { ?>                       
                        <option value="<?php echo $opt_val; ?>" style=""><?php echo $opt_val; ?></option>
                        <div class="awp_searchform_type_fields">
                        <label class="awp_searchform_lbl"><?php echo $opt_val; ?>&nbsp;&nbsp; </label>
                        <input class="absp_jobsearch_input_radio" type="radio" name="<?php echo $formFields['fieldid'];?>" />
                        </div>
                        <?php } ?>
                  </div>
             </div>

       <?php }
     } 
       }?>       
            
	<?php }
	if($jobsearchform[submit_button_type]=="submit" &&($jobsearchform[submit_button_val])!=""){
        $button_value = 'value="'.$jobsearchform[submit_button_val].'"';
      }
      else{
      	
      	if($hrjobsform[submit_button_val] == '' || empty($hrjobsform[submit_button_val])) :
      		$hrjobsform[submit_button_val] = awp_image('submit_button');
      	endif;
      	
         $button_value = 'src="'.$jobsearchform[submit_button_val].'"';
      }
      $html = '<div class="awp_searchform_submit" ><input type="'.$jobsearchform[submit_button_type].'" class="absp_jobsearch_button_submit awp_jobsearchform_submit_'.$jobsearchform[name].'" '.$button_value.' name="awp_jobsearchform_submit_'.$jobsearchform[name].'"  id="awp_jobsearchform_submit_'.$jobsearchform[name].'" /></div>';
      echo $html;
?>
</form>
  </div>
</div>
<?php } 
echo $after_widget;
?>