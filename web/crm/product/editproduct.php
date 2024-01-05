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
	
	if ($ename) {
        $ename = str_replace("'", "\'", $ename);
        $ename = str_replace('"', '\"', $ename);
    }
    if ($cname) {
        $cname = str_replace("'", "\'", $cname);
        $cname = str_replace('"', '\"', $cname);
    }
	
	if($agentFlag){
	  $ffree = 0;//运费暂时不需要
	  $new_product = new cProduct;
	  $new_product->setProductid($productid);
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
	  $returnFlag = $productManager->update($new_product);
      if ($returnFlag){
	    $message="成功修改产品信息";
	    Header("Location:product.php?start=$start&range=$range&msg=$message");
	    exit();
	  }else{
	    $message="修改产品信息失败";
	  }
	}else{
	  $message="代理姓名输入错误";
	}
	msg($message);
  }
  
  if (!$_POST){
    $productid = trim($_GET["productid"]);
	$start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  }
  $db = new cDatabase;
  //mysql_query('set names utf8');
  $productManager = new cProductPeer;
  $product = $productManager->getProduct($productid);
  $ename = $product->getEname();
  $cname = $product->getCname();
  $spec = $product->getSpec();
    switch($spec){
      case 1:
        $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\" checked>盒
	               <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\">罐
	               <input type=\"radio\" name=\"spec\" value=\"4\">箱
	               <input type=\"radio\" name=\"spec\" value=\"5\">块
	               <input type=\"radio\" name=\"spec\" value=\"6\">只
	               <input type=\"radio\" name=\"spec\" value=\"7\">袋";
        break;
	  case 2:
	    $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\">盒
	               <input type=\"radio\" name=\"spec\" value=\"2\" checked>瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\">罐
	               <input type=\"radio\" name=\"spec\" value=\"4\">箱
	               <input type=\"radio\" name=\"spec\" value=\"5\">块
	               <input type=\"radio\" name=\"spec\" value=\"6\">只
	               <input type=\"radio\" name=\"spec\" value=\"7\">袋";
        break;
	  case 3:
	    $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\">盒
	               <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\" checked>罐
	               <input type=\"radio\" name=\"spec\" value=\"4\">箱
	               <input type=\"radio\" name=\"spec\" value=\"5\">块
	               <input type=\"radio\" name=\"spec\" value=\"6\">只
	               <input type=\"radio\" name=\"spec\" value=\"7\">袋";
        break;
	  case 4:
	    $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\">盒
	               <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\">罐
	               <input type=\"radio\" name=\"spec\" value=\"4\" checked>箱
	               <input type=\"radio\" name=\"spec\" value=\"5\">块
	               <input type=\"radio\" name=\"spec\" value=\"6\">只
	               <input type=\"radio\" name=\"spec\" value=\"7\">袋";
        break;
      case 5:
	    $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\">盒
	               <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\">罐
	               <input type=\"radio\" name=\"spec\" value=\"4\">箱
	               <input type=\"radio\" name=\"spec\" value=\"5\" checked>块
	               <input type=\"radio\" name=\"spec\" value=\"6\">只
	               <input type=\"radio\" name=\"spec\" value=\"7\">袋";
        break;
      case 6:
	    $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\">盒
	               <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\">罐
	               <input type=\"radio\" name=\"spec\" value=\"4\">箱
	               <input type=\"radio\" name=\"spec\" value=\"5\">块
	               <input type=\"radio\" name=\"spec\" value=\"6\" checked>只
	               <input type=\"radio\" name=\"spec\" value=\"7\">袋";
        break;
      case 7:
	    $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\">盒
	               <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\">罐
	               <input type=\"radio\" name=\"spec\" value=\"4\">箱
	               <input type=\"radio\" name=\"spec\" value=\"5\">块
	               <input type=\"radio\" name=\"spec\" value=\"6\">只
	               <input type=\"radio\" name=\"spec\" value=\"7\" checked>袋";
        break;
	  default:
	    $specStr ="<input type=\"radio\" name=\"spec\" value=\"1\">盒
	               <input type=\"radio\" name=\"spec\" value=\"2\">瓶
	               <input type=\"radio\" name=\"spec\" value=\"3\">罐
	               <input type=\"radio\" name=\"spec\" value=\"4\">箱
	               <input type=\"radio\" name=\"spec\" value=\"5\">块
	               <input type=\"radio\" name=\"spec\" value=\"6\">只
	               <input type=\"radio\" name=\"spec\" value=\"7\">袋";
        break;
    }
  $unit = $product->getUnit();
    switch($unit){
      case 1:
        $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\" checked>克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
	  case 2:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\" checked>毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
	  case 3:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\" checked>粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
	  case 4:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\" checked>天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
      case 5:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\" checked>块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
      case 6:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\" checked>只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
      case 7:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\" checked>袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
      case 8:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\" checked>片";
        break;
	  default:
	    $unitStr ="<input type=\"radio\" name=\"unit\" value=\"1\">克
	   			   <input type=\"radio\" name=\"unit\" value=\"2\">毫升
	     		   <input type=\"radio\" name=\"unit\" value=\"3\">粒
	   			   <input type=\"radio\" name=\"unit\" value=\"4\">天
	   			   <input type=\"radio\" name=\"unit\" value=\"5\">块
	   			   <input type=\"radio\" name=\"unit\" value=\"6\">只
	   			   <input type=\"radio\" name=\"unit\" value=\"7\">袋
	   			   <input type=\"radio\" name=\"unit\" value=\"8\">片";
        break;
    }
  $unitnum = $product->getUnitnum();
  $ppricenz = $product->getPpricenz();
  $spricecn = $product->getSpricecn();
  $weight = $product->getWeight();
  $supermarket = $product->getSupermarket();
    switch($supermarket){
      case 1:
        $supermarketStr ="<tr {$strings['TABLE_LIGHT_COLOR']}>
                     <td width=\"100\" height=\"27\"  align=\"right\">货源</td>
                     <td height=\"27\">&nbsp;
	                   <input type=\"radio\" name=\"supermarket\" value=\"0\">不是超市货
	                   <input type=\"radio\" name=\"supermarket\" value=\"1\" checked>是超市货
	                   <input type=\"radio\" name=\"supermarket\" value=\"2\">HK
	                 </td>
                   </tr>";
        break;
      case 2:
        $supermarketStr ="<tr {$strings['TABLE_LIGHT_COLOR']}>
                     <td width=\"100\" height=\"27\"  align=\"right\">货源</td>
                     <td height=\"27\">&nbsp;
	                   <input type=\"radio\" name=\"supermarket\" value=\"0\">不是超市货
	                   <input type=\"radio\" name=\"supermarket\" value=\"1\">是超市货
	                   <input type=\"radio\" name=\"supermarket\" value=\"2\" checked>HK
	                 </td>
                   </tr>";
        break;
      default:
        $supermarketStr ="<tr {$strings['TABLE_LIGHT_COLOR']}>
                     <td width=\"100\" height=\"27\"  align=\"right\">货源</td>
                     <td height=\"27\">&nbsp;
	                   <input type=\"radio\" name=\"supermarket\" value=\"0\" checked>不是超市货
	                   <input type=\"radio\" name=\"supermarket\" value=\"1\">是超市货
	                   <input type=\"radio\" name=\"supermarket\" value=\"2\">HK
	                 </td>
                   </tr>";
        break;
    }
  $user=new user;
  $productAgentname = $user->getUserbyId($product->getAgentid())->realname;
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
        <td colspan=\"2\" height=\"30\" class=hdr>修改产品信息</td>
       </tr>
     <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">英文名称</td>
      <td >&nbsp;<input type=\"text\" name=\"ename\" value=\"$ename\" style=\"width:300px;\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">中文名称</td>
      <td >&nbsp;<input type=\"text\" name=\"cname\" value=\"$cname\" style=\"width:250px;\"></td>
     </tr>
     <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品规格</td>
      <td height=\"27\">&nbsp;
	   $specStr
	  </td>
    </tr>
    <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品单位</td>
      <td height=\"27\">&nbsp;
	   $unitStr
	  </td>
    </tr>
    <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">每规格含单位数</td>
      <td >&nbsp;<input type=\"text\" name=\"unitnum\" value=\"$unitnum\"></td>
     </tr>
     <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品进价</td>
      <td >&nbsp;<input type=\"text\" name=\"ppricenz\" value=\"$ppricenz\">新西兰元</td>
     </tr>
     <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品售价</td>
      <td >&nbsp;<input type=\"text\" name=\"spricecn\" value=\"$spricecn\">人民币，可在下单时修改</td>
     </tr>
    <tr {$strings['TABLE_DARK_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">产品重量</td>
      <td >&nbsp;<input type=\"text\" name=\"weight\" value=\"$weight\">克</td>
     </tr>
     <tr {$strings['TABLE_LIGHT_COLOR']}>
      <td width=\"100\" height=\"27\"  align=\"right\">代购人</td>
      <td >&nbsp;<input type=\"text\" name=\"agentname\" value=\"$productAgentname\">可不填，如有需要填写正确的代购人姓名</td>
     </tr>
     $supermarketStr
    <tr{$strings['TABLE_DARK_COLOR']}>
     <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改产品信息\"></td>
    </tr>
   </form>
   </table>";
?>