<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
    session_start();
    //if ((!session_is_registered("SESS_USERID")) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    	Header("Location:../phpinc/main.php");
		exit();
    }

	//require_once("../include/mysql.inc.php");
	require_once("../include/ccomborderpeer.inc.php");
	require_once("../include/ccombologpeer.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/csuborderpeer.inc.php");
	require_once("../include/cproductpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/clogisticspeer.inc.php");
	require_once("../include/cprofitpeer.inc.php");
	require_once("../include/user.inc.php");
	require_once("../include/cconstantspeer.inc.php");
	
	$comborderid = trim($_GET["comborderid"]);
	
	$bgcolor1 = $strings['TABLE_DARK_COLOR'].$over=$strings['DARK_OVER'];
    $bgcolor2 = $strings['TABLE_LIGHT_COLOR'].$over=$strings['LIGHT_OVER'];
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
	$comborderManager = new cComborderPeer;
	$comborder = $comborderManager->getComborder($comborderid);
	$name = $comborder->getCname();
  	$address = $comborder->getAddress();
  	$mobile = $comborder->getMobile();
  	$logisticsid = $comborder->getLogisticsid();
	$logisticscode = $comborder->getLogisticscode();
	
	//物流信息
    if($logisticsid > 0){
      $logisticsManager = new cLogisticsPeer;
  	  $logistics = $logisticsManager->getLogistics($logisticsid);
      $logisticsname = $logistics->getCName();
    }else{
      $logisticsname = "暂无物流信息";
    }
	
	$combologManager = new cCombologPeer;
	$maxorderid = $combologManager->getCombologMaxOrderid($comborderid);
	
	 //常量利率
    $constantsManager = new cConstantsPeer;
    $rrate = $constantsManager->getNewConstants(1);
    $packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    $displayRrate = $rrate-0.1;
    
    $orderProductStr = "";
    $productListBgCCount = 0;
    $weight = 0;
    $pfreight = 0;
    $pprice = 0;
	
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
  	    $weight = $weight+$order->getWeight();
  	    $pfreight = $pfreight+$order->getPfreight();
  	    $pprice = $pprice+$order->getPprice();
  	    
        $suborderManager = new cSuborderPeer;
	    $suborderCount = $suborderManager->getSuborderCount($orderid);
        if($suborderCount > 0){
          $suborderList = $suborderManager->getSuborderlist($orderid,0,100);
          foreach($suborderList as $suborder){
  	        $suborder = (object)$suborder;
  	        $productid = $suborder->getProductid();
  	        
  	        $productManager = new cProductPeer;
	        $product = $productManager->getProduct($productid);
	        $ename = $product->getEname();
            $cname = $product->getCname();
            $unitnum = $product->getUnitnum();
            $ppricenz = $product->getPpricenz();
              
            $sellSingleProductNumber = $suborder->getOrdernum();
            $purchaseRealPrice = $ppricenz*$displayRrate*$sellSingleProductNumber;
        
            if($productListBgCCount%2 == 0){
              $productListBgCStr = $bgcolor2;
            }else{
              $productListBgCStr = $bgcolor1;
            }
            
            $orderProductStr .="<tr $productListBgCStr align=\"center\">";
   		    $orderProductStr .="<td height=\"27\">&nbsp;$cname</td>";
   		    $orderProductStr .="<td height=\"27\">&nbsp;$ename</td>";
   		    $orderProductStr .="<td height=\"27\">&nbsp;$sellSingleProductNumber</td>";
   		    $orderProductStr .="<td height=\"27\">&nbsp;$ppricenz</td>";
   		    $orderProductStr .="<td height=\"27\">&nbsp;$purchaseRealPrice</td>";
   		    $orderProductStr .="</tr>";
        
            $productListBgCCount++;
          }
        }
      }
    }
	
	//产品重量g->kg
    //$weightKG = $weight/1000;
    $weightPackageKG = ($weight+$packagePinWeightG)/1000;
    
    $productPrice = ($pprice-$pfreight);//扣除运费的产品价格
    
    if($weight == 0){//没有运费
      $pindanPfreight = 0;
    }else{
      $weight = $weight + $packagePinWeightG;
      if($weight < 1000) $weight = 1000;
      $pindanPfreight = ($weight/1000)*7*($rrate-0.1);//新实付运费，(总KG+0.7KG}*7新西兰元*(真实汇率-0.1)，人民币
    }
    
    $micaPay = $productPrice+$pindanPfreight;
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  //$titlebars = array("订单管理"=>"order.php","订单详情"=>"orderview.php?orderid=$orderid"); 
  //$operations = array("订单详情"=>"orderview.php?orderid=$orderid");
  //$jumptarget = "cmsright";
  //include("../phpinc/titlebar.php");
  if(!empty($msg)) {	
    echo "<span class=cur>$msg</span>";
  }
  
   $table = "<meta charset=utf-8>";
   $table .= "<table>";
   $table .= "<tr>";
   $table .= "<td>";
   
   $table .= "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
   $table .=" <tr align=\"center\" >";
   $table .="<td height=\"30\" colspan=\"8\" class=hdr>Kia Ora Mica的订货出单</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订单号</td>";
   $table .="<td height=\"27\">&nbsp;$maxorderid</td>";
   $table .="<td height=\"27\">&nbsp;收件地址</td>";
   $table .="<td height=\"27\" colspan=\"3\">&nbsp;$address</td>";
   $table .="<td height=\"27\">&nbsp;快递单号</td>";
   $table .="<td height=\"27\">&nbsp;$logisticscode</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订货人</td>";
   $table .="<td height=\"27\">&nbsp;$name</td>";
   $table .="<td height=\"27\">&nbsp;电话</td>";
   $table .="<td height=\"27\">&nbsp;$mobile</td>";
   $table .="<td height=\"27\">&nbsp;代理</td>";
   $table .="<td height=\"27\">&nbsp;无代理人</td>";
   $table .="<td height=\"27\">&nbsp;快递公司</td>";
   $table .="<td height=\"27\">&nbsp;$logisticsname</td>";
   $table .="</tr>";
   $table .="</table>";
   
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<td>";
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<td>";
   
   $table .= "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;产品中文名</td>";
   $table .="<td height=\"27\">&nbsp;产品英文名</td>";
   $table .="<td height=\"27\" width=\"40\">&nbsp;订货量</td>";
   $table .="<td height=\"27\" width=\"75\">&nbsp;产品进价（纽币单价）</td>";
   $table .="<td height=\"27\" width=\"60\">&nbsp;进价合计（人民币）</td>";
   $table .="</tr>";
   $table .= $orderProductStr;
   $table .="</table>";
   
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<td>";
   $table .= "</td>";
   $table .= "</tr>";
   $table .= "<tr>";
   $table .= "<tr>";
   $table .= "<td>";
   
   $table .= "<table width=\"200\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\" align=\"left\">&nbsp;进货总价</td>";
   $table .="<td height=\"27\" align=\"right\">&nbsp;$productPrice&nbsp;元人民币</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\" align=\"left\">&nbsp;包装总重量</td>";
   $table .="<td height=\"27\" align=\"right\">&nbsp;$weightPackageKG&nbsp;公斤</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\" align=\"left\">&nbsp;邮费</td>";
   $table .="<td height=\"27\" align=\"right\">&nbsp;$pindanPfreight&nbsp;元人民币</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\" align=\"left\">&nbsp;姚竹应付</td>";
   $table .="<td height=\"27\" align=\"right\">&nbsp;$micaPay&nbsp;元人民币</td>";
   $table .="</tr>";
   $table .="</table>";
   
   $table .= "</td>";
   $table .= "</tr>";
   /*
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\" colspan=\"2\">&nbsp;进货总价公式（人民币）：[所有产品求和(产品进价NZD*订单量))+(订单总重KG+0.7KG)*7]*$displayRrate</td>";
   $table .="</tr>";


   $table .="</table>";
   $table .="<table width=\"100%\"  border=\"0\" cellpadding=\"1\">";
   $table .="<tr>";
   $table .="<td>&nbsp;</td>";
   $table .="<td>&nbsp;</td>";
   $table .="<td>&nbsp;</td>";
   $table .="</tr>";*/
   $table .="</table>";
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>