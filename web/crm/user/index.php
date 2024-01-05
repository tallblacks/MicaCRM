<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    session_start();
    if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)) {
        Header("Location:../phpinc/main.php");
		    exit();
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
    </head>

    <frameset rows="*" cols="118,586" framespacing="0" frameborder="no" border="0" noresize >
        <frame name=cmsleft src="/user/menu.php" scrolling="no" frameborder="0">
        <frame name=cmsright src="/user/list.php"  scrolling="auto" frameborder="0" >
    </frameset>
    <noframes>
    <body>
    </body>
    </noframes>
</html>