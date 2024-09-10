<?php
namespace Common\Common;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: pengyong <i@pengyong.info>
// +----------------------------------------------------------------------
/**
 * ThinkPHP 邮件处理类
 */
class Email
{
	var $smtp_port;
	var $time_out;
	var $host_name;
	var $log_file;
	var $relay_host;
	var $ssl;
	var $debug;
	var $auth;
	var $user;
	var $pass;
	var $sock;
	var $charset = "UTF-8";
    static $_log = null;

	public function __construct()
	{
		$this->debug = false;
		$this->smtp_port = C('SMTP_PORT');
		$this->relay_host = C('SMTP_SERVER');
		if(C('SMTP_SSL')){
			$this->ssl = "ssl://";
		}else{
			$this->ssl = "";
		}
		$this->time_out = C('SMTP_TIME_OUT');
		$this->auth = C('SMTP_AUTH');
		$this->user = C('SMTP_USER');
		$this->pass = C('SMTP_PWD');
		$this->host_name = "localhost";
		$this->log_file ="/tmp/send_mail_log";
		$this->sock = FALSE;

        if (!isset(self::$_log)) {
            self::$_log = new \Think\Log\Driver\Logger('email');
        }
	}

	public function send($data, $from='', $subject = "", $body = "", $mailtype='html', $cc = "", $bcc = "", $additional_headers = "")
	{
		if(is_array($data))
		{
			$data['mailtype']  = empty($data['mailtype'])? C('SMTP_MAIL_TYPE'):$data['mailtype'];
			$data['mailfrom']  = empty($data['mailfrom'])? C('SMTP_USER_EMAIL'):$data['mailfrom'];
			$data['subject']  = empty($data['subject'])? 'no subject':$data['subject'];
			$data['body']  = empty($data['body']) ? 'no title':$data['body'];
			$from = $data['mailfrom'];
			$subject = $data['subject'];
			$body = $data['body'];
			$mailtype = $data['mailtype'];
			$to = $data['mailto'];
		}
		else
		{
			$to = $data;
		}
		$mail_from = $this->get_address($this->strip_comment($from));
		$body = ereg_replace("(^|(\r\n))(\\.)", "\\1.\\3", $body);
		$header .= "MIME-Version:1.0\r\n";
		if($mailtype=="HTML")
		{
			$header .= "Content-Type:text/html;charset=".$this->charset."\r\n";
		}
		 $header .= "To: ".$to."\r\n";
		if ($cc != "")
		{
			$header .= "Cc: ".$cc."\r\n";
		}
		$header .= "From: ZipcodeXpress<".$from.">\r\n";
		$header .= "Subject: ".$subject."\r\n";
		$header .= $additional_headers;
		$header .= "Date: ".date("r")."\r\n";
		$header .= "X-Mailer:By Redhat (PHP/".phpversion().")\r\n";
		list($msec, $sec) = explode(" ", microtime());
		$header .= "Message-ID: <".date("YmdHis", $sec).".".($msec*1000000).".".$mail_from.">\r\n";
		$TO = explode(",", $this->strip_comment($to));
		 
		if ($cc != "") {
			$TO = array_merge($TO, explode(",", $this->strip_comment($cc)));
		}
		if ($bcc != "") {
			$TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));
		}

		 
		$sent = TRUE;
		foreach ($TO as $rcpt_to) {
		$rcpt_to = $this->get_address($rcpt_to);
		if (!$this->smtp_sockopen($rcpt_to)) 
		{
			$this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
			$sent = FALSE;
			continue;
		}
		if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $header, $body)) 
		{
			$this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
		} 
		else 
		{
			$this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
			$sent = FALSE;
		}
		fclose($this->sock);
			$this->log_write("Disconnected from remote host\n");
		}
		if($this->debug)
		{
			echo "<br>";
			echo $header;
		}
		return $sent;
	}
	
	public function debug($debug)
	{
		$this->debug = $debug;
		return $this;
	}
	
	/* Private Functions */
	function smtp_send($helo, $from, $to, $header, $body = "")
	{
		if (!$this->smtp_putcmd("HELO", $helo)) 
		{
			return $this->smtp_error("sending HELO command");
		}
		#auth
		if($this->auth){
		if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->user))) 
		{
			return $this->smtp_error("sending HELO command");
		}
	 
		if (!$this->smtp_putcmd("", base64_encode($this->pass))) 
		{
			return $this->smtp_error("sending HELO command");
		}
		}
		if (!$this->smtp_putcmd("MAIL", "FROM:<".$from.">")) 
		{
			return $this->smtp_error("sending MAIL FROM command");
		}
	 
		if (!$this->smtp_putcmd("RCPT", "TO:<".$to.">")) 
		{
			return $this->smtp_error("sending RCPT TO command");
		}
	 
		if (!$this->smtp_putcmd("DATA")) 
		{
			return $this->smtp_error("sending DATA command");
		}
	 
		if (!$this->smtp_message($header, $body)) 
		{
			return $this->smtp_error("sending message");
		}
	 
		if (!$this->smtp_eom()) 
		{
			return $this->smtp_error("sending <CR><LF>.<CR><LF> [EOM]");
		}
	 
		if (!$this->smtp_putcmd("QUIT")) 
		{
			return $this->smtp_error("sending QUIT command");
		}
			return TRUE;
	}
 
	function smtp_sockopen($address)
	{
		if ($this->relay_host == "") {
		return $this->smtp_sockopen_mx($address);
		} else {
		return $this->smtp_sockopen_relay();
		}
	}

 
	function smtp_sockopen_relay()
	{
		$this->log_write("Trying to ".$this->relay_host.":".$this->smtp_port."\n");
		$this->sock = @fsockopen($this->ssl.$this->relay_host, $this->smtp_port, $errno, $errstr, $this->time_out);
		if (!($this->sock && $this->smtp_ok())) {
		$this->log_write("Error: Cannot connenct to relay host ".$this->relay_host."\n");
		$this->log_write("Error: ".$errstr." (".$errno.")\n");
		return FALSE;
		}
		$this->log_write("Connected to relay host ".$this->relay_host."\n");
		return TRUE;;
	}

 
	function smtp_sockopen_mx($address)
	{
		$domain = ereg_replace("^.+@([^@]+)$", "\\1", $address);
		if (!@getmxrr($domain, $MXHOSTS)) 
		{
			$this->log_write("Error: Cannot resolve MX \"".$domain."\"\n");
			return FALSE;
		}
		foreach ($MXHOSTS as $host) 
		{
			$this->log_write("Trying to ".$host.":".$this->smtp_port."\n");
			$this->sock = @fsockopen($this->ssl.$host, $this->smtp_port, $errno, $errstr, $this->time_out);
			if (!($this->sock && $this->smtp_ok())) 
			{
				$this->log_write("Warning: Cannot connect to mx host ".$host."\n");
				$this->log_write("Error: ".$errstr." (".$errno.")\n");
				continue;
			}
			$this->log_write("Connected to mx host ".$host."\n");
			return TRUE;
		}
		$this->log_write("Error: Cannot connect to any mx hosts (".implode(", ", $MXHOSTS).")\n");
		return FALSE;
	}
 
	function smtp_message($header, $body)
	{
		fputs($this->sock, $header."\r\n".$body);
		$this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n> "));
		return TRUE;
	}
 
	function smtp_eom()
	{
		fputs($this->sock, "\r\n.\r\n");
		$this->smtp_debug(". [EOM]\n"); 
		return $this->smtp_ok();
	}
 
	function smtp_ok()
	{
		$response = str_replace("\r\n", "", fgets($this->sock, 512));
		$this->smtp_debug($response."\n");
	 
	if (!ereg("^[23]", $response)) 
	{
		fputs($this->sock, "QUIT\r\n");
		fgets($this->sock, 512);
		$this->log_write("Error: Remote host returned \"".$response."\"\n");
		return FALSE;
	}
		return TRUE;
	}
 
	function smtp_putcmd($cmd, $arg = "")
	{
		if ($arg != "")
		{
			if($cmd=="") $cmd = $arg;
			else $cmd = $cmd." ".$arg;
		} 
		fputs($this->sock, $cmd."\r\n");
		$this->smtp_debug("> ".$cmd."\n");
		return $this->smtp_ok();
	}
 
	function smtp_error($string)
	{
		$this->log_write("Error: Error occurred while ".$string.".\n");
		return FALSE;
	}
 
	function log_write($message)
	{
		$this->smtp_debug($message);
	 
		if ($this->log_file == "") 
		{
			return TRUE;
		}
	 
		$message = date("M d H:i:s ").get_current_user()."[".getmypid()."]: ".$message;
		if (!@file_exists($this->log_file) || !($fp = @fopen($this->log_file, "a"))) 
		{
			$this->smtp_debug("Warning: Cannot open log file \"".$this->log_file."\"\n");
			return FALSE;
		}
		flock($fp, LOCK_EX);
		fputs($fp, $message);
		fclose($fp);
		return TRUE;
	}
 
	function strip_comment($address)
	{
		$comment = "\\([^()]*\\)";
		while (ereg($comment, $address)) 
		{
			$address = ereg_replace($comment, "", $address);
		}
		return $address;
	}
 
	function get_address($address)
	{
		$address = ereg_replace("([ \t\r\n])+", "", $address);
		$address = ereg_replace("^.*<(.+)>.*$", "\\1", $address); 
		return $address;
	}
 
	function smtp_debug($message)
	{
		if ($this->debug) 
		{
			echo $message."<br>";
		}
	}
 
	function get_attach_type($image_tag) 
	{ //
	 
		$filedata = array();
		 
		$img_file_con=fopen($image_tag,"r");
		unset($image_data);
		while ($tem_buffer=AddSlashes(fread($img_file_con,filesize($image_tag))))
		$image_data.=$tem_buffer;
		fclose($img_file_con);
		$filedata['context'] = $image_data;
		$filedata['filename']= basename($image_tag);
		$extension=substr($image_tag,strrpos($image_tag,"."),strlen($image_tag)-strrpos($image_tag,"."));
		switch($extension)
		{
			case ".gif":
			$filedata['type'] = "image/gif";
			break;
			case ".gz":
			$filedata['type'] = "application/x-gzip";
			break;
			case ".htm":
			$filedata['type'] = "text/html";
			break;
			case ".html":
			$filedata['type'] = "text/html";
			break;
			case ".jpg":
			$filedata['type'] = "image/jpeg";
			break;
			case ".tar":
			$filedata['type'] = "application/x-tar";
			break;
			case ".txt":
			$filedata['type'] = "text/plain";
			break;
			case ".zip":
			$filedata['type'] = "application/zip";
			break;
			default:
			$filedata['type'] = "application/octet-stream";
			break;
		}
		return $filedata;
	}

    public function sendEmail($email, $memberId, $type='register', $data=array()) {

        if(empty($email) || empty($memberId)) return false;

        $maildata = [
            'mailfrom' => C('SMTP_USER_EMAIL'),
            'mailto'=> $email,
        ];

        if($type == 'register' && $data['vcode']) {
            $maildata['subject'] = 'Please Verify Your Account Email Address';
            $maildata['body'] = 'Your verification code is: '.$data['vcode'];
        } else if($type == 'resetpsd' && $data['vcode']) {
            $maildata['subject'] = 'ZipcodeXpress Reset Password';
            $maildata['body'] = 'Your verification code is: '.$data['vcode'];
        //} else if($type == 'resetpsd') {
        //    $token = md5(\Org\Util\String::randString(20));
        //    S(C('memcache_config'))->set($token, $memberId);
        //    $resetpsdurl = C('WWW_ADDRESS').U('/Api/login/resetpsd', array('token'=>$token));
        //    $maildata['subject'] = 'ZipcodeXpress Reset Password';
        //    $maildata['body'] = 'Click the link to reset your password: <a href="'.$resetpsdurl.'">the link ...</a>';
        } else if($type == 'initpsd' && $data['psd']) {
            $maildata['subject' ] = 'ZipcodeXpress Register Notice';
            $maildata['body'] = 
                'Hello '.$email.','.
                '<br>'.
                'Welcome to ZipcodeXpress, we provide a convenient  and affordable way for you to rent movie disk.'.
                '<br>'.
                'This is your login account:'.
                '<br>'.
                '  loginname:  '.$email.
                '<br>'.
                '  password: '.$data['psd'].
                '<br>'.
                'Please download ZipcodeXpress App and start your 2 weeks free trial!'.
                '<br>'.
                '<img src="'.C('CDN_ADDRESS').'/images/scan_for_app.png" width=200 />'.
                '<br>';
        } else {
            return false;
        }

        //$result = $mgClient->sendMessage($domain, $maildata);
        $ret =  $this->sendByPHPMailer($maildata);
        return $ret;
    }

    public function sendMovieCodeEmail($email, $movieCode) {

        if(empty($email) || empty($movieCode)) return false;

        $maildata = [
            'mailfrom' => C('SMTP_USER_EMAIL'),
            'mailto'=> $email,
        ];

        $maildata['subject' ] = 'ZipcodeXpress Hotel Service Notice';
        $maildata['body'] = 'The movie code for today is:'.$movieCode;

        return $this->sendByPHPMailer($maildata);
    }

    public function sendByPHPMailer($maildata){
        //Create a new PHPMailer instance
        $mail = new \PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'text';
        //Set the hostname of the mail server
        $mail->Host = $this->relay_host;
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = $this->smtp_port;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "ssl";
        //Username to use for SMTP authentication
        $mail->Username = $this->user;
        //Password to use for SMTP authentication
        $mail->Password = $this->pass;
        //Set who the message is to be sent from
        $mail->setFrom($this->user, 'ZipcodeXpress');


        //Set an alternative reply-to address
        if($maildata['replyto']) {
            $mail->addReplyTo($maildata['replyto'], 'ZipcodeXpress');
        }
        //Set who the message is to be sent to
        if($maildata['mailto']){
            $mail->addAddress($maildata['mailto']);
        }
        if($maildata['mailtoArr']){
            foreach($maildata['mailtoArr'] as $mailto) {
                $mail->addAddress($mailto);
            }
        }
        //$mail->addAddress('yanglin21@yeah.net','yanglin');
        //Set the subject line
        $mail->isHTML(true);
        $mail->Subject = $maildata['subject'];
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        $mail->Body = $maildata['body'];
        //send the message, check for errors
        $ret = $mail->send();
        if ($ret) {
            self::$_log->write(['status' => 'success', 'maildata' => $maildata, 'mailObject'=>$mail]);
        } else {
            self::$_log->write(['status' => 'fail', 'maildata' => $maildata, 'mailObject'=>$mail]);
        }
        return $ret;
    }
 }
