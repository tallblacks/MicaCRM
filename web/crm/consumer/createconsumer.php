<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
	session_start();
	if (!isset($_SESSION["SESS_USERID"])){
		Header("Location:index.php");
		exit();
	}

  //require_once("../include/mysql.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  require_once("../include/user.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	$type = 1;
	$status = 0;
	$user = new user;
	
	if($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY){
	  if($agentname != null || $agentname != ""){
  	    $agentid = $user->getUserbyRealname($agentname)->userid;
  	    if($agentid){
  	      $agentFlag = 1;
  	    }else{
  	      $agentFlag = 0;
  	    }
	  }else{
	    $agentid = 0;
	    $agentFlag = 1;
	  }
	}else if($_SESSION['SESS_TYPE'] == XIAOMI){//xiaomi
	  $agentid = 0;
	  $agentFlag = 1;
	}else{
	  $agentid = $_SESSION['SESS_USERID'];
	  $agentFlag = 1;
	}
	
	if($_SESSION['SESS_TYPE'] != ADMIN){
	  $wechat = "";
	}
	
  	if($agentFlag){
	  $new_consumer = new cConsumer;
	  $new_consumer->setCname($name);
      $new_consumer->setAddress($address);
      $new_consumer->setMemo($memo);
      $new_consumer->setMobile($mobile);
      $new_consumer->setTelephone($telephone);
      $new_consumer->setWechat($wechat);
      $new_consumer->setAgentid($agentid);
      if($_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI){
        $xiaomid = $_SESSION['SESS_USERID'];
      }else if($_SESSION['SESS_TYPE'] == ADMIN){
        $xiaomid = 0;
      }else{//代理
        $xiaomid = $user->getSecretaryUser()->userid;
      }
      $new_consumer->setXiaomid($xiaomid);
      $new_consumer->setCtype($type);
      $new_consumer->setPstatus($status);
	
	  $pconsumerManager = new cConsumerPeer;
	  $returnFlag = $pconsumerManager->create($new_consumer);
      if ($returnFlag){
	    $message="成功创建新消费者";
	  }else{
	    $message="创建新消费者失败";
	  }
	}else{
	  $message="代理姓名输入错误";
	}
	msg($message);
  }
  
  $agentid = 0;
  if($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY){
    $agentnameStr = "<tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
      <td >&nbsp;<input type=\"text\" name=\"agentname\" id=\"needajax\">可不填，如有需要填写正确的代理人姓名</td>
     </tr>";
  }else if($_SESSION['SESS_TYPE'] == XIAOMI){
    $agentnameStr = "";
  }else{
    $user = new user;
    $agentid = $_SESSION['SESS_USERID'];
    $agentname = $user->getUserbyId($agentid)->realname;
    $agentnameStr = "<tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
      <td >&nbsp;$agentname</td>
     </tr>";
  }
  if($_SESSION['SESS_TYPE'] == ADMIN){
    $wechatStr = "<tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">微信号</td>
      <td >&nbsp;<input type=\"text\" name=\"wechat\"></td>
     </tr>";
  }else{
    $wechatStr = "";
  }
?>
<style type="text/css">
<!--
body {
	margin-top: 0px;
	margin-left: 0px;
}
-->
</style>
<link rel=stylesheet type=text/css href=/style/global.css>
<!--script type="text/javascript" src="/ajaxserver/ajax2013.js"></script>
<script type="text/javascript">
  alter("fuck");
</script-->
<?php
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>添加新的消费者</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">姓名</td>
      <td >&nbsp;<input type=\"text\" name=\"name\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">地址</td>
      <td >&nbsp;<input type=\"text\" name=\"address\" style=\"width:300px;\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">备注</td>
      <td >&nbsp;<input type=\"text\" name=\"memo\">可不填</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">手机</td>
      <td >&nbsp;<input type=\"text\" name=\"mobile\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">座机</td>
      <td >&nbsp;<input type=\"text\" name=\"telephone\">可不填</td>
     </tr>
      $agentnameStr
      $wechatStr
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"添加新消费者\"></td>
    </tr>
   </form>
   </table>";
?>