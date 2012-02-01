<?php
/*
 Template Name:Default Template
 Template Type: Shortcode
 */ 
$form = $jobsearchform;
$form_Fields = $jobsearchform['fields'];
$allJobs = $JobSearchResults;
if( $jobsearchform[css] != '' )
{
	echo $css='<style type="text/css">'.$jobsearchform[css].'</style>';
}
if( (!$jobsearchForm_Submit) && ( $result_type == 'widget' || $result_type == '') )  // $display_jobsearchForm is boolean
{ 
?>
<div class="search_main">
<div class="jobsearch_main">

<form id="<?php echo $jobsearchform[name].'_jobsearchforms '; ?>" name="<?php echo $jobsearchform[name].'_jobsearchforms '; ?>" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
<input type="hidden" value="<?php echo $jobsearchform[name]; ?>" name="awp_job_seachformname" id="awp_job_seachformname">
<?php 
foreach( $form_Fields as $formFields)
{
	?>
	<?php 
	if($formFields['type'] == 'text')
	{ 
		?>
		 <div class="text_bg job_keywords">
                 <div class="label"> <span class="absp_jobsearch_label"><?php echo $formFields['showtext']; ?></span> </div>
                 <div class="field" ><input type="text" border="0" value="Keyword" class="absp_jobsearch_input_text" onclick="if(this.value=='Keyword') this.value='';" onblur="if(this.value=='') this.value='Keyword';" name="<?php echo $formFields['fieldid'];?>" id="<?php echo $formFields['fieldid']; ?>"></div>
         </div>
             <?php } ?>
     <?php 
      $optionvalues = $formFields['options']; 
     if($optionvalues != '') { 
     if($formFields['fieldid'] == 'customfield1')
     {

     ?>
      <div  class="text_bg job_industry">
                 <div class="label"> <span class="absp_jobsearch_label"><?php echo $formFields['showtext']; ?> </span> </div>
                 <div class="field">
                   <select class="absp_jobsearch_select" value="" name="customfield1" id="customfield1"  border="0" style="border: 0px none; width: 168px; height: 21px; margin-top: 2px;">
                   <option selected="selected" value="All" style="">Select  <?php echo $formFields['showtext'];?></option>
                   <?php foreach($optionvalues as $opt_val)
                   { 
                   	$option_arr = explode('::',$opt_val);
                    ?>                       
                        <option value="<?php echo $option_arr[0]; ?>" style=""><?php echo $option_arr[1]; ?></option>
                    <?php } ?>
                        </select>
                 </div>
             </div>
             
     <?php } else if($formFields['fieldid'] == 'customfield2')
     { 

     ?>        
    <?php  if( $formFields['type'] == 'select')
    {   
    
     ?>  
     <div class="text_bg jobtype_drop">
                 <div class="label"> <span class="absp_jobsearch_label"><?php echo $formFields['showtext']; ?> </span> </div>
                 <div class="field">
                   <select class="absp_jobsearch_select" value="" name="<?php echo $formFields['fieldid']; ?>" id="<?php echo $formFields['fieldid']; ?>"  border="0" style="border: 0px none; width: 168px; height: 21px; margin-top: 2px;">
                   <option value="" style="">Select  <?php echo $formFields['showtext'];?></option>
                   <?php foreach($optionvalues as $opt_val)
                   {   $opt_value = strtoupper(trim($opt_val)); 
                       $opt_value = str_replace(" ","_",$opt_value);
     				?>                       
                        <option value="<?php echo $opt_value; ?>" style=""><?php echo $opt_val; ?></option>
                    <?php } ?>
                        </select>
                 </div>
             </div>
             
    <?php } else if( $formFields['type'] == 'checkbox' ) {?> 
    
      <div class="text_bg jobtype_chk">
                 <div class="label"> <span class="absp_jobsearch_label"><?php echo $formFields['showtext']; ?> </span> </div>
                 <div class="field">
                    <?php foreach($optionvalues as $opt_val)
                   {   $opt_value = strtoupper(trim($opt_val)); 
                       $opt_value = str_replace(" ","_",$opt_value);?>                       
                        
                        <input class="absp_jobsearch_input_checkbox" value="<?php echo $opt_value; ?>" type="checkbox" name="<?php echo $formFields['fieldid'].'[]';?>" /> &nbsp;&nbsp;<label><?php echo $opt_val; ?> </label><br />
                    <?php } ?>
                  </div>
             </div>

       <?php }  else if( $formFields['type'] == 'radio' ) {?> 
    
      <div  class="text_bg jobtype_radio">
                 <div class="label"> <span class="absp_jobsearch_label"><?php echo $formFields['showtext']; ?> </span> </div>
                 <div class="field">
                    <?php foreach($optionvalues as $opt_val)
                   { ?>                       
                        <option value="<?php echo $opt_val; ?>" style=""><?php echo $opt_val; ?></option>
                        <input class="absp_jobsearch_input_radio" type="radio" name="<?php echo $formFields['fieldid'];?>" />&nbsp;&nbsp;<label><?php echo $opt_val; ?></label><br />
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
      $html = '<div class="jobsrch_submit"><input type="'.$jobsearchform[submit_button_type].'" class="absp_jobsearch_button_submit submit" '.$button_value.' name="awp_jobsearchform_submit_'.$jobsearchform[name].'"  id="awp_jobsearchform_submit_'.$jobsearchform[name].'" /></div>';
      echo $html;
?>
</form>
  </div>
</div>
<?php } else { 
		
	?>
<div class="cnt_srch">
<?php 
if($allJobs['0'] != '')
{
                $count = count($allJobs);
                for($i=0;$i<$count;$i++){
                ?>
                  <p><a title="<?php echo $allJobs[$i]->jobTitle; ?>" href="<?php echo add_query_arg('vacancyno', $allJobs[$i]->jobNumber, get_permalink($target_pageid));?>"><b><?php echo $allJobs[$i]->jobTitle; ?></b></a></p>
                  <p class="readmore"><?php echo substr(strip_tags($allJobs[$i]->jobDescription),0,280) ?>... <a title="<?php echo $allJobs[$i]->jobTitle; ?>" href="<?php echo add_query_arg('vacancyno', $allJobs[$i]->jobNumber, get_permalink($target_pageid));?>">Read More..</a></p>
                 <?php } 
 }else {
                 echo 'No jobs are found with the selected keywords. Please modify your search and try again';
                   
              }
  ?>
</div>
<?php 
}
    
?>