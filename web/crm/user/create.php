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

  //require_once("../include/mysql.inc.php");
  require_once("../include/user.inc.php");
  //require_once("../include/function.inc.php");
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	$db=new cDatabase;
	//mysql_query('set names utf8');
	$user=new user;
	$user_id=$user->add($username, $password, $password1, $realname, $email, $mobile, $type);
    if ($user_id){
	 $message=$strings['USER_SUCCESS'];
	}else{
	 $message=$user->error_message;
	}
	msg($message);
  }
?>
<style type="text/css">
<!--
body {
	margin-top: 0px;
	margin-left: 0px;
}
-->
</style>
<link rel=stylesheet type=text/css href=/style/global.css>
<?php
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>{$strings['USER_TITLE_ADD']}</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
        <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_USERNAME']}</td>
        <td>&nbsp;<input type=\"text\" name=\"username\">（登录系统用，可不填）</td>
      </tr>
      <tr {$strings['TABLE_LIGHT_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_PASSWORD']}</td>
       <td>&nbsp;<input type=\"password\" name=\"password\">（登录系统用，可不填）</td>
      </tr>
      <tr {$strings['TABLE_DARK_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_PASSWORD_CONFIRM']}</td>
       <td>&nbsp;<input type=\"password\" name=\"password1\">（登录系统用，可不填）</td>
      </tr>
      <tr {$strings['TABLE_LIGHT_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_REALNAME']}</td>
       <td >&nbsp;<input type=\"text\" name=\"realname\"></td>
      </tr>
      <tr {$strings['TABLE_DARK_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_EMAIL']}</td>
       <td>&nbsp;<input type=\"text\" name=\"email\">（可不填）</td>
      </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">{$strings['USER_MOBILE']}</td>
      <td >&nbsp;<input type=\"text\" name=\"mobile\">（可不填）</td>
     </tr>
      <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">{$strings['USER_TYPE']}</td>
      <td height=\"27\">&nbsp;
	   <input type=\"radio\" name=\"type\" value=\"".ADMIN."\">{$strings['USER_TYPE_LIST'][ADMIN]}
	   <input type=\"radio\" name=\"type\" value=\"".AGENT."\" checked>{$strings['USER_TYPE_LIST'][AGENT]}
	   <input type=\"radio\" name=\"type\" value=\"".SECRETARY."\">{$strings['USER_TYPE_LIST'][SECRETARY]}
	   <input type=\"radio\" name=\"type\" value=\"".XIAOMI."\">{$strings['USER_TYPE_LIST'][XIAOMI]}
	   <input type=\"radio\" name=\"type\" value=\"".PURCHASE."\">{$strings['USER_TYPE_LIST'][PURCHASE]}
	  </td>
    </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"{$strings['USER_SUBMIT_ADD']}\"></td>
    </tr>
   </form>
   </table>";
?>