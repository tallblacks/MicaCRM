<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
    session_start();
    if (!isset($_SESSION["SESS_USERID"])){
    	Header("Location:../phpinc/main.php");
		exit();
    }
?>
<html><head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<!--frameset cols=160,* border=0 frameborder=0 framespacing=0>
<frameset frameborder=0 framespacing=0 border=0 cols=* rows=0,*>
<frame src=left.html name=cmsleft scrolling=auto marginheight=0 marginwidth=0 noresize>
<frame marginwidth=5 marginheight=5 src=menu.html name=menu noresize scrolling=auto frameborder=0>
</frameset>
<frame src=constants.php?type=1 name=cmsright scrolling=auto marginheight=0 marginwidth=5 noresize>
</frameset-->
<frameset rows="*" cols="118,586" framespacing="0" frameborder="no" border="0" noresize >
  <frame name=cmsleft src="left.php" scrolling="no" frameborder="0">
  <frame name=cmsright src="order.php"  scrolling="auto" frameborder="0" >
</frameset>
</html>