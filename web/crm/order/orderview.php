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
	$idcode = $order->getIdcode();
  	$operatorid = $order->getOperatorid();
  	  $user = new user;
  	  $operatorname = $user->getUserbyId($operatorid)->realname;
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
	$discount = $order->getDiscount();
	$memo = $order->getMemo();
    $create_time = $order->getCreatetime();
    
    //产品重量g->kg
    $weightKG = $weight/1000;
    
    //常量利率
    $constantsManager = new cConstantsPeer;
    $rrate = $constantsManager->getNewConstants(1);
    
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
        if(!$user) $user = new user;
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
              case 7:
	            $unitStr ="袋";
                break;
              case 8:
	            $unitStr ="片";
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
        
        $orderProductStr .="<tr $bgcolor1 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;产品名称（英文）</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$ename</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor2 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;产品名称（中文）</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$cname</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor1 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;产品规格</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$specStr</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor2 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;产品单位</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$unitStr</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor1 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;单位规格中含产品数量</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$unitnum</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor2 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;产品重量（克）</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$productweight</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor1 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;进货价格NZ，据汇率$rrate-0.1 折算RMB</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$ppricenz,&nbsp;$ppricecn</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor2 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;代购人</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$pagentname</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor1 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;该产品在本单的销售价格（人民币）</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$sellSingleProductPricecn</td>";
        $orderProductStr .="</tr>";
        $orderProductStr .="<tr $bgcolor2 align=\"center\">";
        $orderProductStr .="<td height=\"27\">&nbsp;本产品的销售数量</td>";
        $orderProductStr .="<td height=\"27\">&nbsp;$sellSingleProductNumber</td>";
        $orderProductStr .="</tr>";
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
   $table .= "<table width=\"480\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>";
   $table .=" <tr align=\"center\" >";
   $table .="<td height=\"30\" colspan=\"9\" class=hdr>订单详情</td>";
   $table .="</tr>";
   $table .="<tr align=\"center\" {$strings['TABLE_TITLE_BKCOLOR']}>";
   $table .="<td height=\"28\">订单项目</td>";
   $table .="<td>订单内容</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订单编号</td>";
   $table .="<td height=\"27\">&nbsp;$orderid</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订单识别码</td>";
   $table .="<td height=\"27\">&nbsp;$idcode</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订单日期</td>";
   $table .="<td height=\"27\">&nbsp;$create_time</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;下单人</td>";
   $table .="<td height=\"27\">&nbsp;$operatorname</td>";
   $table .="</tr>";
   $table .=$orderProductStr;
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;收件人姓名</td>";
   $table .="<td height=\"27\">&nbsp;$consumerName</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;收件人地址</td>";
   $table .="<td height=\"27\">&nbsp;$consumerAddress</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;收件人电话</td>";
   $table .="<td height=\"27\">&nbsp;$consumerMobile</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;代理人姓名</td>";
   $table .="<td height=\"27\">&nbsp;$sagentname</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;本订单汇率</td>";
   $table .="<td height=\"27\">&nbsp;$rate</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;本订单产品重量（不含包装），KG</td>";
   $table .="<td height=\"27\">&nbsp;$weightKG</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;应付运费（人民币）</td>";
   $table .="<td height=\"27\">&nbsp;$freight</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\" colspan=\"2\">&nbsp;应付运费公式（人民币）：(订单总重KG+0.7KG)*8新西兰元*$rate</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;实付运费（人民币）</td>";
   $table .="<td height=\"27\">&nbsp;$pfreight</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订单总价（人民币）</td>";
   $table .="<td height=\"27\">&nbsp;$price</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;真实进货总价（人民币）</td>";
   $table .="<td height=\"27\">&nbsp;$pprice</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\" colspan=\"2\">&nbsp;真实进货总价公式（人民币）：[所有产品求和(产品进价NZD*订单量))+(订单总重KG+0.7KG)*7]*($rrate-0.1)</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;代理看到的进货总价（人民币）</td>";
   $table .="<td height=\"27\">&nbsp;$spprice</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\" colspan=\"2\">&nbsp;代理看到的进货总价公式（人民币）：[所有产品求和(产品进价NZD*订单量))+(订单总重KG+0.7KG)*8]*$rate</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;真实总利润</td>";
   $table .="<td height=\"27\">&nbsp;$totalProfit</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;代理人看到的总利润</td>";
   $table .="<td height=\"27\">&nbsp;$totalSProfit</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;代理利润</td>";
   $table .="<td height=\"27\">&nbsp;$sagentProfit</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;代购人利润</td>";
   $table .="<td height=\"27\">&nbsp;$pagentProfit</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;代理人看到的代购人利润</td>";
   $table .="<td height=\"27\">&nbsp;$pagentSProfit</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;姚竹利润</td>";
   $table .="<td height=\"27\">&nbsp;$micaProfit</td>";
   $table .="</tr>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;净利润</td>";
   $table .="<td height=\"27\">&nbsp;$JProfit</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订单优惠</td>";
   $table .="<td height=\"27\">&nbsp;$discount</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor2 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;国际物流公司</td>";
   $table .="<td height=\"27\">&nbsp;$logisticsname</td>";
   $table .="</tr>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;国际物流单号</td>";
   $table .="<td height=\"27\">&nbsp;$logisticscode</td>";
   $table .="</tr>";
   $table .="<tr $bgcolor1 align=\"center\">";
   $table .="<td height=\"27\">&nbsp;订单备注</td>";
   $table .="<td height=\"27\">&nbsp;$memo</td>";
   $table .="</tr>";

   $table .="</table>";
   $table .="<table width=\"100%\"  border=\"0\" cellpadding=\"1\">";
   $table .="<tr>";
   $table .="<td>&nbsp;</td>";
   $table .="<td>&nbsp;</td>";
   $table .="<td>&nbsp;</td>";
   $table .="</tr>";
   $table .="</table>";
  

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;
?>