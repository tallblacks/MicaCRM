<?php
/*
* 定时生成各城市的往期页面
* URL类型定义：
* http://www.ihm.com/beijing/deals --参数是beijing或shanghai，城市的英文名
*/
include_once 'include/ihaomy/init.php';
Loader::loadFile('public.func.php', true, LIB_ROOT.'function/sw');
include_once(DOC_ROOT.'conf/group.config.php');

$pagesize = 20;
// 提取已经开通的城市列表
$data_city = Group :: getOnlineCity();
echo "=========".date("Y-m-d H:i:s")."=========\n";
for($i = 0,$cc = count($data_city);$i < $cc;$i++){
	//if ($data_city[$i]["cityId"] == 100){
	//	continue;
	//}
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
echo "=====================end================\n";