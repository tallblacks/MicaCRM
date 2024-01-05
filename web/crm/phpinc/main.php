<?php
    require_once("../include/function.inc.php");
    require_once("../include/config.inc.php");
    require_once("../include/user.inc.php");
    require_once("../include/mysql.inc.php");
    CheckCookies();

	session_start();
	if (!isset($_SESSION["SESS_USERID"])){
		Header("Location:index.php");
		exit();
	}
?>
<html>
    <head>
        <title></title>
        <link rel=stylesheet type=text/css href=/style/global.css>
    </head>
    <BODY BGCOLOR=#ffffff LINK=#000099 ALINK=#cc0000 VLINK=#000099 TOMARGIN=8>
        <TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width=100%>
            <tr>
                <td height=10 align=center class=hdr>
                </td>
            </tr>
            <tr><td class=cur align=center>任何问题请邮件：<a href=mailto:micayao@126.com>micayao@126.com</a>
                </td>
            </tr>
        </table>
    </body>
</html>