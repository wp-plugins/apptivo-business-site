<?php
/*
Template Name:Single-Column-Inline-Layout1
Template Type: Shortcode
 */
$awp_all_testimonials = $awp_testimonials['alltestimonials'];
if( $awp_testimonials[custom_css] != '' )
{
	$css='<style type="text/css">'.$awp_testimonials[custom_css].'</style>';
}

echo '<style type="text/css">
.testimonials p{font-family: Arial,Helvetica,sans-serif;font-size: 12px; line-height: 21px;text-align: justify;}
.whl_testimonials .heading{font-family:Arial, Helvetica, sans-serif;font-size:14px;padding-bottom:10px;font-weight:bold;}
.whl_testimonials{padding:10px;border-bottom:1px dashed #666;}
pic_1{display:inline-block;}
</style>';

foreach($awp_all_testimonials as $testimonial)
{
$testimonialStatus=$testimonial->testimonialStatus;
    if($testimonialStatus=="APPROVED")
{
echo '<div class="whl_testimonials">
<div class="testimonials">
<p class="absp_testimonials_description" >'.$testimonial->testimonial.'</p>
</div>';
if(strlen(trim($testimonial->testimonialImageUrl)) != 0 )
{
echo '<div class="pic_1"><img class="absp_testimonials_image" src="'.$testimonial->testimonialImageUrl.'" /></div>';
}
echo '<div class="testimonials">
<p></p>
</div>
</div>
';
}
}

echo $css;
?>