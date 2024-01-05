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
  require_once("../include/cproductpeer.inc.php");
  require_once("../include/user.inc.php");
  //require_once("../include/function.inc.php");
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
	$productManager = new cProductPeer;
	$returnFlag = $productManager->delete($productid);
    if ($returnFlag){
	  $message="成功删除产品";
	  Header("Location:product.php?start=$start&range=$range&msg=$message");
	  exit();
	}else{
	  $message="删除产品失败";
	}
	
	msg($message);
  }
  
  if (!$_POST){
    $productid = trim($_GET["productid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $db = new cDatabase;
  
  $productManager = new cProductPeer;
  $product = $productManager->getProduct($productid);
  $ename = $product->getEname();
  $cname = $product->getCname();
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
      <input type=\"hidden\" name=\"productid\" value=\"$productid\">
      <input type=\"hidden\" name=\"start\" value=\"$start\">
      <input type=\"hidden\" name=\"range\" value=\"$range\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>删除产品信息</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">英文名称</td>
      <td >&nbsp;$ename</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">中文名称</td>
      <td >&nbsp;$cname</td>
     </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除产品信息\"></td>
    </tr>
   </form>
   </table>";
?>