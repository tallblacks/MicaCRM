<?php
/*
* 重新发布首页，主要功能包括：
* 1.重新发布已开通城市首页
* 以crontab方式运行，时间设置为0：01分运行一次，处理当天最新城市首页
* 1 0 * * * /data/php/bin/php /ihaomy/web/shell/handlePublishIndex.php >> /data/log/crontab.ihaomy.com/handle_publish_index.log
*/
include_once 'include/ihaomy/init.php';
include_once DOC_ROOT.'conf/group.config.php';

// 各程序首页 {{{
$sql = " SELECT cityId,ename FROM dict_city c WHERE isonline = 1 ";
$dbw = Sw_Db_Wrapper::getInstance();
$data = $dbw->getAllCached("product", $sql, 0);
for($i = 0,$cc = count($data);$i < $cc;$i++){
	$url = SW_URL_CMS."modules/product/handle/publicindex.php";
	if ($data[$i]["cityId"] == 100){
		$url = SW_URL_CMS."modules/product/handle/publicindexV2.php";
	}
	$url .= "?skip=1&cityId=".$data[$i]["cityId"]."&eName=".$data[$i]["ename"];
	$ret = file_get_contents($url);
	echo "all city index page ::: ".$url."\n";
}

// 北京的各频道页，目前只开通北京有频道功能 {{{
$url = SW_URL_CMS."modules/product/handle/publicchannelsV2.php";
$url .= "?skip=1&cityId=100&eName=beijing";
$ret = file_get_contents($url);
echo "beijing channels ::: ".$url."\n";
// }}}
echo "=====================\n";
// }}}

