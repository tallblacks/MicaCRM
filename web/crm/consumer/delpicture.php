<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();
    
    if ($_POST) {
		$pictureid = trim($_POST["pictureid"]);
        $start = trim($_POST["start"]);
        $range = trim($_POST["range"]);
    } else {
        $pictureid = trim($_GET["pictureid"]);
        $start = trim($_GET["start"]);
        $range = trim($_GET["range"]);
    }
    
    $db = new cDatabase;
    require_once("../include/cpicturepeer.inc.php");
    $pictureManager = new cPicturePeer;
    $picture = $pictureManager->getPictureByPictureid($pictureid);
    $picUserid = $picture->getUserid();
    $picOperatorid = $picture->getOperatorid();
    $picName = $picture->getName();
    
    $user = new user;
    $sql = "select * from user where userid=$picOperatorid";
    $query = $db->query($sql);
    $data = $db->fetch_array($query);
    $picRealname = $data['realname'];
    
    if (($_SESSION['SESS_USERID'] != $picOperatorid) && ($_SESSION['SESS_TYPE'] != ADMIN)) {
    	  Header("Location:../phpinc/main.php");
	      exit();
    }

    if ($_POST) {
        $delFlag = $pictureManager->delPicture($pictureid);
        if ($delFlag) {
            $message="成功删除图片";
        } else {
            $message="删除图片失败，请联系管理员";
        }
        Header("Location:editconsumer.php?consumerid=$picUserid&pictureid=$pictureid&start=$start&range=$range&msg=$message");
        exit();
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
            <input type=\"hidden\" name=\"pictureid\" value=\"$pictureid\">
            <input type=\"hidden\" name=\"start\" value=\"$start\">
            <input type=\"hidden\" name=\"range\" value=\"$range\">
            <tr {$strings['TABLE_TITLE_BKCOLOR']}>
                <td colspan=\"2\" height=\"30\" class=hdr>删除图片</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">图片名称</td>
                <td >&nbsp;$picName</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">上传用户</td>
                <td >&nbsp;$picRealname</td>
            </tr>
            <tr{$strings['TABLE_DARK_COLOR']}>
                <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认删除图片\"></td>
            </tr>
        </form>
    </table>";
?>