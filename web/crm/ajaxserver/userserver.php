<?
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/user.inc.php");
  require_once("../include/function.inc.php");
  
  echo $_GET['val'];
  
  /*
  $username = $_GET['serverid'];
  $realname = $_GET['arg'];
	
  $user = new user;
  $agentid = $user->getUserbyRealname($realname)->userid;
  if($agentid){
    echo "代理存在，输入正确！";
  }else{
	echo "代理不存在，输入错误！";
  }*/
?>