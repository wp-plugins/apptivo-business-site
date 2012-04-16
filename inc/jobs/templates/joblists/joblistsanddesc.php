<?php
/*
 Template Name:job List with Description
 Template Type: Shortcode
 */ 
?>
<div class="cnt_srch">
<?php
if($allJobs['0'] != '')
{
                $count = count($allJobs);
                for($i=0;$i<$count;$i++){
                ?>
                  <p><a title="<?php echo $allJobs[$i]->jobTitle; ?>" href="<?php echo add_query_arg('vacancyno', $allJobs[$i]->jobNumber, get_permalink($target_pageid));?>" ><b><?php echo $allJobs[$i]->jobTitle; ?></b></a></p>
                  <p class="readmore"><?php echo substr(strip_tags($allJobs[$i]->jobDescription),0,280) ?>... <a title="<?php echo $allJobs[$i]->jobTitle; ?>" href="<?php echo add_query_arg('vacancyno', $allJobs[$i]->jobNumber, get_permalink($target_pageid));?>">Read more ..</a></p>
                 <?php } ?>
 

  <?php }else {     
  				  echo 'No jobs are found';                 
              }
             
  ?>
</div>