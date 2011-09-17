<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @author Abhishek Shrivastava <i.abhi27[at]gmail.com>
 * @description Common utilities functions, for reading and writing to config file.
 */
$configs = parse_ini_file(__DIR__.'/config.ini',true);

function write_ini_file($assoc_arr, $path, $has_sections = TRUE)
{
 $content = ""; 

 if ($has_sections) { 
  foreach ($assoc_arr as $key=>$elem) { 
   $content .= "[".$key."]\n"; 
   foreach ($elem as $key2=>$elem2) 
   { 
    if(is_array($elem2)) 
    { 
     for($i=0;$i<count($elem2);$i++) 
     { 
      $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
     } 
    } 
    else if($elem2=="") $content .= $key2." = \n"; 
    else $content .= $key2." = \"".$elem2."\"\n"; 
   } 
  } 
 } 
 else
 { 
  foreach ($assoc_arr as $key=>$elem) { 
   if(is_array($elem)) 
   { 
    for($i=0;$i<count($elem);$i++) 
    { 
     $content .= $key2."[] = \"".$elem[$i]."\"\n"; 
    } 
   } 
   else if($elem=="") $content .= $key2." = \n"; 
   else $content .= $key2." = \"".$elem."\"\n"; 
  } 
 } 

 if (!$handle = fopen($path, 'w'))
 { 
  return false; 
 }

 if (!fwrite($handle, $content))
 { 
  return false; 
 }

 fclose($handle); 
 return true;
}

function define_req_configs()
{
	global $configs;
	$constsect = array("basic","advanced");
	foreach($constsect as $section)
	{
		$configarr = $configs[$section];
		foreach($configarr as $key=>$val)
		{
			if(defined($key))
				unset($key);
			define($key,$val);
		}
	}
}

function save_config($key,$val,$section='cache')
{
	global $configs;
	$configs[$section][$key]=$val;
	if(!write_ini_file($configs,__DIR__.'/config.ini',true))
		echo "FAILED TO WRITE CONFIG FILE";
}

function get_cache_config($key)
{
	global $configs;
	return isset($configs['cache'][$key])?$configs['cache'][$key]:false;
}

define_req_configs();
