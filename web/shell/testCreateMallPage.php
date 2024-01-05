<?php
/*
* 群发最新团购信息
* 特殊处理北京－发送全部产品，其他城市暂时发送新上线产品
* 发送邮件时间过长，暂时手动运行此程序
*/
include_once ("include/ihaomy/init.php");
include_once DOC_ROOT.'conf/group.config.php';

/*
流程：
1.发布北京当日商品列表邮件页面
2.设置发送用户数量分隔数据和发送邮箱信息
2.提取最新团购的fileName, title, cityId
3.提取最新团购的城市的cityId,ename，//$eName = Group::getEnameByCityId($cityId);
4.根据cityid即有新团购的城市，逐个提取用户信息，并发邮件（循环中）
*/

echo "==========begin=============\n";
//1、发布北京当日商品列表邮件页面
$url = SW_URL_CMS."modules/product/handle/createMallPage.php";
echo file_get_contents($url);
?>