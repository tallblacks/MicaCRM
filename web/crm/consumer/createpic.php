<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    if (!isset($_SESSION["SESS_USERID"])) {
    	Header("Location:../phpinc/main.php");
	    exit();
    }
    
    $type = trim($_GET["type"]);
    $start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
    if ($type == 2) {
        $userid = trim($_GET["userid"]);
    }
    

    if ($type == 2) {
        $hintStr = "为用户添加身份证照片（照片尺寸必须小于2MB！）";
        $picName = "<select name = 'name'>
                    <option value = '身份证正面'>身份证正面</option>
                    <option value = '身份证反面'>身份证反面</option>
                    <option value = '身份证正反面一起'>身份证正反面一起</option>
                    </select>";
        $idStr = "<input type=\"hidden\" name=\"userid\" value=\"$userid\">";
    }
  
    if ($_POST) {
        $type = trim($_POST["type"]);
        $name = trim($_POST["name"]);
        $start = trim($_POST["start"]);
        $range = trim($_POST["range"]);
        if ($type == 2) {
            $userid = trim($_GET["userid"]);
        }
        
        require_once("../include/cpicturepeer.inc.php");
        $db = new cDatabase;
        define('PIC_DIR','/data/crm/web/crm/upfile/pic/');
        define('SPIC_DIR','/data/crm/web/crm/upfile/spic/');
        define('DISP_PIC_DIR','/upfile/pic/');
        define('DISP_SPIC_DIR','/upfile/spic/');
        $returnFlag = false;
        
        if ($type == 2) {
            if (empty($userid)) {
                $message = "无效的ID号";
                $returnFlag = true;
            }
        }
        if (empty($name)) {
            $message = "请输入图片名称";
            $returnFlag = true;
        }
        
        if ($returnFlag) {
            msg($message);
        } else {
            $upfile = $_FILES['upfile']['tmp_name'];
            $upfile_type = $_FILES['upfile']['type'];
            $upfile_size = $_FILES['upfile']['size'];
            
            $createarray = NewPhoto();
            if($createarray[0] == 0){
                if ($type == 2) {
                    $headerStr = "editconsumer.php?consumerid=$commonid&start=$start&range=$range&msg=$createarray[1]";
                }
                Header("Location:$headerStr");
                exit();
            }else{
                msg($createarray[1]);
            }
        }
    }
    
    function NewPhoto()
    {
        global $type,$name,$upfile,$upfile_size,$upfile_type;
        if ($type == 2) {
            global $userid;
        }
        $year = substr(date("Y"),-2);
        $month = date("m");
        $day = date("d");
        $imgdir = PIC_DIR.$day."/".$month."/".$year."/";
        $titledir = SPIC_DIR.$day."/".$month."/".$year."/";
            
        if($upfile_size >= 2048000) {
            $retarray = array(1,"请选择小于2MB的图片！");
            return $retarray;
        }
        if($upfile_size <= 0){
            $retarray = array(2,"您没有选择有效的图片！");
            return $retarray;
        }
			
        $check="$upfile_type";//文件类型
        if(($check != "image/gif") && ($check != "image/pjpeg") && ($check != "image/jpeg")) {
            $retarray = array(3,"请选择gif或者jpg格式的图片上传！");
            return $retarray;
        }
        if(!is_dir($imgdir)){//没有目录
            mkdir($imgdir, 0777);
        }
        if(!is_dir($titledir)){//没有目录
            mkdir($titledir, 0777);
        }
        
        //存储图片
	    $f = "$upfile";
	    srand(time());
	    $picrightname = rand();
        $spicrightname = $picrightname*3+31415926;
        if ($type == 2){
            $CopyName = $type."_".$userid."_".$picrightname;
	        $TitalName = $type."_".$userid."_".$spicrightname;
        }
	    if ($check == "image/gif") {
            $CopyName = $CopyName.".gif";
            $TitalName = $TitalName.".gif";
	    } else {
            $CopyName = $CopyName.".jpg";
            $TitalName = $TitalName.".jpg";
	    }
	    $CopyFileDest = $imgdir.$CopyName;
	    $TitleFileDest = $titledir.$TitalName;
	    $imgdirDB = DISP_PIC_DIR.$day."/".$month."/".$year."/".$CopyName;
	    $titledirDB = DISP_SPIC_DIR.$day."/".$month."/".$year."/".$TitalName;
	    if (file_exists('$CopyFileDest') || file_exists('$TitleFileDest')) {//如果文件存在，返回
            $retarray = array(4,"你刚刚上传了图片，请稍后再传！");
            return $retarray;
	    }
        
        //copy image
        $testRet = copy($f,$CopyFileDest);

        if ($type == 2) {
            $orderid = 0;
            $suborderid = 0;
            $productid = 0;
            $userid = $userid;
            $operatorid = $_SESSION['SESS_USERID'];
        }
        
        $pictureManager = new cPicturePeer;
        $picture = new cPicture;
        $picture->setOrderid($orderid);
        $picture->setSuborderid($suborderid);
        $picture->setProductid($productid);
        $picture->setUserid($userid);
        $picture->setOperatorid($operatorid);
        $picture->setName($name);
        $picture->setType($type);
        $picture->setStatus(0);
        $picture->setPictureUrl($imgdirDB);
        $picture->setSpictureUrl($titledirDB);
        $createFlag = $pictureManager->create($picture);
        if ($createFlag) {
            $retarray = array(0,"上载图片成功");
            return $retarray;
        } else {
            $retarray = array(0,"创立数据库记录失败");
            return $retarray;
        }	
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
            <form name=\"form1\" method=\"post\" action=\"\" enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"type\" value=\"$type\">
            {$idStr}
            <input type=\"hidden\" name=\"start\" value=\"$start\">
            <input type=\"hidden\" name=\"range\" value=\"$range\">
            <tr {$strings['TABLE_TITLE_BKCOLOR']}>
                <td colspan=\"2\" height=\"30\" class=hdr>{$hintStr}</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">图片名称：</td>
                <td >{$picName}</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">本地计算机图片：</td>
                <td >&nbsp;<input type=\"file\" size=\"40\" name=\"upfile\"></td>
            </tr>
            <tr{$strings['TABLE_DARK_COLOR']}>
                <td height=\"40\" colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"Submit\" value=\"确认添加图片\"></td>
            </tr>
            </form>
        </table>";
?>