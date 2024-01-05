<?php
	session_start();
	session_destroy();
	$SESS_USERID="";
	$SESS_REALNAME="";
	$SESS_TYPE="";
	unset($_SESSION["SESS_USERID"]);
	unset($_SESSION["SESS_REALNAME"]);
	unset($_SESSION["SESS_TYPE"]);
	
	setcookie('userid','',time()-3600,'/','crm.nzshop.cn');
	setcookie('userstring','',time()-3600,'/','crm.nzshop.cn');
	
	Header("Location:/index.php");
?>