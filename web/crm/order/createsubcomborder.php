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
  require_once("../include/corderpeer.inc.php");
  require_once("../include/ccombologpeer.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
  	$status = 0;
  	  
  	$combologManager = new cCombologPeer;
	$combolog = new cCombolog;
    $combolog->setComborderid($comborderid);
    $combolog->setOrderid($orderid);
    $combolog->setPstatus($status);
	$returnFlag = $combologManager->create($combolog);
    if ($returnFlag){
	  $message="成功在拼单中添加订单";
	  Header("Location:createcomborderlog.php?comborderid=$comborderid&start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="在拼单中添加订单失败";
	}

	msg($message);
  }
  
  if(!$_POST){
    $comborderid = trim($_GET["comborderid"]);
    $orderid = trim($_GET["orderid"]);
    $start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }

  $orderManager = new cOrderPeer;
  $order = $orderManager->getOrder($orderid);
  $consumerid = $order->getConsumerid();
    $consumerManager = new cConsumerPeer;
  	$consumer = $consumerManager->getConsumer($consumerid);
  	$consumerName = $consumer->getCname();
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
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"comborderid\" value=\"$comborderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>订单加入拼单</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">确认将</td>
         <td >&nbsp;$consumerName</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">编号为</td>
         <td >&nbsp;$orderid&nbsp;的订单</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">加入到编号为</td>
         <td >&nbsp;$comborderid&nbsp;的拼单中？</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认加入拼单\"></td>
    </tr>
   </form>
   </table>";
?>