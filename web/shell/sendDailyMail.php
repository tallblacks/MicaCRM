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
// 发布最新团购的邮件页面
$url = SW_URL_CMS."modules/product/handle/publicdetail_mail.php";
echo file_get_contents($url);
//打印发送HTML邮件内容的URL，如/ihaomy/web/www/beijing/deal/hjyzqx399_email.html

// 提取最新团购的fileName, title, cityId
$sql = " SELECT fileName, title, cityId
		FROM group_product 
		WHERE beginTime >= ".strtotime(date("Y-m-d 00:00:00"))."	
		AND beginTime <= ".strtotime(date("Y-m-d 23:59:59"))."
		ORDER BY cityId ";
$dbw = Sw_Db_Wrapper::getInstance();
$data = $dbw->getAllCached("product", $sql, 0);
$group_data = array();
$cc = count($data);
for($i = 0;$i < $cc;$i++){
	$group_data[$data[$i]["cityId"]][] = array("fileName" => $data[$i]["fileName"], "title" => $data[$i]["title"]);
    //echo "cityID:".$data[$i]["cityId"].",fileName:".$data[$i]["fileName"].",title:".$data[$i]["title"];
	//cityID，比如北京就是100，协助数据库查询;fileName，URL最后的页面名字地址;title，长标题
}
if (!$cc){
	echo "no new group\n";
	echo "==========end=============\n";
	exit;
}

// 提取最新团购的城市的cityId,ename
$cityIds = array_keys($group_data);
$cc = count($cityIds);
$city_data = array();
for($i = 0;$i < $cc;$i++){
	$city_data[$cityIds[$i]] = Group::getEnameByCityId($cityIds[$i]); // array(100=>"beijing")
}

// 根据cityid即有新团购的城市，逐个提取用户信息，并发邮件（循环中）
$setp = 2000; // 每次取2000个用户进行群发
$sendseq = 0;
$haomyMailList = array(
	"noreply@ihaomy.com",
	"noreply7@ihaomy.com",
	"noreply10@ihaomy.com",
	"noreply-1@ihaomy.com",
	"noreply-2@ihaomy.com",
	"noreply-3@ihaomy.com",
	"noreply-4@ihaomy.com",
	"noreply-5@ihaomy.com",
	"noreply-6@ihaomy.com",
	"noreply-7@ihaomy.com",
	"noreply-8@ihaomy.com",
	"noreply-9@ihaomy.com",
	"noreply-10@ihaomy.com"
	);
for($i = 0;$i < $cc;$i++){
	$cityId = $cityIds[$i];//城市数据库代码
	$eName = $city_data[$cityId];//城市中文名字
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
	
	/////////////////////////////
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
	}
	/////////////////////////////
	
	// 根据该城市的用户总数 和 每次取多少个用户 计算出需要提取多少次用户数据
	for($page = 1; $page <= $setp_count; $page++){
		// 发送邮件
		$user_cc = count($user_data[$page-1]);
		if (!$user_cc){
			echo "error!! ".$cityId."/".$eName." no users\n";
			break;
		}
		echo "本轮发送邮件的用户数：".$user_cc."\n";
		for($k = 0;$k < $user_cc;$k++){
			// 数组，因为该城市也许每天有2款产品上线
			$group_city = $group_data[$cityId]; 
			$gcc = count($group_city);
			for($g = 0;$g < $gcc;$g++){
				$fileName = DOC_ROOT.$eName."/".CMS_DETAIL."/".$group_city[$g]["fileName"]."_email.html";
				$title = $group_city[$g]["title"];
				// 匹配群发邮箱
				$sendRand = 0;
				switch($sendseq % 13){
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
					case 4:
						$sendRand = 4;
						break;
					case 5:
						$sendRand = 5;
						break;
					case 6:
						$sendRand = 6;
						break;
					case 7:
						$sendRand = 7;
						break;
					case 8:
						$sendRand = 8;
						break;
					case 9:
						$sendRand = 9;
						break;
					case 10:
						$sendRand = 10;
						break;
					case 11:
						$sendRand = 11;
						break;
					case 12:
						$sendRand = 12;
						break;
				}
				// 发送邮件
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
				// 间隔1秒
				sleep(1);
				$sendseq++;
			} // end of 该城市每天上线的新团购（可能不止一个）
		} // end of 分区提取的用户逐个遍历
	} // end of 用户分区提取
} // end of 新团购涉及的城市
echo "==========end=============\n";
?>