<?php
/*
Template Name: Name, Testimonials
Template Type: Shortcode
*/
$awp_all_testimonials = $awp_testimonials['alltestimonials'];
if( $awp_testimonials[custom_css] != '' )
{
	$css='<style type="text/css">'.$awp_testimonials[custom_css].'</style>';
}
?>
<div id="sfstest-page">
<?php 
foreach($awp_all_testimonials as $Tesimonials)
{ 
$testimonialStatus=$Tesimonials->testimonialStatus;
if($testimonialStatus=="APPROVED")
{
$Name = $Tesimonials->account->accountName;
$JobTitle = $Tesimonials->contact->jobTitle;
$companyName = $Tesimonials->contact->companyName;
$website = $Tesimonials->account->website;
$imgSrc = $Tesimonials->testimonialImageUrl;
$seperator = ',';
$testimonial = $Tesimonials->testimonial;
?>
             <div class="testimonial_title_text"><?php echo $Name; ?></div>
             <div class="testimonial_description_text">
             <?php echo $testimonial; ?>
             </div>
             <div align="left" class="bdr"></div>
             
<?php }
}
?>
</div>
<?php echo $css; ?>            