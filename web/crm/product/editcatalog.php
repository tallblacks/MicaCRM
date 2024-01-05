<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

  session_start();
  if ((!isset($_SESSION["SESS_USERID"])) || ($_SESSION['SESS_TYPE'] != ADMIN)){
    Header("Location:../phpinc/main.php");
	exit();
  }

  require_once("../include/ccatalogpeer.inc.php");
  require_once("../include/user.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	if($catalogid && $name){	
	  $catalogManager = new cCatalogPeer;
	  $returnFlag = $catalogManager->update($catalogid, $name);
      if ($returnFlag){
	    $message="成功修改分类信息";
	    Header("Location:catalog.php?msg=$message");
	    exit();
	  }else{
	    $message="修改分类信息失败";
	  }
	}
	
	msg($message);
  }
  
  if (!$_POST){
    $catalogid = trim($_GET["catalogid"]);
  }
  
  $catalogManager = new cCatalogPeer;
  $catalog = $catalogManager->getCatalog($catalogid);
  $name = $catalog->getName();
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
  if(!empty($msg)) {	
    echo "<span class=cur>$msg</span>";
  }
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
      <input type=\"hidden\" name=\"catalogid\" value=\"$catalogid\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>修改产品分类信息</td>
       </tr>
     <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">分类名称</td>
      <td >&nbsp;<input type=\"text\" name=\"name\" value=\"$name\" style=\"width:300px;\"></td>
     </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改\"></td>
    </tr>
   </form>
   </table>";
?>