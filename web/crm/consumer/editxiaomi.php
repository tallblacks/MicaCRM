<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

  //require_once("../include/config.inc.php");
  session_start();
  if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)){
	Header("Location:index.php");
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
	#mysql_query('set names utf8');
	$pconsumerManager = new cConsumerPeer;
	$returnFlag = $pconsumerManager->updateXiaomid($xiaomid,$consumerid);
    if ($returnFlag){
	  $message="成功指定小蜜";
	  Header("Location:consumer.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="指定小蜜失败";
	}

	msg($message);
  }

  if (!$_POST){
    $consumerid = trim($_GET["consumerid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $db = new cDatabase;
  //mysql_query('set names utf8');
  $consumerManager = new cConsumerPeer;
  $consumer = $consumerManager->getConsumer($consumerid);
  $consumerName = $consumer->getCname();
  $consumerAddress = $consumer->getAddress();
  $consumerMemo = $consumer->getMemo();
  $consumerMobile = $consumer->getMobile();
  $consumerTelephone = $consumer->getTelephone();
  
  $consumerAgentid = $consumer->getAgentid();
  if($_SESSION['SESS_TYPE'] != ADMIN && $_SESSION['SESS_TYPE'] != SECRETARY){
    if($consumerAgentid != $_SESSION['SESS_USERID']) exit();
  }
    
  $user=new user;
  $consumerAgentname = $user->getUserbyId($consumerAgentid)->realname;
  
  $consumerXiaomid = $consumer->getXiaomid();
  $sql = "select userid,realname from user where type=5 or type=6";
  $query = $db->query($sql);
  while($data=$db->fetch_array($query)){
    $xiaomidList[] = $data['userid'];
    $xiaominameList[] = $data['realname'];
  }
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
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"consumerid\" value=\"$consumerid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>为消费者指定小蜜</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">消费者姓名</td>
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
      <td width=\"100\" height=\"27\"  align=\"right\">座机</td>
      <td >&nbsp;$consumerTelephone</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
      <td >&nbsp;$consumerAgentname</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">指定小蜜</td>
      <td >&nbsp;
        <select name=xiaomid>
          <option value=0>不指定</option>";
            $xiaomidCount = 0;
            foreach($xiaomidList as $xiaomid){
              if($consumerXiaomid == $xiaomid){
                echo "<option value=$xiaomid selected>$xiaominameList[$xiaomidCount]</option>";
              }else{
                echo "<option value=$xiaomid>$xiaominameList[$xiaomidCount]</option>";
              }
              $xiaomidCount++;
            }
  echo "</select>
      </td>
     </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认指定小蜜\"></td>
    </tr>
   </form>
   </table>";
?>