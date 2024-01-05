<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
    session_start();
    if (!isset($_SESSION["SESS_USERID"])){
    	Header("Location:../phpinc/main.php");
		exit();
    }

  //require_once("../include/mysql.inc.php");
  require_once("../include/corderpeer.inc.php");
  require_once("../include/cconstantspeer.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
  	  $weight = 0;
  	  $freight = 0;
  	  $pfreight = 0;
  	  $price = 0;
  	  $pprice = 0;
  	  $spprice = 0;
  	  $logisticsid = 0;
  	  $logisticscode = null;
  	  $discount = 0;
  	  $cfreight = 0;
  	  $cpfreight = 0;
  	  $paystatus = 0;
  	  $memo = null;
  	  $type = 1;
  	  $status = 0;
  	  
  	  $operatorid = $_SESSION['SESS_USERID'];
  	
	  $new_order = new cOrder;
	  $new_order->setOperatorid($operatorid);
	  $new_order->setConsumerid($consumerid);
      $new_order->setNRate($rate);
      $new_order->setWeight($weight);
      $new_order->setFreight($freight);
      $new_order->setPfreight($pfreight);
      $new_order->setPrice($price);
      $new_order->setPprice($pprice);
      $new_order->setSpprice($spprice);
      $new_order->setLogisticsid($logisticsid);
      $new_order->setLogisticscode($logisticscode);
      $new_order->setDiscount($discount);
      $new_order->setCfreight($cfreight);
      $new_order->setCpfreight($cpfreight);
      $new_order->setPaystatus($paystatus);
      $new_order->setMemo($memo);
      $new_order->setCtype($type);
      $new_order->setPstatus($status);
	
	  $orderManager = new cOrderPeer;
	  $returnFlag = $orderManager->create($new_order);
      if ($returnFlag){
	    $message="成功创建新订单";
	  }else{
	    $message="创建新订单失败";
	  }

	msg($message);
  }else{
    $consumerid = trim($_GET["consumerid"]);
    $consumerManager = new cConsumerPeer;
  	$consumer = $consumerManager->getConsumer($consumerid);
  	$consumerName = $consumer->getCname();
  	$consumerAgentid = $consumer->getAgentid();
    
    $constantsManager = new cConstantsPeer;
    $rate = $constantsManager->getNewConstants(1);
  }
  
  if($_SESSION['SESS_TYPE'] == AGENT && $consumerAgentid != $_SESSION['SESS_USERID']) exit();
  
  if($_SESSION['SESS_TYPE'] == ADMIN){
    $rateStr1 = "";
    $rateStr2 = "<input type=\"text\" name=\"rate\" value=\"$rate\">可以为本订单设定特殊汇率";
  }else{
    $rateStr1 = "<input type=\"hidden\" name=\"rate\" value=\"$rate\">";
    $rateStr2 = "$rate";
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href=/style/global.css>
<!--script type="text/javascript" src="/ajaxserver/ajax2013.js"></script-->
<?php
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"consumerid\" value=\"$consumerid\">
      $rateStr1
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>添加新订单</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">下单用户</td>
         <td >&nbsp;$consumerName</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">当前汇率</td>
         <td >&nbsp;$rateStr2</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认添加订单\"></td>
    </tr>
   </form>
   </table>";
?>