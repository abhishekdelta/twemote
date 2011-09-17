<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @description Generates the route information using Google maps between a source and a destination.
 */
class TwemoteMaps{

public $p;
public function __construct()
{

}

public function input($params)
{
	
	$srcindex;
	$destindex;
	
	for($i=0;$i<count($params);$i++)
	{
		if($params[$i] == "#src")
		{
			$srcindex = $i;
		}	
	}
	
	for($i=0;$i<count($params);$i++)
	{
		if($params[$i] == "#dest")
		{
			$destindex = $i;
		}	
	}
	
	$source = "";
	$destination="";
	for($i=$srcindex+1;$i<$destindex;$i++)
	{
		$source.=$params[$i]." ";
	}
	for($i=$destindex+1;$i<count($params);$i++)
	{
		$destination.=$params[$i]." ";
	}
	$this->p[0] = $source;
	$this->p[1] = $destination;

}

function getdirection ($source,$destination) 
{
	
	$source = urlencode($source);
	$destination = urlencode($destination);
	$url = "http://maps.googleapis.com/maps/api/directions/json?origin=".$source."&destination=".$destination."&sensor=false";	
	$raw_data =  file_get_contents($url);
	$arrdata = json_decode($raw_data,true);
	$arr=$arrdata["routes"][0]["legs"];
	$result = "";
	$result.="Destination : ";
	$result.=$arr[0]["end_address"]." ";
	$result.=$arr[0]["distance"]["text"]." ";
	$result.=$arr[0]["duration"]["text"]." ";
	
	$result.="\n";
	$result.="Route : ";
	$result.="\n";
	
	$arr = $arr[0]["steps"];
	for($i=0;$i<count($arr);$i++)
	{
		$result.=strip_tags($arr[$i]["html_instructions"]).", ";	
		$result.=$arr[$i]["distance"]["text"].", ";
		$result.=$arr[$i]["duration"]["text"]." ";
		$result.="\n";
	}
	
	return $result;
}
public function output()
{
	return $this->getdirection($this->p[0],$this->p[1]);
}

}
?>

