<?php
/*
 Plugin Name: TCR Tweeter
Description: Automatically tweets new post titles and links to a Twitter account by @author.
License: GPL
Version: 1.0.1
Plugin URI: http://thecellarroom.net
Author: The Cellar Room Limited
Author URI: http://www.thecellarroom.net
Copyright (c) 2013 The Cellar Room Limited
*/

/* include twitteroauth.php */
/* you can get my copy from github.com/kutf/twitter_auth/
 */

include_once('tcr_tweeter/twitteroauth.php');

/* Register Actions this is what triggers the post */
add_action('new_to_publish', 'tcr_tweeter', 10, 1);
add_action('draft_to_publish', 'tcr_tweeter', 10, 1);
add_action('auto-draft_to_publish', 'tcr_tweeter', 10, 1);
add_action('pending_to_publish', 'tcr_tweeter', 10, 1);
add_action('future_to_publish', 'tcr_future', 10, 1);

/* the function */
function tcr_tweeter($post_id)
{
    $post= get_post($post_id);

    if ($post->post_type == 'post' && $post->post_status == 'publish' ) {

		/* get the post that's being published */
		$post_title = $post->post_title;
		 
		/* author needs a twitterid in their meta data*/
		$author = get_the_author_meta('twitterid',$post->post_author);
		 
		/* get the permalink */
		$url = get_permalink($post_id);
		 
		/* and shorten it */
		$short_url = getBitlyUrl($url);
		 
		//check to make sure the tweet is within the 140 char limit
		//if not, shorten and place ellipsis and leave room for link.
		if (strlen($post_title) + strlen($short_url) > 100) {
			$total_len = strlen($post_title) + strlen($short_url);
			$over_flow_count = $total_len - 100;
			$post_title = substr($post_title,0,strlen($post_title) - $over_flow_count - 3);
			$post_title .= '...';
		}

		//add in the shortened bit.ly link
		$message =  "New: ".$post_title." - ".$short_url." by @".$author." #Chelsea #CFC";

		//call the tweet function to tweet out the message
		goTweet($message);
		
		//call the mail function to mail out the message
		tcr_email($message);

	}
}



function tcr_future($post_id)
{
    $post= get_post($post_id);

    if ($post->post_type == 'post' && $post->post_status == 'future') {

		/* get the post that's being published */
		$post_title = $post->post_title;
		 
		/* author needs a twitterid in their meta data*/
		$author = get_the_author_meta('twitterid',$post->post_author);
		 
		/* get the permalink */
		$url = get_permalink($post_id);
		 
		/* and shorten it */
		$short_url = getBitlyUrl($url);
		 
		//check to make sure the tweet is within the 140 char limit
		//if not, shorten and place ellipsis and leave room for link.
		if (strlen($post_title) + strlen($short_url) > 100) {
			$total_len = strlen($post_title) + strlen($short_url);
			$over_flow_count = $total_len - 100;
			$post_title = substr($post_title,0,strlen($post_title) - $over_flow_count - 3);
			$post_title .= '...';
		}

		//add in the shortened bit.ly link
		$message =  "New: ".$post_title." - ".$short_url." by @".$author." #Chelsea #CFC";

		//call the tweet function to tweet out the message
		goTweet($message);
		
		//call the mail function to mail out the message
		tcr_email('future'.$message);

	}
}



/* do the tweet */
function goTweet($message) {
	$connection = new TwitterOAuth(
			'xxx',
			'xxx',
			'xx-xx',
			'xxx');
	$connection->get('account/verify_credentials');
	$connection->post('statuses/update',array('status' => $message));
}

/* do the email */
function tcr_email($message){
  $email = 'wordpress@thechels.co.uk';
  $title = get_bloginfo('title');
  $result=wp_mail($email,'New: '.$title, $message );
}
 
?>
