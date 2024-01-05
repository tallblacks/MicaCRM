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
  require_once("../include/clogisticspeer.inc.php");
  require_once("../include/ccomborderpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	if($orderid > 0){
	  if($logisticscode == "" || $logisticscode == null){
	    $logisticscode = $logisticscodemulti;
	  }
	  $orderManager = new cOrderPeer;
	  $returnFlag = $orderManager->updateLogistics($orderid,$logisticsid,$logisticscode);
	}
	if($comborderid > 0){
	  $comborderManager = new cComborderPeer;
	  $returnFlag = $comborderManager->updateLogistics($comborderid,$logisticsid,$logisticscode);
	}
  	
    if ($returnFlag){
	  $message="成功修改物流信息";
	  if($orderid > 0) Header("Location:order.php?start=$start&range=$range&msg=$message");
	  if($comborderid > 0) Header("Location:comborder.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="修改物流信息失败";
	}

	msg($message);
  }else{
    $orderid = trim($_GET["orderid"]);
    $comborderid = trim($_GET["comborderid"]);
    $start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
    
    //for edit
    $db = new cDatabase;
    $orderManager = new cOrderPeer;
    $order = $orderManager->getOrder($orderid);
    $logisticsidOrder = $order->getLogisticsid();
    $logisticscode = $order->getLogisticscode();
    if(strlen($logisticscode) > 20){
      $logisticscodemulti = $logisticscode;
      $logisticscode = "";
    }
    
    $logisticsManager = new cLogisticsPeer;
	//$logisticsCount = 0;
	$logisticsList = $logisticsManager->getLogisticslist(0,0,100);
	//$logisticsCount = sizeof($logisticsList);
	
	$logisticsStr = "";
	foreach($logisticsList as $logistics){
  	  $logistics = (object)$logistics;
  	  $logisticsid = $logistics->getLogisticsid();
  	  $logisticsname = $logistics->getCName();
  	  
  	  if($logisticsidOrder == $logisticsid){
  	    $logisticsStr .= "<input type=\"radio\" name=\"logisticsid\" value=\"$logisticsid\" checked>$logisticsname";
  	  }else{
  	    $logisticsStr .= "<input type=\"radio\" name=\"logisticsid\" value=\"$logisticsid\">$logisticsname";
      }
    }
    
    if($orderid > 0) $subStr = "为订单添加物流单号";
    if($comborderid > 0) $subStr = "为拼单添加物流单号";
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
  if(!empty($msg)) {	
    echo "<span class=cur>$msg</span>";
  }
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"comborderid\" value=\"$comborderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>$subStr</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">选择物流公司</td>
         <td >&nbsp;$logisticsStr</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">一张物流单号</td>
         <td >&nbsp;<input type=\"text\" name=\"logisticscode\" value=\"$logisticscode\"></td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">多张物流单号</td>
         <td >&nbsp;<textarea rows=\"6\" name=\"logisticscodemulti\">$logisticscodemulti</textarea>注意，仅支持普通订单物流！暂不支持拼单物流！对于百世汇通单号之间回车分割！对于申通单号之间用%0d%0a分割！</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改物流信息\"></td>
    </tr>
   </form>
   </table>";
?>