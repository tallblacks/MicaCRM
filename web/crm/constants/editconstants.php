<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    require_once("../include/cconstantspeer.inc.php");
    CheckCookies();

    session_start();
    if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)) {
    	  Header("Location:../phpinc/main.php");
		    exit();
    }

    $db = new cDatabase;
    if ($_POST) {
	      foreach ($_POST as $key => $val) {
		        $$key=$val;
	      }
	
	      $constantsManager = new cConstantsPeer;
	      $returnFlag = $constantsManager->update($constantsid,$constantsVal,$type);
        if ($returnFlag) {
	          $message = "成功修改变量信息";
	          Header("Location:constants.php?start=$start&range=$range&msg=$message");
	          exit();
	      } else {
	          $message="修改变量信息失败";
	      }
	      msg($message);
    }
  
    if (!$_POST) {
        $constantsid = trim($_GET["constantsid"]);
	      $start = trim($_GET["start"]);
        $range = trim($_GET["range"]);
    }
    $constantsManager = new cConstantsPeer;
    $constants = $constantsManager->getConstants($constantsid);
    $constantsVal = $constants->getConstants();
    $type = $constants->getCType();
    if ($type == 1) {
        $typeStr = "<input type=\"radio\" name=\"type\" value=\"1\" checked>汇率
                    <input type=\"radio\" name=\"type\" value=\"2\">拼单货品总重（克）
                    <input type=\"radio\" name=\"type\" value=\"3\">直邮订单包装重量（克）
                    <input type=\"radio\" name=\"type\" value=\"4\">拼单订单包装重量（克）";
    } else if ($type == 2) {
        $typeStr = "<input type=\"radio\" name=\"type\" value=\"1\">汇率
                    <input type=\"radio\" name=\"type\" value=\"2\" checked>拼单货品总重（克）
                    <input type=\"radio\" name=\"type\" value=\"3\">直邮订单包装重量（克）
                    <input type=\"radio\" name=\"type\" value=\"4\">拼单订单包装重量（克）";
    } else if ($type == 3) {
        $typeStr = "<input type=\"radio\" name=\"type\" value=\"1\">汇率
                    <input type=\"radio\" name=\"type\" value=\"2\">拼单货品总重（克）
                    <input type=\"radio\" name=\"type\" value=\"3\" checked>直邮订单包装重量（克）
                    <input type=\"radio\" name=\"type\" value=\"4\">拼单订单包装重量（克）";
    } else if ($type == 4) {
        $typeStr = "<input type=\"radio\" name=\"type\" value=\"1\">汇率
                    <input type=\"radio\" name=\"type\" value=\"2\">拼单货品总重（克）
                    <input type=\"radio\" name=\"type\" value=\"3\">直邮订单包装重量（克）
                    <input type=\"radio\" name=\"type\" value=\"4\" checked>拼单订单包装重量（克）";
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
            <input type=\"hidden\" name=\"constantsid\" value=\"$constantsid\">
            <input type=\"hidden\" name=\"start\" value=\"$start\">
            <input type=\"hidden\" name=\"range\" value=\"$range\">
            <tr {$strings['TABLE_TITLE_BKCOLOR']}>
            <td colspan=\"2\" height=\"30\" class=hdr>编辑常量</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
            <td width=\"100\" height=\"27\"  align=\"right\">常量值</td>
            <td >&nbsp;<input type=\"text\" name=\"constantsVal\" value=\"$constantsVal\"></td>
            </tr>
            <tr {$strings['TABLE_LIGHT_COLOR']}>
            <td width=\"100\" height=\"27\"  align=\"right\">常量类型</td>
            <td height=\"27\">&nbsp;
                $typeStr
            </td>
            </tr>
            <tr{$strings['TABLE_DARK_COLOR']}>
            <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认编辑常量\"></td>
            </tr>
            </form>
            </table>";
?>