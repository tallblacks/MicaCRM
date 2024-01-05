<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();
    
    if ($_POST){
		//foreach($_POST as $key => $val) $$key=$val;
		$pictureid = trim($_POST["pictureid"]);
        $orderid = trim($_POST["orderid"]);
        $idcode = trim($_POST["idcode"]);
        $start = trim($_POST["start"]);
        $range = trim($_POST["range"]);
    }else{
        $pictureid = trim($_GET["pictureid"]);
        $orderid = trim($_GET["orderid"]);
        $idcode = trim($_GET["idcode"]);
        $start = trim($_GET["start"]);
        $range = trim($_GET["range"]);
    }
    
    $db = new cDatabase;
    //mysql_query('set names utf8');
    require_once("../include/ccrmpicturepeer.inc.php");
    $crmpictureManager = new cCrmPicturePeer;
    $crmpicture = $crmpictureManager->getCrmPictureByPictureid($pictureid);
    $picUserid = $crmpicture->getUserid();
    $picName = $crmpicture->getName();
    
    $user=new user;
    $sql="select * from user where userid=$picUserid";
    $query=$db->query($sql);
    $data=$db->fetch_array($query);
    $picRealname = $data['realname'];
    
    session_start();
    if(($_SESSION['SESS_USERID'] != $picUserid) && ($_SESSION['SESS_TYPE'] != ADMIN)){
    	Header("Location:../phpinc/main.php");
	exit();
    }

    if ($_POST){
        $delFlag = $crmpictureManager->delCrmPicture($pictureid);
        if($delFlag){
            $message="成功删除图片";
        }else{
            $message="删除图片失败，请联系管理员";
        }
        Header("Location:editmemo.php?idcode=$idcode&start=$start&range=$range&msg=$message");
        exit();
    }
/*
  
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}

	$orderManager = new cOrderPeer;
	$returnFlag = $orderManager->delete($orderid);
    if ($returnFlag){
	  $message="成功删除订单";
	  Header("Location:order.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="删除订单失败";
	}

	msg($message);
  }
  
  if (!$_POST){
    $orderid = trim($_GET["orderid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $doNotDelFlag = false;
  
  $orderManager = new cOrderPeer;
  $order = $orderManager->getOrder($orderid);
  $price = $order->getPrice();
  $weight = $order->getWeight();
  if($price != 0 || $weight != 0){
    $doNotDelFlag = true;
  }
  if(!$doNotDelFlag){
    $profitManager = new cProfitPeer;
    $profitcount = $profitManager->getProfitCount($orderid);
    if($profitcount != 0){//有利润数据
      for($i=1;$i<8;$i++){
        $profit = $profitManager->getProfit($orderid,$i);
        $profitvalue = $profit->getProfit();
        if($profitvalue != 0){
          $doNotDelFlag = true;
        }
      }
    }
  }
  if(!$doNotDelFlag){
    $consumerid = $order->getConsumerid();
    $rate = $order->getNRate();
    $consumerManager = new cConsumerPeer;
  	$consumer = $consumerManager->getConsumer($consumerid);
  	$consumerName = $consumer->getCname();
  }
  
  $paystatus = $order->getPaystatus();
  //判断付款锁定状态
  if($paystatus == 1) exit();*/
?>
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
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"pictureid\" value=\"$pictureid\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"idcode\" value=\"$idcode\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>删除图片</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">图片名称</td>
         <td >&nbsp;$picName</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">上传用户</td>
         <td >&nbsp;$picRealname</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除图片\"></td>
    </tr>
   </form>
   </table>";
?>