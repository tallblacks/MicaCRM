<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

  //require_once("../include/config.inc.php");
  session_start();
  if (!isset($_SESSION["SESS_USERID"])){
    Header("Location:../phpinc/main.php");
	exit();
  }
  if($_SESSION['SESS_TYPE'] != ADMIN && $_SESSION['SESS_TYPE'] != SECRETARY && $_SESSION['SESS_TYPE'] != XIAOMI){
    Header("Location:../phpinc/main.php");
	exit();
  }

  //require_once("../include/mysql.inc.php");
  require_once("../include/corderpeer.inc.php");
  require_once("../include/cconstantspeer.inc.php");
  require_once("../include/cproductpeer.inc.php");
  require_once("../include/csuborderpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  $orderManager = new cOrderPeer;
  
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	//判断日期，秘书
	$order = $orderManager->getOrder($orderid);
	$operatorid = $order->getOperatorid();
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

	$returnFlag = $orderManager->updateprice($orderid,$discount);
    if ($returnFlag){
	  $message="成功给予订单优惠";
	  Header("Location:order.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="给予订单优惠失败";
	}

	msg($message);
  }
  
  if(!$_POST){
    $orderid = trim($_GET["orderid"]);
  }
  $start = trim($_GET["start"]);
  $range = trim($_GET["range"]);
  //$order = $orderManager->getOrder($orderid);
  //$price = $order->getPrice();
  
  $doNotModifyFlag = false;
  $order = $orderManager->getOrder($orderid);
  $price = $order->getPrice();
  $weight = $order->getWeight();
  $discount = $order->getDiscount();
  $paystatus = $order->getPaystatus();
  $operatorid = $order->getOperatorid();
  
  //判断付款锁定状态
  if($paystatus == 1) exit();
  
  //判断日期，秘书
  $create_time = $order->getCreatetime();
  if($_SESSION['SESS_TYPE'] == SECRETARY){
    if(substr($create_time,0,4) == "2013") exit();
    if(substr($create_time,0,4) == "2014"){
      if(substr($create_time,5,2) == "1" || substr($create_time,5,2) == "2" || substr($create_time,5,2) == "3" || substr($create_time,5,2) == "4" || substr($create_time,5,2) == "5") exit();
      if(substr($create_time,5,2) == "6"){
        //if(substr($create_time,8,2) == "01") exit();
        if(substr($create_time,8,2) < 15) exit();
      }
    }
    
    //9月10日起，如果operatorid是admin或者是小蜜的，超级小蜜不能看
    if((substr($create_time,0,4) != "2013" && substr($create_time,0,4) != "2014") || (substr($create_time,0,4) == "2014" || substr($create_time,5,1) != "0") || (substr($create_time,0,4) == "2014" || substr($create_time,5,2) == "09" || substr($create_time,8,1) != "0")){
      $user = new user;
  	  $userType = $user->getUserbyId($operatorid)->type;
      if($userType != 3 && $userType != 5) exit();
    }
  }
  
  //小蜜不能看operatorid不是自己的
  if($_SESSION['SESS_TYPE'] == XIAOMI){
    if($operatorid != $_SESSION['SESS_USERID']) exit();
  }
  
  if($price != 0 && $weight != 0){
    $doNotModifyFlag = true;
  }
  if(!$doNotModifyFlag){
    require_once("../include/cprofitpeer.inc.php");
    $profitManager = new cProfitPeer;
    $profitcount = $profitManager->getProfitCount($orderid);
    if($profitcount != 0){//有利润数据
      for($i=1;$i<8;$i++){
        $profit = $profitManager->getProfit($orderid,$i);
        $profitvalue = $profit->getProfit();
        if($profitvalue != 0){
          $doNotModifyFlag = true;
        }
      }
    }
  }
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
body {
	margin-top: 0px;
	margin-left: 0px;
}
-->
</style>
<link rel=stylesheet type=text/css href=/style/global.css>
<?php
  if(!empty($msg)) {	
    echo "<span class=cur>$msg</span>";
  }
  
  $discountStr = "";
  if($discount > 0){
    $discountStr = "<tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\" colspan=\"2\" align=\"center\">&nbsp;本订单已经设置过优惠了，你将修改优惠金额！</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\" colspan=\"2\" align=\"center\"><font color=\"red\"><b>&nbsp;直接输入你希望的基于原价格的优惠金额即可！如订单价1000元，原来输入优惠300元，现在反悔了就想在原价基础上优惠100元，则直接输入100元即可。</b></font></td>
       </tr>";
  }
  
  if($doNotModifyFlag){
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>请输入本单优惠的价格</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">本订单优惠</td>
         <td >&nbsp;<input type=\"text\" name=\"discount\" value=\"$discount\">人民币，可输入小数点后两位，如：80.75</td>
       </tr>
       $discountStr
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认给予优惠\"></td>
    </tr>
   </form>
   </table>";
  }else{
    echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>空订单不能给予优惠</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">点击返回</td>
         <td >&nbsp;<a href=\"/order/order.php?start=$start&range=$range\">返回</a></td>
       </tr>
   </table>";
  }
?>