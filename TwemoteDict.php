<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @description Generates the dictionary meaning of a particular word.
 */
class TwemoteDict {
  
  public $outputstring;
  public $p;
  
  public function __construct()
  {
  	$this->outputstring ="";
  }
  public function input($params)
  {
  	$this->p = $params[0];
  }
  public function output()
  {
  	return $this->lookup($this->p);
  }
  public function lookup($params)
  {
	  require("Wordnik.php"); 
	  $api_key = "d1aa3e2004ffb04eb15050fa244069def43fb2b4b282e6da3";
	  $wordnik = Wordnik::instance($api_key);
	  $wordstring = $params;
	  $definitions = $wordnik->getDefinitions($wordstring);  
	  $this->outputstring = $wordstring.":\n";
	  
	  if (empty($definitions))
	  {
	    $this->outputstring = "Not Found";
	    return ;
	  }
	  else
	  {
		  foreach($definitions as $definition) {
		    if (isset($definition->text)) {
		      $this->outputstring = $this->outputstring.$definition->text." ; ";
		    }
		  }
	   }
	   $this->outputstring = $this->outputstring."\r\n"."Usage : ";
	   $examples = $wordnik->getExamples($wordstring);
	   if (empty($examples))
	   {
	   }
	   else
	   {
		    $example = $examples[0];
		    if (isset($example->display)) {
		      $this->outputstring = $this->outputstring."\n".$example->display;
		  }
	   }
	   return $this->outputstring;
   }
   
}
?>
