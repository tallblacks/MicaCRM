<?php
/*
* 重新发布商城首页，主要功能包括：
* 1.重新发布已开通城市商城首页
* 以crontab方式运行，时间设置为0：02分运行一次，处理当天最新城市商城首页
* 2 0 * * * /data/php/bin/php /ihaomy/web/shell/handlePublishShop.php >> /data/log/crontab.ihaomy.com/handle_publish_shop.log
*/
include_once 'include/ihaomy/init.php';
include_once DOC_ROOT.'conf/group.config.php';

// 目前只有北京开通商城首页
$url = SW_URL_CMS."modules/product/handle/publicshop.php";
$url .= "?skip=1&cityId=100&eName=beijing";
$ret = file_get_contents($url);
echo $url."\n";

/*
$sql = " SELECT cityId,ename FROM dict_city c WHERE isonline = 1 ";
$dbw = Sw_Db_Wrapper::getInstance();
$data = $dbw->getAllCached("product", $sql, 0);
for($i = 0,$cc = count($data);$i < $cc;$i++){
	$url = SW_URL_CMS."modules/product/handle/publicshop.php";
	$url .= "?skip=1&cityId=".$data[$i]["cityId"]."&eName=".$data[$i]["ename"];
	$ret = file_get_contents($url);
	echo $url."\n";
}
*/
echo "=====================".date("Y-m-d H:i:s")."=====================\n";