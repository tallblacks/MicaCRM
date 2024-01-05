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
  require_once("../include/cconsumerpeer.inc.php");
  require_once("../include/ccomborderpeer.inc.php");
  //require_once("../include/function.inc.php");
  session_start();
  if (!isset($_SESSION["SESS_USERID"])){
	Header("Location:index.php");
	exit();
  }
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  $comborderManager = new cComborderPeer;
  
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}

	$consumerFlag = true;
	if($name != null || $name != ""){
	  if(($mobile == null || $moble == "") && ($address == null || $address == "")){
	    $consumerManager = new cConsumerPeer;
	    $consumer = $consumerManager->getConsumerByName($name);
	    if($consumer){
	      $mobile = $consumer->getMobile();
	      $address = $consumer->getAddress();
	    }else{
	      $consumerFlag = false;
	      $message="拼单收货人姓名，不在消费者管理库中";
	    }
	  }
	}
	
	if($consumerFlag){
	  $comborder = new cComborder;
	  $comborder->setComborderid($comborderid);
	  $comborder->setCname($name);
      $comborder->setAddress($address);
      $comborder->setMemo($memo);
      $comborder->setMobile($mobile);
      $returnFlag = $comborderManager->update($comborder);
      if ($returnFlag){
	    $message="成功修改拼单的物流和备注信息";
	    Header("Location:comborder.php?start=$start&range=$range&msg=$message");
	    exit();
	  }else{
	    $message="修改拼单的物流和备注信息失败";
	  }
	}

	msg($message);
  }

  if (!$_POST){
    $comborderid = trim($_GET["comborderid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  
  $comborder = $comborderManager->getComborder($comborderid);
  $name = $comborder->getCname();
  $address = $comborder->getAddress();
  $mobile = $comborder->getMobile();
  $memo = $comborder->getMemo();
  
  //$user=new user;
  //$consumerAgentname = $user->getUserbyId($consumer->getAgentid())->realname;
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
    if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }
    
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"comborderid\" value=\"$comborderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>修改拼单的物流和备注信息</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">拼单收货人姓名</td>
      <td >&nbsp;<input type=\"text\" name=\"name\" value=\"$name\">只有当姓名填写了、手机和地址没填写时，系统会判断收货人信息是否在消费者管理中，如果是，则用消费者管理中的手机和地址信息填充</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">拼单收货人手机</td>
      <td >&nbsp;<input type=\"text\" name=\"mobile\" value=\"$mobile\">系统不会自动保存新的拼单收货人信息供下次使用</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">拼单收货地址</td>
      <td >&nbsp;<input type=\"text\" name=\"address\" value=\"$address\" style=\"width:300px;\">重要：每次修改完毕后，请再次返回这个页面确认正确！</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">拼单备注</td>
      <td >&nbsp;<input type=\"text\" name=\"memo\" value=\"$memo\" style=\"width:300px;\"></td>
     </tr>
     <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改拼单的物流和备注信息\"></td>
    </tr>
   </form>
   </table>";
?>