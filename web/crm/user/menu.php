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
	require_once("../include/config.inc.php");
	require_once("../include/function.inc.php");

	echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} height=100%  class=eng>
  		<tr {$strings['TABLE_TITLE_BKCOLOR']}>
   		<td height=\"37\" algin=\"center\" colspan=\"2\" class=hdr>{$strings['USER_MENU']}</td>
  		</tr>";
	echo "<tr {$strings['TABLE_DARK_COLOR']}>
       	<td width=\"19%\" height=\"28\" algin=\"center\" {$strings['TABLE_TITLE_BKCOLOR']}><img src={$menuico['HOME']}></td>
       	<td width=\"81%\" {$strings['DARK_OVER']}>&nbsp;".make_link($menulink['HOME'],$menuname['HOME'],"_top")."</td>
      	</tr>";
	$i=1;
 	foreach($menuname['USER'] as $key =>$value){
  		if ($i==0) {$bgcolor=$strings['TABLE_DARK_COLOR'].$over=$strings['DARK_OVER'];$i=1;}
  		else {$bgcolor=$strings['TABLE_LIGHT_COLOR'].$over=$strings['LIGHT_OVER'];$i=0;}
  
  		echo "<tr $bgcolor>
         	<td width=\"19%\" height=\"28\" algin=\"center\" {$strings['TABLE_TITLE_BKCOLOR']}><img src={$menuico['USER'][$key]}></td>
         	<td width=\"81%\" $over>&nbsp;".make_link($menulink['USER'][$key],$value,"cmsright")."</td>
        	</tr>";
 	}
	echo "
  		<tr {$strings['TABLE_TITLE_BKCOLOR']}>
    	<td algin=\"center\" colspan=\"2\"></td>
  		</tr>
		</table>";
?>