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
  require_once("../include/clogisticspeer.inc.php");
  //require_once("../include/function.inc.php");
  
  // get parameters
  $comborderid = trim($_GET["comborderid"]);
  $start = trim($_GET["start"]);
  $range = trim($_GET["range"]);
  $msg = trim($_GET["msg"]);
  
  /*$doSearch = trim($_POST["dosearch"]);
  
  if($doSearch){
    $orderid = trim($_POST["orderid"]);
    $search = trim($_POST["search"]);
  }else{//非第一搜索页
    $doSearch = trim($_GET["dosearch"]);
    if($doSearch){//搜索＋翻页
      $orderid = trim($_GET["orderid"]);
      $search = trim($_GET["search"]);
    }else{//非搜索情况
      $orderid = trim($_GET["orderid"]);
      $search = "";
    }
  }*/

    echo "<html><head>
          <title></title>
          <link rel=stylesheet type=text/css href=\"/style/global.css\">
          </head>
          <BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
          <center>";
          
    $titlebars = array("拼单管理"=>"comborder.php","添加拼单订单"=>""); 
    $operations = array("拼单管理"=>"comborder.php");
    $jumptarget = "cmsright";
    include("../phpinc/titlebar.php");
    /*echo "<form name=searchproduct method=post action=createsuborder.php>
          <input type=hidden name=dosearch value=1>
          <input type=hidden name=orderid value=$orderid>
          <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
          <td>输入产品名称，中英文均可，<font color=red><b>注意，搜索区分大小写！</b></font></td>
          <td><input type=type name=search value=$search></td>
          <td><input type=submit name=submit value=模糊搜索></td>
          </tr>
          </table>
          </form>";*/

    $db = new cDatabase;
    //mysql_query('set names utf8');
    $orderManager = new cOrderPeer;
	$total = $orderManager->getOrderByTypeCount(2);
	$orderCount = 0;
	$orderList = $orderManager->getOrderByTypeList(2,0,1000);
	$orderCount = sizeof($orderList);

	if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }

    /*if($orderCount > 0) {
      $table ="<table cellpadding=1 cellspacing=1 border=0 width=100%>";
      $table .=" <tr>";
      $table .="  <td width=40% align=left nowrap class=line>";
      echo $table;
             
      if( ($start-$range) >= 0 ) {
    	$starts=$start-$range;
        echo "&laquo; <a href=\"createsuborder.php?status=$status&range=$range&start=$starts&orderid=$orderid&dosearch=$dosearch&search=$search\">前$range</a>";
      } else {
        echo "&nbsp;";
      }
      echo "</td>";
      if($doSearch){
        echo "<td width=20% align=center nowrap class=line>共搜索出&nbsp;$total&nbsp;个产品</td>";
      }else{
        echo "<td width=20% align=center nowrap class=line>共有&nbsp;$total&nbsp;款产品</td>";
      }
      echo "<td width=40% align=right nowrap class=line>";
      if(($start+$range) < $total ) {
 	    //$range=(($start+$range-$total)<range)?($total-$start):$range;
 	    $starts=$start+$range;
        echo "<a href=\"createsuborder.php?status=$status&range=$range&start=$starts&orderid=$orderid&dosearch=$dosearch&search=$search\">后$range</a> &raquo;";
      } else {
        echo " &nbsp;";
      }
 
      echo "</td></tr></table>";*/
    
      $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
      $table .=" <tr bgcolor=#dddddd class=tine>";
      $table .=" <td align=center width='10%'>订单编号</td>";
      $table .="  <td align=center width='10%'>消费者</td>";
      $table .="  <td align=center width='12%'>物流单号</td>";
      $table .="  <td align=center width='9%'>实收运</td>";
      $table .="  <td align=center width='9%'>实际运</td>";
      $table .="  <td align=center width='9%'>订单价格</td>";
      $table .="  <td align=center width='9%'>真总成本</td>";
      $table .="  <td align=center width='12%'>时间</td>";
      $table .="  <td align=center width='12%'>付款</td>";
      $table .="  <td align=center width='8%'>加入拼单</td></tr>";

      // iterate through users, show info
      //$rowColor = 0;
      $bgcolor = "";

      if(!empty($orderList)){
        $rowColor=1;
        foreach($orderList as $order){
  	    $order = (object)$order;
  	    $orderid = $order->getOrderid();
  	    $consumerid = $order->getConsumerid();
  	    $consumerManager = new cConsumerPeer;
  	    $consumer = $consumerManager->getConsumer($consumerid);
  	    $consumerName = $consumer->getCname();
  	    $weight = $order->getWeight();
            $weightKG = $weight/1000;
  	    $freight = $order->getFreight();
  	    $pfreight = $order->getPfreight();
            $price = $order->getPrice();
            $pprice = $order->getPprice();
            $logisticsid = $order->getLogisticsid();
            $logisticscode = $order->getLogisticscode();
            $discount = $order->getDiscount();
  	    $paystatus = $order->getPaystatus();
  	    $create_time = $order->getCreatetime();
          
            if( $rowColor%2 == 0 ) {
	       $bgcolor = "#ffffcc";
            } else {
	       $bgcolor = "#eeeeee";
            }

            $table .=" <tr bgcolor=$bgcolor class=line>";
            $table .="  <td align=center><a href=pub2sagent.php?orderid=$orderid target=_ablank>$orderid</a></td>";
            $table .="  <td align=center>$consumerName</td>";
            //$table .="  <td align=center>$weightKG</td>";
            if($logisticscode == null || $logisticscode == ""){
                $table .="  <td align=center></td>";
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
          
          
          
          $table .="  <td align=center>$freight</td>";
          $table .="  <td align=center>$pfreight</td>";
          $table .="  <td align=center>$price</td>";
          $table .="  <td align=center>$pprice</td>";
          $table .="  <td align=center>".substr($create_time,0,10)."</td>";
          if($paystatus == 0){
            $table .="  <td align=center><font color=red><b>未付款</b></font></td>";
          }else{
            $table .="  <td align=center>已付款</td>";
          }
          $table .="  <td align=center><a href=\"createsubcomborder.php?comborderid=$comborderid&orderid=$orderid&start=$start&range=$range\">加入拼单</a></td></tr>";
          $rowColor++;
        }
      }
      $table .="</table></center>";
      $table .="</BODY></html>";
    
      echo $table;
    //}
?>