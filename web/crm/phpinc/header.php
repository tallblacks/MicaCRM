<?php
    require_once("../include/function.inc.php");
    require_once("../include/config.inc.php");
    require_once("../include/user.inc.php");
    require_once("../include/mysql.inc.php");
    CheckCookies();

    session_start();
?>
<html>
	<head>
 	    <title></title>
 	    <META content=text/html; charset=gb2312 http-equiv=Content-Type>
 	    <link rel=stylesheet type=text/css href=../style/global.css>
 	</head>
 	<BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
 	    <table cellpadding=0 cellspacing=0 border=0 width=95%>
 		    <tr>
 		        <td width=20% align=center><a href=main.php target=main><img src=/images/all_black.jpg alt=cms admin border=0></a></td>
 		        <td align=center><a href=/order/index.php target=main><img src=/images/column1.jpg border=0></a></td>
 		        <td align=center><a href=/consumer/index.php target=main><img src=/images/column2.jpg border=0></a></td>
 		        <td align=center><a href=/product/index.php target=main><img src=/images/column3.jpg border=0></a></td>
 		        <?php if ($_SESSION['SESS_TYPE'] == ADMIN) {?>
 		        <td align=center><a href=/logistics/index.php target=main><img src=/images/column4.jpg border=0></a></td>
 		        <td align=center><a href=/constants/index.php target=main><img src=/images/column5.jpg border=0></a></td>
 		        <td align=center><a href=/user/index.php target=main><img src=/images/column6.jpg border=0></a></td>
 		        <!-- td align=center><a href=/wechatlog/index.php target=main><img src=/images/column7.jpg border=0></a></td -->
 		        <?php }?>
 		        <td align=center><a href=/user/logout.php target=_top><img src=/images/exit.jpg width="40" height="40" border=0></a></td>
 		    </tr>
        </table>
    </body>
</html>
