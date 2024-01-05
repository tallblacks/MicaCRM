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
// }}}

// 提取最新团购的fileName, title, cityId{{{
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

// 根据cityid即有新团购的城市，逐个提取用户信息，并发邮件（循环中）{{{
$setp = 2000; // 每次取100个用户进行群发
$sendseq = 0;
$haomyMailList = array(
	"noreply@ihaomy.com",
	"noreply8@ihaomy.com",
	"noreply7@ihaomy.com",
	"noreply6@ihaomy.com",
	"noreply5@ihaomy.com",
	"noreply4@ihaomy.com",
	"noreply3@ihaomy.com",
	"noreply2@ihaomy.com",
	"noreply9@ihaomy.com",
	"noreply10@ihaomy.com"
	);
for($i = 0;$i < $cc;$i++){
	$cityId = $cityIds[$i];
	$eName = $city_data[$cityId];
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
	// 根据该城市的用户总数 和 每次取多少个用户 计算出需要提取多少次用户数据
	$setp_count = ceil($user_total / $setp); 
	for($page = 1; $page <= $setp_count; $page++){
		// 初始化
		$user_data = array();
		$nStartID = ($page-1)*$setp;
		// 提取用户信息
		/*
		$sql = " SELECT nickName, email	FROM user WHERE cityId = ".$cityId." AND isOrderMail = 1 LIMIT ".$nStartID.", ".$setp;
		echo $sql."\n";
		$dbw = Sw_Db_Wrapper::getInstance();
		$user_data = $dbw->getAllCached('user', $sql, 0);
		*/
		$mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
		if (!mysql_ping($mysql)){
			echo "mysql_ping; \n";
			mysql_close($mysql);
			$mysql = mysql_connect(SW_DB_WRITE_HOSTS,SW_DB_WRITE_USER,SW_DB_WRITE_PASS);
		}
		mysql_select_db("user");
		$sql = " SELECT nickName, email	FROM user WHERE cityId = ".$cityId." AND isOrderMail = 1 LIMIT ".$nStartID.", ".$setp;
		echo $sql."\n";
		$result = mysql_query($sql);
		$u = 0;
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				$user_data[$u]["nickName"] 	= $row["nickName"];
				$user_data[$u]["email"] 	= $row["email"];
				$u++;
			}
			mysql_free_result($result);
		}
		mysql_close($mysql);
		//print_r($user_data);
		// 发送邮件
		$user_cc = count($user_data);
		if (!$user_cc){
			echo "error!! ".$cityId."/".$eName." no users\n";
			break;
		}		
		for($k = 0;$k < $user_cc;$k++){
			// 数组，因为该城市也许每天有2款产品上线
			$group_city = $group_data[$cityId]; 
			$gcc = count($group_city);
			for($g = 0;$g < $gcc;$g++){
				$fileName = DOC_ROOT.$eName."/".CMS_DETAIL."/".$group_city[$g]["fileName"]."_email.html";
				$title = $group_city[$g]["title"];
				// 匹配群发邮箱 {{{
				$sendRand = 0;
				if ($sendseq >= 0 && $sendseq <= 500){
					$sendRand = 0;
				}else if ($sendseq >= 501 && $sendseq <= 1000){
					$sendRand = 1;
				}else if ($sendseq >= 1001 && $sendseq <= 1500){
					$sendRand = 2;
				}else if ($sendseq >= 1501 && $sendseq <= 2000){
					$sendRand = 3;
				}else if ($sendseq >= 2001 && $sendseq <= 2500){
					$sendRand = 4;
				}else if ($sendseq >= 2501 && $sendseq <= 3000){
					$sendRand = 5;
				}else if ($sendseq >= 3001 && $sendseq <= 3500){
					$sendRand = 6;
				}else if ($sendseq >= 3501 && $sendseq <= 4000){
					$sendRand = 7;
				}else if ($sendseq >= 4001 && $sendseq <= 4500){
					$sendRand = 8;
				}else if ($sendseq >= 4501 && $sendseq <= 5000){
					$sendRand = 9;
				}
				// }}}
				// 发送邮件
				/*$ret = send_mail($fileName, 
							null, 
							$title,
							$user_data[$k]["email"],
							$user_data[$k]["nickName"],
							$haomyMailList[$sendRand],
							"noreplypassword"
							);*/
				echo $haomyMailList[$sendRand]."|".$user_data[$k]["email"]."|".$ret."|".date("Y-m-d H:i:s")."\n";
				// 间隔10秒
				//sleep(10);
				$sendseq++;
			} // end of 该城市每天上线的新团购（可能不止一个）
		} // end of 分区提取的用户逐个遍历
	} // end of 用户分区提取
} // end of 新团购涉及的城市
// }}}
echo "==========end=============\n";
