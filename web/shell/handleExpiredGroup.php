<?php
/*
* 处理过期定单，主要功能包括：
* 1.对已下单但未支付的用户定单做过期状态标识
* 2.重新生成过期团购的静态html页面，去掉ajax部分
* 以crontab方式运行，时间设置为0：05分运行一次，处理前一天过期的订单
* 5 0 * * * /data/php/bin/php /ihaomy/web/shell/handleExpiredGroup.php >> /data/log/crontab.ihaomy.com/handle_expired_group.log
*/
include_once 'include/ihaomy/init.php';
include_once DOC_ROOT.'conf/group.config.php';

// 得到已经结束的团购列表
$groupArray = Group :: loadExpiredGroup();
$cc = count($groupArray);
echo $cc." ".date("Y-m-d H:i:s")."\n";
for ($i = 0;$i < $cc;$i++) {
	// 更新用户过期定单 {{{
	// 载入买了该团购的所有用户定单
	$orderArray = Order :: loadOrdersByGroupId($groupArray[$i]["groupId"]);
	// 拼接orderId
	$orderIds = array();
	foreach($orderArray as $each){
		$orderIds[] = $each->orderId;
	}
	// 整体提交更新，将该团购的所有用户定单的显示状态更新为过期状态
	$ret = Order :: updateOrderShowStatus($orderIds, -1);
	echo "expired order r num ".$ret."\n";
	// }}}
	
	// 重新发布过期团购html {{{
	$eName = Group :: getEnameByCityId($groupArray[$i]["cityId"]);
	$url = SW_URL_CMS."modules/product/handle/publicdetail_expired.php?groupId=".$groupArray[$i]["groupId"]."&eName=".$eName;
	$ret = file_get_contents($url);
	echo "re publish ".$groupArray[$i]["groupId"].":".$ret."\n";
	// }}}
}
echo "=====================\n";