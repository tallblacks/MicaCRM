<?
include_once ("include/ihaomy/init.php");
include_once DOC_ROOT.'conf/group.config.php';

$haomyMailList = array(
	"noreply@ihaomy.com",
	"noreply8@ihaomy.com",
	"noreply7@ihaomy.com",
	"noreply6@ihaomy.com",
	"noreply5@ihaomy.com",
	"noreply4@ihaomy.com",
	"noreply3@ihaomy.com",
	"noreply2@ihaomy.com",
	"noreply9@ihaomy.com",
	"noreply10@ihaomy.com"
	);
	
	$ret = send_mail1("/data/ihaomy/web/www/beijing/deal/hjyzqx399_email.html", 
							null, 
							"test老曹0",
							"levin@ihaomy.com",
							"巴格达",
							$haomyMailList[2],
							"noreplypassword"
							);
	echo $haomyMailList[1];
	if($ret){
	  echo "good";
	}else{
	  echo "bad";
	}
	/*sleep(10);						
	$ret = send_mail("http://www.ihaomy.com/beijing/deal/hjyzqx399_email.html", 
							null, 
							"test老曹1",
							"levincao@gmail.com",
							"巴格达",
							$haomyMailList[1],
							"noreplypassword"
							);
	echo $haomyMailList[1];
	sleep(10);							
	$ret = send_mail("http://www.ihaomy.com/beijing/deal/hjyzqx399_email.html", 
							null, 
							"test老曹2",
							"levincao@gmail.com",
							"巴格达",
							$haomyMailList[2],
							"noreplypassword"
							);
	echo $haomyMailList[2];
	sleep(10);					
	$ret = send_mail("/data/ihaomy/web/www/beijing/deal/hjyzqx399_email.html", 
							null, 
							"test老曹3",
							"levincao@gmail.com",
							"巴格达",
							$haomyMailList[3],
							"noreplypassword"
							);
	echo $haomyMailList[3];
	sleep(10);*/
	
	function send_mail1($body_file = null, $body_string = null, $subject, $address, $nick, $from_email, $from_pwd) {
        //include_once(PHPMAILER_DIR."class.phpmailer.php");
        include_once("/data/ihaomy/library/third/phpMailer/class.phpmailer.php");
        $mail = new PHPMailer();
        echo "11111111111111111";
        if (!empty($body_file)){
                if (!is_file($body_file)){
                        return false;
                }
                $body = $mail->getFile($body_file);
                $body = eregi_replace("[\]",'',$body);
                echo ">>>>>>>>>";
        }else {
                $body = $body_string;
                echo "<<<<<<<<<<";
        }
        echo "22222222222";
        $mail->IsSMTP();
        $mail->SMTPAuth   = true;               // enable SMTP authentication
        $mail->SMTPSecure = "ssl";              // sets the prefix to the servier
        $mail->Host       = "smtp.gmail.com";   // sets GMAIL as the SMTP server
        $mail->Port       = 465;                // set the SMTP port for the GMAIL server
        $mail->Username   = $from_email;                // GMAIL username
        $mail->Password   = $from_pwd;                  // GMAIL password
        $mail->CharSet    = "utf-8";
        $mail->From       = "noreply@ihaomy.com";
        $mail->FromName   = "好买都市网";
        //$mail->Subject    = $subject;
        $mail->Subject    =  "=?UTF-8?B?".base64_encode($subject)."?=";
        $mail->WordWrap   = 50; // 自动换行
        $mail->MsgHTML($body);
        $mail->AddAddress($address, $nick);
        //$mail->AddAttachment("images/phpmailer.gif");             // attachment
        echo "3";
        $mail->IsHTML(true); // send as HTML
        if(!$mail->Send()) {
                echo $mail->ErrorInfo;
                echo "4";
                return false;
        } else {
                echo "5";
                return true;
        }
}

?>