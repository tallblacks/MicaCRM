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
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="pragma" content="no-cache">
<style type="text/css">
<!--
body {
	margin-top: 0px;
	margin-left: 0px;
}
-->
</style>
<link rel=stylesheet type=text/css href=/style/global.css>
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor=#6699CC height=100%  class=eng>
  		<tr bgcolor=#A5C2E0>
   		<td height="37" algin="center" colspan="2" class=hdr>订单管理菜单</td>
  		</tr>
  		
  		<tr bgcolor='#F2F7FB'>
       	    <td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/home.gif></td>
       	    <td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/index_logon.php" target='_top'>返回首页</a></td>
      	</tr>
      	
      	<tr bgcolor='#E2ECF5' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/node.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">&nbsp;<a href="/order/order.php" target='cmsright'>订单管理</a></td>
        </tr>
        <tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/order/profit.php" target='cmsright'>利润视图</a></td>
        </tr>
        <tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">&nbsp;<a href="/order/puborder.php" target='cmsright'>出单视图</a></td>
        </tr>
        <?php if($_SESSION['SESS_TYPE'] == ADMIN){?>
        <tr bgcolor='#E2ECF5' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/node.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/order/report.php" target='cmsright'>统计管理</a></td>
        </tr>
        <tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">&nbsp;<a href="/order/mireport.php" target='cmsright'>小蜜统计</a></td>
        </tr>
        <tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">&nbsp;<a href="/order/outexcel.php" target='cmsright'>表格导出</a></td>
        </tr>
        <tr bgcolor='#E2ECF5' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/node.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/order/comborder.php" target='cmsright'>拼单管理</a></td>
        </tr>
        <tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/order/comborderprofit.php" target='cmsright'>拼单利润</a></td>
        </tr>
        <tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/order/comborderoutexcel.php" target='cmsright'>拼单表格</a></td>
        </tr>
        <?php }?>
  		<tr bgcolor=#A5C2E0>
    	<td algin="center" colspan="2"></td>
  		</tr>
		</table>