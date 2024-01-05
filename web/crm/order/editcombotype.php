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
  require_once("../include/ccombologpeer.inc.php");
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
	  $type = 2;
	}else{
	  $type = 1;
	}
	
	$returnFlag = $orderManager->updatecombotype($orderid,$type);
    if ($returnFlag){
	  $message="成功修改订单拼单状态";
	  Header("Location:order.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="修改订单拼单状态失败";
	}

	msg($message);
  }
  
  if(!$_POST){
    $orderid = trim($_GET["orderid"]);
    $idcode = trim($_GET["idcode"]);
    
    if($orderid){
      if(!$orderManager->getOrder($orderid)) exit();
      $order = $orderManager->getOrder($orderid);
      $idcode = $order->getIdcode();
    }else{
      if(!$orderManager->getOrderByIdcode($idcode)) exit();
      $order = $orderManager->getOrderByIdcode($idcode);
      $orderid = $order->getOrderid();
    }
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
  }
  $start = trim($_GET["start"]);
  $range = trim($_GET["range"]);
  
  $paystatus = $order->getPaystatus();
  //判断付款锁定状态
  if($paystatus == 1) exit();

  //$order = $orderManager->getOrder($orderid);
  $type = $order->getCType();
  
  if($type > 1){
    $combologManager = new cCombologPeer;
    $combologstatus = $combologManager->getOrderStatus($orderid);
  }
  require_once("../include/ccombologpeer.inc.php");
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
  
  $typeStr = "";
  if($type == 1){
    $typeStr1 = "修改本订单为一个拼单订单";
    $typeStr2 = "修改后将按照拼单货品总重3KG计算本单所需运费，请使用\"拼单管理\"，将本订单纳入到一个拼单中！";
  }else{
    if($combologstatus == 1){
      $typeStr1 = "不可将本订单从拼单订单恢复成一个独立的正常订单";
      $typeStr2 = "因为本订单正与一个拼单绑定！";
    }else{
      $typeStr1 = "将本订单从拼单订单恢复成一个独立的正常订单";
      $typeStr2 = "恢复后，本订单将作为一个独立的订单自动核算运费与收益！";
    }
  }
  
if($type > 1 && $combologstatus == 1){
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr align=\"center\">$typeStr1</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td colspan=\"2\" width=\"100\" height=\"27\" align=\"center\">$typeStr2</td>
       </tr>
   </table>";
}else{
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
      <input type=\"hidden\" name=\"type\" value=\"$type\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr align=\"center\">$typeStr1</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td colspan=\"2\" width=\"100\" height=\"27\" align=\"center\">$typeStr2</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改\"></td>
    </tr>
   </form>
   </table>";
}
?>