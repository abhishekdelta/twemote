<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @author Akash Chauhan <akash6190 [at] gmail [dot] com>
 * @description Generates the latest cricket scores.
 */
require_once(__DIR__.'/common.php');
class TwemoteCricket
{
	
	function __construct()
	{
	}
	public function input($params){
	}
	public function output()
	{
		$yql_query_url="http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20feed%20where%20url%3D'http%3A%2F%2Fstatic.espncricinfo.com%2Frss%2Flivescores.xml'&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
		$session=curl_init($yql_query_url);
		curl_setopt ($session , CURLOPT_RETURNTRANSFER, TRUE);
		$json = curl_exec($session);
		$queryReply=json_decode($json,true);
		//print_r($queryReply);
		if(!is_null($queryReply['query']['results']))
		{
			$cricketScores = $queryReply['query']['results'];
			$scores='';
			foreach ($cricketScores['item'] as $value)
			{
				$scores.=$value['title']." ";
			}
			return $scores;
		}
	}
}
?>
