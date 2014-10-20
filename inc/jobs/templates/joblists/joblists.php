<?php
/*
 Template Name: Job List
 Template Type: Shortcode
 */
?>
<div class="list">
<?php
if($allJobs['0'] != '')
{
                $count = count($allJobs);
                ?><ul><?php 
                for($i=0;$i<$count;$i++){
                ?> 
                 <li><a title="<?php echo $allJobs[$i]->jobTitle; ?>" href="<?php echo add_query_arg('vacancyno', $allJobs[$i]->jobNumber, get_permalink($target_pageid)); ?>" ><?php echo $allJobs[$i]->jobTitle; ?></a></li>
                 <?php } ?></ul>
 <?php }else {     
  					
                    echo 'No jobs are found';
                   
              }
             
  ?>
</div>