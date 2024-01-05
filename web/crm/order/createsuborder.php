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
  require_once("../include/cproductpeer.inc.php");
  require_once("../include/corderpeer.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  
  //require_once("../include/cconstantspeer.inc.php");
  //require_once("../include/function.inc.php");
  
  // get parameters
  $status = trim(@$_GET["status"]);
  $start = trim(@$_GET["start"]);
  $range = trim(@$_GET["range"]);
  $msg = trim(@$_GET["msg"]);
  $status = 0;
  if(empty($range)){
    $range = 10;
  }
  
  $dosearch = trim(@$_POST["dosearch"]);
  
  if($dosearch){
    $orderid = trim(@$_POST["orderid"]);
    $search = trim(@$_POST["search"]);
  }else{//非第一搜索页
    $dosearch = trim(@$_GET["dosearch"]);
    if($dosearch){//搜索＋翻页
      $orderid = trim(@$_GET["orderid"]);
      $search = trim(@$_GET["search"]);
    }else{//非搜索情况
      $orderid = trim(@$_GET["orderid"]);
      $idcode = trim(@$_GET["idcode"]);
      $search = "";
    }
  }
  
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
  $consumerid = $order->getConsumerid();
    $consumerManager = new cConsumerPeer;
    $consumer = $consumerManager->getConsumer($consumerid);
    $consumerAgentid = $consumer->getAgentid();
    if($_SESSION['SESS_TYPE'] == AGENT && $consumerAgentid != $_SESSION['SESS_USERID']) exit();
  $paystatus = $order->getPaystatus();
  $operatorid = $order->getOperatorid();
  
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

    echo "<html><head>
          <title></title>
          <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
          <link rel=stylesheet type=text/css href=\"/style/global.css\">
          </head>
          <BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
          <center>";
          
    $titlebars = array("订单管理"=>"order.php","添加订单产品"=>""); 
    $operations = array("订单管理"=>"order.php");
    $jumptarget = "cmsright";
    include("../phpinc/titlebar.php");
    echo "<form name=searchproduct method=post action=createsuborder.php>
          <input type=hidden name=dosearch value=1>
          <input type=hidden name=orderid value=$orderid>
          <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
          <td>输入产品名称，中英文均可，<font color=red><b>注意，搜索区分大小写！</b></font></td>
          <td><input type=type name=search value=$search></td>
          <td><input type=submit name=submit value=模糊搜索></td>
          </tr>
          </table>
          </form>";

    
    $productManager = new cProductPeer;
	$total = $productManager->getProductCount($status, $search);
	$productCount = 0;
	$productList = $productManager->getProductlist($status, $start, $range, $search);
	$productCount = sizeof($productList);

	if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }

    if($productCount > 0) {
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
      if($dosearch){
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
 
      echo "</td></tr></table>";
    
      $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
      $table .=" <tr bgcolor=#dddddd class=tine>";
      $table .=" <td align=center width='38%'>英文名</td>";
      $table .="  <td align=center width='28%'>中文名</td>";
      $table .="  <td align=center width='4%'>规格</td>";
      $table .="  <td align=center width='4%'>单位</td>";
      $table .="  <td align=center width='6%'>售价CN</td>";
      $table .="  <td align=center width='5%'>重量克</td>";
      if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center width='8%'>设置时间</td>";
      $table .="  <td align=center width='7%'>添加产品</td></tr>";

      // iterate through users, show info
      //$rowColor = 0;
      $bgcolor = "";

      if(!empty($productList)){
        $rowColor=1;
        foreach($productList as $product){
  	      $product = (object)$product;
  	      $productid = $product->getProductid();
  	      $ename = $product->getEname();
  	      $cname = $product->getCname();
  	      $spec = $product->getSpec();
  	      $unit = $product->getUnit();
  	      $unitnum = $product->getUnitnum();
  	      $pprizenz = $product->getPpricenz();
  	      $sprizecn = $product->getSpricecn();
  	      $weight = $product->getWeight();
  	      $supermarket = $product->getSupermarket();
          $createtime = $product->getCreatetime();
          
          if( $rowColor%2 == 0 ) {
	        $bgcolor = "#ffffcc";
          } else {
	        $bgcolor = "#eeeeee";
          }

          $table .=" <tr bgcolor=$bgcolor class=line>";
          $table .="  <td align=left>$ename</td>";
          $table .="  <td align=left>$cname</td>";
          switch($spec){
            case 1:
              $table .="  <td align=center>盒</td>";
              break;
	        case 2:
	          $table .="  <td align=center>瓶</td>";
              break;
	        case 3:
	          $table .="  <td align=center>罐</td>";
              break;
	        case 4:
	          $table .="  <td align=center>箱</td>";
              break;
            case 5:
	          $table .="  <td align=center>块</td>";
              break;
            case 6:
	          $table .="  <td align=center>只</td>";
              break;
            case 7:
	          $table .="  <td align=center>袋</td>";
              break;
	        default:
	          $table .="  <td align=center>无</td>";
              break;
          }
          switch($unit){
            case 1:
              $table .="  <td align=center>克</td>";
              break;
	        case 2:
	          $table .="  <td align=center>毫升</td>";
              break;
	        case 3:
	          $table .="  <td align=center>粒</td>";
              break;
	        case 4:
	          $table .="  <td align=center>天</td>";
              break;
            case 5:
	          $table .="  <td align=center>块</td>";
              break;
            case 6:
	          $table .="  <td align=center>只</td>";
              break;
            case 7:
	          $table .="  <td align=center>袋</td>";
              break;
            case 8:
	          $table .="  <td align=center>片</td>";
              break;
	        default:
	          $table .="  <td align=center>无</td>";
              break;
          }
          $table .="  <td align=center>$sprizecn</td>";
          $table .="  <td align=center>$weight</td>";
          if($_SESSION['SESS_TYPE'] == ADMIN) $table .="  <td align=center>".substr($createtime,0,10)."</td>";
          $table .="  <td align=center><a href=\"/order/createsubdetail.php?orderid=$orderid&productid=$productid\">添加产品</a></td></tr>";
          $rowColor++;
        }
      }
      $table .="</table></center>";
      $table .="</BODY></html>";
    
      echo $table;
    }
?>