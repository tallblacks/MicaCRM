<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
	session_start();
	if (!isset($_SESSION["SESS_USERID"])){
		Header("Location:index.php");
		exit();
	}

  //require_once("../include/mysql.inc.php");
  require_once("../include/corderpeer.inc.php");
  require_once("../include/cconstantspeer.inc.php");
  require_once("../include/cproductpeer.inc.php");
  require_once("../include/csuborderpeer.inc.php");
  //require_once("../include/function.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	//Agent判断
    $orderManager = new cOrderPeer;
    $order = $orderManager->getOrder($orderid);
    $consumerid = $order->getConsumerid();
    $consumerManager = new cConsumerPeer;
    $consumer = $consumerManager->getConsumer($consumerid);
    $consumerAgentid = $consumer->getAgentid();
    if($_SESSION['SESS_TYPE'] == AGENT && $consumerAgentid != $_SESSION['SESS_USERID']) exit();
    
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
	
  	  $type = 1;
  	  $status = 1;
  	
	  $new_suborder = new cSuborder;
	  $new_suborder->setOrderid($orderid);
      $new_suborder->setProductid($productid);
      $new_suborder->setPrice($price);
      $new_suborder->setOrdernum($ordernum);
      $new_suborder->setWeight($weight);
      $new_suborder->setSupermarket($supermarket);
      $new_suborder->setCtype($type);
      $new_suborder->setPstatus($status);
	
	  $suborderManager = new cSuborderPeer;
	  
	  $returnFlag = $suborderManager->update($new_suborder);
      if ($returnFlag){
	    $message="成功修改订单中的产品信息";
	    Header("Location:suborderlist.php?orderid=$orderid&msg=$message");
	    exit();
	  }else{
	    $message="修改订单中的产品信息失败";
	  }

	msg($message);
  }
  
  if(!$_POST){
    $orderid = trim($_GET["orderid"]);
    $productid = trim($_GET["productid"]);
    
    //Agent判断
    $orderManager = new cOrderPeer;
    $order = $orderManager->getOrder($orderid);
    $consumerid = $order->getConsumerid();
    $consumerManager = new cConsumerPeer;
    $consumer = $consumerManager->getConsumer($consumerid);
    $consumerAgentid = $consumer->getAgentid();
    if($_SESSION['SESS_TYPE'] == AGENT && $consumerAgentid != $_SESSION['SESS_USERID']) exit();
    
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
  
    $productManager = new cProductPeer;
  	$product = $productManager->getProduct($productid);
  	$ename = $product->getEname();
  	$cname = $product->getCname();
  	$ppricenz = $product->getPpricenz();
  	$spricecn = $product->getSpricecn();
  	$weight = $product->getWeight();
  	$supermarket = $product->getSupermarket();
  	
  	//$orderManager = new cOrderPeer;
    //$rate = $orderManager->getOrder($orderid)->getNRate();
    $rate = $order->getNRate();
  	
  	$ppricecn = $ppricenz*$rate;
  	
  	$suborderManager = new cSuborderPeer;
  	$suborder = $suborderManager->getSuborder($orderid,$productid);
  	$price = $suborder->getPrice();
  	$ordernum = $suborder->getOrdernum();
  	
  	if($_SESSION['SESS_TYPE'] == ADMIN){
  	  $spricecnStr1 = "";
  	  $spricecnStr2 = "<tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">设定本订单的产品售价，注意是单价</td>
         <td >&nbsp;<input type=\"text\" name=\"price\" value=\"$price\">早前设定的产品价格，可以修改啦～</td>
       </tr>";
  	}else{
  	  $spricecnStr1 = "<input type=\"hidden\" name=\"price\" value=\"$price\">";
  	  $spricecnStr2 = "<tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">本订单的产品售价，注意是单价</td>
         <td >&nbsp;$price</td>
       </tr>";
  	}
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
<!--script type="text/javascript" src="/ajaxserver/ajax2013.js"></script>
<script type="text/javascript">
  alter("fuck");
</script-->
<?php
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
      <input type=\"hidden\" name=\"productid\" value=\"$productid\">
      <input type=\"hidden\" name=\"weight\" value=\"$weight\">
      <input type=\"hidden\" name=\"supermarket\" value=\"$supermarket\">
      $spricecnStr1
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>修改订单产品信息</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">产品英文文名称</td>
         <td >&nbsp;$ename</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">产品中文名称</td>
         <td >&nbsp;$cname</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">单品重量</td>
         <td >&nbsp;$weight</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">产品进价</td>
         <td >&nbsp;$ppricenz,&nbsp;$rate,&nbsp;$ppricecn&nbsp;（新西兰元、当前订单汇率、人民币价格）</td>
       </tr>
       $spricecnStr2
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">购买数量</td>
         <td >&nbsp;<input type=\"text\" name=\"ordernum\" value=\"$ordernum\"></td>
       </tr>
       <!--tr {$strings['TABLE_LIGHT_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">是否包邮</td>
         <td height=\"27\">&nbsp;
	       <input type=\"radio\" name=\"ffree\" value=\"0\" checked>不包
	       <input type=\"radio\" name=\"ffree\" value=\"1\">包邮
	     </td>
       </tr-->
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改订单产品信息\"></td>
    </tr>
   </form>
   </table>";
?>