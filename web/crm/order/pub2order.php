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
	require_once("../include/csuborderpeer.inc.php");
	require_once("../include/cproductpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/clogisticspeer.inc.php");
	require_once("../include/cprofitpeer.inc.php");
	require_once("../include/user.inc.php");
	require_once("../include/cconstantspeer.inc.php");
	
	$orderid = trim($_GET["orderid"]);
	
	$bgcolor1 = $strings['TABLE_DARK_COLOR'].$over=$strings['DARK_OVER'];
    $bgcolor2 = $strings['TABLE_LIGHT_COLOR'].$over=$strings['LIGHT_OVER'];
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
    $orderManager = new cOrderPeer;
	$order = $orderManager->getOrder($orderid);
	$consumerid = $order->getConsumerid();
	$rate = $order->getNRate();
	$weight = $order->getWeight();
	$freight = $order->getFreight();
	$pfreight = $order->getPfreight();
	$price = $order->getPrice();
	$pprice = $order->getPprice();
	  $productPrice = ($pprice-$pfreight);//扣除运费的产品价格
	$logisticsid = $order->getLogisticsid();
	$logisticscode = $order->getLogisticscode();
	$ordertype = $order->getCType();
    $create_time = $order->getCreatetime();
    
    //常量
    $constantsManager = new cConstantsPeer;
    $rrate = $constantsManager->getNewConstants(1);
    $displayRrate = $rrate-0.1;
    $packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
    $packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    
    //产品重量g->kg
    //$weightKG = $weight/1000;
    if($ordertype == 1){//直邮
      $weight = $weight + $packageZhiWeightG;
      if($weight < 1000) $weight = 1000;
    }else{
      $weight = $weight + $packagePinWeightG;
    }
    $weightPackageKG = $weight/1000;
    //$weightPackageKG = ($weight+700)/1000;
    
    //下单消费者信息
    $consumerManager = new cConsumerPeer;
  	$consumer = $consumerManager->getConsumer($consumerid);
  	$consumerName = $consumer->getCname();
    $consumerAddress = $consumer->getAddress();
    $consumerMobile = $consumer->getMobile();
    $sagentid = $consumer->getAgentid();
      if($sagentid == 0){
        $sagentname = "无代理人";
      }else{
        $user = new user;
  	    $sagentname = $user->getUserbyId($sagentid)->realname;
      }
      
    //利润信息
    $profitManager = new cProfitPeer;
    $profitCount = $profitManager->getProfitCount($orderid);
    if($profitCount == 0){
      $totalProfit = 0;
      $totalSProfit = 0;
      $sagentProfit = 0;
      $pagentProfit = 0;
      $pagentSProfit = 0;
      $micaProfit = 0;
      $JProfit = 0;
    }else{
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
  	        $JProfit = $profit->getProfit();
  	        break;
  	      default:
  	        break;
  	    }
  	  }
    }
    
    //物流信息
    if($logisticsid > 0){
      $logisticsManager = new cLogisticsPeer;
  	  $logistics = $logisticsManager->getLogistics($logisticsid);
      $logisticsname = $logistics->getCName();
    }else{
      $logisticsname = "暂无物流信息";
    }
    
    //该订单下所有产品
    $productListBgCCount = 0;
    $orderProductStr = "";
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
          $spec = $product->getSpec();
            switch($spec){
              case 1:
                $specStr ="盒";
                break;
	          case 2:
	            $specStr ="瓶";
                break;
	          case 3:
	            $specStr ="罐";
                break;
	          case 4:
	            $specStr ="箱";
                break;
              case 5:
	            $specStr ="块";
                break;
              case 6:
	            $specStr ="只";
                break;
              case 7:
	            $specStr ="袋";
                break;
	          default:
	            $specStr ="无";
                break;
            }
          $unit = $product->getUnit();
            switch($unit){
              case 1:
                $unitStr ="克";
                break;
	          case 2:
	            $unitStr ="毫升";
                break;
	          case 3:
	            $unitStr ="粒";
                break;
	          case 4:
	            $unitStr ="天";
                break;
              case 5:
	            $unitStr ="块";
                break;
              case 6:
	            $unitStr ="只";
                break;
	          default:
	            $unitStr ="无";
                break;
            }
          $unitnum = $product->getUnitnum();
          $ppricenz = $product->getPpricenz();
            $ppricecn = $ppricenz*($rrate-0.1);
          $productweight = $product->getWeight();
          $pagentid = $product->getAgentid();
            if($pagentid == 0){
              $pagentname = "无代购人";
            }else{
              if(!$user) $user = new user;
  	          $pagentname = $user->getUserbyId($pagentid)->realname;
            }
        $sellSingleProductPricecn = $suborder->getPrice();
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
   $table .="<td height=\"27\">&nbsp;$orderid</td>";
   $table .="<td height=\"27\">&nbsp;收件地址</td>";
   $table .="<td height=\"27\" colspan=\"3\">&nbsp;$consumerAddress</td>";
   $table .="<td height=\"27\">&nbsp;快递单号</td>";
   $table .="<td height=\"27\">&nbsp;$logisticscode</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订货人</td>";
   $table .="<td height=\"27\">&nbsp;$consumerName</td>";
   $table .="<td height=\"27\">&nbsp;电话</td>";
   $table .="<td height=\"27\">&nbsp;$consumerMobile</td>";
   $table .="<td height=\"27\">&nbsp;代理</td>";
   $table .="<td height=\"27\">&nbsp;$sagentname</td>";
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
   $table .="<td height=\"27\" align=\"right\">&nbsp;$pfreight&nbsp;元人民币</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\" align=\"left\">&nbsp;姚竹应付</td>";
   $table .="<td height=\"27\" align=\"right\">&nbsp;$pprice&nbsp;元人民币</td>";
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