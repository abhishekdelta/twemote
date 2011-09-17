<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @author Abhishek Shrivastava <i.abhi27[at]gmail.com>
 * @description TwemoteBrowser app to browse the computer remotely and view file contents.
 * Supported commands : ls,cs,cat,head,tail,line(print the content of that line only),range(content between 2 lines)
 */
class TwemoteBrowser
{
	private $result;
	private $CMD_CD_CONTEXT;
	
	function __construct()
	{
		$this->result = "";
		$this->CMD_CD_CONTEXT = get_cache_config('CMD_CD_CONTEXT');
		var_dump($this->CMD_CD_CONTEXT);
	}
	
	/*
	 * Possible commands : cd, ls, cat, head, tail, line, range, find
	 */
	public function input($params)
	{
		$cmd = $params[0];
		$cmd = trim($cmd,'#');
		
		switch($cmd)
		{
			case 'cd' : 	if(!isset($params[1])) $params[1]=".";
					$this->doCD($params[1]); break;
			case 'ls' : 	if(!isset($params[1])) $params[1]="";
					$this->doLS($params[1]); break;
			case 'cat': 	if(!isset($params[1])) $params[1]="";
					$this->doCAT($params[1]); break;
			case 'head':	if(!isset($params[1])) $params[1]="";
					$this->doHEAD($params[1]); break;
			case 'tail':	if(!isset($params[1])) $params[1]="";
					$this->doTAIL($params[1]); break;
			case 'line':	if(!isset($params[1])) $params[1]="";
					if(!isset($params[2])) $params[2]=0;
					$this->doLINE($params[1],$params[2]); break;
			case 'range':	if(!isset($params[1])) $params[1]="";
					if(!isset($params[2])) $params[2]=0;
					if(!isset($params[3])) $params[3]=1;
					$this->doRANGE($params[1],$params[2],$params[3]); break;
		}	
	
	}
	public function doCD($path)
	{
		
		$output = array();

		if(isset($this->CMD_CD_CONTEXT)==true)
		{
			if(strlen($path)==0 || $path[0]!='/')
				$path = $this->CMD_CD_CONTEXT.'/'.$path;
			
		}
		
		if(is_dir($path)==false)
		{
			$this->result .= "Invalid directory:".$path."."." ";
			$path = $this->CMD_CD_CONTEXT;
		}
		else $this->CMD_CD_CONTEXT = realpath($path);
			
	
		$this->result .= "You are currently in directory:".$this->CMD_CD_CONTEXT;
		
		save_config('CMD_CD_CONTEXT',$this->CMD_CD_CONTEXT);
		
	}
	public function doLS($path="")
	{
		$output = array();
		$path = $this->CMD_CD_CONTEXT.$path;
		$output = scandir($path);
		array_shift($output);
		array_shift($output);
		$this->result = "Directory:$path>"." ".join(" ",$output);
	}
	public function doCAT($fname)
	{
		$path = $this->CMD_CD_CONTEXT.'/'.$fname;
		if(!is_file($path))
		{
			$this->result = "Invalid file name. This file doesn't exist : ".$path;
			return;
		}
		if(!is_readable($path))
		{
			$this->result = "Sorry, this file is not readable : ".$path;
			return;
		}
		
		$output = file($path);
		$this->result = "File:$path>"." ".join(" ",$output);
	}
	public function doHEAD($fname)
	{
		$path = $this->CMD_CD_CONTEXT.'/'.$fname;
		if(!is_file($path))
		{
			$this->result = "Invalid file name. This file doesn't exist : ".$path;
			return;
		}
		if(!is_readable($path))
		{
			$this->result = "Sorry, this file is not readable : ".$path;
			return;
		}
		
		$output=array();
		exec("head ".$path,$output);
		
		$this->result = "Head:$path>"." ".join(" ",$output);
	}
	public function doTAIL($fname)
	{
		$path = $this->CMD_CD_CONTEXT.'/'.$fname;
		if(!is_file($path))
		{
			$this->result = "Invalid file name. This file doesn't exist : ".$path;
			return;
		}
		if(!is_readable($path))
		{
			$this->result = "Sorry, this file is not readable : ".$path;
			return;
		}
		
		$output=array();
		exec("tail ".$path,$output);
		
		$this->result = "Tail:$path>"." ".join(" ",$output);
	}
	public function doLINE($fname,$lineno)
	{
		$path = $this->CMD_CD_CONTEXT.'/'.$fname;
		if(!is_file($path))
		{
			$this->result = "Invalid file name. This file doesn't exist : ".$path;
			return;
		}
		if(!is_readable($path))
		{
			$this->result = "Sorry, this file is not readable : ".$path;
			return;
		}
		
		$cont = file($path);
		$output = $cont[$lineno];
		
		$this->result = "File:$path,Line:$lineno>"." ".$output;
	}
	public function doRANGE($fname,$st,$en)
	{
		$path = $this->CMD_CD_CONTEXT.'/'.$fname;
		if(!is_file($path))
		{
			$this->result = "Invalid file name. This file doesn't exist : ".$path;
			return;
		}
		if(!is_readable($path))
		{
			$this->result = "Sorry, this file is not readable : ".$path;
			return;
		}
		
		$cont = file($path);
		$output = array();
		for($i=$st;$i<=$en;$i++)
			$output[]=$cont[$i];
		
		$this->result = "File:$path,Start:$st,End:$en>"." ".join(" ",$output);
	}
	public function output()
	{
		return $this->result;
	}
}

