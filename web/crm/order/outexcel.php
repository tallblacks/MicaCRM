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
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/cproductpeer.inc.php");
	require_once("../include/cconstantspeer.inc.php");
	
  // get parameters
  /*$consumerid = trim($_GET["consumerid"]);
  $start = trim($_GET["start"]);
  $range = trim($_GET["range"]);
  $msg = trim($_GET["msg"]);

  if(empty($range)){
    $range = 15;
  }*/
  $dosearch = trim(@$_POST["dosearch"]);

  if($dosearch){
    $startdate = trim($_POST["startdate"]);
    $enddate = trim($_POST["enddate"]);
   
    //验证数据 
    $wrongInputFlag = false;
    $wrongInputMessage = false;
    if(strlen($startdate) != 10 || strlen($enddate) != 10){
      $wrongInputFlag = true;
      $wrongInputMessage = "日期输入错误";
    }
    if(!$wrongInputFlag){
      if(substr($startdate,4,1) != "-" || substr($startdate,7,1) != "-" || substr($enddate,4,1) != "-" || substr($enddate,7,1) != "-"){
        $wrongInputFlag = true;
        $wrongInputMessage = "日期连接符'-'输入错误";
      }
    }
    if(!$wrongInputFlag){
      if(substr($startdate,0,4) < 2013 || substr($startdate,0,4) > 2100 || substr($enddate,0,4) < 2013 || substr($enddate,0,4) > 2100){
        $wrongInputFlag = true;
        $wrongInputMessage = "年份输入错误";
      }
    }
    if(!$wrongInputFlag){
      if(substr($startdate,5,2) < 1 || substr($startdate,5,2) > 12 || substr($enddate,5,2) < 1 || substr($enddate,5,2) > 12){
        $wrongInputFlag = true;
        $wrongInputMessage = "月份输入错误";
      }
    }
    if(!$wrongInputFlag){
      if(substr($startdate,8,2) < 1 || substr($startdate,8,2) > 31 || substr($enddate,8,2) < 1 || substr($enddate,8,2) > 31){
        $wrongInputFlag = true;
        $wrongInputMessage = "日期输入错误";
      }
    }

    //后台查询
    if(!$wrongInputFlag){
      $db = new cDatabase;
      //mysql_query('set names utf8');
      $orderManager = new cOrderPeer;
      $orderCount = 0;
      $orderList = $orderManager->getOrderlistbyStEdSa($startdate,$enddate,0,9);
      if($orderList) $orderCount = sizeof($orderList);
      
      $disp_type = trim($_POST["disp_type"]);
      $disp_ppricenz = trim($_POST["disp_ppricenz"]);
      $disp_ppricenof = trim($_POST["disp_ppricenof"]);
      $disp_weight = trim($_POST["disp_weight"]);
      $disp_pfreight = trim($_POST["disp_pfreight"]);
      $disp_pprice = trim($_POST["disp_pprice"]);
    }else{
      $msg = $wrongInputMessage;
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
  if($wrongInputFlag || $dosearch != 1){
  
  //$titlebars = array("报表管理"=>"report.php",$name=>""); 
  $titlebars = array("报表管理"=>"report.php"); 
  $operations = array("表格导出"=>"outexcel.php");
  $jumptarget = "cmsright";
  include("../phpinc/titlebar.php");

  echo "<form name=searchreport method=post action=>
        <input type=hidden name=dosearch value=1>
        <table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>
          <tr class= itm bgcolor='#dddddd'>
            <td>起始日期</td>
            <td><input type=type name=startdate><font color=red><b>输入：2014-01-01，注意“-”是英文的不是中文的</b></font></td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>结束日期</td>
            <td><input type=type name=enddate><font color=red><b>输入：2014-02-28，注意“-”是英文的不是中文的</b></font></td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>订单类型</td>
            <td>
              <input type=radio name=disp_type value=1 checked>普通订单
              <input type=radio name=disp_type value=0>全部订单
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>进价单价（纽币）</td>
            <td>
              <input type=radio name=disp_ppricenz value=1>显示
              <input type=radio name=disp_ppricenz value=0 checked>不显示
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>产品总价（不包括运费，人民币）</td>
            <td>
              <input type=radio name=disp_ppricenof value=1>显示
              <input type=radio name=disp_ppricenof value=0 checked>不显示
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>总重量（公斤，加包装0.7/0.4）</td>
            <td>
              <input type=radio name=disp_weight value=1>显示
              <input type=radio name=disp_weight value=0 checked>不显示
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>运费</td>
            <td>
              <input type=radio name=disp_pfreight value=1>显示
              <input type=radio name=disp_pfreight value=0 checked>不显示
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>总价</td>
            <td>
              <input type=radio name=disp_pprice value=1>显示
              <input type=radio name=disp_pprice value=0 checked>不显示
            </td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td></td>
            <td><input type=submit name=submit value=数据查询></td>
          </tr>
        </table>
        </form>";
        
  }
        
  if(!empty($msg)) {
    echo "<span class=cur>$msg</span>";
  }
  
  if(!$wrongInputFlag && $dosearch == 1){
  
  if($orderCount == 0){
    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center>从$startdate 至 $enddate</td></tr>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=5>没有订单</td></tr>";
  }else{

    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=15>从$startdate 至 $enddate</td></tr>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=15>
               <a href=orderexcel.php?startdate=$startdate&enddate=$enddate&disp_type=$disp_type&disp_ppricenz=$disp_ppricenz&disp_ppricenof=$disp_ppricenof&disp_weight=$disp_weight&disp_pfreight=$disp_pfreight&disp_pprice=$disp_pprice>Excel表格下载</a>
               </td></tr>";
    
    $table .=" <tr bgcolor=#dddddd class=tine>";
    $table .="  <td align=center width='10%'>识别码</td>";
    $table .="  <td align=center width='10%'>发货人</td>";
    $table .="  <td align=center width='33%'>发货明细（格式：产品英文名, 产品中文名, 订货数量）</td>";
    //$table .="  <td align=center width='15%'>发货明细中文</td>";
    //$table .="  <td align=center width='8%'>订货数量</td>";
    $table .="  <td align=center width='8%'>收件人</td>";
    $table .="  <td align=center width='9%'>收件人地址</td>";
    $table .="  <td align=center width='7%'>收件人电话</td>";
    $table .="  <td align=center width='10%'>标记</td>";
    if($disp_ppricenz) $table .="  <td align=center width='10%'>进价单价NZ</td>";
    if($disp_ppricenof) $table .="  <td align=center width='10%'>产品总价NZ（不包括运费）</td>";
    if($disp_weight) $table .="  <td align=center width='10%'>总重（含包装）</td>";
    if($disp_pfreight) $table .="  <td align=center width='10%'>实收运费CN,NZ</td>";
    if($disp_pprice) $table .="  <td align=center width='15%'>本单总价</td>";
    if(!$disp_type) $table .="  <td align=center width='10%'>订单类型</td>";
    $table .="  <td align=center width='10%'>订单备注</td>";
    $table .="  <td align=center width='3%'>付款</td>";
    $table .="  </tr>";
  
  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";
  
  $orderCount = 0;
  $totalPpricenof = 0;
  $totalWeight = 0;
  $totalPfreight = 0;
  $totalPprice = 0;
  $cpfreightFlag = false;
  
  //常量利率
  $constantsManager = new cConstantsPeer;
  $rrate = $constantsManager->getNewConstants(1);
  $packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
  $packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
  
  foreach($orderList as $order){
    if( $rowColor%2 == 0 ) {
	  $bgcolor = "#ffffcc";
    }else{
	  $bgcolor = "#eeeeee";
    }
  
    $order = (object)$order;
    $orderid = $order->getOrderid();
    $idcode = $order->getIdcode();
    $type = $order->getCType();
  	  if($disp_type == 1 && $type > 1) continue;
    $weight = $order->getWeight();
    $freight = $order->getFreight();
      $freightnz = $freight/$rrate;
    $pfreight = $order->getPfreight();
    $pprice = $order->getPprice();
    $memo = $order->getMemo();
    $paystatus = $order->getPaystatus();
    
      if($type == 1){//直邮
        $weight = $weight + $packageZhiWeightG;
        if($weight < 1000) $weight = 1000;
      }else{
        $weight = $weight + $packagePinWeightG;
      }
      $weightKG = $weight/1000;
      /*if($weightKG > 0 && $weightKG < 0.3){
        $weightKG = 1;
      }else if($weightKG >= 0.3){
        $weightKG = $weightKG + 0.7;
      }*/
      $suborderManager = new cSuborderPeer;
      $suborderCount = $suborderManager->getSuborderCount($orderid);
      if($suborderCount > 0){
        $suborderList = $suborderManager->getSuborderlist($orderid,0,100);
        $consumerid = $order->getConsumerid();
  	      $consumerManager = new cConsumerPeer;
  	      //$consumer = $consumerManager->getConsumer($consumerid);
  	      $consumer = $consumerManager->getAnyConsumer($consumerid);
  	      $consumerName = $consumer->getCname();
  	      $consumerAddress = $consumer->getAddress();
          $consumerMobile = $consumer->getMobile();
          
          //代理
  	      $consumerSagentid = $consumer->getAgentid();
  	      if($consumerSagentid > 0){
  	        $user = new user;
            $sagentName = $user->getUserbyId($consumerSagentid)->realname;
          }
        foreach($suborderList as $suborder){
  	      $suborder = (object)$suborder;
  	      $productid = $suborder->getProductid();
  	        $productManager = new cProductPeer;
	        $product = $productManager->getProduct($productid);
	        $ename = $product->getEname();
            $cname = $product->getCname();
            $ppricenz = $product->getPpricenz();
              $ppricecn = $ppricenz*($rrate-0.1);
            $productweight = $product->getWeight();
          $sellSingleProductNumber = $suborder->getOrdernum();
            $productTotalWeightKG = ($productweight*$sellSingleProductNumber)/1000;
            $productTotalPrice = $ppricenz*$sellSingleProductNumber;
              /*$productTotalPriceCN = $ppricecn*$sellSingleProductNumber;
            if($productTotalWeightKG == 0){
              $productPfreight = 0;
            }else if($productTotalWeightKG < 0.3 && $productTotalWeightKG > 0){
              $productPfreight = 1*7*($rrate-0.1);
            }else{
              $productPfreight = ($productTotalWeightKG+0.7)*7*($rrate-0.1);
            }
            $productPprice = $productTotalPriceCN+$productPfreight;*/
            
          $table .=" <tr bgcolor=$bgcolor class=line>";
          $table .="  <td align=center>$idcode</td>";
          if($consumerSagentid > 0){
            $table .="  <td align=center>$sagentName, $orderid</td>";
          }else{
            $table .="  <td align=center>Mica Yao, $orderid</td>";
          }
          //$table .="  <td align=center>Mica Yao 0211752625</td>";
          $table .="  <td align=center>$ename, $cname, *$sellSingleProductNumber</td>";
          //$table .="  <td align=center>$cname</td>";
          //$table .="  <td align=center>$sellSingleProductNumber</td>";
          $table .="  <td align=center>$consumerName</td>";
          $table .="  <td align=center>$consumerAddress</td>";
          $table .="  <td align=center>$consumerMobile</td>";
          $table .="  <td align=center>新西兰报纸</td>";
          if($disp_ppricenz) $table .="  <td align=center>$ppricenz</td>";
          if($disp_ppricenof) $table .="  <td align=center>$productTotalPrice</td>";
          if($disp_weight) $table .="  <td align=center>$weightKG</td>";
          if($disp_pfreight) $table .="  <td align=center>$freight,$freightnz</td>";
          if($disp_pprice) $table .="  <td align=center>$pprice</td>";
          if(!$disp_type){
            if($type == 1){
              $table .="  <td align=center width='10%'>普通订单</td>";
            }else if($type == 2){
              $table .="  <td align=center width='10%'>未分配拼单子订单</td>";
            }else if($type == 3){
              $table .="  <td align=center width='10%'>已分配拼单子订单</td>";
            }else{
              $table .="  <td align=center width='10%'>警告！状态未知</td>";
            }
          }
          if($memo){
            $table .="  <td align=center>$memo</td>";
          }else{
            $table .="  <td align=center>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
          }
          if($paystatus == 0){
            $table .="  <td align=center><font color=red><b>未付款</b></font></td>";
          }else{
            $table .="  <td align=center>已付款</td>";
          }
          $table .="  </tr>";
        }
      }else{
        continue;
      }
    
  	$cpfreight = $order->getCpfreight();
  	  if($cpfreight == 1) $cpfreightFlag = true;
  	$create_time = $order->getCreatetime();
  	
  	$productprice = $pprice - $pfreight;
    
    $orderCount++;
    $totalPpricenof = $totalPpricenof + $productprice;
    $totalWeight = $totalWeight + $weight;
    $totalPfreight = $totalPfreight + $pfreight;
    $totalPprice = $totalPprice + $pprice;
    
    $rowColor++;
  }
  
    $table .=" <tr bgcolor=$bgcolor class=line>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    //$table .="  <td align=center></td>";
    $table .="  <td align=center>总计</td>";
    $table .="  <td align=center>$orderCount&nbsp;单</td>";
    if($disp_ppricenz) $table .="  <td align=center></td>";
    if($disp_ppricenof) $table .="  <td align=center></td>";
    if($disp_weight) $table .="  <td align=center></td>";
    /*if($cpfreightFlag){
      if($disp_pfreight) $table .="  <td align=center>$totalPfreight&nbsp;已扣除运费优惠</td>";
    }else{
      if($disp_pfreight) $table .="  <td align=center>$totalPfreight</td>";
    }*/
    if($disp_pfreight) $table .="  <td align=center></td>";
    if($disp_pprice) $table .="  <td align=center>$totalPprice</td>";
    if(!$disp_type) $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  </tr>";
  
  }//没有订单括号
  
  $table .="</table></center>";
  $table .="</BODY></html>";

  echo $table;
  }

  /*$db = new cDatabase;
  $orderManager = new cOrderPeer;
  //$total = $orderManager->getOrderCountbyDate(0,$month,$year);
  $orderCount = 0;
  $orderList = $orderManager->getOrderlistbyDate(0,$month,$year);
  $orderCount = sizeof($orderList);



  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=11>当月统计</td></tr>";
  $table .=" <tr bgcolor=#dddddd class=tine>";
  $table .=" <td align=center width='10%'>日期</td>";
  $table .="  <td align=center width='9%'>订单个数</td>";
  $table .="  <td align=center width='9%'>实收总运费</td>";
  $table .="  <td align=center width='9%'>实际总运费</td>";
  $table .="  <td align=center width='9%'>订单总价</td>";
  $table .="  <td align=center width='9%'>总成本</td>";
  $table .="  <td align=center width='9%'>总优惠</td>";
  $table .="  <td align=center width='9%'>代理总利润</td>";
  $table .="  <td align=center width='9%'>代理总应付</td>";
  $table .="  <td align=center width='9%'>总净利润</td>";
  $table .="  <td align=center width='9%'>详细情况</td></tr>";

  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";
  
  $monthOrdercount = 0;
  $monthFreight = 0;
  $monthPfreight = 0;
  $monthPrice = 0;
  $monthPprice = 0;
  $monthDiscount = 0;
  $monthSagentprofit = 0;
  $monthSagentpay = 0;
  $monthJprofit = 0;
  
  $profitManager = new cProfitPeer;

  for($j=1; $j<=$day; $j++){
    if( $rowColor%2 == 0 ) {
	  $bgcolor = "#ffffcc";
    } else {
	  $bgcolor = "#eeeeee";
    }
    
    if($j < 10){
      $strJ = "0".$j;
    }else{
      $strJ = $j;
    }
    
    $dateStr = $year."-".$month."-".$strJ;
    $dateOrdercount = 0;
    $dateFreight = 0;
    $datePfreight = 0;
    $datePrice = 0;
    $datePprice = 0;
    $dateDiscount = 0;
    $dateSagentprofit = 0;
    $dateSagentpay = 0;
    $dateJprofit = 0;
    
    foreach($orderList as $order){
      $order = (object)$order;
      $orderid = $order->getOrderid();
      $create_time = $order->getCreatetime();
      if($dateStr == substr($create_time,0,10)){
        $consumerid = $order->getConsumerid();
  	      $consumerManager = new cConsumerPeer;
  	      $consumer = $consumerManager->getConsumer($consumerid);
  	      $sagentid = $consumer->getAgentid();
        $freight = $order->getFreight();
  	    $pfreight = $order->getPfreight();
        $price = $order->getPrice();
        $pprice = $order->getPprice();
        $discount = $order->getDiscount();
        
        $sagentprofit = 0;
        $sagentprofitObj = $profitManager->getProfitbyDate($strJ,$month,$year,$orderid,3);
        if(!empty($sagentprofitObj)){
          //foreach($sagentprofitList as $profit){
            //$profit = (object)$profit;
            $threeprofit = $sagentprofitObj->getProfit();
            $sagentprofit = $sagentprofit + $threeprofit;
          //}
        }
        $Jprofit = 0;
        $JprofitObj = $profitManager->getProfitbyDate($strJ,$month,$year,$orderid,7);
        if(!empty($JprofitObj)){
          //foreach($JprofitList as $profit){
            //$profit = (object)$profit;
            $Jprofit = $Jprofit + $JprofitObj->getProfit();
          //}
        }
        if($sagentid > 0){//如果有代理，那么代理应付款项要增加
          $dateSagentpay = $dateSagentpay + ($price - $threeprofit);
          $monthSagentpay = $monthSagentpay + ($price - $threeprofit);
        }
      
        $dateOrdercount++;
        $dateFreight = $dateFreight + $freight;
        $datePfreight = $datePfreight + $pfreight;
        $datePrice = $datePrice + $price;
        $datePprice = $datePprice + $pprice;
        $dateDiscount = $dateDiscount + $discount;
        $dateSagentprofit = $dateSagentprofit + $sagentprofit;
        $dateJprofit = $dateJprofit + $Jprofit;
        
        $monthOrdercount++;
        $monthFreight = $monthFreight + $freight;
        $monthPfreight = $monthPfreight + $pfreight;
        $monthPrice = $monthPrice + $price;
        $monthPprice = $monthPprice + $pprice;
        $monthDiscount = $monthDiscount + $discount;
        $monthSagentprofit = $monthSagentprofit + $sagentprofit;
        $monthJprofit = $monthJprofit + $Jprofit;
      }else{
        continue;
      }
    }
  
    if($dateOrdercount != 0){
      $table .=" <tr bgcolor=$bgcolor class=line>";
      $table .="  <td align=center>$dateStr</td>";
      $table .="  <td align=center>$dateOrdercount&nbsp;个</td>";
      $table .="  <td align=center>$dateFreight&nbsp;元</td>";
      $table .="  <td align=center>$datePfreight&nbsp;元</td>";
      $table .="  <td align=center>$datePrice&nbsp;元</td>";
      $table .="  <td align=center>$datePprice&nbsp;元</td>";
      $table .="  <td align=center>$dateDiscount&nbsp;元</td>";
      $table .="  <td align=center>$dateSagentprofit&nbsp;元</td>";
      $table .="  <td align=center>$dateSagentpay&nbsp;元</td>";
      $table .="  <td align=center>$dateJprofit&nbsp;元</td>";
      $table .="  <td align=center>详细情况</td></tr>";
    
      $rowColor++;
    }
  }
  
  $table .=" <tr bgcolor=$bgcolor class=line>";
  $table .="  <td align=center>总计</td>";
  $table .="  <td align=center>$monthOrdercount&nbsp;个</td>";
  $table .="  <td align=center>$monthFreight&nbsp;元</td>";
  $table .="  <td align=center>$monthPfreight&nbsp;元</td>";
  $table .="  <td align=center>$monthPrice&nbsp;元</td>";
  $table .="  <td align=center>$monthPprice&nbsp;元</td>";
  $table .="  <td align=center>$monthDiscount&nbsp;元</td>";
  $table .="  <td align=center>$monthSagentprofit&nbsp;元</td>";
  $table .="  <td align=center>$monthSagentpay&nbsp;元</td>";
  $table .="  <td align=center>$monthJprofit&nbsp;元</td>";
  $table .="  <td align=center>详细情况</td></tr>";

$table .="</table></center>";
$table .="</BODY></html>";

echo $table;*/
?>