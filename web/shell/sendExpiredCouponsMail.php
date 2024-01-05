<?php
/*
* 功能：提前7天对好买券即将过期还未使用的用户做邮件提醒
* 以crontab方式运行，时间设置为3：12分运行一次
* 12 3 * * * /data/php/bin/php /ihaomy/web/shell/sendExpiredCouponsMail.php >> /data/log/crontab.ihaomy.com/send_expired_coupons_mail.log
*/
include_once 'include/ihaomy/init.php';
include_once DOC_ROOT.'conf/group.config.php';

// 提取从当前时间点开始计算，在其第七天要过期的团购好买券（即提前7天）{{{
$tDate = strtotime ( "+7 day" );
$sql = " SELECT groupId, title
		FROM group_product
		WHERE couponEndTime >= ".strtotime(date("Y-m-d 00:00:00", $tDate))." AND  couponEndTime <= ".strtotime(date("Y-m-d 23:59:59", $tDate));
$dbw = Sw_Db_Wrapper::getInstance();
$data_group = $dbw->getAllCached("product", $sql, 0);
if (empty($data_group)){
	echo date("Y-m-d",$tDate)." No group Expired\n";
	exit;
}
// }}}

// 提取团购对应的产品ID {{{
$productIds = array();
$productIdMailName = array(); // productId => groupName（产品Id=>团购名称）
for ($i = 0,$cc = count($data_group);$i < $cc;$i++){
	$ret = Group :: getGroupSettingsByGroupId($data_group[$i]["groupId"]);
	if (!empty($ret)){
		$productIds[] = $ret[0]["productId"];
		$productIdMailName[$ret[0]["productId"]] = $data_group[$i]["title"];
	}
}
if (empty($productIds)){
	echo "settings error! no productIds\n";
	exit;
}
// }}}

// 根据productId提取未使用的好买券的用户id {{{
$result = array(); // productId => userArray（产品Id=>用户结果集）
for ($i = 0,$cc = count($productIds);$i < $cc;$i++){
	$sql = " SELECT userId
			FROM user_haomyCoupon
			WHERE productId = '".$productIds[$i]."' 
			AND status = 1 ";
	$data_user = $dbw->getAllCached("user", $sql, 0);
	if (!empty($data_user)){
		$userIds = array();
		for ($k = 0,$kcc = count($data_user);$k < $kcc;$k++){
			$userIds[] = $data_user[$k]["userId"];
		}
		$result[$productIds[$i]] = $userIds;
	}
}
// }}}

// 查询用户昵称 {{{
$mail_data = array();
foreach($result as $productId => $userIds){
	$sql = " SELECT nickName, email FROM user WHERE userId IN ( ".implode(",",$userIds)." )";
	$data_user = $dbw->getAllCached('user', $sql, 2);
	for ($i = 0,$cc = count($data_user);$i < $cc;$i++){
		$mail_data[$productId][] = array(
									"email" => $data_user[$i]["email"],
									"nick"  => $data_user[$i]["nickName"]
									);
	}
	echo "productId:".$productId.",unused haomyCoupon user count:".$cc."\n";
}
// }}}

// 发送邮件{{
foreach($mail_data as $productId => $each){
	for ($i = 0,$cc = count($each);$i < $cc;$i++){
		$msg = "您好 ".$each[$i]["nick"]."，<br/><br/>您购买的“".$productIdMailName[$productId]."”好买券即将于".date("m月d日",$tDate)."23时59分过期请及时消费。<br/><br/>=====================<br/>好买都市网<br/>".date("Y-m-d");
		//echo $msg."\n";
		$ret = send_mail(null, 
						$msg, 
						"好买券过期提醒【好买都市网】", //$each_grupfile[$k]["title"]
						$each[$i]["email"],
						$each[$i]["nick"],
						"noreply10@ihaomy.com",
						"noreplypassword"
						);
		echo $each[$i]["email"]."|".$ret."|".date("Y-m-d H:i:s")."\n";
		// 间隔10秒
		sleep(10);
	}
}
// }}}


