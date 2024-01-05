<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
    session_start();
    if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    	Header("Location:../phpinc/main.php");
		exit();
    }

  //require_once("../include/mysql.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  require_once("../include/user.inc.php");
  //require_once("../include/function.inc.php");
  
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
	$pconsumerManager = new cConsumerPeer;
	$returnFlag = $pconsumerManager->delete($consumerid);
    if ($returnFlag){
	  $message="成功删除消费者";
	  Header("Location:consumer.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="删除消费者失败";
	}

	msg($message);
  }

  if (!$_POST){
    $consumerid = trim($_GET["consumerid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $db = new cDatabase;
  mysql_query('set names utf8');
  $consumerManager = new cConsumerPeer;
  $consumer = $consumerManager->getConsumer($consumerid);
  $consumerName = $consumer->getCname();
  $consumerAddress = $consumer->getAddress();
  $consumerMemo = $consumer->getMemo();
  $consumerMobile = $consumer->getMobile();
  $consumerTelephone = $consumer->getTelephone();
    
  $user=new user;
  $consumerAgentname = $user->getUserbyId($consumer->getAgentid())->realname;
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
<?php
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"consumerid\" value=\"$consumerid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>删除消费者信息</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">姓名</td>
      <td >&nbsp;$consumerName</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">地址</td>
      <td >&nbsp;$consumerAddress</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">备注</td>
      <td >&nbsp;$consumerMemo</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">手机</td>
      <td >&nbsp;$consumerMobile</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
      <td >&nbsp;$consumerAgentname</td>
     </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除消费者信息\"></td>
    </tr>
   </form>
   </table>";
?>