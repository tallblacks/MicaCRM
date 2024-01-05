<?php
/*
* hao123定时xml
* 以crontab方式运行，时间设置为2：50分运行一次，处理过期好买券
* 50 2 * * * /data/php/bin/php /ihaomy/web/shell/groupnav/hao123.php >> /data/log/crontab.ihaomy.com/hao123.log
*/
include_once '/ihaomy/library/include/ihaomy/init.php';
include_once DOC_ROOT.'conf/group.config.php';
// 前台显示的假数据 groupId=>假数据
$showSellNum = array(
			28=>60,
			31=>60,
			36=>20,
			38=>10,
			48=>19,
			18=>260,
			69=>77,
			71=>72,
			83=>4,
			89=>100,
			85=>27,
			93=>43,
			95=>35,
			99=>1,
			100=>7,
			105=>6,
			101=>5,
			106=>3,
			102=>1,
			103=>5,
			104=>4,
			96=>16,
			108=>4,
			109=>2,
			110=>3,
			111=>5,
			113=>2,
			114=>4,
			115=>3,
			116=>6,
			117=>3,
			78=>25,
			107=>8,
			120=>35,
			119=>10,
			122=>7,
			124=>4,
			121=>30
);

$finalCompany = array();
// 获取已开通的城市列表
$data_city = Group :: getOnlineCity();
$showDate = date("Y-m-d");
// 生成hao123的xml {{{
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><urlset>";
for($i = 0,$cc = count($data_city);$i < $cc;$i++) {
	$groupIds = getSchedule($data_city[$i]["cityId"], $showDate);
	if ( empty($groupIds) ) continue;
	$data_group = Group :: loadGroupByGroupIds($groupIds, 0);
	foreach($data_group as $each){
		$eachProduct = array_shift($each->productarray);
		// 计算折扣
		$discount = 0;
		if ($eachProduct->marketPrice > 0){
			$discount = round($eachProduct->groupPrice / $eachProduct->marketPrice, 2) * 10;
		}
		$xml .="<url>
		<loc>".SW_URL_HOME.$data_city[$i]["eName"]."/".CMS_DETAIL."/".$each->fileName.".html</loc>
		<data>
			<display>
				<website>好买都市网</website>
				<siteurl>".SW_URL_HOME."</siteurl>
				<city>".$data_city[$i]["cityName"]."</city>
				<title><![CDATA[".$each->title."]]></title>
				<image>".SW_URL_UPROOT.$each->mainPic."</image>
				<startTime>".$each->beginTime."</startTime>
				<endTime>".$each->endTime."</endTime>
				<value>".$eachProduct->marketPrice."</value>
				<price>".$eachProduct->groupPrice."</price>
				<rebate>".$discount."</rebate>
				<bought>".($each->sellNum + 0 +$showSellNum[$each->groupId] + $each->maskNum)."</bought>
			</display>
		</data>
		</url>";
	}
}
$xml .= "</urlset>";
// }}}

// 生成xml文件 {{{
// hao123 http://www.hao123.com
file_put_contents(DOC_ROOT."xml/hao123.xml", $xml);
echo date("Y-m-d H:i:s")." hao123.xml\n";
// 网购在线 http://www.wgou.com
file_put_contents(DOC_ROOT."xml/wgou.xml", $xml);
echo date("Y-m-d H:i:s")." wgou.xml\n";
// 怎团 http://www.zentuan.com
file_put_contents(DOC_ROOT."xml/zentuan.xml", $xml);
echo date("Y-m-d H:i:s")." zentuan.xml\n";
// 精品团购指南 http://www.buy70.com
file_put_contents(DOC_ROOT."xml/buy70.xml", $xml);
echo date("Y-m-d H:i:s")." buy70.xml\n";
// 有团网 http://www.youtuan.net/
file_put_contents(DOC_ROOT."xml/youtuan.xml", $xml);
echo date("Y-m-d H:i:s")." youtuan.xml\n";
// 2012团购	http://www.tuan2012.com/
file_put_contents(DOC_ROOT."xml/tuan2012.xml", $xml);
echo date("Y-m-d H:i:s")." tuan2012.xml\n";
// 一站齐 http://yizhanqi.com/
file_put_contents(DOC_ROOT."xml/yizhanqi.xml", $xml);
echo date("Y-m-d H:i:s")." yizhanqi.xml\n";
// mosh http://www.mosh.cn/
file_put_contents(DOC_ROOT."xml/mosh.xml", $xml);
echo date("Y-m-d H:i:s")." mosh.xml\n";
// http://www.tuanegou.com
file_put_contents(DOC_ROOT."xml/tuanegou.xml", $xml);
echo date("Y-m-d H:i:s")." tuanegou.xml\n";
// http://www.tuan138.com
file_put_contents(DOC_ROOT."xml/tuan138.xml", $xml);
echo date("Y-m-d H:i:s")." tuan138.xml\n";
// http://www.daohangtuan.cn
file_put_contents(DOC_ROOT."xml/daohangtuan.xml", $xml);
echo date("Y-m-d H:i:s")." daohangtuan.xml\n";
// http://www.jutuaner.com
file_put_contents(DOC_ROOT."xml/jutuaner.xml", $xml);
echo date("Y-m-d H:i:s")." jutuaner.xml\n";
// http://www.tlive.com.cn
file_put_contents(DOC_ROOT."xml/tlive.xml", $xml);
echo date("Y-m-d H:i:s")." tlive.xml\n";
// http://www.igogo360.com
file_put_contents(DOC_ROOT."xml/igogo360.xml", $xml);
echo date("Y-m-d H:i:s")." igogo360.xml\n";
// http://www.6fj.com
file_put_contents(DOC_ROOT."xml/6fj.xml", $xml);
echo date("Y-m-d H:i:s")." 6fj.xml\n";
// http://www.txtg.com
file_put_contents(DOC_ROOT."xml/txtg.xml", $xml);
echo date("Y-m-d H:i:s")." txtg.xml\n";
// http://www.dazhe.com
file_put_contents(DOC_ROOT."xml/dazhe.xml", $xml);
echo date("Y-m-d H:i:s")." dazhe.xml\n";
// }}}

// 1.搜狐团购导航{{{
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><ActivitySet>";
$xml .= "<Site>好买都市网</Site>";
$xml .= "<SiteUrl>www.".SW_DOMAIN_SUFFIX."</SiteUrl>";
$xml .= "<Update>".date("Y-m-d")."</Update>";
for($i = 0,$cc = count($data_city);$i < $cc;$i++) {
	$groupIds = getSchedule($data_city[$i]["cityId"], $showDate);
	if ( empty($groupIds) ) continue;
	$data_group = Group :: loadGroupByGroupIds($groupIds, 0);
	foreach($data_group as $each){
		// 查询商户信息
		$companyData = $finalCompany[$each->companyId];
		if (empty($companyData)){
			$companyData = Company :: loadCompany($each->companyId);
			$finalCompany[$each->companyId] = $companyData;
		}
		$eachProduct = array_shift($each->productarray);

		// 计算折扣
		$discount = 0;
		if ($eachProduct->marketPrice > 0){
			$discount = round($eachProduct->groupPrice / $eachProduct->marketPrice, 2) * 10;
		}
		$AreaCode = 0;
		if ($data_city[$i]["cityId"] == 100){ // 北京
			$AreaCode = 110000;
		}
		$Quantity = 10000;
		if ($each->upperLimit > 0){ // 商品总数
			$Quantity = $each->upperLimit;
		}
		// tel
		$companyTel = $companyData["tel"];
		$companyTel = str_replace("<br>","",$companyTel);
		$companyTel = str_replace("<br/>","",$companyTel);
		// name
		$companyName = $companyData["name"];
		$companyName = str_replace("<br>","",$companyName);
		$companyName = str_replace("<br/>","",$companyName);
		// website
		$companyWebsite = $companyData["website"];
		$companyWebsite = str_replace("<br>","",$companyWebsite);
		$companyWebsite = str_replace("<br/>","",$companyWebsite);
		// linkman
		$companyLinkman = $companyData["linkman"];
		$companyLinkman = str_replace("<br>","",$companyLinkman);
		$companyLinkman = str_replace("<br/>","",$companyLinkman);
		// address
		$companyAddress = $companyData["address"];
		$companyAddress = str_replace("<br>","",$companyAddress);
		$companyAddress = str_replace("<br/>","",$companyAddress);
		
		$xml .="<Activity>
			<Title><![CDATA[".$each->title."]]></Title>
			<Url>".SW_URL_HOME.$data_city[$i]["eName"]."/".CMS_DETAIL."/".$each->fileName.".html</Url>
			<Description><![CDATA[".$each->description."]]></Description>
			<ImageUrl>".SW_URL_UPROOT.$each->mainPic."</ImageUrl>
			<CityName>".$data_city[$i]["cityName"]."</CityName>
			<AreaCode>".$AreaCode."</AreaCode>
			<Value>".$eachProduct->marketPrice."</Value>
			<Price>".$eachProduct->groupPrice."</Price>
			<ReBate>".$discount."</ReBate>
			<StartTime>".date("YmdHis",$each->beginTime)."</StartTime>
			<EndTime>".date("YmdHis",$each->endTime)."</EndTime>
			<Quantity>".$Quantity."</Quantity>
			<Bought>".($each->sellNum + 0 + $showSellNum[$each->groupId] + $each->maskNum)."</Bought>
			<MinBought>".$each->lowerLimit."</MinBought>
			<BoughtLimit>".$each->upperBuyNum."</BoughtLimit>
			<Goods>
				<Name><![CDATA[".$eachProduct->productName."]]></Name>
				<ProviderName><![CDATA[".$companyName."]]></ProviderName>
				<ProviderUrl><![CDATA[".$companyWebsite."]]></ProviderUrl>
				<ImageUrlSet>".SW_URL_UPROOT.$each->mainPic."</ImageUrlSet>
				<Contact><![CDATA[".$companyLinkman["linkman"]."]]></Contact>
				<Telephone><![CDATA[".$companyTel."]]></Telephone>
				<Address><![CDATA[".$companyAddress."]]></Address>
				<Map></Map>
				<Description><![CDATA[".$each->description."]]></Description>
			</Goods> 
		</Activity>";
	}
}
$xml .= "</ActivitySet>";
// http://t123.sohu.com/site.html
file_put_contents(DOC_ROOT."xml/t123.xml", $xml);
echo date("Y-m-d H:i:s")." t123.xml\n";
// }}}


// 2.tuan123/tuanp{{{
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><urlset>";
for($i = 0,$cc = count($data_city);$i < $cc;$i++) {
	$groupIds = getSchedule($data_city[$i]["cityId"], $showDate);
	if ( empty($groupIds) ) continue;
	$data_group = Group :: loadGroupByGroupIds($groupIds, 0);
	foreach($data_group as $each){
		// 查询商户信息
		$companyData = $finalCompany[$each->companyId];
		if (empty($companyData)){
			$companyData = Company :: loadCompany($each->companyId);
			$finalCompany[$each->companyId] = $companyData;
		}
		
		$eachProduct = array_shift($each->productarray);
		// 计算折扣
		$discount = 0;
		if ($eachProduct->marketPrice > 0){
			$discount = round($eachProduct->groupPrice / $eachProduct->marketPrice, 2) * 10;
		}
		$companyTel = $companyData["tel"];
		$companyTel = str_replace("<br>","",$companyTel);
		$companyTel = str_replace("<br/>","",$companyTel);
		$companyName = $companyData["name"];
		$companyName = str_replace("<br>","",$companyName);
		$companyName = str_replace("<br/>","",$companyName);
		// address
		$companyAddress = $companyData["address"];
		$companyAddress = str_replace("<br>","",$companyAddress);
		$companyAddress = str_replace("<br/>","",$companyAddress);
		$xml .="<url>
		<loc>".SW_URL_HOME.$data_city[$i]["eName"]."/".CMS_DETAIL."/".$each->fileName.".html</loc>
			<data>
				<display> 
					<website>好买都市网</website>
					<siteurl>".SW_URL_HOME."</siteurl>
					<city>".$data_city[$i]["cityName"]."</city>
					<title><![CDATA[".$each->title."]]></title>
					<image>".SW_URL_UPROOT.$each->mainPic."</image>
					<startTime>".$each->beginTime."</startTime>
					<endTime>".$each->endTime."</endTime>
					<value>".$eachProduct->marketPrice."</value>
					<price>".$eachProduct->groupPrice."</price>
					<description><![CDATA[".$each->description."]]></description>
					<bought>".($each->sellNum + 0 + $showSellNum[$each->groupId] + $each->maskNum)."</bought>
					<merchantName><![CDATA[".$companyName."]]></merchantName>
					<merchantPhone><![CDATA[".$companyTel."]]></merchantPhone>
					<merchantAddr><![CDATA[".$companyAddress."]]></merchantAddr>
					<detail><![CDATA[".$each->description."]]></detail>
				</display>
			</data>
		</url>";
	}
}
$xml .= "</urlset>";
// http://www.tuan123.net
file_put_contents(DOC_ROOT."xml/tuan123.xml", $xml);
echo date("Y-m-d H:i:s")." tuan123.xml\n";
// http://www.tuanp.com
file_put_contents(DOC_ROOT."xml/tuanp.xml", $xml);
echo date("Y-m-d H:i:s")." tuanp.xml\n";
// }}}

// 3.tuan800{{{
// tuan800分类说明
$tuan800TagArray = array(
	1=>"美食",
	2=>"娱乐",
	3=>"邮购",
	4=>"生活",
	5=>"健身运动",
	6=>"日常服务",
	7=>"美容",
	8=>"美发",
	9=>"票务",
	10=>"购物券卡",
	11=>"其它"
);
// 与好买标签转换 
// 好买标签ID => tuan800标签ID
// tuan800标签：从 美食,娱乐,邮购,生活,健身运动,日常服务,美容,美发,票务,购物券卡,其它 里选择，可多选
$haomy2tuan800 = array(
	1=>"邮购", // 杂志报刊=>邮购
	2=>"娱乐", // 休闲娱乐=>娱乐
	3=>"生活", // 保健=>生活
	4=>"美食", // 餐饮=>美食
	5=>"美容,美发", // 美容美发=>美发
	6=>"生活", // 摄影摄像=>生活
	7=>"娱乐", // 夜店酒吧=>娱乐
	8=>"票务", // 活动=>票务
	9=>"生活", // 教育=>生活
	10=>"生活", // 汽车=>生活
	11=>"生活", // 旅游=>生活
	12=>"生活", // 宾馆酒店=>生活
	13=>"娱乐", // 演出=>娱乐
	14=>"生活", // 数码=>生活
	15=>"生活",	// 通信=>生活
	16=>"邮购"  // 邮购=>邮购
);
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><urlset>";
for($i = 0,$cc = count($data_city);$i < $cc;$i++) {
	$groupIds = getSchedule($data_city[$i]["cityId"], $showDate);
	if ( empty($groupIds) ) continue;
	$data_group = Group :: loadGroupByGroupIds($groupIds, 0);
	foreach($data_group as $each){
		// 查询商户信息
		$companyData = $finalCompany[$each->companyId];
		if (empty($companyData)){
			$companyData = Company :: loadCompany($each->companyId);
			$finalCompany[$each->companyId] = $companyData;
		}
		$tagIds = $companyData["tagIds"];
		$tagArray = explode(",", $tagIds);
		$temp = array();
		for ($t = 0,$tcc = count($tagArray);$t < $tcc;$t++){
			$temp[] = $haomy2tuan800[$tagArray[$t]];
		}
		$tagStr = implode(",", $temp);
		
		$companyTel = $companyData["tel"];
		$companyTel = str_replace("<br>","",$companyTel);
		$companyTel = str_replace("<br/>","",$companyTel);
		$companyName = $companyData["name"];
		$companyName = str_replace("<br>","",$companyName);
		$companyName = str_replace("<br/>","",$companyName);
		// address
		$companyAddress = $companyData["address"];
		$companyAddress = str_replace("<br>","",$companyAddress);
		$companyAddress = str_replace("<br/>","",$companyAddress);
		
		$eachProduct = array_shift($each->productarray);
		// 计算折扣
		$discount = 0;
		if ($eachProduct->marketPrice > 0){
			$discount = round($eachProduct->groupPrice / $eachProduct->marketPrice, 2) * 10;
		}
		$maxQuota = "";
		if ($each->upperLimit > 0){
			$maxQuota = "<maxQuota>".$each->upperLimit."</maxQuota>";
		}
		$minQuota = "";
		if ($each->lowerLimit > 0){
			$minQuota = "<minQuota>".$each->lowerLimit."</minQuota>";
		}
		if ($eachProduct->isLogistics){
			$post = "<post>yes</post>";
		}else{
			$post = "<post>no</post>";
		}
			
		$xml .="<url><loc>".SW_URL_HOME.$data_city[$i]["eName"]."/".CMS_DETAIL."/".$each->fileName.".html</loc>
			<data>
				<display>
					<website>好买都市网</website>
					<identifier>".$each->groupId."</identifier>
					<siteurl>".SW_URL_HOME."</siteurl>
					<city>".$data_city[$i]["cityName"]."</city>
					<title><![CDATA[".$each->title."]]></title>
					<image>".SW_URL_UPROOT.$each->mainPic."</image>
					<tag>".$tagStr."</tag>
					<startTime>".$each->beginTime."</startTime>
					<endTime>".$each->endTime."</endTime>
					<value>".$eachProduct->marketPrice."</value>
					<price>".$eachProduct->groupPrice."</price>
					<bought>".($each->sellNum + 0 + $showSellNum[$each->groupId] + $each->maskNum)."</bought>
					".$maxQuota."
					".$minQuota."
					".$post."
					<merchantEndTime>".$each->endTime."</merchantEndTime>
					<tip></tip>
					<detail><![CDATA[".$each->description."]]></detail>
				</display>
				<shops>  
					<shop>  
						<name><![CDATA[".$companyName."]]></name>
						<tel><![CDATA[".$companyTel."]]></tel>
						<addr><![CDATA[".$companyAddress."]]></addr>
						<area></area>
						<longitude></longitude>
						<latitude></latitude>
					</shop>
				</shops> 
			</data>
		</url>";
	}
}
$xml .= "</urlset>";
// http://www.tuan800.com
file_put_contents(DOC_ROOT."xml/tuan800.xml", $xml);
echo date("Y-m-d H:i:s")." tuan800.xml\n";
// http://www.122.net
file_put_contents(DOC_ROOT."xml/122.xml", $xml);
echo date("Y-m-d H:i:s")." 122.xml\n";
//}}}

// 4.http://www.tuannaer.com {{{
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?><deals>";
for($i = 0,$cc = count($data_city);$i < $cc;$i++) {
	$groupIds = getSchedule($data_city[$i]["cityId"], $showDate);
	if ( empty($groupIds) ) continue;
	$data_group = Group :: loadGroupByGroupIds($groupIds, 0);
	foreach($data_group as $each){
		$eachProduct = array_shift($each->productarray);
		// 计算折扣
		$discount = 0;
		if ($eachProduct->marketPrice > 0){
			$discount = round($eachProduct->groupPrice / $eachProduct->marketPrice, 2) * 10;
		}
		if (!empty($each->description)){
			$detail = $digest = $each->description;
		}else{
			$detail = $digest = $each->descriptionHtml;
		}
		$xml .="<deal><goods_url>".SW_URL_HOME.$data_city[$i]["eName"]."/".CMS_DETAIL."/".$each->fileName.".html</goods_url>
				<goods_info>
					<website>好买都市网</website>
					<siteurl>".SW_URL_HOME."</siteurl>
					<city>".$data_city[$i]["cityName"]."</city>
					<title><![CDATA[".$each->title."]]></title>
					<image>".SW_URL_UPROOT.$each->mainPic."</image>
					<startTime>".$each->beginTime."</startTime>
					<endTime>".$each->endTime."</endTime>
					<value>".$eachProduct->marketPrice."</value>
					<price>".$eachProduct->groupPrice."</price>
					<discount>".$discount."</discount>
					<bought>".($each->sellNum + 0 + $showSellNum[$each->groupId] + $each->maskNum)."</bought>
					<digest><![CDATA[".$digest."]]></digest>
					<detail><![CDATA[".$detail."]]></detail>
				</goods_info>
		</deal>";
	}
}
$xml .= "</deals>";
// http://www.tuannaer.com
file_put_contents(DOC_ROOT."xml/tuannaer.xml", $xml);
echo date("Y-m-d H:i:s")." tuannaer.xml\n";
//}}}

function getSchedule($cityId, $showDate) {
	$sql = " SELECT groupIds
			FROM html_schedule
			WHERE cityId = '".$cityId. "' 
			AND showDate = '".$showDate."' ";
	$dbw = Sw_Db_Wrapper::getInstance();
	$data = $dbw->getAllCached('product', $sql, 60);
	$idArray = array();
	for($i = 0,$cc = count($data);$i < $cc;$i++){
		$idArray[] = $data[$i]["groupIds"];
	}
	return implode(",",$idArray);
}
