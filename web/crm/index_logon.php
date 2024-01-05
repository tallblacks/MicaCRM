<?php
    require_once("./include/function.inc.php");
    require_once("./include/config.inc.php");
    require_once("./include/user.inc.php");
    require_once("./include/mysql.inc.php");
    CheckCookies();

    if (!isset($_SESSION["SESS_USERID"])) {
        Header("Location:index.php");
        exit();
    }
?>
<html>
	<head>
		<title>Mica的客户关系管理系统</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<frameset rows=80,1*,60 border=0 frameborder=0 framespacing=0>
  	<frame src="phpinc/header.php" name=header scrolling=no marginheight=5 marginwidth=5 noresize>
  	<frame src="phpinc/main.php" name=main scrolling=auto noresize>
  	<frame src="phpinc/footer.php" name=bottom scrolling=no noresize>
	</frameset>
	<noframes></noframes>
</html>