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
	
	$order = $orderManager->getOrder($orderid);
	//判断日期，秘书
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
	
    if($type == 1){
      if($_SESSION['SESS_TYPE'] == ADMIN){
        $message="非拼单不支持修改实收运费";
      }else{
        $message="非拼单不支持修改收取运费";
      }
    }else if($freight < $pfreight){
      if($_SESSION['SESS_TYPE'] == ADMIN){
        $message="错误！实收运费小于实际运费";
      }else{
        $message="错误！收取运费太少！";
      }
    }else{
      $returnFlag = $orderManager->updateFreight($orderid,$freight);
      if ($returnFlag){
        if($_SESSION['SESS_TYPE'] == ADMIN){
	      $message="成功修改订单中的真实运费信息";
	    }else{
          $message="成功修改订单中的收取运费信息";
        }
	    Header("Location:order.php?start=$start&range=$range&msg=$message");
	    exit();
	  }else{
	    if($_SESSION['SESS_TYPE'] == ADMIN){
	      $message="修改订单中的真实运费信息失败";
	    }else{
          $message="修改订单中的收取运费信息失败";
        }
	  }
    }

	msg($message);
  }
  
  if(!$_POST){
    $orderid = trim($_GET["orderid"]);
    $idcode = trim($_GET["idcode"]);
    $start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  if($orderid){
    if(!$orderManager->getOrder($orderid)) exit();
    $order = $orderManager->getOrder($orderid);
    $idcode = $order->getIdcode();
  }else{
    if(!$orderManager->getOrderByIdcode($idcode)) exit();
    $order = $orderManager->getOrderByIdcode($idcode);
    $orderid = $order->getOrderid();
  }
  $weight = $order->getWeight();
  $rate = $order->getNRate();
  $freight = $order->getFreight();
  $pfreight = $order->getPfreight();
  $price = $order->getPrice();
  $type = $order->getCType();
  $paystatus = $order->getPaystatus();

  //判断付款锁定状态
  if($paystatus == 1) exit();
  
  //判断日期，秘书
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
  
  //小蜜不显示实际运费，修改如下
  if($_SESSION['SESS_TYPE'] == ADMIN){
    $adminPriceMemo = "本订单总收入";
    $adminPfreightStr = "<tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">本订单实际运费</td>
         <td >&nbsp;$pfreight</td>
       </tr>";
    
    //计算订单理论实收运费
    $constantsManager = new cConstantsPeer;
    $rcombototalweight = $constantsManager->getNewConstants(2);//当前拼单后产品总重
    $packageZhiWeightG = $constantsManager->getNewConstants(3);//直邮包装重，克
    $packagePinWeightG = $constantsManager->getNewConstants(4);//拼单包装重，克
    if($type == 1){
      if($weight <= 0){//没有运费
        $countFreight = 0;
      }else if($weight < 300){//不到1公斤，按1公斤收费
    	$countFreight = 1*8*$rate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
      }else{
    	$countFreight = (($weight+$packageZhiWeightG)/1000)*8*$rate;//新实收总运费，(总KG+0.7KG)*8新西兰元*本单汇率，人民币
      }
    }else{//type=2,3 均为拼单
      if($weight <= 0){
        $countFreight = 0;
      }else{
        $wzhanbi = round($weight/$rcombototalweight,2);
    	if($wzhanbi < $weight/$rcombototalweight) $wzhanbi = $wzhanbi+0.01;
    	$countFreight = $wzhanbi*((($rcombototalweight+$packagePinWeightG)/1000)*8*$rate);//新实收总运费，人民币
    	$countFreight = round($countFreight,2);
      }
    }
    
    $adminFreightMemoStr = "（公式计算应收运费：$countFreight 元）实收运费必须大于实际运费";
    $adminTitleMemoStr = "修改订单实收运费";
    $adminSubmitMemoStr = "确认修改订单真实运费";
  }else{
    $adminPriceMemo = "本订单金额";
    $adminPfreightStr = "";
    $adminFreightMemoStr = "";
    $adminTitleMemoStr = "修改订单收取运费";
    $adminSubmitMemoStr = "确认修改订单收取运费";
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
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"pfreight\" value=\"$pfreight\">
      <input type=\"hidden\" name=\"type\" value=\"$type\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>$adminTitleMemoStr</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">$adminPriceMemo</td>
         <td >&nbsp;$price</td>
       </tr>
       $adminPfreightStr
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">$adminTitleMemoStr</td>
         <td >&nbsp;<input type=\"text\" name=\"freight\" value=\"$freight\">$adminFreightMemoStr</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"$adminSubmitMemoStr\"></td>
    </tr>
   </form>
   </table>";
?>