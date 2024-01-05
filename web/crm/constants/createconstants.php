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
  require_once("../include/cconstantspeer.inc.php");
  //require_once("../include/function.inc.php");
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	$new_constants = new cConstants;
	$new_constants->setConstants($constants);
    $new_constants->setCType($type);
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
	$constantsManager = new cConstantsPeer;
	$returnFlag = $constantsManager->create($new_constants);
    if ($returnFlag){
	  $message="成功创建新变量";
	}else{
	  $message="创建新变量失败";
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
        <td colspan=\"2\" height=\"30\" class=hdr>添加新的常量</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">常量值</td>
      <td >&nbsp;<input type=\"text\" name=\"constants\"></td>
     </tr>
      <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">常量类型</td>
      <td height=\"27\">&nbsp;
	   <input type=\"radio\" name=\"type\" value=\"1\" checked>汇率
	   <input type=\"radio\" name=\"type\" value=\"2\">拼单货品总重（克）
	   <input type=\"radio\" name=\"type\" value=\"3\">直邮订单包装重量（克）
	   <input type=\"radio\" name=\"type\" value=\"4\">拼单订单包装重量（克）
	  </td>
    </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"添加新常量\"></td>
    </tr>
   </form>
   </table>";
?>