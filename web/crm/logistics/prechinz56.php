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
  
  $logisticscode = trim($_GET["logisticscode"]);
?>
<body onload="document.getElementById('chinz56').submit();">
<form id="chinz56" name="chinz56" method="post" action="http://www.chinz56.co.nz/cgi-bin/GInfo.dll?EmmisTrack">
<input name='w' type='hidden' value='chinz56'>
<input name='ntype' type='hidden' value='1000'>
<input name='cno' type='hidden' value='<?=$logisticscode;?>'>
</form>
</body>