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
  $combologManager = new cCombologPeer;
  
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}

	if($doDel){ 
	  $returnFlag = $combologManager->delete($combologid,$orderid);
      if ($returnFlag){
	    $message="成功删除拼单中的订单";
	    Header("Location:editcomborderlog.php?comborderid=$comborderid&start=$start&range=$range&msg=$message");
	    exit();
	  }else{
	    $message="删除拼单中的订单失败";
	  }
	}else{
	  $message="尚未确认删除拼单中的订单";
	}
	msg($message);
  }
  
  if(!$_POST){
    $combologid = trim($_GET["combologid"]);
    $start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $combolog = $combologManager->getCombolog($combologid);
  $comborderid = $combolog->getComborderid();
  $orderid = $combolog->getOrderid();
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
  if(!empty($msg)) {
  	echo "<span class=cur>$msg</span>";
  }

echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"combologid\" value=\"$combologid\">
      <input type=\"hidden\" name=\"comborderid\" value=\"$comborderid\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
      <input type=\"hidden\" name=\"doDel\" value=\"1\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>拼单中的订单</td>
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
         <td width=\"100\" height=\"27\"  align=\"right\">从编号为</td>
         <td >&nbsp;$comborderid&nbsp;的拼单中移除？</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除拼单中的订单\"></td>
    </tr>
   </form>
   </table>";
?>