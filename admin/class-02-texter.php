<?php
/**
 * Copy of the cabbage texter implementation
 */
class O2Texter {
	var $login_post_url;
	var $texturl;
	var $send_texturl;
	var $login_string;
	var $send_message_string;
	var $loginpageresult;
	var $textpageresult;
	var $sid;
	var $sendmessageresult;
	var $messagesleft;
	var $cookie;
	var $cookiedir = 'cookiebank';			//Make sure to create a directory called cookiebank and CHMOD to 733
	
	function setTheCookie($u)
	{
		srand((double)microtime()*1000000); 
		$this->cookie = $u.rand(1,100000);
	}
	function delCookie()
	{
		unlink(dirname(__FILE__)."/$this->cookiedir/$this->cookie");
	}
	
	function login($u,$p)
	{
		$this->setTheCookie($u);
		$this->login_post_url = 'https://www.o2online.ie/amserver/UI/Login';
		$this->login_string = "org=o2ext&IDToken1=$u&IDToken2=$p";
		
		$url=$this->login_post_url."?".$this->login_string;
		$url="https://www.o2online.ie/amserver/UI/Login?org=o2ext&goto=//www.o2online.ie/o2/my-o2/&IDButton=Go&org=o2ext&CONNECTFORMGET=TRUE&IDToken1=".urlencode($u)."&IDToken2=".urlencode($p)."&go-button.x=19&go-button.y=13";
		$this->loginpageresult=$this->curlURL($url,"get");
		if (strstr($this->loginpageresult,"Login Failed"))
		{
			return -1;
		}
		else if (strstr($this->loginpageresult,"Thank you for logging in"))
		{
			return 1;
		}
		else
		{
			return 1;	//could not connect to site
		}
	}
	function goto_text_page()
	{
		$this->texturl ="http://messaging.o2online.ie/ssomanager.osp?APIID=AUTH-WEBSSO";
		$this->textpageresult=$this->curlURL($this->texturl,"get");
		$this->sid="73055_otfeuwos";
		if(strstr($this->textpageresult,"support.osp?SID=")){
			$sp=explode("support.osp?SID=",$this->textpageresult);
			$sp=explode("&",$sp[1]);
			$this->sid=$sp[0];
		}
		error_log('$this->sid '.$this->sid);
		
		$this->texturl = 'http://messaging.o2online.ie/o2om_smscenter_new.osp?SID='.$this->sid.'&REF=1226337573&MsgContentID=-1';
		$this->texturl = "http://messaging.o2online.ie/o2om_smscenter_new.osp?MsgContentID=-1&SID=_&SID=".$this->sid;
		$this->textpageresult=$this->curlURL($this->texturl,"get");
		error_log('$this->textpageresult='.$this->textpageresult);
		
		if(strstr($this->textpageresult,"<span id=\"spn_WebtextFree\">")) {
			$t=explode("<span id=\"spn_WebtextFree\">",$this->textpageresult);
			$t=explode('</span>',$t[1]);
			$this->messagesleft=$t[0];
			return 1;
		}
		else {
			return -5;
		}
	}
	

	function send_message($d, $m)
	{
		$this->texturl="http://messaging.o2online.ie/smscenter_send.osp?SID=".$this->sid."&MsgContentID=-1&SMSTo=".urlencode($d)."&SMSText=".urlencode($m);
		$this->textpageresult=$this->curlURL3($this->texturl,"post");
		if(strstr($this->textpageresult,"isSuccess : true")){
			return 1;
		}else{
			return -5;
		}
	}

	function curlURL($tUrl,$method)
	{
		$tRes="-2";
		$str="";
		if($method=="post")
		{
			$sp=explode("?",$tUrl);
			$tUrl=$sp[0];
			$str=$sp[1];
		}
		
		$ch = curl_init ($tUrl);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL,$tUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 8); 
		
		curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__)."/$this->cookiedir/$this->cookie");
		curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/$this->cookiedir/$this->cookie");
		
		if($method=="post")
		{
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
		}
		else
		{
			curl_setopt($ch, CURLOPT_POST, 0); 
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($ch, CURLOPT_REFERER, "http://www.o2online.ie/wps/wcm/connect/O2/Logged+in/LoginCheck"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$tRes = curl_exec($ch);
		curl_close($ch); 
		return $tRes;
	}

	function curlURL3($tUrl,$method)
	{
		$tRes="-2";
		$str="";
		if($method=="post")
		{
			$sp=explode("?",$tUrl);
			$tUrl=$sp[0];
			$str=$sp[1];
		}
		
		$ch = curl_init ($tUrl);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_URL,$tUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.6) Gecko/20060728 Firefox/1.5.0.6");	
		curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(WP_CONTENT_DIR)."/$this->cookiedir/$this->cookie");
		curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(WP_CONTENT_DIR)."/$this->cookiedir/$this->cookie");
		//error_log('WP_CONTENT_DIR '.WP_CONTENT_DIR);
		
		if($method=="post")
		{
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
		}
		else
		{
			curl_setopt($ch, CURLOPT_POST, 0); 
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded","Host: messaging.o2online.ie","Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8","Accept-Language: en-gb,en;q=0.5","Accept-Encoding: gzip, deflate","Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7")); 
		curl_setopt($ch, CURLOPT_REFERER, "http://messaging.o2online.ie/o2om_smscenter_new.osp?MsgContentID=-1&SID=_&SID=" + $this->sid);
		//error_log('$this->sid '.$this->sid);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$tRes = curl_exec($ch);
		error_log('$tRes '.$tRes);
		curl_close($ch); 
		return $tRes;
	}
}
?>