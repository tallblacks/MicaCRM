<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    if (!isset($_SESSION["SESS_USERID"])) {
        Header("Location:index.php");
	      exit();
    }
  
    require_once("../include/cconsumerpeer.inc.php");
    require_once("../include/user.inc.php");
  
    if ($_POST) {
        foreach ($_POST as $key => $val) {
		        $$key=$val;
	      }
	
	      $type = 1;
	      $status = 0;
	      $db = new cDatabase;

	      if ($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY) {
	          if ($agentname != null || $agentname != "") {
	              $user = new user;
  	            $agentid = $user->getUserbyRealname($agentname)->userid;
  	            if ($agentid) {
  	                $agentFlag = 1;
  	            } else {
  	                $agentFlag = 0;
  	            }
	          } else {
	              $agentid = 0;
	              $agentFlag = 1;
	          }
	      } else if ($_SESSION['SESS_TYPE'] == XIAOMI) {//xiaomi
	          $agentid = 0;
	          $agentFlag = 1;
	      }else{
	          $agentid = $_SESSION['SESS_USERID'];
	          $agentFlag = 1;
	      }
	
	      if($_SESSION['SESS_TYPE'] != ADMIN){
	          $wechat = "";
	      }
	
  	    if ($agentFlag) {
	          $new_consumer = new cConsumer;
	          $new_consumer->setConsumerid($consumerid);
	          $new_consumer->setCname($name);
            $new_consumer->setAddress($address);
            $new_consumer->setMemo($memo);
            $new_consumer->setMobile($mobile);
            $new_consumer->setTelephone($telephone);
            $new_consumer->setWechat($wechat);
            $new_consumer->setAgentid($agentid);
            $new_consumer->setCtype($type);
            $new_consumer->setPstatus($status);
	
	          $pconsumerManager = new cConsumerPeer;
	          $returnFlag = $pconsumerManager->update($new_consumer);
            if ($returnFlag) {
	              $message="成功修改消费者信息";
	              Header("Location:consumer.php?start=$start&range=$range&msg=$message");
	              exit();
	          } else {
	              $message="修改消费者信息失败";
	          }
	      } else {
	          $message="代理姓名输入错误";
	      }
	      msg($message);
    }

    if (!$_POST) {
        $consumerid = trim($_GET["consumerid"]);
	      $start = trim($_GET["start"]);
        $range = trim($_GET["range"]);
    }
    $db = new cDatabase;

    $consumerManager = new cConsumerPeer;
    $consumer = $consumerManager->getConsumer($consumerid);
    $consumerName = $consumer->getCname();
    $consumerAddress = $consumer->getAddress();
    $consumerMemo = $consumer->getMemo();
    $consumerMobile = $consumer->getMobile();
    $consumerTelephone = $consumer->getTelephone();
    $consumerWechat = $consumer->getWechat();
  
    $consumerAgentid = $consumer->getAgentid();
    $consumerXiaomid = $consumer->getXiaomid();
  
    if ($consumerAgentid > 0) {//有代理
        if ($_SESSION['SESS_TYPE'] != ADMIN && $_SESSION['SESS_TYPE'] != SECRETARY) {
            if($consumerAgentid != $_SESSION['SESS_USERID']) exit();
        }
        if($_SESSION['SESS_TYPE'] == XIAOMI) exit();
  
        $user = new user;
        $consumerAgentname = $user->getUserbyId($consumerAgentid)->realname;
        if ($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY) {
            $agentnameStr = "<tr {$strings['TABLE_DARK_COLOR']}>
                            <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
                            <td >&nbsp;<input type=\"text\" name=\"agentname\" value=\"$consumerAgentname\">可不填，如有需要填写正确的代理人姓名</td>
                            </tr>";
        } else {
            $agentnameStr = "<tr {$strings['TABLE_DARK_COLOR']}>
                            <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
                            <td >&nbsp;$consumerAgentname</td>
                            </tr>";
        }
    } else {//没有代理
        if ($_SESSION['SESS_TYPE'] == SECRETARY || $_SESSION['SESS_TYPE'] == XIAOMI) {
            if ($consumerXiaomid != $_SESSION['SESS_USERID']) exit();
        }
        if ($_SESSION['SESS_TYPE'] == ADMIN || $_SESSION['SESS_TYPE'] == SECRETARY) {
            $agentnameStr = "<tr {$strings['TABLE_DARK_COLOR']}>
                            <td width=\"100\" height=\"27\"  align=\"right\">代理人</td>
                            <td >&nbsp;<input type=\"text\" name=\"agentname\">可不填，如有需要填写正确的代理人姓名</td>
                            </tr>";
        } else if($_SESSION['SESS_TYPE'] == XIAOMI) {
            $agentnameStr = "";
        } else {
            exit();
        }
    }
    if ($_SESSION['SESS_TYPE'] == ADMIN) {
        $wechatStr = "<tr {$strings['TABLE_DARK_COLOR']}>
                        <td width=\"100\" height=\"27\"  align=\"right\">微信号</td>
                        <td >&nbsp;<input type=\"text\" name=\"wechat\" value=\"$consumerWechat\"></td>
                      </tr>";
    } else {
        $wechatStr = "";
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
    if (!empty($msg)) {	
        echo "<span class=cur>$msg</span>";
    }
    echo "<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
            <form name=\"form1\" method=\"post\" action=\"\">
            <input type=\"hidden\" name=\"consumerid\" value=\"$consumerid\">
            <input type=\"hidden\" name=\"start\" value=\"$start\">
            <input type=\"hidden\" name=\"range\" value=\"$range\">
            <tr {$strings['TABLE_TITLE_BKCOLOR']}>
                <td colspan=\"2\" height=\"30\" class=hdr>修改消费者信息</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">姓名</td>
                <td >&nbsp;<input type=\"text\" name=\"name\" value=\"$consumerName\"></td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">地址</td>
                <td >&nbsp;<input type=\"text\" name=\"address\" value=\"$consumerAddress\" style=\"width:300px;\"></td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">备注</td>
                <td >&nbsp;<input type=\"text\" name=\"memo\" value=\"$consumerMemo\">可不填</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">手机</td>
                <td >&nbsp;<input type=\"text\" name=\"mobile\" value=\"$consumerMobile\"></td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">座机</td>
                <td >&nbsp;<input type=\"text\" name=\"telephone\" value=\"$consumerTelephone\">可不填</td>
            </tr>
            $agentnameStr
            $wechatStr
            <tr{$strings['TABLE_DARK_COLOR']}>
                <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认修改消费者信息\"></td>
            </tr>
            </form>
          </table>";
    echo "<br><br>";
?>
<?php
    require_once("../include/cpicturepeer.inc.php");
    $pictureManager = new cPicturePeer;
    $pictureType = 2;//用户身份证
    $consumerIDPictureList = $pictureManager->getPictureListByUserid($consumerid);
?>
<?php
    echo "<table border=1 borderColorDark=#ffffec borderColorLight=#5e5e00 cellPadding=0 cellSpacing=0 width=70% align=center>
    <tr class=tine bgcolor=#dddddd>
        <td align=center width=20%>图片名</td>
        <td align=center width=55%>URL（点击图片看原图）</td>
        <td align=center width=15%>上载者</td>
        <td align=center width=10%>删除</td>
    </tr>";

    if (!empty($consumerIDPictureList)) {
        $rowColor=1;
        foreach ($consumerIDPictureList as $picture) {
  	        $picture = (object)$picture;
            $pictureid = $picture->getPictureid();
  	        $name = $picture->getName();
  	        $url = $picture->getPictureUrl();
  	        $operatorid = $picture->getOperatorid();
        
            $sql = "select * from user where userid=$operatorid";
	          $query=$db->query($sql);
	          $data=$db->fetch_array($query);
            $picRealname = $data['realname'];
        
            if (($_SESSION['SESS_USERID'] == $operatorid) || ($_SESSION['SESS_TYPE'] == ADMIN)) {
                $picDelStr = "<td align=center><a href=delpicture.php?pictureid=$pictureid&start=$start&range=$range>删除</a></td>";
            } else {
                $picDelStr = "<td align=center>删除</td>";
            }

            echo "<tr class=tine bgcolor=#dddddd>
                <td align=center>$name</td>
                <td align=center><a href=$url target=_ablank><img src=$url width=300></a></td>
                <td align=center>$picRealname</td>
                $picDelStr
                </tr>";
        }
    }

    echo "<tr class=tine bgcolor=#dddddd>
        <td align=center width=100% colspan=7><a href=createpic.php?type=$pictureType&userid=$consumerid&start=$start&range=$range>上传用户身份证</a></td>
        </tr>";
?>