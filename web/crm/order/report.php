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
	require_once("../include/cprofitpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
	require_once("../include/user.inc.php");
	
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
    $sagentid = trim($_POST["sagentid"]);
    $paystatus = trim($_POST["paystatus"]);
    //echo $startdate."-".$enddate."-".$sagentid;
   
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
      //$total = $orderManager->getOrderCountbyDate(0,$month,$year);
      $orderCount = 0;
      $orderList = $orderManager->getOrderlistbyStEdSa($startdate,$enddate,$sagentid,$paystatus);
      $orderCount = sizeof($orderList);
    }else{
      $msg = $wrongInputMessage;
    }
  }
?>
<html><head>
<title></title>
<link rel=stylesheet type=text/css href="/style/global.css">
<script type="text/javascript" src="/ajaxjs/crm_paystatus.js"></script>
</head>
<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
<center>
<?php
  //$titlebars = array("报表管理"=>"report.php",$name=>""); 
  $titlebars = array("报表管理"=>"report.php"); 
  $operations = array("报表管理"=>"report.php");
  $jumptarget = "cmsright";

  include("../phpinc/titlebar.php");
  
  if(@$wrongInputFlag || @$dosearch != 1){
  
  $year = date('Y');
  $month = date('m');
  $day = date('d');
  
  $sagentidList = array();
  $db = new cDatabase;
  //mysql_query('set names utf8');
  $user = new user;
  $sql = "select userid,realname from user where type=3";
  $query = $db->query($sql);
  while($data=$db->fetch_array($query)){
    $sagentidList[] = $data['userid'];
    $sagentnameList[] = $data['realname'];
  }
  
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
            <td>代理姓名</td>
            <td><select name=sagentid>
                <option value=0>全部</option>";
            $sagentidCount = 0;
            foreach($sagentidList as $sagentid){
              echo "<option value=$sagentid>$sagentnameList[$sagentidCount]</option>";
              $sagentidCount++;
            }
       echo "</select></td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td>付款状态</td>
            <td><select name=paystatus>
                <option value=9>全部</option>
                <option value=0>未付款</option>
                </select></td>
          </tr>
          <tr class= itm bgcolor='#dddddd'>
            <td></td>
            <td><input type=submit name=submit value=生成报告><font color=red><b>注意：所有订单详情将在一页中显示，如果所选时间跨度较大，请耐心等候！</b></font></td>
          </tr>
        </table>
        </form>";
        
  }
        
  if(!empty($msg)) {
    echo "<span class=cur>$msg</span>";
  }
  
  if(!@$wrongInputFlag && @$dosearch == 1){
  if($orderCount == 0){
    $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center>从$startdate 至 $enddate</td></tr>";
    if($sagentid > 0){
      $user = new user;
      $sagentName = $user->getUserbyId($sagentid)->realname;
  
      $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=5>代理人：$sagentName</td></tr>";
    }
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=5>没有订单</td></tr>";
    
  }else{
  
  $table="<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=100%>";
  $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=10>从$startdate 至 $enddate</td></tr>";
  if($sagentid > 0){
    $user = new user;
    $sagentName = $user->getUserbyId($sagentid)->realname;
  
    $table .=" <tr bgcolor=#dddddd class=tine><td align=center colspan=7>代理人：$sagentName</td></tr>";
  }
  if($sagentid == 0){
    $table .=" <tr bgcolor=#dddddd class=tine>";
    $table .=" <td align=center width='12%'>订单号</td>";
    $table .="  <td align=center width='11%'>订货人</td>";
    $table .="  <td align=center width='9%'>代理人</td>";
    $table .="  <td align=center width='9%'>销售总额</td>";
    $table .="  <td align=center width='9%'>代理利润</td>";
    $table .="  <td align=center width='9%'>代理应付</td>";
    $table .="  <td align=center width='9%'>Mica利润</td>";
    $table .="  <td align=center width='9%'>净利润</td>";
    $table .="  <td align=center width='15%'>订购日期</td>";
    $table .="  <td align=center width='8%'>付款</td>";
    $table .="  </tr>";
  }else{
    $table .=" <tr bgcolor=#dddddd class=tine>";
    $table .=" <td align=center width='20%'>订单号</td>";
    $table .="  <td align=center width='20%'>订货人</td>";
    $table .="  <td align=center width='15%'>销售总额</td>";
    $table .="  <td align=center width='10%'>代理利润</td>";
    $table .="  <td align=center width='10%'>代理应付</td>";
    $table .="  <td align=center width='15%'>订购日期</td>";
    $table .="  <td align=center width='10%'>付款</td>";
    $table .="  </tr>";
  }
  
  // iterate through users, show info
  $rowColor = 0;
  $bgcolor = "";
  
  $orderCount = 0;
  $totalPrice = 0;
  $totalSagentprofit = 0;
  $totalSagentpay = 0;
  $totalMicaprofit = 0;
  $totalJprofit = 0;
  
  $profitManager = new cProfitPeer;
  $user = new user;
  
  foreach($orderList as $order){
    $order = (object)$order;
    $orderid = $order->getOrderid();
    $consumerid = $order->getConsumerid();
  	  $consumerManager = new cConsumerPeer;
  	  $consumer = $consumerManager->getConsumer($consumerid);
  	  if($consumer){
  	    $consumerName = $consumer->getCname();
  	    $consumerSagentid = $consumer->getAgentid();
        $sagentName = $user->getUserbyId($consumerSagentid)->realname;
      }
  	$price = $order->getPrice();
  	$paystatus = $order->getPaystatus();
  	$create_time = $order->getCreatetime();
  	
  	$sagentprofit = 0;
    $sagentprofitObj = $profitManager->getProfit($orderid,3);
    if(!empty($sagentprofitObj)){
      $sagentprofit = $sagentprofitObj->getProfit();
      //$sagentprofit = $sagentprofit + $threeprofit;
    }
    if($sagentid > 0 || $consumerSagentid > 0){
      $sagentpay = $price - $sagentprofit;
    }else{
      $sagentpay = 0;
    }
    
    $micaprofit = 0;
    $micaprofitObj = $profitManager->getProfit($orderid,6);
    if(!empty($micaprofitObj)){
      $micaprofit = $micaprofitObj->getProfit();
    }
    
    $Jprofit = 0;
    $JprofitObj = $profitManager->getProfit($orderid,7);
    if(!empty($JprofitObj)){
      $Jprofit = $JprofitObj->getProfit();
    }
    
    $orderCount++;
    $totalPrice = $totalPrice + $price;
    $totalSagentprofit = $totalSagentprofit + $sagentprofit;
    $totalSagentpay = $totalSagentpay + $sagentpay;
    $totalMicaprofit = $totalMicaprofit + $micaprofit;
    $totalJprofit = $totalJprofit + $Jprofit;
      
    if( $rowColor%2 == 0 ) {
	  $bgcolor = "#ffffcc";
    }else{
	  $bgcolor = "#eeeeee";
    }
    if($sagentid == 0){
      $table .=" <tr bgcolor=$bgcolor class=line>";
      $table .="  <td align=center>$orderid</td>";
      $table .="  <td align=center>$consumerName</td>";
      $table .="  <td align=center>$sagentName</td>";
      $table .="  <td align=center>$price&nbsp;元</td>";
      $table .="  <td align=center>$sagentprofit&nbsp;元</td>";
      $table .="  <td align=center>$sagentpay&nbsp;元</td>";
      $table .="  <td align=center>$micaprofit&nbsp;元</td>";
      $table .="  <td align=center>$Jprofit&nbsp;元</td>";
      $table .="  <td align=center>$create_time</td>";
      if($paystatus == 0){
        $table .="  <td align=center>
                      <form name=\"$orderid\" action=\"javascript:void%200\" onsubmit=\"sendData($orderid);return false\">
                        <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
                        <input type=\"hidden\" name=\"paystatus\" value=\"1\">
                        <div id=\"$orderid\"><button type=\"submit\" onclick=\"javascript: return confirm('是否确认 $consumerName 已付款?')\">确认付</button></div>
                      </form>
                    </td>";
      }else{
        $table .="  <td align=center>已付款</td>";
      }
      $table .="  </tr>";
    }else{
      $table .=" <tr bgcolor=$bgcolor class=line>";
      $table .="  <td align=center>$orderid</td>";
      $table .="  <td align=center>$consumerName</td>";
      $table .="  <td align=center>$price&nbsp;元</td>";
      $table .="  <td align=center>$sagentprofit&nbsp;元</td>";
      $table .="  <td align=center>$sagentpay&nbsp;元</td>";
      $table .="  <td align=center>$create_time</td>";
      if($paystatus == 0){
        $table .="  <td align=center>
                      <form name=\"$orderid\" action=\"javascript:void%200\" onsubmit=\"sendData($orderid);return false\">
                        <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
                        <input type=\"hidden\" name=\"paystatus\" value=\"1\">
                        <div id=\"$orderid\"><button type=\"submit\" onclick=\"javascript: return confirm('是否确认 $consumerName 已付款?')\">确认付</button></div>
                      </form>
                    </td>";
      }else{
        $table .="  <td align=center>已付款</td>";
      }
      $table .="  </tr>";
    }
      
    $rowColor++;
  }
  
  if($sagentid == 0){
    $table .=" <tr bgcolor=$bgcolor class=line>";
    $table .="  <td align=center>总计</td>";
    $table .="  <td align=center>$orderCount&nbsp;单</td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center>$totalPrice&nbsp;元</td>";
    $table .="  <td align=center>$totalSagentprofit&nbsp;元</td>";
    $table .="  <td align=center>$totalSagentpay&nbsp;元</td>";
    $table .="  <td align=center>$totalMicaprofit&nbsp;元</td>";
    $table .="  <td align=center>$totalJprofit&nbsp;元</td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  </tr>";
  }else{
    $table .=" <tr bgcolor=$bgcolor class=line>";
    $table .="  <td align=center>总计</td>";
    $table .="  <td align=center>$orderCount&nbsp;单</td>";
    $table .="  <td align=center>$totalPrice&nbsp;元</td>";
    $table .="  <td align=center>$totalSagentprofit&nbsp;元</td>";
    $table .="  <td align=center>$totalSagentpay&nbsp;元</td>";
    $table .="  <td align=center></td>";
    $table .="  <td align=center></td>";
    $table .="  </tr>";
  }
  
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