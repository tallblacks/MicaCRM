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
	require_once("../include/cconsumerpeer.inc.php");
	
  // get parameters
  $comborderid = trim($_GET["comborderid"]);
  $start = trim($_GET["start"]);
  $range = trim($_GET["range"]);
  $msg = trim($_GET["msg"]);

  if(empty($range)){
    $range = 20;
  }
?>
<html><head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  $titlebars = array("拼单管理"=>"comborder.php","修改拼单内订单"=>""); 
  $operations = array("修改拼单内订单"=>"editcomborderlog.php?comborderid=$comborderid&start=$start&range=$range");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");

  $db = new cDatabase;
  //mysql_query('set names utf8');
  $orderManager = new cOrderPeer;
  
  $combologManager = new cCombologPeer;
  $combologCount = 0;
  $combologList = $combologManager->getCombologList($comborderid);
  $combologCount = sizeof($combologList);

  if(!empty($msg)) {
  	echo "<span class=cur>$msg</span>";
  }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .=" <td align=center width='12%'>订单编号</td>";
  $table .="  <td align=center width='12%'>消费者</td>";
  $table .="  <td align=center width='8%'>重Kg</td>";
  $table .="  <td align=center width='9%'>实收运</td>";
  $table .="  <td align=center width='9%'>实际运</td>";
  $table .="  <td align=center width='9%'>订单价格</td>";
  $table .="  <td align=center width='9%'>真总成本</td>";
  $table .="  <td align=center width='12%'>订单时间</td>";
  $table .="  <td align=center width='12%'>付款状态</td>";
  $table .="  <td align=center width='8%'>移出拼单</td></tr>";

  // iterate through users, show info
  $rowColor = 1;
  $bgcolor = "";

  if(!empty($combologList)){
    foreach($combologList as $combolog){
  	  $combolog = (object)$combolog;
  	  $combologid = $combolog->getCombologid();
  	  $orderid = $combolog->getOrderid();
  	    $order = $orderManager->getOrder($orderid);
        $consumerid = $order->getConsumerid();
  	      $consumerManager = new cConsumerPeer;
  	      //有删除的用户，所以这里用Any
  	      //$consumer = $consumerManager->getConsumer($consumerid);
  	      $consumer = $consumerManager->getAnyConsumer($consumerid);
  	      $consumerName = $consumer->getCname();
  	      $consumerStatus = $consumer->getPstatus();
  	      if($consumerStatus == 1) $consumerName = $consumerName."（已删除用户）";
  	    $weight = $order->getWeight();
          $weightKG = $weight/1000;
  	    $freight = $order->getFreight();
  	    $pfreight = $order->getPfreight();
        $price = $order->getPrice();
        $pprice = $order->getPprice();
        $discount = $order->getDiscount();
  	    $paystatus = $order->getPaystatus();
  	    $create_time = $order->getCreatetime();
  	    
  	    $paystatus = $order->getPaystatus();//为锁定修改
      
      if( $rowColor%2 == 0 ) {
	    $bgcolor = "#ffffcc";
      } else {
	    $bgcolor = "#eeeeee";
      }
      
      //if($paystatus == 1) $bgcolor = "#FF9999";

      $table .=" <tr bgcolor=$bgcolor class=line>";
      $table .="  <td align=center>$orderid</td>";
      $table .="  <td align=center>$consumerName</td>";
      $table .="  <td align=center>$weightKG</td>";
      $table .="  <td align=center>$freight</td>";
      $table .="  <td align=center>$pfreight</td>";
      $table .="  <td align=center>$price</td>";
      $table .="  <td align=center>$pprice</td>";
      $table .="  <td align=center>".substr($create_time,0,10)."</td>";
      if($paystatus == 0){
        $table .="  <td align=center>未付款</td>";
      }else{
        $table .="  <td align=center>已付款</td>";
      }
      //if($paystatus == 1){
        //$table .="  <td align=center>不可移出/锁</td></tr>";
      //}else{
        $table .="  <td align=center><a href=\"delsubcomborder.php?combologid=$combologid&start=$start&range=$range\">移出拼单</a></td></tr>";
      //}
      $rowColor++;
   }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>