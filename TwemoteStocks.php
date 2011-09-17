<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @author Akash Chauhan <akash6190 [at] gmail [dot] com>
 * @description Gets the latest stock market info about a set of companies.
 */

//require_once('config.inc.php');
require_once(__DIR__.'/common.php');
class TwemoteStocks
{
	private $params;
	private $message;
	function __construct()
	{
		$this->message="";
	}
	public function input($params){
		$this->params = $params[0];
	}
	public function output()
	{
		return $this->show_stock_quotes($this->params);
	}
	function show_stock_quotes ($stock_list) 
	{
		$message = "";
		$url = "http://quote.yahoo.com/d/quotes.csv?s=". $stock_list . "&f=nl1c1&e=.csv";
		$filesize = 2000;
		$handle = fopen($url, "r");
		$raw_quote_data = fread($handle, $filesize);
		fclose($handle);
		$quote_array = explode("\n", $raw_quote_data);
		//var_dump($quote_array);
		foreach ($quote_array as $quote_value) 
		{
			$quote = explode(",", $quote_value);
			if(!isset($quote[1]))
			{
			  return $message;
			}
			$symbol = str_replace("\"", "", $quote[0]);
			$value = $quote[1];
			$change = $quote[2];
			//echo $symbol;
			$message = $message.$symbol.": ".$value." , ".$change."\n";
		}
		return $message;
	}

}

?>




