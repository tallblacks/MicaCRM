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
	$returnFlag = $logisticsManager->delete($logisticsid);
    if ($returnFlag){
	  $message="成功删除物流公司信息";
	  Header("Location:logistics.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="删除物流公司信息失败";
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
        <td colspan=\"2\" height=\"30\" class=hdr>删除物流公司信息，<font color=\"red\"><b>此删除不可逆！</b></font></td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">公司名称</td>
      <td >&nbsp;$name</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">公司简介</td>
      <td >&nbsp;$description</td>
     </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除物流公司信息\"></td>
    </tr>
   </form>
   </table>";
?>