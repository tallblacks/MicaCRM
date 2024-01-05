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
  require_once("../include/cprofitpeer.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}

	$orderManager = new cOrderPeer;
	$returnFlag = $orderManager->delete($orderid);
    if ($returnFlag){
	  $message="成功删除订单";
	  Header("Location:order.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="删除订单失败";
	}

	msg($message);
  }
  
  if (!$_POST){
    $orderid = trim($_GET["orderid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $doNotDelFlag = false;
  
  $orderManager = new cOrderPeer;
  $order = $orderManager->getOrder($orderid);
  $price = $order->getPrice();
  $weight = $order->getWeight();
  if($price != 0 || $weight != 0){
    $doNotDelFlag = true;
  }
  if(!$doNotDelFlag){
    $profitManager = new cProfitPeer;
    $profitcount = $profitManager->getProfitCount($orderid);
    if($profitcount != 0){//有利润数据
      for($i=1;$i<8;$i++){
        $profit = $profitManager->getProfit($orderid,$i);
        $profitvalue = $profit->getProfit();
        if($profitvalue != 0){
          $doNotDelFlag = true;
        }
      }
    }
  }
  if(!$doNotDelFlag){
    $consumerid = $order->getConsumerid();
    $rate = $order->getNRate();
    $consumerManager = new cConsumerPeer;
  	$consumer = $consumerManager->getConsumer($consumerid);
  	$consumerName = $consumer->getCname();
  }
  
  $paystatus = $order->getPaystatus();
  //判断付款锁定状态
  if($paystatus == 1) exit();
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
  if($doNotDelFlag){
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>删除订单</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\">订单内有数据时不可删除订单！</td>
    </tr>
   </table>";
  }else{
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>删除订单</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">订单编号</td>
         <td >&nbsp;$orderid</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">下单用户</td>
         <td >&nbsp;$consumerName</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除订单\"></td>
    </tr>
   </form>
   </table>";
   }
?>