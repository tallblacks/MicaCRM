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
<html><head>
<title></title>
</head>
<frameset rows="*" cols="118,586" framespacing="0" frameborder="no" border="0" noresize >
  <frame name=cmsleft src="left.php" scrolling="no" frameborder="0">
  <frame name=cmsright src="wechatlog.php"  scrolling="auto" frameborder="0" >
</frameset>
</html>