<?php
/*
 Template Name:Description with type
 Template Type: Shortcode
 */ 
?>
		  <div class="content_cnt">
          <div class="cnt_top job_title">
            <p><b>Job Title : </b><span> <?php echo $jobDetail->jobTitle; ?></span></p>
          </div>
          <div class="cnt_mdl">
             
              <div class="job_type">
              <div class="job_type1">
               <p><b>Job Type : </b><span> <?php echo $jobDetail->jobTypeName; ?></span></p>
              </div>             
              <b>Job Description:</b><br />
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