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
include_once('tcr_tweeter/twitteroauth.php');

/* Register Actions this is what triggers the post */
add_action('publish_post', 'tcr_tweet_onpublish');

/* the function */
function tcr_tweet_onpublish($postID)
{

	if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
	        /* get the post that's being published */
	        $post = get_post($postID); 
	        $post_title = $post->post_title;
	        
	        /* get the author of the post */
	        $author_id=$post->post_author;
	        /* author needs a twitterid in their meta data*/
	        $author = get_the_author_meta('twitterid',$author_id );
	        
	        /* get the permalink and shorten it */
	        $url = get_permalink($postID);
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
	        $message =  "New: ".$post_title." - ".$short_url." by @".$author." #hashtag";

		 if ( $post->post_status != 'publish' ) return;
	        //call the tweet function to tweet out the message
	        goTweet($message);
        }
}

/* do the tweet */
function goTweet($message) {
        $connection = new TwitterOAuth(
                'key', 
                'key', 
                'key', 
                'key');
        $connection->get('account/verify_credentials');
        $connection->post('statuses/update',array('status' => $message));
}


?>
