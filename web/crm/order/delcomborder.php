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
  require_once("../include/ccombologpeer.inc.php");
  require_once("../include/ccomborderpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  $comborderManager = new cComborderPeer;
  
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}

	$returnFlag = $comborderManager->delete($comborderid);
    if ($returnFlag){
	  $message="成功删除拼单";
	  Header("Location:comborder.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="删除拼单失败";
	}

	msg($message);
  }
  
  if (!$_POST){
    $comborderid = trim($_GET["comborderid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $doNotDelFlag = false;
  
  $combologListCount = 0;
  $combologManager = new cCombologPeer;
  $combologList = $combologManager->getCombologList($comborderid);
  //$combologListCount = sizeof($combologList);
  //if($combologListCount > 0){
  if($combologList){
    $doNotDelFlag = true;
  }
  if(!$doNotDelFlag){
    $comborder = $comborderManager->getComborder($comborderid);
    $name = $comborder->getCname();
    $address = $comborder->getAddress();
    $mobile = $comborder->getMobile();
    $memo = $comborder->getMemo();
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
  if($doNotDelFlag){
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>删除拼单</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\">拼单内有订单时不可删除拼单！</td>
    </tr>
   </table>";
  }else{
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"comborderid\" value=\"$comborderid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>删除拼单</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单编号</td>
         <td >&nbsp;$comborderid</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单收货人姓名</td>
         <td >&nbsp;$name</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单收货地址</td>
         <td >&nbsp;$address</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单收货人手机</td>
         <td >&nbsp;$mobile</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单备注</td>
         <td >&nbsp;$memo</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除拼单\"></td>
    </tr>
   </form>
   </table>";
   }
?>