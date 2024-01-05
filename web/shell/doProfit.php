<?php
/*
* 返利处理
* 完成功能：
	1.团购结束时对返利进行统计，并将结束保存入user.money中
	2.发邮件给userId确认返利情况
* 以crontab方式运行，时间设置为0：05分运行一次
* 15 2 * * * /data/php/bin/php /ihaomy/web/shell/doProfit.php >> /data/log/crontab.ihaomy.com/do_profit.log
*/
include_once ("include/ihaomy/init.php");
include_once DOC_ROOT.'conf/group.config.php';

echo "==========begin=============\n";
// 得到已经结束的团购列表
$groupArray = Group :: loadExpiredGroup();
$cc = count($groupArray);
echo date("Y-m-d", strtotime("-1 day"))."结束的团购总数是:".$cc."\n";
$mail_array = array();
if ($cc > 0){
	for ($i = 0;$i < $cc;$i++) {
		$groupId = $groupArray[$i]["groupId"];
		// 载入已经结束团购的需返利记录
		// 查询产品名称{{{
		$profit_productId_data = Profit :: loadProfitProductIdByGroupId($groupId);
		$productIds = array();
		for($d = 0;$d < count($profit_productId_data);$d++){
			$productIds[] = $profit_productId_data[$d]["productId"];
		}
		$product_data = Product :: loadProductNameByIds($productIds);
		$product_key = array();
		$pcc = count($product_data);
		if ($pcc > 0){
			for ($p = 0;$p < $pcc;$p++){
				$product_key[$product_data[$p]["productId"]] = $product_data[$p]["productName"];
			}
		}
		// }}}
		$profit_userid_data = Profit :: loadProfitUserIdByGroupId($groupId);
		$kcc = count($profit_userid_data);
		if ($kcc > 0){
			for ($k = 0;$k < $kcc;$k++) {
				$userId = $profit_userid_data[$k]["profitUserId"];
				$productId = $profit_userid_data[$k]["productId"];
				// 计算该团购的总返利之和
				$profit_ret = Profit :: loadSumProfit($groupId, $userId);
				$profit_sum = $profit_ret[0]["sum"]; // group by了所以是单个产品返利金额
				$profit_sum_total = $profit_sum * count($profit_ret);
				$orderId = $profit_ret[0]["orderId"];
				// 修改返利记录状态至已返利
				$ret = Profit :: updateProfitStatus($groupId, $userId);
				$mail_array[$userId][] = array("sum" => $profit_sum_total, "groupId" => $groupId, "buyUserCount" => $ret, "productId" => $productId);
				echo "团购:".$groupId." 受利用户:".$userId." 更新记录数:".$ret." 返利总和:".$profit_sum_total."\n";
				
				// 更新用户表user.money {{{
				$userObj = new Sw_User();
				$userObj->userId = $userId;
				$userObj->loadUserById();
				$old_money = $userObj->money;
				$new_money = $old_money + $profit_sum_total;
				$update_user = array();
				$update_user[] = " money = ".$new_money;
				Sw_User :: updUserData($update_user, $userId);
				// }}}
				// 更新该用户的交易记录{{{
				$tradeObj = new Trade();
				for ($mm = 0,$mmc = count($profit_ret);$mm < $mmc;$mm++) {
					$tradeObj->orderId = $profit_ret[$mm]["orderId"];
					$tradeObj->userId = $userId;
					$tradeObj->tradeDesc = "收入 - 推荐返利";
					if (!empty($product_key[$productId])) {
						$tradeObj->tradeDesc .= " - ".$product_key[$productId];
					}
					$tradeObj->tradeTargetId = -2; // 交易目标 -1-充值 -2推荐返利 正值是productId
					$tradeObj->tradeType = 1; // 1-收入 2-支出
					$tradeObj->tradeMoney = $profit_sum; // 金额
					$tradeObj->balanceMoney = $old_money + $profit_sum; // 余额
					$tradeObj->tradeTime = time();
					$isadded = $tradeObj->isExist();
					if (!$isadded){
						$trade_ret = $tradeObj->addTradeLog();
						echo $userId."的交易记录添加结果:".$trade_ret."\n";
					}
					$old_money = $old_money + $profit_sum;
				}
				// }}}
			}
		}
	}
	// 查询用户信息
	$ids = array_keys($mail_array); 
	$countids = count($ids);
	if ($countids > 0){
		$user_data = Sw_User :: loadUserByUserIds($ids);
		// 合并数组数据，用户数据 + 返利数据 + 团购数据
		$final_array = array();
		for ($k = 0, $kcc = count($user_data);$k < $kcc;$k++) {
			$userId = $user_data[$k]["userId"];
			$mail_array_each = $mail_array[$userId];
			for ($m = 0, $mcc = count($mail_array_each);$m < $mcc;$m++) {
				$final_array[$userId][] = array(
							"nickName" => $user_data[$k]["nickName"],
							"email" => $user_data[$k]["email"],
							"money" => $user_data[$k]["money"], // 目前帐户余额
							"addmoney" => $mail_array_each[$m]["sum"], // 此次增加的金额
							"groupTitle" => $product_key[$mail_array_each[$m]["productId"]],
							"buyUserCount" => $mail_array_each[$m]["buyUserCount"]
							);
			}
		}
	} // end if 
}

$mcc = count($final_array);
if ($mcc > 0){
	foreach($final_array as $userId => $each){
		$mail_content = "您好，".$each[0]["nickName"]."：<br/><br/>";
		for ($k = 0, $kcc = count($each);$k < $kcc;$k++) {
			$mail_content .= "您参与的".$each[$k]["groupTitle"]."活动已经结束，其中您推荐的好友共有 ".$each[$k]["buyUserCount"]." 人已成功购买了该产品，您得到返利 ".$each[$k]["addmoney"]." 元。<br/>";
		}
		$mail_content .= "<br/>以上返利已经成功添加到您的好买都市网帐户中，您目前的帐户余额是：".$each[0]["money"]." 元。<br/>感谢您的支持！<br/>";	
		$mail_content .= "-------------------<br/>";
		$mail_content .= "好买都市网<br/>";
		$mail_content .= date("Y-m-d")."<br/>";
		//echo $mail_content;
		// 发送邮件
		$ret = send_mail(null, 
						$mail_content, 
							"好买都市网推荐好友返利确认",
							$each[0]["email"],
							$each[0]["nickName"],
							"noreply10@ihaomy.com",
							"noreplypassword"
							);
		echo $each[0]["email"]."|".$ret."|".date("Y-m-d H:i:s")."\n";
		// 间隔10秒
		sleep(10);
	}
}
// }}}
echo "==========end=============\n";


