<?php
/**
 * @package Twemote
 * @copyright Hackersquad (Refer README for details)
 * @description Will send an email via a user's email account to any other email including an attachment if needed.
 */
require_once('./class.phpmailer.php');

//todo add config variables for login credentials and include the file accordingly
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded
class TwemoteEmail{
	private $message;
	
	public function __construct(){
	}
	
	public function input($params){
		//params divided into 0->send/search/open 1->from 2->to 3->attach"/path/to/file" 4->sub"" 5->body""
		//examples of mails send ttwecon@yahoo.com xyz@xyz.xyz attatchment /path/to/attatchment 
		switch($params[0])
		{
			case 's' :   if(strpos($params[1],'@yahoo.')){
						$username = YAHOO_USERNAME;
						$password = YAHOO_PASSWORD;
						$host = YAHOO_HOST;
					}
					else if(strpos($params[1],'@gmail.')){
						$username = GMAIL_USERNAME;
						$password = GMAIL_PASSWORD;
						$host = GMAIL_HOST;
					}
					$i=3;
					if($params[3]=='attach')
					{
						$attatchment=get_cache_config('CMD_CD_CONTEXT').'/'.$params[4];
						$i=5;
					}
					else{
						$attatchment=null;
					}
					$len = count($params);
					$subject='';
					for(;$i<=$len;$i++)
					{
						$subject.=$params[$i]." ";
					}
					
//					$body=$params[5];
					$body=$subject;
					if(!is_null($attatchment)){
						$body.='Please find the attached file.';	
					}
					
										
//					$attatchment=$params[3];
					$this->email_send($host,$username,$password,$params[2],$subject,$body,$attatchment);
					$this->message = "Email sent successfully to {$params[2]}";
					
					break;
		//	case 'search' : 
		//			$this->message='';///send id::subject id::subject
		//			break;
		//	case 'view' : $this->email_view($params[1]); //$params1 will be the message id
		//			break;
			default: $this->message='Sorry invalid query.';
					break;
		}
	}
	
	public function output()
	{
		return $this->message;
	}
	private function email_send($host,$username,$password,$to,$subject,$body,$attatchment)
	{
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		
		$mail->IsSMTP(); // telling the class to use SMTP
		
		try {
			// $mail->Host       = "plus.smtp.mail.yahoo.com	"; // SMTP server
			$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			//$mail->Host       = "smtp.mail.yahoo.com";      // sets GMAIL as the SMTP server
			$mail->Host	= $host;
			$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
			//$mail->Username   = "ttwecon@yahoo.com";  // GMAIL username
			$mail->Username = $username;
			//$mail->Password   = "123456abcdefgh";            // GMAIL password
			$mail->Password = $password;
			$mail->AddReplyTo($username, $username);
			$mail->AddAddress($to, $to);
			$mail->SetFrom($username, $username);
			$mail->Subject = $subject;
			$mail->Body = $body; // optional - MsgHTML will create an alternate automatically
			// $mail->AddAttachment('images/phpmailer.gif');      // attachment
			// $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
			if(!is_null($attatchment))
			{
				$mail->AddAttachment($attatchment);
			}
			$mail->Send();
			echo "Message Sent OK</p>\n";
		} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
		}
	}
}
?>
