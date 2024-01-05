<?php
/*
* 批量刷新，重新静态页
*/
include_once '/ihaomy/library/include/ihaomy/init.php';
include_once DOC_ROOT.'conf/group.config.php';

// 生成网页相关静态页面{{{
// 以下地址都是针对于DOC_ROOT的相对路径
$pages = array(
		/*****about相关*****/
		"about/about.php",
		"about/faq.php",
		"about/learn.php",
		"about/buy.php",
		"about/privacy.php",
		"about/compensate.php",
		"about/opening.php",
		"subscribe.php",
		/*****login相关*****/
		"account/login.php",
		"account/signup.php",
		"account/resetpwd.php",
		/*****error相关*****/
		);
// 生成静态页
for($i = 0, $cc = count($pages);$i < $cc; $i++){
	$ret = file_get_contents(SW_URL_HOME.$pages[$i]);
	echo SW_URL_HOME.$pages[$i]."\n";
}
echo "===============================\n";
// }}}

// 发布团购详情页{{{
$sql = " SELECT g.groupId, c.ename
		FROM group_product g, dict_city c
		WHERE g.cityId = c.cityid ";
$dbw = Sw_Db_Wrapper::getInstance();
$data = $dbw->getAllCached("product", $sql, 60*5);
for($i = 0,$cc = count($data);$i < $cc;$i++){
	$url = SW_URL_CMS."modules/product/handle/publicdetail.php";
	$url .= "?skip=1&groupId=".$data[$i]["groupId"]."&eName=".$data[$i]["ename"];
	$ret = file_get_contents($url);
	echo $url."\n";
}
echo "===============================\n";
// }}}

// 发布已开通城市的团购首页{{{
$sql = " SELECT cityId,ename FROM dict_city c WHERE isonline = 1 ";
$dbw = Sw_Db_Wrapper::getInstance();
$data = $dbw->getAllCached("product", $sql, 60*5);
for($i = 0,$cc = count($data);$i < $cc;$i++){
	$url = SW_URL_CMS."modules/product/handle/publicindex.php";
	$url .= "?skip=1&cityId=".$data[$i]["cityId"]."&eName=".$data[$i]["ename"];
	$ret = file_get_contents($url);
	echo $url."\n";
}
echo "===============================\n";
// }}}

$pagesize = 20;
// 提取已经开通的城市列表{{{
$data_city = Group :: getOnlineCity();
for($i = 0,$cc = count($data_city);$i < $cc;$i++){
	$total = Group :: getGroupCountByCityId($data_city[$i]["cityId"]);
	$pagecount = ceil($total / $pagesize);
	if (!$pagecount) $pagecount = 1;
	for($page = 1;$page <= $pagecount;$page++){
		echo "eName=".$data_city[$i]["eName"]."/page=".$page."\n";
		$url = SW_URL_HOME."doPublishDeals.php?cityname=".$data_city[$i]["eName"]."&page=".$page;
		$ret = file_get_contents($url);
		echo $url."\n";
	}
}
echo "===============================\n";
// }}}

// 北京的各频道页，目前只开通北京有频道功能 {{{
$url = SW_URL_CMS."modules/product/handle/publicchannels.php";
$url .= "?skip=1&cityId=100&eName=beijing";
$ret = file_get_contents($url);
echo $url."\n";
echo "===============end=====================\n";
// }}}

//  {{{
$url = SW_URL_CMS."modules/product/handle/publicchannelsV2.php";
$url .= "?skip=1&cityId=100&eName=beijing";
$ret = file_get_contents($url);
echo $url."\n";
echo "===============end=====================\n";
// }}}

//  temp{{{
$url = SW_URL_CMS."modules/product/handle/publicdetailV2.php";
$url .= "?skip=1&cityId=100&eName=beijing";
$ret = file_get_contents($url);
echo $url."\n";
echo "===============end=====================\n";
// }}}

//  temp{{{
$url = SW_URL_CMS."modules/product/handle/publicindexV2.php";
$url .= "?skip=1&cityId=100&eName=beijing";
$ret = file_get_contents($url);
echo $url."\n";
echo "===============end=====================\n";
// }}}
exit;
