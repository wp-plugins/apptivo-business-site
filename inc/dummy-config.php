<?php
/**Default new,Events,Newsletter configuration Files*/

function dummy_testimonials()
{
	$default_testimonials[] =new stdClass();	
	$default_testimonials[0]->account->accountName = 'Your Customer Name Here';
	$default_testimonials[0]->testimonial = 'This is where a customer can say something great about their experience with your company!';
	$default_testimonials[0]->publishedAt = date('d,M,Y');
	$default_testimonials[0]->readmoretext = 'Readmore...';
	$default_testimonials[0]->readmorelink = get_permalink(get_option('awp_testimonials_pageid'));
		
	return $default_testimonials;
}

function dummy_news()
{
	$default_news[] =new stdClass();	
	$default_news[0]->newsHeadLine = 'Your Business News';
	$default_news[0]->description = 'Replace this with a great news of your business!';
	$default_news[0]->publishedAt = date('d,M,Y');
	$default_news[0]->readmoretext = 'Readmore...';
	$default_news[0]->readmorelink = get_permalink(get_option('awp_news_pageid'));
		
	return $default_news;
}

function dummy_events()
{
	$default_events[] =new stdClass();	
	$default_events[0]->eventName = 'Your Business Events';
	$default_events[0]->description = 'Replace this with a great events of your business!';
	$default_events[0]->publishedAt = date('M-d-Y');
	$default_events[0]->publishedBy = 'Admin';
	$default_events[0]->readmoretext = 'Readmore...';
	$default_events[0]->readmorelink = get_permalink(get_option('awp_events_pageid'));
	
	return $default_events;
}