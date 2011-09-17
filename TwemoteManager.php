<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @author Abhishek Shrivastava <i.abhi27[at]gmail.com>
 * @description TwemoteManager takes the sms from the reader script, parse it, call the appropriate 
 * handler for the sms command and return the output to the user's mobile via the sender script.
 */
require_once(__DIR__.'/common.php');
class TwemoteManager
{
	private $app;
	private $sms;
	private $result;
	private $params;
	
	function __construct($sms)
	{
		$this->sms = $sms; 
	}
	public function run()
	{
		$this->parse();		
		$this->execute();
		$this->sendsms();
	}
	public function parse()
	{
		
		$parts = explode(' ',$this->sms);
		switch($parts[0])
		{
			case '#route'	:
						$this->app = 'TwemoteMaps';
						break;
			case '#stocks'	:
						$this->app='TwemoteStocks';
						break;
			case '#dictionary' :
			case '#dict'	:	
						$this->app='TwemoteDict';
						break;
			case '#email'	: 
			case '#em'	:
						$this->app = 'TwemoteEmail';
						break;
			case '#browse'	:
			case '#br'	:
						$this->app = 'TwemoteBrowser';
						break;
			case '#cricket' :
			case '#cric'	:
						$this->app = 'TwemoteCricket';
						break;
		}
		
		array_shift($parts);
		$this->params = $parts;
		
	}
	public function execute()
	{
		require_once(__DIR__.'/'.$this->app.'.php');
		$object = new $this->app();

		$object->input($this->params);
		$this->result = $object->output();
		//var_dump(str_replace('\n','<br/>',$this->result));
	}
	public function sendsms()
	{
		echo "Trying to send the result, which is :";
		if(!file_exists(__DIR__.'/'.SENDSMS_AUTHFILE))
		{
			exec(PYTHONCMD." ".__DIR__."/".SENDSMS_SCRIPT." --setup ".SMS_USERNAME." ".SMS_PASSWORD);
		}
		$this->result = addslashes($this->result);
		
		var_dump($this->result);
		
		$out = array();
		
		exec(PYTHONCMD." ".__DIR__."/".SENDSMS_SCRIPT." ".USER_MOBILE." \"".$this->result."\"",$out);
		
	}
}

?>
