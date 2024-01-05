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
	
	$catalogid = 0;
	$brandid = 0;
	$status = 0;
	
	$db = new cDatabase;
	//mysql_query('set names utf8');
	if($agentname != null || $agentname != ""){
	  $user = new user;
  	  $agentid = $user->getUserbyRealname($agentname)->userid;
  	  if($agentid){
  	    $agentFlag = 1;
  	  }else{
  	    $agentFlag = 0;
  	  }
	}else{
	  $agentid = 0;
	  $agentFlag = 1;
	}
	
	if($agentFlag){
	  $ffree = 0;//运费暂时不需要
	  $new_product = new cProduct;
	  $new_product->setCatalogid($catalogid);
      $new_product->setBrandid($brandid);
      $new_product->setEname($ename);
      $new_product->setCname($cname);
      $new_product->setSpec($spec);
      $new_product->setUnit($unit);
      $new_product->setUnitnum($unitnum);
      $new_product->setPpricenz($ppricenz);
      $new_product->setSpricecn($spricecn);
      $new_product->setWeight($weight);
      $new_product->setSupermarket($supermarket);
      $new_product->setAgentid($agentid);
      $new_product->setPstatus($status);
	
	  $productManager = new cProductPeer;
	  $returnFlag = $productManager->create($new_product);
      if ($returnFlag){
	    $message="成功创建新产品";
	  }else{
	    $message="创建新产品失败";
	  }
	}else{
	  $message="代理姓名输入错误";
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
        <td colspan=\"2\" height=\"30\" class=hdr>添加新的产品</td>
       </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">英文名称</td>
      <td >&nbsp;<input type=\"text\" name=\"ename\" style=\"width:300px;\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">中文名称</td>
      <td >&nbsp;<input type=\"text\" name=\"cname\" style=\"width:250px;\"></td>
     </tr>
     <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品规格</td>
      <td height=\"27\">&nbsp;
	   <input type=\"radio\" name=\"spec\" value=\"1\" checked>盒
	   <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	   <input type=\"radio\" name=\"spec\" value=\"3\">罐
	   <input type=\"radio\" name=\"spec\" value=\"4\">箱
	   <input type=\"radio\" name=\"spec\" value=\"5\">块
	   <input type=\"radio\" name=\"spec\" value=\"6\">只
	   <input type=\"radio\" name=\"spec\" value=\"7\">袋
	  </td>
    </tr>
    <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品单位</td>
      <td height=\"27\">&nbsp;
	   <input type=\"radio\" name=\"unit\" value=\"1\" checked>克
	   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   <input type=\"radio\" name=\"unit\" value=\"8\">片
	  </td>
    </tr>
    <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">每规格含单位数</td>
      <td >&nbsp;<input type=\"text\" name=\"unitnum\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品进价</td>
      <td >&nbsp;<input type=\"text\" name=\"ppricenz\">新西兰元</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品售价</td>
      <td >&nbsp;<input type=\"text\" name=\"spricecn\">人民币，可在下单时修改</td>
     </tr>
    <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品重量</td>
      <td >&nbsp;<input type=\"text\" name=\"weight\">克</td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">代购人</td>
      <td >&nbsp;<input type=\"text\" name=\"agentname\">可不填，如有需要填写正确的代购人姓名</td>
     </tr>
     <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">货源</td>
      <td height=\"27\">&nbsp;
	   <input type=\"radio\" name=\"supermarket\" value=\"0\" checked>不是超市货
	   <input type=\"radio\" name=\"supermarket\" value=\"1\">是超市货
	   <input type=\"radio\" name=\"supermarket\" value=\"2\">HK
	  </td>
    </tr>
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"添加新产品\"></td>
    </tr>
   </form>
   </table>";
?>