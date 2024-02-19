<?php
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
    CheckCookies();

    session_start();
    if (!isset($_SESSION["SESS_USERID"])){
    	Header("Location:../phpinc/main.php");
	exit();
    }
    
    $orderid = trim($_GET["orderid"]);
    $idcode = trim($_GET["idcode"]);
    $start = trim($_GET["start"]);
    $range = trim($_GET["range"]);
  
    if ($_POST){
        //foreach($_POST as $key => $val) $$key=$val;
        $orderid = trim($_POST["orderid"]);
        $idcode = trim($_POST["idcode"]);
        $start = trim($_POST["start"]);
        $range = trim($_POST["range"]);
        require_once("../include/ccrmpicturepeer.inc.php");
        $db = new cDatabase;
        //mysql_query('set names utf8');
        define('PIC_DIR','/data/MicaCRM/web/crm/upfile/pic/');
        define('SPIC_DIR','/data/MicaCRM/web/crm/upfile/spic/');
        define('DISP_PIC_DIR','/upfile/pic/');
        define('DISP_SPIC_DIR','/upfile/spic/');
        $returnFlag = false;
        
        $name = trim($_POST["name"]);
        
        if(empty($orderid)){
            $message = "无效的订单号";
            $returnFlag = true;
        }
        if(empty($name)){
            $message = "请输入图片名称";
            $returnFlag = true;
        }
        
        if($returnFlag){
            msg($message);
        }else{
            $upfile = $_FILES['upfile']['tmp_name'];
            $upfile_type = $_FILES['upfile']['type'];
            $upfile_size = $_FILES['upfile']['size'];
            
            //echo "name:".$upfile;
            //echo "type:".$upfile_type;
            //echo "size:".$upfile_size;
            
            $createarray = NewPhoto();
            if($createarray[0] == 0){
                Header("Location:editmemo.php?idcode=$idcode&start=$start&range=$range&msg=$createarray[1]");
                exit();
            }else{
                msg($createarray[1]);
            }
        }
    }
    
    function NewPhoto(){
        global $orderid,$name,$upfile,$upfile_size,$upfile_type;
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
	    if (!mkdir($imgdir, 0777, true)) {
              die('Failed to create directory: ' . $imgdir);
            }
        }
        if(!is_dir($titledir)){//没有目录
	    print($titledir);
            mkdir($titledir, 0777);
        }
        
        //存储图片
	$f="$upfile";
	srand(time());
	$picrightname = rand();
	$spicrightname = $picrightname*3+31415926;
	$CopyName = $orderid."_".$picrightname;
	$TitalName = $orderid."_".$spicrightname;
	if($check == "image/gif"){
            $type = 2;
            $CopyName = $CopyName.".gif";
            $TitalName = $TitalName.".gif";
	}else{
            $type = 1;
            $CopyName = $CopyName.".jpg";
            $TitalName = $TitalName.".jpg";
	}
	$CopyFileDest = $imgdir.$CopyName;
	$TitleFileDest = $titledir.$TitalName;
	$imgdirDB = DISP_PIC_DIR.$day."/".$month."/".$year."/".$CopyName;
	$titledirDB = DISP_SPIC_DIR.$day."/".$month."/".$year."/".$TitalName;
	if (file_exists('$CopyFileDest') || file_exists('$TitleFileDest')){//如果文件存在，返回
            $retarray = array(4,"你刚刚上传了图片，请稍后再传！");
            return $retarray;
	}
        
        //copy image
        $testRet = copy($f,$CopyFileDest);
        
        $crmpictureManager = new cCrmPicturePeer;
        $crmpicture = new cCrmPicture;
	$crmpicture->setOrderid($orderid);
        $crmpicture->setSuborderid(0);
        $crmpicture->setProductid(0);
        $crmpicture->setUserid($_SESSION['SESS_USERID']);
        $crmpicture->setName($name);
        $crmpicture->setType($type);
        $crmpicture->setStatus(0);
        $crmpicture->setPictureUrl($imgdirDB);
        $crmpicture->setSpictureUrl($titledirDB);
	$createFlag = $crmpictureManager->create($crmpicture);
        if($createFlag){
            $retarray = array(0,"上载图片成功");
            return $retarray;
        }else{
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
  if(!empty($msg)) {	
    echo "<span class=cur>$msg</span>";
  }
    echo "
  .     <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"1\" {$strings['TABLE_LINK_BK_COLOR']} class=eng>
        <form name=\"form1\" method=\"post\" action=\"\" enctype=\"multipart/form-data\">
        <input type=\"hidden\" name=\"orderid\" value=\"$orderid\">
        <input type=\"hidden\" name=\"idcode\" value=\"$idcode\">
        <input type=\"hidden\" name=\"start\" value=\"$start\">
        <input type=\"hidden\" name=\"range\" value=\"$range\">
            <tr {$strings['TABLE_TITLE_BKCOLOR']}>
                <td colspan=\"2\" height=\"30\" class=hdr>为订单添加图片附件（图片尺寸必须小于2MB！）</td>
            </tr>
            <tr {$strings['TABLE_DARK_COLOR']}>
                <td width=\"100\" height=\"27\"  align=\"right\">图片名称：</td>
                <td >&nbsp;<input name=name size=40></td>
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
