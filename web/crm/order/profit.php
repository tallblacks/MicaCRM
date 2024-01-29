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
	require_once("../include/cprofitpeer.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");

  // get parameters
  $start = trim(@$_GET["start"]);
  $range = trim(@$_GET["range"]);
  $msg = trim(@$_GET["msg"]);

  if(empty($range)){
    $range = 15;
  }

  if (empty($start)) {
	$start = 0;
    }
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  $titlebars = array("订单管理"=>"order.php","利润视图"=>"profit.php"); 
  $operations = array("利润视图"=>"profit.php");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");

    $db = new cDatabase;
    //mysql_query('set names utf8');
    $profitManager = new cProfitPeer; 
    if($_SESSION['SESS_TYPE'] == ADMIN){
      $total = $profitManager->getProfitCount(0)/7;
    }else if($_SESSION['SESS_TYPE'] == SECRETARY){
      $total = $profitManager->getProfitCountForSecretary()/7;
    }else if($_SESSION['SESS_TYPE'] == XIAOMI){
      $total = $profitManager->getProfitCountForXiaomi($_SESSION['SESS_USERID'])/7;
    }else{
      $total = $profitManager->getProfitCountByAgentid($_SESSION['SESS_USERID'])/7;
    }
    $orderidCount = 0;
    if($_SESSION['SESS_TYPE'] == ADMIN){
      $orderidList = $profitManager->getOrderidlist($start,$range);
    }else if($_SESSION['SESS_TYPE'] == SECRETARY){
      $orderidList = $profitManager->getOrderidlistForSecretary($start,$range);
    }else if($_SESSION['SESS_TYPE'] == XIAOMI){
      $orderidList = $profitManager->getOrderidlistForXiaomi($_SESSION['SESS_USERID'],$start,$range);
    }else{
      $orderidList = $profitManager->getOrderidlistByAgentid($_SESSION['SESS_USERID'],$start,$range);
    }
    $orderidCount = sizeof($orderidList);

	if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }

    if($orderidCount > 0) {
      $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
      $table .=" <tr>";
      $table .="  <td width=50% align=left nowrap class=line>";
      echo $table;
             
     if( ($start-$range) >= 0 ) {
    	$starts=$start-$range;
        echo "&laquo; <a href=\"profit.php?range=$range&start=$starts\">前$range</a>";
     } else {
       echo "&nbsp;";
     }
     echo "</td>
           <td width=50% align=right nowrap class=line>";
     if(($start+$range) < $total ) {
 	   //$range=(($start+$range-$total)<range)?($total-$start):$range;
 	   $starts=$start+$range;
       echo "<a href=\"profit.php?range=$range&start=$starts\">后$range</a> &raquo;";
     } else {
       echo " &nbsp;";
     }
 
     echo "</td></tr></table>";
   }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  if($_SESSION['SESS_TYPE'] == ADMIN){
    $table .=" <td align=center width='7%'>订单编号</td>";
    $table .="  <td align=center width='8%'>消费者</td>";
    $table .="  <td align=center width='8%'>总利润</td>";
    $table .="  <td align=center width='10%'>代理看总利润</td>";
    $table .="  <td align=center width='8%'>代理利润</td>";
    $table .="  <td align=center width='8%'>代购利润</td>";
    $table .="  <td align=center width='11%'>代理看代购利润</td>";
    $table .="  <td align=center width='7%'>Mica利润</td>";
    $table .="  <td align=center width='7%'>净利润</td>";
    $table .="  <td align=center width='7%'>优惠</td>";
    $table .="  <td align=center width='12%'>订单时间</td>";
    $table .="  <td align=center width='7%'>订单详情</td>";
  }else{
    $table .=" <td align=center width='13%'>订单编号/识别码</td>";
    $table .="  <td align=center width='18%'>消费者</td>";
    $table .="  <td align=center width='13%'>利润总和</td>";
    $table .="  <td align=center width='13%'>代理利润</td>";
    //$table .="  <td align=center width='12%'>代购利润</td>";
    if($_SESSION['SESS_TYPE'] == SECRETARY){
      $table .="  <td align=center width='13%'>净利润</td>";
    }else{
      $table .="  <td align=center width='13%'>Mica利润</td>";
    }
    $table .="  <td align=center width='13%'>优惠</td>";
    $table .="  <td align=center width='17%'>订单时间</td>";
  }
  $table .="  </tr>";

  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";

  if(!empty($orderidList)){
    $rowColor=1;
    foreach($orderidList as $orderid){
  	  $orderid = (int)$orderid;
  	  $totalProfit = 0;
  	  $totalSProfit = 0;
  	  $sagentProfit = 0;
  	  $pagentProfit = 0;
  	  $pagentSProfit = 0;
  	  $micaProfit = 0;
  	  $jProfit = 0;
		
	  $profitList = $profitManager->getProfitlist($orderid,0,100);
  	  foreach($profitList as $profit){
  	    $profit = (object)$profit;
  	    $type = $profit->getCType();
  	    switch($type){
  	      case 1:
  	        $totalProfit = $profit->getProfit();
  	        break;
  	      case 2:
  	        $totalSProfit = $profit->getProfit();
  	        break;
  	      case 3:
  	        $sagentProfit = $profit->getProfit();
  	        break;
  	      case 4:
  	        $pagentProfit = $profit->getProfit();
  	        break;
  	      case 5:
  	        $pagentSProfit = $profit->getProfit();
  	        break;
  	      case 6:
  	        $micaProfit = $profit->getProfit();
  	        break;
  	      case 7:
  	        $jProfit = $profit->getProfit();
  	        break;
  	      default:
  	        break;
  	    }
  	  }
  	      
      $orderManager = new cOrderPeer;
  	  $order = $orderManager->getOrder($orderid);
  	  $idcode = $order->getIdcode();
  	  $consumerid = $order->getConsumerid();
  	    $consumerManager = new cConsumerPeer;
  	    $consumer = $consumerManager->getConsumer($consumerid);
  	    if($consumer){
  	      $consumerName = $consumer->getCname();
  	    }else{
  	      $consumerName = "已删除";
  	    }
  	  $orderstatus = $order->getPstatus();
  	    if($orderstatus == 4) continue;
  	  $discount = $order->getDiscount();
      $create_time = $order->getCreatetime();
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }

     $table .=" <tr bgcolor=$bgcolor class=line>";
     if($_SESSION['SESS_TYPE'] == ADMIN){
       $table .="  <td align=center>$orderid</td>";
     }else{
       if($idcode){
         $table .="  <td align=center>$idcode</td>";
       }else{
         $table .="  <td align=center>$orderid</td>";
       }
     }
     $table .="  <td align=center>$consumerName</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>$totalProfit</td>";
     $table .="  <td align=center>$totalSProfit</td>";
     $table .="  <td align=center>$sagentProfit</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>$pagentProfit</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>$pagentSProfit</td>";
     $table .="  <td align=center>$micaProfit</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>$jProfit</td>";
     $table .="  <td align=center>$discount</td>";
     $table .="  <td align=center>$create_time</td>";
     if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center><a href=\"/order/orderview.php?orderid=$orderid\">订单详情</a></td></tr>";
     
     $rowColor++;
   }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>
