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
  require_once("../include/ccomborderpeer.inc.php");
  //require_once("../include/function.inc.php");
  
  $db = new cDatabase;
  //mysql_query('set names utf8');
  if ($_POST){
	foreach($_POST as $key => $val) {
		$$key=$val;
	}
  	$type = 1;
  	$status = 0;
  	  
  	$comborderManager = new cComborderPeer;

	$comborder = new cComborder;
    $comborder->setMemo($memo);
    $comborder->setCtype($type);
    $comborder->setPstatus($status);
	
	$returnFlag = $comborderManager->create($comborder);
    if ($returnFlag){
	  $message="新建拼单成功";
	  Header("Location:comborder.php?msg=$message");
	  exit();
	}else{
	  $message="新建拼单失败";
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
    if(!empty($msg)) {
  	  echo "<span class=cur>$msg</span>";
    }
    
echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
      <form name=\"form1\" method=\"post\" action=\"\">
       <tr {$strings['TABLE_TITLE_BKCOLOR']}>
        <td colspan=\"2\" height=\"30\" class=hdr>新建拼单</td>
       </tr>
       <!--tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单收货人姓名</td>
         <td >&nbsp;<input type=\"text\" name=\"name\">可暂不填</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单收货人手机</td>
         <td >&nbsp;<input type=\"text\" name=\"mobile\">可暂不填</td>
       </tr>
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单收货地址</td>
         <td >&nbsp;<input type=\"text\" name=\"address\">可暂不填</td>
       </tr-->
       <tr {$strings['TABLE_DARK_COLOR']}>
         <td width=\"100\" height=\"27\"  align=\"right\">拼单备注</td>
         <td >&nbsp;<input type=\"text\" name=\"memo\">可暂不填</td>
       </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认新建拼单\"></td>
    </tr>
   </form>
   </table>";
?>