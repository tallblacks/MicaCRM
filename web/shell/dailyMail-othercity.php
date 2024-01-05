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
//打印发送HTML邮件内容的URL，如/ihaomy/web/www/beijing/deal/hjyzqx399_email.html

// 提取最新团购的fileName, title, cityId{{{
$sql = " SELECT fileName, title, cityId
		FROM group_product 
		WHERE beginTime >= ".strtotime(date("Y-m-d 00:00:00"))."	
		AND beginTime <= ".strtotime(date("Y-m-d 23:59:59"))." 
		AND (cityId <> 100 AND cityId <> 101) 
		ORDER BY cityId ";
/*$sql = " SELECT fileName, title, cityId
		FROM group_product 
		WHERE (beginTime >= ".strtotime(date("2011-07-22 00:00:00"))."	
		AND beginTime <= ".strtotime(date("2011-07-23 23:59:59")).") 
		AND cityId <> 100 
		ORDER BY cityId ";*/
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

// 根据cityid即有新团购的城市，逐个提取用户信息，并发邮件（循环中）{{{
$setp = 2000;
$sendseq = 0;
$haomyMailList = array(
	"noreply7@ihaomy.com",
	"noreply10@ihaomy.com",
	"noreply-3@ihaomy.com",
	"noreply@ihaomy.com"
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
		//echo "\n $cityId - $user_cc \n";
		for($k = 0;$k < $user_cc;$k++){
			// 数组，因为该城市也许每天有2款产品上线
			$group_city = $group_data[$cityId]; 
			$gcc = count($group_city);
			for($g = 0;$g < $gcc;$g++){
				$fileName = DOC_ROOT.$eName."/".CMS_DETAIL."/".$group_city[$g]["fileName"]."_email.html";
				$title = $group_city[$g]["title"];
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
				// }}}
				// 发送邮件
				$ret = send_mail($fileName, 
							null, 
							$title,
							$user_data[$k]["email"],
							$user_data[$k]["nickName"],
							$haomyMailList[$sendRand],
							"noreplypassword"
							);
				//echo $haomyMailList[$sendRand]."|".$user_data[$k]["email"]."|".$ret."|".date("Y-m-d H:i:s")."\n";
				echo "城市：".$cityId."轮数：".$page.",计数：".$sendseq.",所用邮箱:".$haomyMailList[$sendRand]."|".$user_data[$k]["email"]."|".$ret."|".date("Y-m-d H:i:s")."\n";
				
				//if(!$ret){
				//	echo ">>>>>>>>>>>>>帐号".$haomyMailList[$sendRand]."发送失败，启动备用邮箱发送\n";
				//	$ret = send_mail($fileName,
				//					null,
				//					$title,
				//					$user_data[$page-1][$k]["email"],
				//					$user_data[$page-1][$k]["nickName"],
				//					"noreply@ihaomy.com",
				//					"noreplypassword"
				//					);
				//	echo "备用邮箱发送完毕<<<<<<<<<<<<<<<<<\n";
				//}
				
				// 间隔10秒
				sleep(10);
				$sendseq++;
			} // end of 该城市每天上线的新团购（可能不止一个）
		} // end of 分区提取的用户逐个遍历
	} // end of 用户分区提取
} // end of 新团购涉及的城市
// }}}
echo "==========end=============\n";
?>