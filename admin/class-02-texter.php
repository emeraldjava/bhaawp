<?php
class O2Texter {
    
	var $login_post_url;
	var $texturl;
	var $send_texturl;
	var $login_string;
	var $send_message_string;
	var $loginpageresult;
	var $textpageresult;
	var $sendmessageresult;
	var $messagesleft;
	var $cookie;
	var $cookiedir = 'cookiebank';			//Make sure to create a directory called cookiebank and CHMOD to 733

    private $logger;

    public function __construct($log)
    {
        $this->logger = $log;
    }

    function setTheCookie($u)
	{
		srand((double)microtime()*1000000);
		$this->cookie = $u.rand(1,100000);
        $this->logger->info(sprintf("setTheCookie %d",$this->cookie));
	}

    function delCookie()
	{
        $this->logger->info(sprintf("del cookie %s","$this->cookiedir/$this->cookie"));
		unlink("$this->cookiedir/$this->cookie");
	}

	function login($u,$p)
	{
        $this->setTheCookie($u);	//generate cookie name.

		$url="https://www.o2online.ie/amserver/UI/Login?org=o2ext&IDToken1=$u&IDToken2=$p";	//Construct the login url
        $this->logger->info($url);
		$this->loginpageresult=$this->curlURL($url,"post");	//execute the url
		if (strstr($this->loginpageresult,"incorrect"))	//See if the response is valid, otherwise return an error
		{
            $this->logger->info("login incorrect");
			return -1;
		}
		else if (strstr($this->loginpageresult,"Thank you for logging in"))
		{
            $this->logger->info("Thank you for logging in");
			return 1;
		}
		else
		{
            $this->logger->info("login could not connect to site");
			return -2;	//could not connect to site
		}
        $this->logger->info("http://messaging.o2online.ie/");
		$this->loginpageresult=$this->curlURL("http://messaging.o2online.ie/","get");	//execute the url
        $this->logger->info("login result "+$this->loginpageresult);
		//print $this->loginpageresult;
	}

	function goto_text_page()
	{
		$this->texturl = 'http://messaging.o2online.ie/ssomanager.osp?APIID=AUTH-WEBSSO';	//construct the url to go to the messaging.o2online.ie doman
        $this->logger->info($this->texturl);
        $this->textpageresult=$this->curlURL($this->texturl,"get"); //execute the url
        //$this->logger->info(sprintf("goto_text_page 1"));//,$this->textpageresult));

        //print $this->textpageresult;
		$this->texturl = 'http://messaging.o2online.ie/o2om_smscenter_new.osp?SID=73055_otfeuwos&REF=1226337573&MsgContentID=-1';//construct the url to go to the text page
        $this->logger->info($this->texturl);
        $this->textpageresult=$this->curlURL($this->texturl,"get");	//execute the url
        //$this->logger->info(sprintf("goto_text_page 2"));// %s",$this->textpageresult));

			if(strstr($this->textpageresult,"<span id=\"spn_WebtextFree\">"))	//If the response is valid, cut out the messages left, otherwise return an error
			{
				$t=explode('<span id="spn_WebtextFree">',$this->textpageresult);
				$t=explode('</span>',$t[1]);
				$this->messagesleft=$t[0];
                $this->logger->info($this->messagesleft);
				return 1;
			}
//			else
//			{
//				return -5;
//			}
	}


	function send_message($d, $m)
	{
		$this->texturl = 'http://messaging.o2online.ie/smscenter_send.osp?SID=73055_otfeuwos&MsgContentID='
            .'-1&FlagDLR=1&RepeatStartDate=2008%2C11%2C10%2C17%2C15%2C00&RepeatEndDate=2008%2C11%2C10%2C17%2C15%2C00&RepeatType=0&'
            .'RepeatEndType=0&FolderID=0&SMSToNormalized=&FID=&RURL=o2om_smscenter_new.osp%3FSID%3D73055_otfeuwos%26MsgContentID%3D-1%26REF%3D1226337214&'
            .'REF=1226337214&SMSTo='.urlencode($d).'&selcountry=00378&SMSText='.urlencode($m).'&Frequency=5&StartDateDay=10&StartDateMonth=11&StartDateYear='
            .'2008&StartDateHour=17&StartDateMin=15&EndDateDay=10&EndDateMonth=11&EndDateYear=2008&EndDateHour=17&EndDateMin=15';	//construct the url to send a message. The only variables are urlencode($d) and urlencode($m)
        //$this->logger->info($this->texturl);
        $this->textpageresult=$this->curlURL($this->texturl,"get");	//execute the url
//		if(strstr($this->textpageresult,"successfully submitted")){	//if the response is valid return 1, otherwise return an error
//			return 1;
//		}else{
//            $this->logger->info("send message fail");
//			return -7;
//		}
	}

	function curlURL($tUrl,$method)	//The function that does the url execution
	{
		$str="";
		if($method=="post")	//Some urls will only work with post data, not a query string so the option is there to change it to post
		{
			$sp=explode("?",$tUrl);
			$tUrl=$sp[0];
			$str=$sp[1];
		}

		$ch = curl_init ($tUrl);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);	//Don't care about certificates
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	//Don't care about certificates
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);	//Return the response text to a variable instead of just printing it out
		//$this->logger->info("tUrl "+$tUrl);
        curl_setopt($ch, CURLOPT_URL,$tUrl);	//The url to execute
		curl_setopt($ch, CURLOPT_HEADER, 0); 	//Don't print out the header
        //$this->logger->info("cookiedir "+$this->cookiedir);
		curl_setopt($ch, CURLOPT_COOKIEFILE, "$this->cookiedir/$this->cookie");	//Where are cookies to be stored. The names are generated based on the phone number and a random number in the setCookie function
		curl_setopt($ch, CURLOPT_COOKIEJAR, "$this->cookiedir/$this->cookie");
		//curl_setopt($ch, CURLOPT_TIMEOUT, 15); 	//The O2 website is very unreliable so time out instead of hanging

		if($method=="post")	//If posting, specify the post string
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
		}
		else	//otherwise specify no post
		{
			curl_setopt($ch, CURLOPT_POST, 0);
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 	//If there are redirects (http 302), follow them
		curl_setopt($ch, CURLOPT_REFERER, "http://google.ie"); 	//What site did I come from? Doesn't really make a diffrerence
		$tRes = curl_exec($ch);	//execute the url
		curl_close($ch); //close the connection
		return $tRes;	//return the http response text
	}
}
?>