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

  //require_once("../include/mysql.inc.php");
  require_once("../include/corderpeer.inc.php");
  require_once("../include/cconsumerpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	if($orderid > 0){
	  $orderManager = new cOrderPeer;
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
	  
	  $returnFlag = $orderManager->updateMemo($orderid,$memo);
	}
  	
    if ($returnFlag){
	  $message="成功编辑备注";
	  Header("Location:order.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="编辑备注失败";
	}

	msg($message);
  }else{
    $orderid = trim(@$_GET["orderid"]);
    $idcode = trim($_GET["idcode"]);
    //$comborderid = trim($_GET["comborderid"]);
    $start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
    
    $orderManager = new cOrderPeer;
    if($orderid){
      if(!$orderManager->getOrder($orderid)) exit();
      $order = $orderManager->getOrder($orderid);
      $idcode = $order->getIdcode();
    }else{
      if(!$orderManager->getOrderByIdcode($idcode)) exit();
      $order = $orderManager->getOrderByIdcode($idcode);
      $orderid = $order->getOrderid();
    }
	
	$consumerid = $order->getConsumerid();
	$memo = $order->getMemo();
	
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
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>编辑订单备注</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">订单备注</td>
         <td >&nbsp;<textarea rows=\"6\" name=\"memo\">$memo</textarea></td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"编辑订单备注\"></td>
    </tr>
   </form>
   </table>";
    
echo "<br><br>";    
?>

<?php
require_once("../include/ccrmpicturepeer.inc.php");
require_once("../include/user.inc.php");
$crmpictureManager = new cCrmPicturePeer;
$orderPictureList = $crmpictureManager->getCrmPictureListByOrderid($orderid);
$user=new user;
?>

<?php
echo "<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=70% align=center>
    <tr class=tine bgcolor=#dddddd>
        <td align=center width=20%>图片名</td>
        <td align=center width=55%>URL（点击图片看原图）</td>
        <td align=center width=15%>上载者</td>
        <td align=center width=10%>删除</td>
    </tr>";

if(!empty($orderPictureList)){
    $rowColor=1;
    foreach($orderPictureList as $crmpicture){
  	$crmpicture = (object)$crmpicture;
        $pictureid = $crmpicture->getPictureid();
  	$name = $crmpicture->getName();
  	$url = $crmpicture->getPictureUrl();
  	$picUserid = $crmpicture->getUserid();
        
        $sql="select * from user where userid=$picUserid";
	$query=$db->query($sql);
	$data=$db->fetch_array($query);
        $picRealname = $data['realname'];
        
        if(($_SESSION['SESS_USERID'] == $picUserid) || ($_SESSION['SESS_TYPE'] == ADMIN)){
            $picDelStr = "<td align=center><a href=delorderpic.php?pictureid=$pictureid&orderid=$orderid&start=$start&range=$range&idcode=$idcode>删除</a></td>";
        }else{
            $picDelStr = "<td align=center>删除</td>";
        }

        echo "<tr class=tine bgcolor=#dddddd>
                <td align=center>$name</td>
                <td align=center><a href=$url target=_ablank><img src=$url width=300></a></td>
                <td align=center>$picRealname</td>
                $picDelStr
            </tr>";
    }
}

echo "<tr class=tine bgcolor=#dddddd>
        <td align=center width=100% colspan=7><a href=createorderpic.php?orderid=$orderid&start=$start&range=$range&idcode=$idcode>为这个订单上传图片附件</a></td>
    </tr>
";
?>