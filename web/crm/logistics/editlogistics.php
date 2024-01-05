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
  require_once("../include/clogisticspeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}

	$logisticsManager = new cLogisticsPeer;
	$returnFlag = $logisticsManager->update($logisticsid,$name,$description,$preurl);
    if ($returnFlag){
	  $message="成功修改物流公司信息";
	  Header("Location:logistics.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="修改物流公司信息失败";
	}
	msg($message);
  }
  if (!$_POST){
    $logisticsid = trim($_GET["logisticsid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $logisticsManager = new cLogisticsPeer;
  $logistics = $logisticsManager->getLogistics($logisticsid);
  $name = $logistics->getCName();
  $description = $logistics->getDescription();
  $preurl = $logistics->getPreurl();
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
      <input type=\"hidden\" name=\"logisticsid\" value=\"$logisticsid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>修改物流公司信息</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">公司名称</td>
      <td >&nbsp;<input type=\"text\" name=\"name\" value=\"$name\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">公司简介</td>
      <td >&nbsp;<input type=\"text\" name=\"description\" value=\"$description\">（可不填）</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">单号查询地址前缀</td>
      <td >&nbsp;<input type=\"text\" name=\"preurl\" value=\"$preurl\">（可不填，如易达通填：http://www.qexpress.co.nz/tracking.aspx?orderNumber=）</td>
     </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改物流公司信息\"></td>
    </tr>
   </form>
   </table>";
?>