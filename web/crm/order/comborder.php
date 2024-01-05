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
	require_once("../include/ccomborderpeer.inc.php");
	require_once("../include/ccombologpeer.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/clogisticspeer.inc.php");
	
  // get parameters
  $start = trim(@$_GET["start"]);
  $range = trim(@$_GET["range"]);
  $msg = trim(@$_GET["msg"]);

  if(empty($range)){
    $range = 15;
  }
  
  /*$doSearch = trim($_POST["dosearch"]);
  
  if($doSearch){
    $search = trim($_POST["search"]);
    $start = trim($_POST["start"]);
  }else{//非第一搜索页
    $doSearch = trim($_GET["dosearch"]);
    if($doSearch){//搜索＋翻页
      $search = trim($_GET["search"]);
    }else{//非搜索情况
      $search = "";
    }
  }
  
  if($search != "" || $search != null){
    if($search < 10000000 || $search > 99999999){
      $search = "";
      $doSearch = 0;
    }else{
      $consumerid = $search;
    }
  }*/
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
  $titlebars = array("拼单管理"=>"comborder.php"); 
  $operations = array("新拼单"=>"createcomborder.php");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");
  
  /*echo "<form name=searchconsumer method=post action=>
          <input type=hidden name=dosearch value=1>
          <input type=hidden name=start value=0>
          <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
          <td>按消费者的“客户编号”搜索订单</td>
          <td><input type=type name=search value=$search></td>
          <td><input type=submit name=submit value=查询></td>
          </tr>
          </table>
          </form>";*/

    $db = new cDatabase;
    //mysql_query('set names utf8');
    $comborderManager = new cComborderPeer;
	$total = $comborderManager->getComborderCount();
	$comborderCount = 0;
	$comborderList = $comborderManager->getComborderlist($start, $range);
	$comborderCount = sizeof($comborderList);

	if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }

    if($comborderCount > 0) {
      $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
      $table .=" <tr>";
      $table .="  <td width=40% align=left nowrap class=line>";
      echo $table;
             
     if( ($start-$range) >= 0 ) {
    	$starts=$start-$range;
        //echo "&laquo; <a href=\"comborder.php?range=$range&start=$starts&dosearch=$doSearch&search=$search\">前$range</a>";
        echo "&laquo; <a href=\"comborder.php?range=$range&start=$starts\">前$range</a>";
     } else {
       echo "&nbsp;";
     }
     echo "</td>
       <td width=20% align=center nowrap class=line>共有&nbsp;$total&nbsp;个有效拼单</td>
       <td width=40% align=right nowrap class=line>";
     if(($start+$range) < $total ) {
 	   //$range=(($start+$range-$orderCount)<range)?($orderCount-$start):$range;
 	   $starts=$start+$range;
       //echo "<a href=\"comborder.php?range=$range&start=$starts&dosearch=$doSearch&search=$search\">后$range</a> &raquo;";
       echo "<a href=\"comborder.php?range=$range&start=$starts\">后$range</a> &raquo;";
     } else {
       echo " &nbsp;";
     }
 
     echo "</td></tr></table>";
   }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .=" <td align=center width='7%'>拼单编号</td>";
  $table .="  <td align=center width='7%'>收单人</td>";
  $table .="  <td align=center width='4%'>货重</td>";
  $table .="  <td align=center width='6%'>实收运</td>";
  $table .="  <td align=center width='6%'>实际运</td>";
  $table .="  <td align=center width='7%'>订单价格</td>";
  $table .="  <td align=center width='7%'>真总成本</td>";
  $table .="  <td align=center width='11%'>物流单号</td>";
  $table .="  <td align=center width='8%'>本单操作</td>";
  $table .="  <td align=center width='14%'>备注</td>";
  $table .="  <td align=center width='7%'>时间</td>";
  $table .="  <td align=center width='8%'>付款状态</td>";
  $table .="  <td align=center width='4%'>编辑</td>";
  $table .="  <td align=center width='4%'>删除</td></tr>";

  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";

  if(!empty($comborderList)){
    $rowColor=1;
    foreach($comborderList as $comborder){
  	  $comborder = (object)$comborder;
  	  $comborderid = $comborder->getComborderid();
  	  $name = $comborder->getCname();
  	  $memo = $comborder->getMemo();
  	  $weight = 0;
  	  $freight = 0;
  	  $pfreight = 0;
      $price = 0;
      $pprice = 0;
      $paystatusAll = true;
      $paystatusNone = true;
  	    $combologManager = new cCombologPeer;
  	    $combologCount = 0;
        $combologList = $combologManager->getCombologList($comborderid);
        $combologCount = sizeof($combologList);
        if(!empty($combologList)){
          $orderManager = new cOrderPeer;
          foreach($combologList as $combolog){
  	        $combolog = (object)$combolog;
  	        $combologid = $combolog->getCombologid();
  	        $orderid = $combolog->getOrderid();
  	        $order = $orderManager->getOrder($orderid);
  	        $weightOrder = $order->getWeight();
  	          $weight = $weight+$weightOrder;
  	        $freightOrder = $order->getFreight();
  	          $freight = $freight+$freightOrder;
  	        $pfreightOrder = $order->getPfreight();
  	          $pfreight = $pfreight+$pfreightOrder;
            $priceOrder = $order->getPrice();
              $price = $price+$priceOrder;
            $ppriceOrder = $order->getPprice();
              $pprice = $pprice+$ppriceOrder;
            $paystatus = $order->getPaystatus();
              if($paystatus == 0) $paystatusAll = false;
              if($paystatus == 1) $paystatusNone = false;
  	      }
  	  } 
  	  $weightKG = $weight/1000;
      $logisticsid = $comborder->getLogisticsid();
      $logisticscode = $comborder->getLogisticscode();
      $type = $comborder->getCType();
      $status = $comborder->getPstatus();
      $create_time = $comborder->getCreatetime();
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }

     $table .=" <tr bgcolor=$bgcolor class=line>";
     $table .="  <td align=center>$comborderid</td>";
     $table .="  <td align=center>$name</td>";
     $table .="  <td align=center>$weightKG</td>";
     $table .="  <td align=center>$freight</td>";
     $table .="  <td align=center>$pfreight</td>";
     $table .="  <td align=center>$price</td>";
     $table .="  <td align=center>$pprice</td>";
     if($logisticscode == null || $logisticscode == ""){
       $table .="  <td align=center><a href=\"/order/createlogisticscode.php?comborderid=$comborderid&start=$start&range=$range\">加物流</a></td>";
     }else{
       $logisticsManager = new cLogisticsPeer;
       $logistics = $logisticsManager->getLogistics($logisticsid);
       $preurl = $logistics->getPreurl();
       if($preurl == null || $preurl == ""){
         $table .="  <td align=center>$logisticscode</td>";
       }else if($preurl == "chinz56"){
         $table .="  <td align=center><a href=../logistics/prechinz56.php?logisticscode=".$logisticscode." target=_ablank>$logisticscode</a></td>";
       }else{
         $table .="  <td align=center><a href=$preurl".$logisticscode." target=_ablank>$logisticscode</a></td>";
       }
     }
     $table .="  <td align=center><a href=\"createcomborderlog.php?comborderid=$comborderid&start=$start&range=$range\">加单</a>|<a href=\"editcomborderlog.php?comborderid=$comborderid&start=$start&range=$range\">改单</a></td>";
     $table .="  <td align=center>$memo</td>";
     $table .="  <td align=center>".substr($create_time,0,10)."</td>";
     if(empty($combologList)){
       $table .="  <td align=center></td>";
     }else if($paystatusAll){
       $table .="  <td align=center>都已付款</td>";
     }else if($paystatusNone){
       $table .="  <td align=center><font color=red>未付款</font></td>";
     }else{
       $table .="  <td align=center><font color=red>部分已付</font></td>";
     }
     $table .="  <td align=center>";
     $table .="   <a href=\"editcomborder.php?comborderid=$comborderid&start=$start&range=$range\"><img src=\"../images/edit.gif\" align=\"bottom\" border=0></a></td>";
     $table .="  <td align=center >";
     $table .="   <a href=\"delcomborder.php?comborderid=$comborderid&start=$start&range=$range\"><img src=\"../images/del.gif\" align=\"bottom\" border=0></a></td></tr>";
     $rowColor++;
   }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>