<?php
/**
 * @package Twemote
 * @author Akash Chauhan <akash6190 [at] gmail [dot] com>
 * @copyright Hackersquad (Refer README for details)
 * @description Fetches the latest direct messages to the user's twitter account and calls the TwemoteManager to handle the command.
 * TODO: 
 *	Automate the installation process for the user...
 *	Security Issues need to work upon (have to apply OAuth)
 */
require_once(__DIR__.'/common.php');

$access_token=TWITTER_ACCESS_TOKEN;
$access_token_secret=TWITTER_ACCESS_TOKEN_SECRET;
$last_id=get_cache_config('TWITTER_LAST_ID');

$yql_query_url = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20twitter.directmessages%20where%20oauth_consumer_secret%3D'IpU0jdaH2FE6nrVaDct9qt0MzJHkzHtk0ZIYkSQYW4'%20and%20oauth_consumer_key%3D'CHxTd18Ft3saD9oR5NhgOQ'%20and%20oauth_token%3D'".$access_token."'%20and%20oauth_token_secret%3D'".$access_token_secret."'%20and%20since_id%3D'".$last_id."'%3B&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";


$session = curl_init();
curl_setopt($session, CURLOPT_URL, "$yql_query_url");
curl_setopt ($session ,CURLOPT_SSL_VERIFYPEER,FALSE);
curl_setopt ($session , CURLOPT_RETURNTRANSFER, TRUE);
$json = curl_exec($session);
$queryReply=json_decode($json,true);


$tweetMessage = $queryReply['query']['results']; 
if(!is_null($tweetMessage))
{
	$newlastid = "";
	$newtext = "";
	$directMessage=$tweetMessage['direct-messages'];
	if(array_key_exists('direct_message',$directMessage)){
	$directMessage=$directMessage['direct_message'];
	require_once(__DIR__.'/TwemoteManager.php');
	if(!array_key_exists('id',$directMessage))
	{
		foreach($tweetMessage['direct-messages']['direct_message'] as $value)
		{
			$newlastid = $value['id'];
			$newtext = $value['text'];
			$obj = new TwemoteManager($newtext);
			$obj->run();
			
		}
		save_config('TWITTER_LAST_ID',$newlastid);
	}
	else 
	{
		$newlastid = $directMessage['id'];
		$newtext = $directMessage['text'];
		$obj = new TwemoteManager($newtext);
		$obj->run();
		save_config('TWITTER_LAST_ID',$newlastid);
	}
	}
	
}
?>
