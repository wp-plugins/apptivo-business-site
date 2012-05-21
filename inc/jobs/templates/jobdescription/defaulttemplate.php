<?php
/*
 Template Name:Default Template
 Template Type: Shortcode
 */
?>
  <div class="content_cnt">
        
          <div class="cnt_mdl">
             
              <div class="job_type">
            
              <?php echo $jobDetail->jobDescription; ?>
              </div>

              <div style="width:100px;margin:0 auto;">
           <form action="<?php echo get_permalink( $applicantpageUrl ); ?>" method="post">
                <input id="jobNo" name="jobNo" type="hidden" value="<?php echo $jobDetail->jobNumber;?>">
                <input id="jobId" name="jobId" type="hidden" value="<?php echo $jobDetail->jobId;?>">
                <input id="jobName" name="jobName" type="hidden" value="<?php echo $jobDetail->jobTitle;?>">
                <input title="Apply For this job" alt="Apply For this job" name="applyjobs" id="applyjobs" type="<?php echo $jobs_settings['submit_type']; ?>"  <?php echo $value;?> <?php echo $imageSrc; ?>> 
            </form> 

          </div>
          </div>
          <div class="cnt_bottom">
          </div>
        </div>