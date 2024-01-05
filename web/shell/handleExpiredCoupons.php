<?php
/*
* 处理过期好买券，主要功能包括：
* 1.根据团购商品的好买券过期日期，对未使用的好买券做过期处理
* 以crontab方式运行，时间设置为1：15分运行一次，处理过期好买券
* 15 1 * * * /data/php/bin/php /ihaomy/web/shell/handleExpiredCoupons.php >> /data/log/crontab.ihaomy.com/handle_expired_coupons.log
*/
include_once 'include/ihaomy/init.php';
include_once DOC_ROOT.'conf/group.config.php';

// 得到好买券到期的团购列表
$groupArray = Group :: loadExpiredCoupon();
$cc = count($groupArray);
echo date("Y-m-d H:i:s")."\n";
echo "共有".$cc."个团购的好买券到期了，分别是：\n";
$retArray = array();
for ($i = 0;$i < $cc;$i++) {
	// 得到该团购的产品配置关系
	$retArray[$groupArray[$i]["groupId"]] =  Group :: getGroupSettingsByGroupId($groupArray[$i]["groupId"]);
	echo $i."::".$groupArray[$i]["title"]."\n";
}
foreach($retArray as $groupId => $productIds){
	$cc = count($productIds);
	for ($i = 0;$i < $cc;$i++){
		$ret = HaoMyCoupon :: updateExpiredCoupon($productIds[$i]["productId"]);
		echo "更新结束：".$ret."个用户的过期好买券\n";
	}
}
echo "=====================\n";