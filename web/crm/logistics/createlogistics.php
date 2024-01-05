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
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	$type = 1;
	$new_logistics = new cLogistics;
	$new_logistics->setCName($name);
	$new_logistics->setDescription($description);
	$new_logistics->setPreurl($preurl);
    $new_logistics->setCType($type);
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
	$logisticsManager = new cLogisticsPeer;
	$returnFlag = $logisticsManager->create($new_logistics);
    if ($returnFlag){
	  $message="成功创建新物流公司";
	}else{
	  $message="创建新物流公司失败";
	}
	msg($message);
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
<?php
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>添加新的物流公司</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">公司名称</td>
      <td >&nbsp;<input type=\"text\" name=\"name\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">公司简介</td>
      <td >&nbsp;<input type=\"text\" name=\"description\">（可不填）</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">单号查询地址前缀</td>
      <td >&nbsp;<input type=\"text\" name=\"preurl\">（可不填，如易达通填：http://www.qexpress.co.nz/tracking.aspx?orderNumber=）</td>
     </tr>
     <!--tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">常量类型</td>
      <td height=\"27\">&nbsp;
	   <input type=\"radio\" name=\"type\" value=\"1\" checked>汇率
	  </td>
    </tr-->
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"添加新物流公司\"></td>
    </tr>
   </form>
   </table>";
?>