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
<table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor=#6699CC height=100%  class=eng>
  		<tr bgcolor=#A5C2E0>
   		<td height="37" algin="center" colspan="2" class=hdr>公共账号管理菜单</td>
  		</tr><tr bgcolor='#E2ECF5'>
       	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/home.gif></td>
       	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/index_logon.php" target='_top'>返回首页</a></td>
      	</tr>
      	
      	<tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/node.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">&nbsp;<a href="/wechatlog/wechatlog.php" target='cmsright'>信息管理</a></td>
        </tr>
        <tr bgcolor='#E2ECF5' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#E2ECF5'">&nbsp;<a href="/wechatlog/wechatlog.php" target='cmsright'>收到信息</a></td>
        </tr>
        <!--tr bgcolor='#F2F7FB' onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">
         	<td width="19%" height="28" algin="center" bgcolor=#A5C2E0><img src=/images/new.gif></td>
         	<td width="81%"  onMouseOver="bgColor='#FFFFFF'" onMouseOut="bgColor='#F2F7FB'">&nbsp;<a href="/constants/createconstants.php" target='cmsright'>添加物流公司</a></td>
        </tr-->
        
  		<tr bgcolor=#A5C2E0>
    	<td algin="center" colspan="2"></td>
  		</tr>
		</table>