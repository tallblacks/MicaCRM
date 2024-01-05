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
	//require_once("../include/mysql.inc.php");
	//require_once("../include/function.inc.php");
	//require_once("../include/user.inc.php");

	$db=new cDatabase;
	$user=new user;
	echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
       	<tr align=\"center\" >
        <td height=\"30\" colspan=\"9\" class=hdr>{$strings['USER_USERS']}</td>
       	</tr>
       	<tr align=\"center\" {$strings['TABLE_TITLE_BKCOLOR']}>
        <td height=\"28\">{$strings['USER_USERNAME']}</td>
        <td>{$strings['USER_REALNAME']}</td>
        <td>{$strings['USER_EMAIL']}</td>
        <td>{$strings['USER_MOBILE']}</td>
        <td>{$strings['USER_TYPE']}</td>
        <td colspan=\"4\">{$strings['USER_OPERATE']}</td>
       </tr>";

	$sql="select * from user";
	$query=$db->query($sql);
	while ($data=$db->fetch_array($query)) {
		$userid=$data['userid'];

  		if ($i==0) {$bgcolor=$strings['TABLE_DARK_COLOR'].$over=$strings['DARK_OVER'];$i=1;}
  		else {$bgcolor=$strings['TABLE_LIGHT_COLOR'].$over=$strings['LIGHT_OVER'];$i=0;}

 		$type="&nbsp;".$strings['USER_TYPE_LIST'][$data['type']];

   
 if ($_SESSION['SESS_TYPE']==ADMIN){
	//$user_view=make_link("view.php?userid=$userid",$strings['USER_VIEW']);
	$user_view=$strings['USER_VIEW'];
	$user_edit=make_link("edit.php?userid=$userid",$strings['USER_EDIT']);
	//$user_edit_type=make_link("edit_type.php?userid=$userid",$strings['USER_EDIT_TYPE']);
	$user_edit_type=$strings['USER_EDIT_TYPE'];
	//$user_delete=make_link("dele.php?userid=$userid",$strings['USER_DELETE']);
	$user_delete=$strings['USER_DELETE'];
 }else{
	$user_view=make_link("view.php",$strings['USER_VIEW']);
  	$user_edit=$strings['USER_EDIT'];
  	$user_edit_type=$strings['USER_EDIT_TYPE'];
  	$user_delete=$strings['USER_DELETE'];
 }
echo " <tr $bgcolor align=\"center\">
        <td height=\"27\">&nbsp;{$data['username']}</td>
        <td height=\"27\">&nbsp;{$data['realname']}</td>
        <td height=\"27\">&nbsp;{$data['email']}</td>
        <td height=\"27\">&nbsp;{$data['mobile']}</td>
        <td height=\"27\">&nbsp;{$type}</td>
        <td height=\"27\">{$user_view}</td>
        <td height=\"27\">{$user_edit}</td>
		<td height=\"27\">{$user_edit_type}</td>
        <td height=\"27\">{$user_delete}</td>
       </tr>";
}	   
echo "	   
     </table>
	 
     <table width=\"100%\"  border=\"0\" cellpadding=\"1\">
      <tr>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
      </tr>
     </table>";

?>