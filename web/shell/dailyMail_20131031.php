<?php
/*
* 群发最新团购信息
* 特殊处理北京－发送全部产品，其他城市暂时发送新上线产品
* 发送邮件时间过长，暂时手动运行此程序
*/
include_once ("include/ihaomy/init.php");
include_once DOC_ROOT.'conf/group.config.php';

/*
流程：
1.发布北京当日商品列表邮件页面
2.设置发送用户数量分隔数据和发送邮箱信息
2.提取最新团购的fileName, title, cityId
3.提取最新团购的城市的cityId,ename，//$eName = Group::getEnameByCityId($cityId);
4.根据cityid即有新团购的城市，逐个提取用户信息，并发邮件（循环中）
*/

echo "==========begin=============\n";
//1、发布北京当日商品列表邮件页面
$url = SW_URL_CMS."modules/product/handle/createMallPage.php";
echo file_get_contents($url);

//2、设置发送用户数量分隔数据和发送邮箱信息
$setp = 2000;
$sendseq = 0;
$haomyMailList = array(
	"noreply7@ihaomy.com",
	"noreply10@ihaomy.com",
	"noreply-3@ihaomy.com",
	"noreply@ihaomy.com"
	);

///////////////////////////////
//beijing send EDM everyday!
$cityId = 100;
$eName = beijing;
// 提取该城市的订阅用户总数，分页提取用户信息用
$sql = " SELECT count(1) FROM user WHERE cityId = ".$cityId." AND isOrderMail = 1";
$dbw = Sw_Db_Wrapper::getInstance();
$user_total = $dbw->getOneCached('user', $sql, 0);
if (!$user_total){
	echo $cityId."/".$eName." no users\n";
	continue;
}else{
	echo $eName." user_total=$user_total \n";
}

$setp_count = ceil($user_total / $setp);
for($page = 1; $page <= $setp_count; $page++){
	// 初始化
	$user_data[] = array();
	$nStartID = ($page-1)*$setp;
	
	$mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
	if (!mysql_ping($mysql)){
		echo "mysql_ping; \n";
		mysql_close($mysql);
	}
	//echo "SW_DB_WRITE_HOSTS".SW_DB_WRITE_HOSTS.",SW_DB_WRITE_USER".SW_DB_WRITE_USER.",SW_DB_WRITE_PASS".SW_DB_WRITE_PASS;
	mysql_select_db("user");
	$sql = " SELECT nickName, email	FROM user WHERE cityId = ".$cityId." AND isOrderMail = 1 LIMIT ".$nStartID.", ".$setp;
	echo $sql."\n";
	$result = mysql_query($sql);
	$u = 0;
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$user_data[$page-1][$u]["nickName"] 	= $row["nickName"];
			$user_data[$page-1][$u]["email"] 	= $row["email"];
			$u++;
		}
		mysql_free_result($result);
	}
	mysql_close($mysql);
	//echo count($user_data[$page-1])."\n";
}
	
for($page = 1; $page <= $setp_count; $page++){
	// 发送邮件
	$user_cc = count($user_data[$page-1]);
	if (!$user_cc){
		echo "error!! ".$cityId."/".$eName." no users\n";
		break;
	}
	echo "本轮发送邮件的用户数：".$user_cc."\n";
	for($k = 0;$k < $user_cc;$k++){
		//$fileName = DOC_ROOT.$eName."/".CMS_DETAIL."/".$group_city[$g]["fileName"]."_email.html";
		$fileName = "/ihaomy/web/www/edmpages/beijing/".date("Y-m-d").".html";
		//$title = "TimeOut好买都市网：［《低声悠扬》中央民族乐团致父亲节声乐专场音乐会］";
		$title = "《低声悠扬》中央民族乐团致父亲节声乐专场音乐会";
		// 匹配群发邮箱 {{{
		$sendRand = 0;
		switch($sendseq % 4){
					case 0:
						$sendRand = 0;
						break;
					case 1:
						$sendRand = 1;
						break;
					case 2:
						$sendRand = 2;
						break;
					case 3:
						$sendRand = 3;
						break;
					default:
						$sendRand = 3;
						break;
		}
		// 发送邮件
		//if($sendseq < 1801){
		//if($sendseq > 10800 && $sendseq < 12601){
		if($sendseq > 12600){
			$ret = send_mail($fileName,
								null,
								$title,
								$user_data[$page-1][$k]["email"],
								$user_data[$page-1][$k]["nickName"],
								$haomyMailList[$sendRand],
								"noreplypassword"
								);
			//echo $haomyMailList[$sendRand]."|".$user_data[$k]["email"]."|".$ret."|".date("Y-m-d H:i:s")."\n";
			echo "轮数：".$page.",计数：".$sendseq.",所用邮箱:".$haomyMailList[$sendRand]."|".$user_data[$page-1][$k]["email"]."|".$ret."|".date("Y-m-d H:i:s")."\n";
			
			//if(!$ret){
			//	echo ">>>>>>>>>>>>>帐号".$haomyMailList[$sendRand]."发送失败，启动备用邮箱发送\n";
			//	$ret = send_mail($fileName,
			//					null,
			//					$title,
			//					$user_data[$page-1][$k]["email"],
			//					$user_data[$page-1][$k]["nickName"],
			//					"noreply2@ihaomy.com",
			//					"noreplypassword"
			//					);
			//	echo "备用邮箱发送完毕<<<<<<<<<<<<<<<<<\n";
			//}
			
			// 间隔15秒
			sleep(15);
		}
		$sendseq++;
	} // end of 该城市每天上线的新团购（可能不止一个）
} // end of 用户分区提取
///////////////////////////////
?>