<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();
    
    if (!session_id()) session_start();
	if (!isset($_SESSION["SESS_USERID"])) {
		Header("Location:index.php");
		exit();
	}
?>

<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    </head>

    <frameset name=main rows="*" cols="118,586" framespacing="0" frameborder="no" border="0" noresize >
        <frame name=cmsleft src="left.html" scrolling="no" frameborder="0">
        <frame name=cmsright src="consumer.php"  scrolling="auto" frameborder="0" >
    </frameset>
</html>