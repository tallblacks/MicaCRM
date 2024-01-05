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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
    require_once("../include/mysql.inc.php");
    require_once("../include/user.inc.php");
    require_once("../include/function.inc.php");

    if ($_POST) {
	      foreach ($_POST as $key => $val) {
		        $$key=$val;
	      }
	      $db=new cDatabase;

	      $user=new user;
	      $returnFlag=$user->update($userid, $username, $password, $password1, $realname, $email, $mobile, $type);

        if ($returnFlag) {
	          $message=$strings['USER_UPDATED'];
	      } else {
	          $message=$user->error_message;
	      }
	      msg($message);
    }
  
    if ($_GET) {
        foreach ($_GET as $key => $val) {
            $$key=$val;
        }
        $db=new cDatabase;
        $user=new user;
        $sql="select * from user where userid = $userid";
	      $query=$db->query($sql);
	      $data=$db->fetch_array($query);
	      $username=$data['username'];
	      $password=$data['password'];
	      $realname=$data['realname'];
	      $email=$data['email'];
	      $mobile=$data['mobile'];
	      $type=$data['type'];
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
    $typestr = "";
    switch ($type) {
        case ADMIN:
            $typestr = "<input type=\"radio\" name=\"type\" value=\"".ADMIN."\" checked>{$strings['USER_TYPE_LIST'][ADMIN]}
	          <input type=\"radio\" name=\"type\" value=\"".AGENT."\">{$strings['USER_TYPE_LIST'][AGENT]}
	          <input type=\"radio\" name=\"type\" value=\"".SECRETARY."\">{$strings['USER_TYPE_LIST'][SECRETARY]}
	          <input type=\"radio\" name=\"type\" value=\"".XIAOMI."\">{$strings['USER_TYPE_LIST'][XIAOMI]}
	          <input type=\"radio\" name=\"type\" value=\"".PURCHASE."\">{$strings['USER_TYPE_LIST'][PURCHASE]}";
	          break;
        case AGENT:
            $typestr = "<input type=\"radio\" name=\"type\" value=\"".ADMIN."\">{$strings['USER_TYPE_LIST'][ADMIN]}
            <input type=\"radio\" name=\"type\" value=\"".AGENT."\" checked>{$strings['USER_TYPE_LIST'][AGENT]}
            <input type=\"radio\" name=\"type\" value=\"".SECRETARY."\">{$strings['USER_TYPE_LIST'][SECRETARY]}
            <input type=\"radio\" name=\"type\" value=\"".XIAOMI."\">{$strings['USER_TYPE_LIST'][XIAOMI]}
            <input type=\"radio\" name=\"type\" value=\"".PURCHASE."\">{$strings['USER_TYPE_LIST'][PURCHASE]}";
            break;
        case PURCHASE:
            $typestr = "<input type=\"radio\" name=\"type\" value=\"".ADMIN."\">{$strings['USER_TYPE_LIST'][ADMIN]}
            <input type=\"radio\" name=\"type\" value=\"".AGENT."\">{$strings['USER_TYPE_LIST'][AGENT]}
            <input type=\"radio\" name=\"type\" value=\"".SECRETARY."\">{$strings['USER_TYPE_LIST'][SECRETARY]}
            <input type=\"radio\" name=\"type\" value=\"".XIAOMI."\">{$strings['USER_TYPE_LIST'][XIAOMI]}
            <input type=\"radio\" name=\"type\" value=\"".PURCHASE."\" checked>{$strings['USER_TYPE_LIST'][PURCHASE]}";
            break;
        case SECRETARY:
            $typestr = "<input type=\"radio\" name=\"type\" value=\"".ADMIN."\">{$strings['USER_TYPE_LIST'][ADMIN]}
            <input type=\"radio\" name=\"type\" value=\"".AGENT."\">{$strings['USER_TYPE_LIST'][AGENT]}
            <input type=\"radio\" name=\"type\" value=\"".SECRETARY."\" checked>{$strings['USER_TYPE_LIST'][SECRETARY]}
            <input type=\"radio\" name=\"type\" value=\"".XIAOMI."\">{$strings['USER_TYPE_LIST'][XIAOMI]}
            <input type=\"radio\" name=\"type\" value=\"".PURCHASE."\">{$strings['USER_TYPE_LIST'][PURCHASE]}";
            break;
        case XIAOMI:
            $typestr = "<input type=\"radio\" name=\"type\" value=\"".ADMIN."\">{$strings['USER_TYPE_LIST'][ADMIN]}
            <input type=\"radio\" name=\"type\" value=\"".AGENT."\">{$strings['USER_TYPE_LIST'][AGENT]}
            <input type=\"radio\" name=\"type\" value=\"".SECRETARY."\">{$strings['USER_TYPE_LIST'][SECRETARY]}
            <input type=\"radio\" name=\"type\" value=\"".XIAOMI."\" checked>{$strings['USER_TYPE_LIST'][XIAOMI]}
            <input type=\"radio\" name=\"type\" value=\"".PURCHASE."\">{$strings['USER_TYPE_LIST'][PURCHASE]}";
            break;
        default:
            $typestr = "<input type=\"radio\" name=\"type\" value=\"".ADMIN."\">{$strings['USER_TYPE_LIST'][ADMIN]}
            <input type=\"radio\" name=\"type\" value=\"".AGENT."\" checked>{$strings['USER_TYPE_LIST'][AGENT]}
            <input type=\"radio\" name=\"type\" value=\"".SECRETARY."\">{$strings['USER_TYPE_LIST'][SECRETARY]}
            <input type=\"radio\" name=\"type\" value=\"".XIAOMI."\">{$strings['USER_TYPE_LIST'][XIAOMI]}
            <input type=\"radio\" name=\"type\" value=\"".PURCHASE."\">{$strings['USER_TYPE_LIST'][PURCHASE]}";
    }


    echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"userid\" value=\"$userid\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>{$strings['USER_TITLE_EDIT']}</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
        <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_USERNAME']}</td>
        <td>&nbsp;<input type=\"text\" name=\"username\" value=\"$username\">（登录系统用，可不填）</td>
      </tr>
      <tr {$strings['TABLE_LIGHT_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_PASSWORD']}</td>
       <td>&nbsp;<input type=\"password\" name=\"password\" value=\"$password\">（登录系统用，可不填）</td>
      </tr>
      <tr {$strings['TABLE_DARK_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_PASSWORD_CONFIRM']}</td>
       <td>&nbsp;<input type=\"password\" name=\"password1\" value=\"$password\">（登录系统用，可不填）</td>
      </tr>
      <tr {$strings['TABLE_LIGHT_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_REALNAME']}</td>
       <td >&nbsp;<input type=\"text\" name=\"realname\" value=\"$realname\"></td>
      </tr>
      <tr {$strings['TABLE_DARK_COLOR']}>
       <td width=\"100\" height=\"27\" align=\"right\">{$strings['USER_EMAIL']}</td>
       <td>&nbsp;<input type=\"text\" name=\"email\" value=\"$email\">（可不填）</td>
      </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">{$strings['USER_MOBILE']}</td>
      <td >&nbsp;<input type=\"text\" name=\"mobile\" value=\"$mobile\">（可不填）</td>
     </tr>
      <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">{$strings['USER_TYPE']}</td>
      <td height=\"27\">&nbsp;
	   {$typestr}
	  </td>
    </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"{$strings['USER_SUBMIT_EDIT']}\"></td>
    </tr>
   </form>
    </table>";
?>