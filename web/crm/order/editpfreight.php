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

    if($freight < $pfreight){
      $message="错误！实际运费大于实收运费";
    }else{
      $returnFlag = $orderManager->updatepfreight($orderid,$pfreight);
      if ($returnFlag){
	    $message="成功修改订单中的真实运费信息";
	    Header("Location:order.php?start=$start&range=$range&msg=$message");
	    exit();
	  }else{
	    $message="修改订单中的真实运费信息失败";
	  }
    }
	msg($message);
  }
  
  if(!$_POST){
    $orderid = trim($_GET["orderid"]);
  }
  $start = trim($_GET["start"]);
  $range = trim($_GET["range"]);
  $order = $orderManager->getOrder($orderid);
  $freight = $order->getFreight();
  $pfreight = $order->getPfreight();
  $price = $order->getPrice();
  $paystatus = $order->getPaystatus();
  
  //判断付款锁定状态
  if($paystatus == 1) exit();
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
      <input type=\"hidden\" name=\"freight\" value=\"$freight\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>修改订单真实运费</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">本订单总收入</td>
         <td >&nbsp;$price</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">本订单实收运费</td>
         <td >&nbsp;$freight</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">修改本单实际运费</td>
         <td >&nbsp;<input type=\"text\" name=\"pfreight\" value=\"$pfreight\">实收运费必须大于实际运费</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改订单真实运费\"></td>
    </tr>
   </form>
   </table>";
?>