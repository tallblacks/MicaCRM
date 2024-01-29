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
	require_once("../include/csuborderpeer.inc.php");
	require_once("../include/cproductpeer.inc.php");
	require_once("../include/corderpeer.inc.php");
    require_once("../include/cconsumerpeer.inc.php");
	//require_once("../include/user.inc.php");
	
  // get parameters
  $orderid = trim($_GET["orderid"]);
  $idcode = trim($_GET["idcode"]);
  $msg = trim($_GET["msg"]);
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  //Agent判断
  $orderManager = new cOrderPeer;
  if($orderid){
    if(!$orderManager->getOrder($orderid)) exit();
    $order = $orderManager->getOrder($orderid);
    $idcode = $order->getIdcode();
  }else{
    if(!$orderManager->getOrderByIdcode($idcode)) exit();
    $order = $orderManager->getOrderByIdcode($idcode);
    $orderid = $order->getOrderid();
  }
  $operatorid = $order->getOperatorid();
  $consumerid = $order->getConsumerid();
    $consumerManager = new cConsumerPeer;
    $consumer = $consumerManager->getConsumer($consumerid);
    $consumerAgentid = $consumer->getAgentid();
    if($_SESSION['SESS_TYPE'] == AGENT && $consumerAgentid != $_SESSION['SESS_USERID']) exit();
  $paystatus = $order->getPaystatus();
  
  //判断付款锁定状态
  if($paystatus == 1) exit();
  
  //判断日期，秘书
  $create_time = $order->getCreatetime();
  if($_SESSION['SESS_TYPE'] == SECRETARY){
    if(substr($create_time,0,4) == "2013") exit();
    if(substr($create_time,0,4) == "2014"){
      if(substr($create_time,5,2) == "01" || substr($create_time,5,2) == "02" || substr($create_time,5,2) == "03" || substr($create_time,5,2) == "04" || substr($create_time,5,2) == "05") exit();
      if(substr($create_time,5,2) == "06"){
        //if(substr($create_time,8,2) == "01") exit();
        if(substr($create_time,8,2) < 15) exit();
      }
    }
    
    //9月10日起，如果operatorid是admin或者是小蜜的，超级小蜜不能看
    if((substr($create_time,0,4) != "2013" && substr($create_time,0,4) != "2014") || (substr($create_time,0,4) == "2014" && substr($create_time,5,1) != "0") || (substr($create_time,0,4) == "2014" && substr($create_time,5,2) == "09" && substr($create_time,8,1) != "0")){
      $user = new user;
  	  $userType = $user->getUserbyId($operatorid)->type;
      if($userType != 3 && $userType != 5) exit();
    }
  }
  
  //小蜜不能看operatorid不是自己的
  if($_SESSION['SESS_TYPE'] == XIAOMI){
    if($operatorid != $_SESSION['SESS_USERID']) exit();
  }

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
  $titlebars = array("订单管理"=>"order.php","订单产品管理"=>"editsuborder.php?orderid=$orderid"); 
  $operations = array("添加订单产品"=>"createsuborder.php?orderid=$orderid");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");

    $suborderManager = new cSuborderPeer;
    $suborderCount = 0;
	$suborderList = $suborderManager->getSuborderlist($orderid,0,100);
        if ($suborderList) {
            $suborderCount = sizeof($suborderList);
	} else {
	    $suborderCount = 0;
	}

	if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }

  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  if($_SESSION['SESS_TYPE'] == ADMIN){
    $table .=" <td align=center width='11%'>订单编号</td>";
  }else{
    $table .=" <td align=center width='11%'>订单编号/识别码</td>";
  }
  $table .="  <td align=center width='8%'>子订单编号</td>";
  $table .="  <td align=center width='26%'>产品英文名称</td>";
  $table .="  <td align=center width='25%'>产品中文名称</td>";
  $table .="  <td align=center width='6%'>售价</td>";
  $table .="  <td align=center width='6%'>数量</td>";
  $table .="  <td align=center width='10%'>建立时间</td>";
  $table .="  <td align=center width='4%'>编辑</td>";
  $table .="  <td align=center width='4%'>删除</td></tr>";

  // iterate through users, show info
  $rowColor = 1;
  $bgcolor = "";

  if(!empty($suborderList)){
    foreach($suborderList as $suborder){
  	  $suborder = (object)$suborder;
  	  $suborderid = $suborder->getSuborderid();
  	  $productid = $suborder->getProductid();
  	    $productManager = new cProductPeer;
  	    $product = $productManager->getProduct($productid);
  	    $ename = $product->getEname();
  	    $cname = $product->getCname();
  	  $price = $suborder->getPrice();
  	  $ordernum = $suborder->getOrdernum();
  	  $create_time = $suborder->getCreatetime();
      
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
     $table .="  <td align=center>$suborderid</td>";
     $table .="  <td align=center>$ename</td>";
     $table .="  <td align=center>$cname</td>";
     $table .="  <td align=center>$price</td>";
     $table .="  <td align=center>$ordernum</td>";
     $table .="  <td align=center>".substr($create_time,0,10)."</td>";
     $table .="  <td align=center >";
     $table .="   <a href=\"/order/editsubdetail.php?orderid=$orderid&productid=$productid\"><img src=\"../images/edit.gif\" align=\"bottom\" border=0></a></td>";
     $table .="  <td align=center >";
     $table .="   <a href=\"/order/delsubdetail.php?orderid=$orderid&productid=$productid\"><img src=\"../images/del.gif\" align=\"bottom\" border=0></a></td></tr>";
     $rowColor++;
   }
  }
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>
