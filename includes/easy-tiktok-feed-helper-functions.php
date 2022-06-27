<?php
/*
* Stop execution if someone tried to get file directly.
*/ 
if ( ! defined( 'ABSPATH' ) ) exit;


								//======================================================================
													// Helpers funtions for Easy Tiktok Feed
								//======================================================================

/*
* Get Human readable number for followers
*/
if(!function_exists('etf_readable_count')){

	function etf_readable_count($etf_number){

	$n_format = '';	$suffix ='';

	if ($etf_number > 0 && $etf_number < 1000) {
		// 1 - 999
		$n_format = floor($etf_number);
		$suffix = '';
	} else if ($etf_number >= 1000 && $etf_number < 1000000) {
		// 1k-999k
		$n_format = floor($etf_number / 1000);
		$suffix = 'K';
	} else if ($etf_number >= 1000000 && $etf_number < 1000000000) {
		// 1m-999m
		$n_format = floor($etf_number / 1000000);
		$suffix = 'M';
	} else if ($etf_number >= 1000000000 && $etf_number < 1000000000000) {
		// 1b-999b
		$n_format = floor($etf_number / 1000000000);
		$suffix = 'B';
	} else if ($etf_number >= 1000000000000) {
		// 1t
		$n_format = floor($etf_number / 1000000000000);
		$suffix = 'T';
	}

	return !empty($n_format . $suffix) ? $n_format . $suffix : 0;

	}	
}


/*
* Convert hashtag text to tiktok hashtag link
*/
if(!function_exists('etf_hastags_to_link')){

	function etf_hastags_to_link($etf_hashtag_text){
		
		return preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<a href="https://www.tiktok.com/tag/\2" class="etf-hashtag-link" target="_blank">#\2</a>', $etf_hashtag_text);
	}
}

/*
* Convert time to human readable
*/
if(!function_exists('etf_time_ago')){ 

	function etf_time_ago( $date, $granularity = 2 ) {

		//Preparing strings to translate
		$date_time_strings = array("second" => __('second', 'easy-tiktok-feed'), 
								   "seconds" =>  __('seconds', 'easy-tiktok-feed'), 
								   "minute" => __('minute', 'easy-tiktok-feed'), 
								   "minutes" => __('minutes', 'easy-tiktok-feed'), 
								   "hour" => __('hour', 'easy-tiktok-feed'), 
								   "hours" => __('hours', 'easy-tiktok-feed'), 
								   "day" => __('day', 'easy-tiktok-feed'), 
								   "days" => __('days', 'easy-tiktok-feed'),
								   "week" => __('week', 'easy-tiktok-feed'),
								   "weeks" => __('weeks', 'easy-tiktok-feed'), 
								   "month"  => __('month', 'easy-tiktok-feed'), 
								   "months"  => __('months', 'easy-tiktok-feed'), 
								   "year" => __('year', 'easy-tiktok-feed'),  
								   "years" => __('years', 'easy-tiktok-feed'),
								   "decade" => __('decade', 'easy-tiktok-feed'),
								   );
		
		$ago_text = __('ago', 'easy-tiktok-feed');

		$date = date("Y-m-d\TH:i:s\Z",$date);

		$date = strtotime($date);

		$difference = time() - $date;

		$retval = '';

		$periods = array('decade' => 315360000,
			'year' => 31536000,
			'month' => 2628000,
			'week' => 604800, 
			'day' => 86400,
			'hour' => 3600,
			'minute' => 60,
			'second' => 1);
	
		foreach ($periods as $key => $value) {
			if ($difference >= $value) {
				$time = floor($difference/$value);
				$difference %= $value;
				$retval .= ($retval ? ' ' : '').$time.' ';
				$retval .= (($time > 1) ? $date_time_strings[$key.'s'] : $date_time_strings[$key] );
				$granularity--;
			}
			if ($granularity == '0') { break; }
		}
		 
		return ''.$retval.' '.$ago_text;      
	}
}
