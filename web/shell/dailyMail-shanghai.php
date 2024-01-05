<?php
/*
* 群发每日最新团购信息
* 以crontab方式运行，时间设置为0：15分运行一次
* 15 0 * * * /data/php/bin/php /ihaomy/web/shell/sendGroupMail.php >> /data/log/crontab.ihaomy.com/send_group_mail.log
*/
include_once ("include/ihaomy/init.php");
include_once DOC_ROOT.'conf/group.config.php';

/*
流程：
1.发布最新团购的html
2.提取最新团购的fileName, title, cityId
3.提取最新团购的城市的cityId,ename，//$eName = Group::getEnameByCityId($cityId);
4.根据cityid即有新团购的城市，逐个提取用户信息，并发邮件（循环中）
*/

echo "==========begin=============\n";
// 发布最新团购的邮件页面{{{
$url = SW_URL_CMS."modules/product/handle/publicdetail_mail.php";
echo file_get_contents($url);

//2、设置发送用户数量分隔数据和发送邮箱信息
$setp = 2000;
$sendseq = 0;
$haomyMailList = array(
	"noreply3@ihaomy.com",
	"noreply4@ihaomy.com",
	"noreply5@ihaomy.com"
	);
	
///////////////////////////////
//shanghai send EDM everyday!
$cityId = 101;
$eName = shanghai;
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
			$user_data[$page-1][$u]["nickName"] = $row["nickName"];
			$user_data[$page-1][$u]["email"] = $row["email"];
			$u++;
		}
		mysql_free_result($result);
	}
	mysql_close($mysql);
	//echo count($user_data[$page-1])."\n";
}	
	
//////////////==============
//比北京多的
// 提取最新团购的fileName, title, cityId{{{
/*$sql = " SELECT fileName, title, cityId
		FROM group_product 
		WHERE beginTime >= ".strtotime(date("Y-m-d 00:00:00"))."	
		AND beginTime <= ".strtotime(date("Y-m-d 23:59:59"))." 
		AND cityId = 101 
		ORDER BY cityId ";*/
$sql = " SELECT fileName, title, cityId 
		FROM group_product 
		WHERE groupId = 389 
		AND cityId = 101 
		ORDER BY cityId ";
$dbw = Sw_Db_Wrapper::getInstance();
$data = $dbw->getAllCached("product", $sql, 0);
$group_data = array();
$cc = count($data);
for($i = 0;$i < $cc;$i++){
	$group_data[$data[$i]["cityId"]][] = array("fileName" => $data[$i]["fileName"], "title" => $data[$i]["title"]);
	 echo "cityID:".$data[$i]["cityId"].",fileName:".$data[$i]["fileName"].",title:".$data[$i]["title"]."\n";
}
if (!$cc){
	echo "no new group\n";
	echo "==========end=============\n";
	exit;
}
// }}}

// 提取最新团购的城市的cityId,ename{{{
$cityIds = array_keys($group_data);
$cc = count($cityIds);
$city_data = array();
for($i = 0;$i < $cc;$i++){
	$city_data[$cityIds[$i]] = Group::getEnameByCityId($cityIds[$i]); // array(100=>"beijing")
}
// }}}
//////////////==============

for($page = 1; $page <= $setp_count; $page++){
	// 发送邮件
	$user_cc = count($user_data[$page-1]);
	if (!$user_cc){
		echo "error!! ".$cityId."/".$eName." no users\n";
		break;
	}
	echo "本轮发送邮件的用户数：".$user_cc."\n";
	$group_city = $group_data[$cityId];
	$gcc = count($group_city);
	for($k = 0;$k < $user_cc;$k++){
		$fileName = DOC_ROOT.$eName."/".CMS_DETAIL."/".$group_city[0]["fileName"]."_email.html";
		//echo "发送文件全路径：".$fileName."\n";
		$title = $group_city[$g]["title"];
		// 匹配群发邮箱 {{{
		$sendRand = 0;
		switch($sendseq % 3){
			case 0:
				$sendRand = 0;
				break;
			case 1:
				$sendRand = 1;
				break;
			case 2:
				$sendRand = 2;
				break;
			default:
				$sendRand = 2;
				break;
		}
		// 发送邮件
		//if($sendseq < 301){
		if($sendseq > 300 && $sendseq < 601){
		//if($sendseq > 1800){
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

			sleep(15);
		} // end of 分区提取的用户逐个遍历
		$sendseq++;
	} // end of 用户分区提取
} // end of 新团购涉及的城市
// }}}
echo "==========end=============\n";
?>