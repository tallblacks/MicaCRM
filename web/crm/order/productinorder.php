<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
	session_start();
	if (!isset($_SESSION["SESS_USERID"]) || $_SESSION['SESS_TYPE'] != ADMIN){
		Header("Location:index.php");
		exit();
	}
	//require_once("../include/mysql.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/user.inc.php");
	require_once("../include/clogisticspeer.inc.php");
	require_once("../include/csuborderpeer.inc.php");
	require_once("../include/ccombologpeer.inc.php");
	
  // get parameters
  $productid = trim($_GET["productid"]);
  $start = trim($_GET["start"]);
  $range = trim($_GET["range"]);
  $msg = trim($_GET["msg"]);

  if(empty($range)){
    $range = 15;
  }
?>
<html><head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href="/style/global.css">
<script type="text/javascript" src="/ajaxjs/crm_paystatus.js"></script>
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  $titlebars = array("订单管理"=>"order.php","货品in订单查询"=>""); 
  $operations = array("订单管理"=>"order.php");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");

  $db = new cDatabase;
  //mysql_query('set names utf8');
  $suborderManager = new cSuborderPeer;
  $total = $suborderManager->getSuborderCountByProductid($productid);
  $orderCount = 0;
  $orderList = $suborderManager->getSuborderlistByProductid($productid, $start, $range);
  $orderCount = sizeof($orderList);

  if(!empty($msg)) {
  	echo "<span class=cur>$msg</span>";
  }

  if($orderCount > 0) {
    $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
    $table .=" <tr>";
    $table .="  <td width=40% align=left nowrap class=line>";
    echo $table;
             
    if( ($start-$range) >= 0 ) {
      $starts=$start-$range;
      echo "&laquo; <a href=\"productinorder.php?productid=$productid&range=$range&start=$starts\">前$range</a>";
    } else {
      echo "&nbsp;";
    }
    echo "</td>";
    echo "<td width=20% align=center nowrap class=line>共有&nbsp;$total&nbsp;个有效订单</td>";
    echo "<td width=40% align=right nowrap class=line>";
    if(($start+$range) < $total ) {
 	  //$range=(($start+$range-$orderCount)<range)?($orderCount-$start):$range;
 	  $starts=$start+$range;
      echo "<a href=\"productinorder.php?productid=$productid&range=$range&start=$starts\">后$range</a> &raquo;";
    } else {
      echo " &nbsp;";
    }
 
    echo "</td></tr></table>";
  }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .=" <td align=center width='10%'>订单编号</td>";
  $table .="  <td align=center width='12%'>消费者</td>";
  $table .="  <td align=center width='8%'>订购数量</td>";
  $table .="  <td align=center width='20%'>物流单号</td>";
  $table .="  <td align=center width='8%'>订单类型</td>";
  $table .="  <td align=center width='13%'>拼单单号</td>";
  $table .="  <td align=center width='13%'>时间</td>";
  $table .="  <td align=center width='8%'>付款</td>";
  $table .="  <td align=center width='8%'>代理出单</td>";
  $table .="  </tr>";

  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";

  if(!empty($orderList)){
    $rowColor=1;
    $orderManager = new cOrderPeer;
    
    foreach($orderList as $suborder){
      $suborder = (object)$suborder;
      $orderid = $suborder->getOrderid();
      $order = $orderManager->getOrder($orderid);
      
      $sellSingleProductNumber = $suborder->getOrdernum();

  	  $consumerid = $order->getConsumerid();
  	    $consumerManager = new cConsumerPeer;
  	    $consumer = $consumerManager->getConsumer($consumerid);
  	    if($consumer != null){
  	    	$consumerName = $consumer->getCname();
  	    	if(($_SESSION['SESS_USERID'] != $consumer->getAgentid()) && ($_SESSION['SESS_TYPE'] != ADMIN)) continue;
  	    }else{
  	    	$consumerName = "";
  	    	if($_SESSION['SESS_TYPE'] != ADMIN) continue;
  	    }
  	    //if(($_SESSION['SESS_USERID'] != $consumer->getAgentid()) && ($_SESSION['SESS_TYPE'] != ADMIN)) continue;
  	  $weight = $order->getWeight();
  	  $weightKG = $weight/1000;
      $logisticsid = $order->getLogisticsid();
      $logisticscode = $order->getLogisticscode();
      $paystatus = $order->getPaystatus();
      $type = $order->getCType();
      if($type == 1){
        $typeStr = "普通订单";
        
        $comborderid = "&nbsp;";
      }else{//type=2,3 均为拼单
        $typeStr = "<font color=green><b>拼单订单</b></font>";
        
        $combologManager = new cCombologPeer;
        $comborderid = $combologManager->getComborderidByOrderid($orderid);
      }
      $status = $order->getPstatus();
      $create_time = $order->getCreatetime();
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }

     $table .=" <tr bgcolor=$bgcolor class=line>";
     $table .="  <td align=center>$orderid</td>";
     $table .="  <td align=center>$consumerName</td>";
     $table .="  <td align=center>$sellSingleProductNumber</td>";
     if($logisticscode == null || $logisticscode == ""){
       $table .="  <td align=center>";
       $table .="  <a href=\"/order/createlogisticscode.php?orderid=$orderid&start=$start&range=$range\">加物流</a>";
       $table .="  </td>";
     }else{
       $logisticsManager = new cLogisticsPeer;
       $logistics = $logisticsManager->getLogistics($logisticsid);
       $preurl = $logistics->getPreurl();
       if($preurl == null || $preurl == ""){
         $table .="  <td align=center>$logisticscode|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
       }else if($preurl == "chinz56"){
         $table .="  <td align=center><a href=../logistics/prechinz56.php?logisticscode=".$logisticscode." target=_ablank>$logisticscode</a>|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
       }else if($preurl == "800bestex"){
         $table .="  <td align=center><a href=../logistics/pre800bestex.php?orderid=".$orderid." target=_ablank>$logisticscode</a>|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
       }else{
         if(strlen($logisticscode) > 20){
           $logisticscodeDsp = substr($logisticscode,0,13)."...";
         }else{
           $logisticscodeDsp = $logisticscode;
         }
         $table .="  <td align=center><a href=$preurl".$logisticscode." target=_ablank>$logisticscodeDsp</a>|<a href=\"/order/editlogisticscode.php?orderid=$orderid&start=$start&range=$range\">改</a></td>";
       }
     }
     $table .="  <td align=center>$typeStr</td>";
     $table .="  <td align=center>$comborderid</td>";
     $table .="  <td align=center>$create_time</td>";
     if($paystatus == 0){
       $table .="  <td align=center><font color=red>未付款</font></td>";
     }else{
       $table .="  <td align=center>已付款</td>";
     }
     $table .="  <td align=center><a href=/order/pub2sagent.php?orderid=$orderid target=_ablank>代理出单</a></td>";
     $table .="</tr>";
     $rowColor++;
   }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>