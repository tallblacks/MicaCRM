<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    session_start();
    if (!isset($_SESSION["SESS_USERID"])) {
		    Header("Location:index.php");
		    exit();
	  }
    require_once("../include/corderpeer.inc.php");
    require_once("../include/csuborderpeer.inc.php");
    require_once("../include/cproductpeer.inc.php");
    require_once("../include/cconsumerpeer.inc.php");
    require_once("../include/clogisticspeer.inc.php");
    require_once("../include/cprofitpeer.inc.php");
    require_once("../include/cconstantspeer.inc.php");
	
    $orderid = trim($_GET["orderid"]);
    $idcode = trim($_GET["idcode"]);
	
    $bgcolor1 = $strings['TABLE_DARK_COLOR'].$over=$strings['DARK_OVER'];
    $bgcolor2 = $strings['TABLE_LIGHT_COLOR'].$over=$strings['LIGHT_OVER'];
	
    $db = new cDatabase;
    $orderManager = new cOrderPeer;
    if ($orderid) {
        if (!$orderManager->getOrder($orderid)) exit();
        $order = $orderManager->getOrder($orderid);
        $idcode = $order->getIdcode();
    } else {
        if (!$orderManager->getOrderByIdcode($idcode)) exit();
        $order = $orderManager->getOrderByIdcode($idcode);
        $orderid = $order->getOrderid();
    }
    $operatorid = $order->getOperatorid();
    $consumerid = $order->getConsumerid();
    $rate = $order->getNRate();
    $weight = $order->getWeight();
    $freight = $order->getFreight();
    $pfreight = $order->getPfreight();
    $price = $order->getPrice();
    $pprice = $order->getPprice();
    $spprice = $order->getSpprice();
    $logisticsid = $order->getLogisticsid();
    $logisticscode = $order->getLogisticscode();
    $paystatus = $order->getPaystatus();
    $discount = $order->getDiscount();
	  $productPrice = $price+$discount-$freight;//扣除运费的产品价格
    $memo = $order->getMemo();
    $ordertype = $order->getCType();
    $create_time = $order->getCreatetime();
    
    //常量
    $constantsManager = new cConstantsPeer;
    $packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
    $packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    
    //产品重量g->kg
    //$weightKG = $weight/1000;
    if ($ordertype == 1) {//直邮
        $weight = $weight + $packageZhiWeightG;
        if ($weight < 1000) $weight = 1000;
    } else {
        $weight = $weight + $packagePinWeightG;
    }
    $weightPackageKG = $weight/1000;
    //$weightPackageKG = ($weight+700)/1000;
    //$rrate = $rate-0.1;
    
    //优惠前的总价
    $preDiscount = $price+$discount;
    
    //下单消费者信息
    $consumerManager = new cConsumerPeer;
  	$consumer = $consumerManager->getConsumer($consumerid);
  	$consumerName = $consumer->getCname();
    $consumerAddress = $consumer->getAddress();
    $consumerMobile = $consumer->getMobile();
    $sagentid = $consumer->getAgentid();
    if ($sagentid == 0) {
        $sagentname = "无代理人";
    } else {
        $user = new user;
  	    $sagentname = $user->getUserbyId($sagentid)->realname;
    }
    
    if ($_SESSION['SESS_TYPE'] == AGENT && $_SESSION['SESS_USERID'] != $sagentid) {
        Header("Location:index.php");
	      exit();
    }
    //判断日期，秘书
    if ($_SESSION['SESS_TYPE'] == SECRETARY) {
        if (substr($create_time,0,4) == "2013") exit();
        if (substr($create_time,0,4) == "2014") {
            if (substr($create_time,5,2) == "01" || substr($create_time,5,2) == "02" || substr($create_time,5,2) == "03" || substr($create_time,5,2) == "04" || substr($create_time,5,2) == "05") exit();
            if (substr($create_time,5,2) == "06") {
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
      
    //利润信息
    $profitManager = new cProfitPeer;
    $profitCount = $profitManager->getProfitCount($orderid);
    if ($profitCount == 0) {
        $totalProfit = 0;
        $totalSProfit = 0;
        $sagentProfit = 0;
        $pagentProfit = 0;
        $pagentSProfit = 0;
        $micaProfit = 0;
        $JProfit = 0;
    } else {
        $profitList = $profitManager->getProfitlist($orderid,0,100);
  	    foreach ($profitList as $profit) {
  	        $profit = (object)$profit;
  	        $type = $profit->getCType();
  	        switch ($type) {
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
    
    $finalSagentPay2Mica = $price - $sagentProfit;
    
    //物流信息
    if ($logisticsid > 0) {
        $logisticsManager = new cLogisticsPeer;
  	    $logistics = $logisticsManager->getLogistics($logisticsid);
        $logisticsname = $logistics->getCName();
    } else {
        $logisticsname = "暂无物流信息";
    }
    
    //该订单下所有产品
    $productListBgCCount = 0;
    $orderProductStr = "";
    $copyProductStr = "";
    $suborderManager = new cSuborderPeer;
	  $suborderCount = $suborderManager->getSuborderCount($orderid);
    if ($suborderCount > 0) {
        $suborderList = $suborderManager->getSuborderlist($orderid,0,100);
        foreach ($suborderList as $suborder) {
  	        $suborder = (object)$suborder;
  	        $productid = $suborder->getProductid();
  	        $productManager = new cProductPeer;
	          $product = $productManager->getProduct($productid);
	          $ename = $product->getEname();
            $cname = $product->getCname();
            $spec = $product->getSpec();
            switch ($spec) {
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
            switch ($unit) {
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
            $ppricecn = $ppricenz*$rate;
            $productweight = $product->getWeight();
            $pagentid = $product->getAgentid();
            if ($pagentid == 0) {
                $pagentname = "无代购人";
            } else {
                if (!$user) $user = new user;
  	            $pagentname = $user->getUserbyId($pagentid)->realname;
            }
            $sellSingleProductPricecn = $suborder->getPrice();
            $sellSingleProductNumber = $suborder->getOrdernum();
            $consumerProductPrice = $sellSingleProductPricecn*$sellSingleProductNumber;
            $sellSingleProductWeightTotal = ($productweight*$sellSingleProductNumber)/1000;
            $suborderDate = substr($suborder->getCreatetime(),0,10);
        
            if ($productListBgCCount%2 == 0) {
                $productListBgCStr = $bgcolor2;
            }else{
                $productListBgCStr = $bgcolor1;
            }
        
            $orderProductStr .= "<tr $productListBgCStr align=\"center\">";
            $orderProductStr .= "<td height=\"27\">&nbsp;$cname</td>";
            $orderProductStr .= "<td height=\"27\">&nbsp;$sellSingleProductNumber</td>";
            $orderProductStr .= "<td height=\"27\">&nbsp;$ppricenz</td>";
            $orderProductStr .= "<td height=\"27\">&nbsp;$sellSingleProductPricecn</td>";
            $orderProductStr .= "<td height=\"27\">&nbsp;$consumerProductPrice</td>";
            $orderProductStr .= "<td height=\"27\">&nbsp;$sellSingleProductWeightTotal</td>";
            $orderProductStr .= "<td height=\"27\">&nbsp;$suborderDate</td>";
            $orderProductStr .= "</tr>";
   		
            $copyProductStr .=$cname."  *".$sellSingleProductNumber;
            if ($ename) $copyProductStr .="  (".$ename.")";
            $copyProductStr .="<br>";
        
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
    $orderIDHtmlStr = "编号/订单号";
    if (!empty($msg)) {	
        echo "<span class=cur>$msg</span>";
    }
  
    $table = "<meta charset=utf-8>";
    $table .= "<table>";
    $table .= "<tr>";
    $table .= "<td>";
   
    $table .= "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
    $table .= " <tr align=\"center\" >";
    if ($ordertype == 2 || $ordertype == 3) {
        $table .="<td height=\"30\" colspan=\"9\" class=hdr>Kia Ora $sagentname&nbsp;，这是您代理的拼单订单详情</td>";
    } else {
        $table .="<td height=\"30\" colspan=\"9\" class=hdr>Kia Ora $sagentname&nbsp;，这是您代理的订单详情</td>";
    }
    $table .= "</tr>";
    $table .= "<tr $bgcolor1 align=\"center\">";
    if ($idcode) {
        $table .="<td height=\"27\">&nbsp;$orderIDHtmlStr</td>";
        $table .="<td height=\"27\">&nbsp;$idcode</td>";
    } else {
        $table .="<td height=\"27\">&nbsp;订单号</td>";
        $table .="<td height=\"27\">&nbsp;$orderid</td>";
    }

    $table .= "<td height=\"27\">&nbsp;收件地址</td>";
    $table .= "<td height=\"27\" colspan=\"3\">&nbsp;$consumerAddress</td>";
    $table .= "<td height=\"27\">&nbsp;快递单号</td>";
    $table .= "<td height=\"27\">&nbsp;$logisticscode</td>";
    $table .= "</tr>";
    $table .= "<tr $bgcolor2 align=\"center\">";
    $table .= "<td height=\"27\">&nbsp;订货人</td>";
    $table .= "<td height=\"27\">&nbsp;$consumerName</td>";
    $table .= "<td height=\"27\">&nbsp;电话</td>";
    $table .= "<td height=\"27\">&nbsp;$consumerMobile</td>";
    $table .= "<td height=\"27\">&nbsp;代理</td>";
    $table .= "<td height=\"27\">&nbsp;$sagentname</td>";
    $table .= "<td height=\"27\">&nbsp;快递公司</td>";
    $table .= "<td height=\"27\">&nbsp;$logisticsname</td>";
    $table .= "</tr>";
    $table .= "</table>";
   
    $table .= "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td>";
    $table .= "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td>";
   
    $table .= "<table width=\"680\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
    $table .= "<tr $bgcolor1 align=\"center\">";
    $table .= "<td height=\"27\">&nbsp;产品中文名</td>";
    $table .= "<td height=\"27\">&nbsp;订货量</td>";
    $table .= "<td height=\"27\">&nbsp;产品进价（纽币单价）</td>";
    $table .= "<td height=\"27\">&nbsp;单价</td>";
    $table .= "<td height=\"27\">&nbsp;合计</td>";
    $table .= "<td height=\"27\">&nbsp;合计重量KG</td>";
    $table .= "<td height=\"27\">&nbsp;订货日期</td>";
    $table .= "</tr>";
    $table .= $orderProductStr;
    $table .= "<tr $bgcolor1 align=\"center\">";
    $table .= "<td height=\"27\" colspan=\"5\" align=\"right\">&nbsp;总利润</td>";
    $table .= "<td height=\"27\">&nbsp;$totalSProfit</td>";
    $table .= "<td height=\"27\">&nbsp;</td>";
    $table .= "</tr>";
    $table .= "<tr $bgcolor2 align=\"center\">";
    $table .= "<td height=\"27\" colspan=\"5\" align=\"right\">&nbsp;代理利润</td>";
    $table .= "<td height=\"27\">&nbsp;$sagentProfit</td>";
    $table .= "<td height=\"27\">&nbsp;</td>";
    $table .= "</tr>";
    $table .= "</table>";
   
    $table .= "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td>";
    $table .= "</td>";
    $table .= "</tr>";
    $table .= "<tr>";
    $table .= "<td>";
   
    $table .= "<table width=\"200\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
    $table .= "<tr $bgcolor1 align=\"center\">";
    $table .= "<td height=\"27\" align=\"left\">&nbsp;商品总价</td>";
    $table .= "<td height=\"27\" align=\"right\">&nbsp;$productPrice&nbsp;元人民币</td>";
    $table .= "</tr>";
    if ($paystatus == 0) {
        $table .= "<tr $bgcolor2 align=\"center\">";
        $table .= "<td height=\"27\" align=\"left\">&nbsp;包装总重量</td>";
        $table .= "<td height=\"27\" align=\"right\">&nbsp;$weightPackageKG&nbsp;公斤</td>";
        $table .= "</tr>";
    }
    $table .= "<tr $bgcolor2 align=\"center\">";
    $table .= "<td height=\"27\" align=\"left\">&nbsp;国际及国内邮费</td>";
    $table .= "<td height=\"27\" align=\"right\">&nbsp;$freight&nbsp;元人民币</td>";
    $table .= "</tr>";
    $table .= "<tr $bgcolor2 align=\"center\">";
    $table .= "<td height=\"27\" align=\"left\">&nbsp;本单总价</td>";
    $table .= "<td height=\"27\" align=\"right\">&nbsp;$preDiscount&nbsp;元人民币</td>";
    $table .= "</tr>";
    if ($discount > 0) {
        $table .= "<tr $bgcolor2 align=\"center\">";
        $table .= "<td height=\"27\" align=\"left\">&nbsp;优惠</td>";
        $table .= "<td height=\"27\" align=\"right\">&nbsp;$discount&nbsp;元人民币</td>";
        $table .= "</tr>";
    }
    $table .= "<tr $bgcolor2 align=\"center\">";
    $table .= "<td height=\"27\" align=\"left\">&nbsp;代理应付</td>";
    $table .= "<td height=\"27\" align=\"right\">&nbsp;$finalSagentPay2Mica&nbsp;元人民币</td>";
    $table .= "</tr>";
    $table .= "</table>";
   
    $table .= "</td>";
    $table .= "</tr>";
   
    if ($memo != "" || $memo != null) {
        $table .= "<tr>";
        $table .= "<td>";
        $table .= "</td>";
        $table .= "</tr>";
   
        $table .= "<tr>";
        $table .= "<td>";
   
        $table .= "<table width=\"400\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
        $table .= "<tr $bgcolor1 align=\"center\">";
        $table .= "<td height=\"27\" align=\"left\">&nbsp;订单备注</td>";
        $table .= "<td height=\"27\" align=\"left\">&nbsp;$memo</td>";
        $table .= "</tr>";
        $table .= "</table>";
   
        $table .= "</td>";
        $table .= "</tr>";
    }
   
    $table .="</table>";
   
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $table .= "<br><hr>";
        $table .= "<div><input type=button id=butn value=复制信息 onclick=copyText('copyText')></div>";
        $table .= "<br>";
        $table .= "<div id=copyText style='font color:#000000;display:none;align-text:left'>";
        $table .= "$idcode<br>";
        $table .= "$consumerName".", ".$consumerAddress.", ".$consumerMobile."<br>";
        $table .= $copyProductStr;
        $table .= "$memo";
        $table .= "</div>";
        $table .= "<script>function copyText(targetid){if(document.getElementById){target=document.getElementById(targetid);if(target.style.display=='block'){target.style.display='none';}else{target.style.display='block';}}}</script>";
    }
  
    $table .="</table></center>";
    $table .="</BODY></html>";

    echo $table;
?>