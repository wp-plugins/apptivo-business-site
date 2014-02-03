<?php
/*
Template Name:Default Testimonials
Template Type: Shortcode
*/
$awp_all_testimonials = $awp_testimonials['alltestimonials'];
if( $awp_testimonials[custom_css] != '' )
{
	$css='<style type="text/css">'.$awp_testimonials[custom_css].'</style>';
}
echo '<style type="text/css">
.testimonials { display: inline-block; margin-top: 15px; }
.middle {  display: inline-block; }
.middle p { margin-left:8px;}
.absp_testimonials_image { float: left;height: 80px;margin: 3px 10px 4px 12px;width: 78px;border: medium none;}    
p { color: #211B15;font-family: Trebuchet MS,Arial,Helvetica,sans-serif;font-size: 13px;font-style: italic;font-weight: normal;margin-right: 12px;text-align: justify;}
#testimonial0 {border:1px solid #ccc;margin: 10px 0px 10px 0px;}
</style>';

foreach($awp_all_testimonials as $Tesimonials)
{ 
$Name = $Tesimonials->account->accountName;
$JobTitle = $Tesimonials->contact->jobTitle;
$companyName = $Tesimonials->contact->companyName;
$website = $Tesimonials->account->website;
$imgSrc = $Tesimonials->testimonialImageUrl;
$seperator = ',';
$testimonial = $Tesimonials->testimonial;
$testimonialStatus=$Tesimonials->testimonialStatus;
if($testimonialStatus=="APPROVED")
{
    ?> <div id="testimonial0">
                        <div class="testimonials">
                        <div class="top"></div>
                        <div class="middle">
                        <?php if( strlen(trim($imgSrc)) != 0 ) { ?>
                        <img class="absp_testimonials_image" src="<?php echo $imgSrc; ?>"> <?php } ?>                                    
<?php echo $testimonial; ?> 

                    <div style="float: right;">
                        <?php if(strlen(trim($Name)) != 0) { ?><p><?php 
                        echo '<span class="absp_testimonials_name"> Name: '.$Name.'</span>';
                        if(strlen(trim($JobTitle)) != 0 ) {
                        echo '&nbsp;<span class="absp_testimonials_jobtitle">'.$JobTitle.$seperator.'</span>';
                        }
                         if(strlen(trim($companyName)) != 0 && strlen(trim($website)) != 0) {
                        echo '<a href="'.$website.'" target="_blank" class="absp_testimonials_company">'.$companyName.'</a>'; }  ?></p> <?php } ?>
                    </div>
                    </div> 
          <div class="bottom"></div>      
                
                </div>
                <br>
                <br>
             </div>
<?php } ?>
<?php  
}
echo $css;
?>