<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

  //require_once("../include/config.inc.php");
  //require_once("../include/mysql.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  require_once("../include/user.inc.php");
  //require_once("../include/function.inc.php");
  session_start();
  if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    	Header("Location:../phpinc/main.php");
		exit();
  }

  $consumerid = trim($_GET["consumerid"]);

  $db = new cDatabase;
  //mysql_query('set names utf8');
  $consumerManager = new cConsumerPeer;
  $consumer = $consumerManager->getAnyConsumer($consumerid);
  $consumerName = $consumer->getCname();
  $consumerAddress = $consumer->getAddress();
  $consumerMemo = $consumer->getMemo();
  $consumerMobile = $consumer->getMobile();
  $consumerTelephone = $consumer->getTelephone();
    
  $user=new user;
  $consumerAgentname = $user->getUserbyId($consumer->getAgentid())->realname;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>修改消费者信息</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">姓名</td>
      <td >&nbsp;<input type=\"text\" name=\"name\" value=\"$consumerName\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">地址</td>
      <td >&nbsp;<input type=\"text\" name=\"address\" value=\"$consumerAddress\" style=\"width:300px;\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">备注</td>
      <td >&nbsp;<input type=\"text\" name=\"memo\" value=\"$consumerMemo\">可不填</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">手机</td>
      <td >&nbsp;<input type=\"text\" name=\"mobile\" value=\"$consumerMobile\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">座机</td>
      <td >&nbsp;<input type=\"text\" name=\"telephone\" value=\"$consumerTelephone\">可不填</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
      <td >&nbsp;<input type=\"text\" name=\"agentname\" value=\"$consumerAgentname\">可不填，如有需要填写正确的代理人姓名</td>
     </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改消费者信息\"></td>
    </tr>
   </table>";
?>