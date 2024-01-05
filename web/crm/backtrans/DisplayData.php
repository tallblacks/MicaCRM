<?
    require_once("../include/config.inc.php");
    require_once("../include/mysql.inc.php");
    require_once("../include/function.inc.php");
    require_once("../include/user.inc.php");
  
    $db=new cDatabase;
    $user=new user;

    //echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";

	$dataContent = "";
	
	$sql="select * from crmpicture";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$pictureid = $data['pictureid'];
        $orderid = $data['orderid'];
        $suborderid = $data['suborderid'];
        $productid = $data['productid'];
        $userid = $data['userid'];
        $name = $data['name'];
        $type = $data['type'];
        $status = $data['status'];
        $picture_url = $data['picture_url'];
        $spicture_url = $data['spicture_url'];
        $create_time = $data['create_time'];
        
        $dataContent .= $pictureid.",".$orderid.",".$suborderid.",".$productid.",".$userid.",".$name.",".$type.",".$status.",".$picture_url.",".$spicture_url.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmCrmpicture.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	
	/*Table: fpcrm.wechatlogdesc
	$sql="select * from wechatlogdesc";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$wechatlogdescid = $data['wechatlogdescid'];
        $wechatlogid = $data['wechatlogid'];
        $parametername = $data['parametername'];
        $parametervalue = $data['parametervalue'];
        $type = $data['type'];
        $status = $data['status'];
        $create_time = $data['create_time'];
        
        $dataContent .= $wechatlogdescid.",".$wechatlogid.",".$parametername.",".$parametervalue.",".$type.",".$status.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmWechatlogdesc.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.wechatlog
	$sql="select * from wechatlog";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$wechatlogid = $data['wechatlogid'];
        $tousername = $data['tousername'];
        $fromusername = $data['fromusername'];
        $createtime = $data['createtime'];
        $msgtype = $data['msgtype'];
        $msgid = $data['msgid'];
        $status = $data['status'];
        $type = $data['type'];
        $create_time = $data['create_time'];
        
        $dataContent .= $wechatlogid.",".$tousername.",".$fromusername.",".$createtime.",".$msgtype.",".$msgid.",".$status.",".$type.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmWechatlog.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.combolog
	$sql="select * from combolog";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$combologid = $data['combologid'];
        $comborderid = $data['comborderid'];
        $orderid = $data['orderid'];
        $status = $data['status'];
        $create_time = $data['create_time'];
        
        $dataContent .= $combologid.",".$comborderid.",".$orderid.",".$status.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmCombolog.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.comborder
	$sql="select * from comborder";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$comborderid = $data['comborderid'];
        $name = $data['name'];
        $address = $data['address'];
        $mobile = $data['mobile'];
        $memo = $data['memo'];
        $logisticsid = $data['logisticsid'];
        $logisticscode = $data['logisticscode'];
        $type = $data['type'];
        $status = $data['status'];
        $create_time = $data['create_time'];
        
        $dataContent .= $comborderid.",".$name.",".$address.",".$mobile.",".$memo.",".$logisticsid.",".$logisticscode.",".$type.",".$status.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmComborder.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.profit
	$sql="select * from profit";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$profitid = $data['profitid'];
        $orderid = $data['orderid'];
        $profit = $data['profit'];
        $type = $data['type'];
        $create_time = $data['create_time'];
        
        $dataContent .= $profitid.",".$orderid.",".$profit.",".$type.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmProfit.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.suborder
	$sql="select * from suborder";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$suborderid = $data['suborderid'];
        $orderid = $data['orderid'];
        $productid = $data['productid'];
        $price = $data['price'];
        $ordernum = $data['ordernum'];
        $weight = $data['weight'];
        $supermarket = $data['supermarket'];
        $type = $data['type'];
        $status = $data['status'];
        $create_time = $data['create_time'];
        
        $dataContent .= $suborderid.",".$orderid.",".$productid.",".$price.",".$ordernum.",".$weight.",".$supermarket.",".$type.",".$status.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmSuborder.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.crmorder
	$sql="select * from crmorder";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$orderid = $data['orderid'];
        $idcode = $data['idcode'];
        $operatorid = $data['operatorid'];
        $consumerid = $data['consumerid'];
        $rate = $data['rate'];
        $weight = $data['weight'];
        $freight = $data['freight'];
        $pfreight = $data['pfreight'];
        $price = $data['price'];
        $pprice = $data['pprice'];
        $spprice = $data['spprice'];
        $logisticsid = $data['logisticsid'];
        $logisticscode = $data['logisticscode'];
        $discount = $data['discount'];
        $cfreight = $data['cfreight'];
        $cpfreight = $data['cpfreight'];
        $paystatus = $data['paystatus'];
        $memo = $data['memo'];
        $type = $data['type'];
        $status = $data['status'];
        $create_time = $data['create_time'];
        
        $logisticscode = str_replace("\n", " ", $logisticscode);
        $logisticscode = str_replace("\r", " ", $logisticscode);
        $memo = str_replace("\n", " ", $memo);
        $memo = str_replace("\r", " ", $memo);
        
        $dataContent .= $orderid.",".$idcode.",".$operatorid.",".$consumerid.",".$rate.",".$weight.",".$freight.",".$pfreight.",".$price.",".$pprice.",".$spprice.",".$logisticsid.",".$logisticscode.",".$discount.",".$cfreight.",".$cpfreight.",".$paystatus.",".$memo.",".$type.",".$status.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmCrmorder.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.consumer
	$sql="select * from consumer";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$consumerid = $data['consumerid'];
        $name = $data['name'];
        $address = $data['address'];
        $memo = $data['memo'];
        $mobile = $data['mobile'];
        $telephone = $data['telephone'];
        $wechat = $data['wechat'];
        $agentid = $data['agentid'];
        $xiaomid = $data['xiaomid'];
        $type = $data['type'];
        $status = $data['status'];
        $create_time = $data['create_time'];
        
        $dataContent .= $consumerid.",".$name.",".$address.",".$memo.",".$mobile.",".$telephone.",".$wechat.",".$agentid.",".$xiaomid.",".$type.",".$status.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmConsumer.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.catalog
	$sql="select * from catalog";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$catalogid = $data['catalogid'];
        $name = $data['name'];
        
        $dataContent .= $catalogid.",".$name."\n";
    }
    
	$fp = fopen('fpcrmCatalog.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
	
	/*Table: fpcrm.logistics
	$sql="select * from logistics";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$logisticsid = $data['logisticsid'];
        $name = $data['name'];
        $description = $data['description'];
        $preurl = $data['create_time'];
        $type = $data['type'];
        $create_time = $data['create_time'];
        
        $dataContent .= $logisticsid.",".$name.",".$description.",".$preurl.",".$type.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmLogistics.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/

	/*Table: fpcrm.constants
	$sql="select * from constants";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$constantsid = $data['constantsid'];
        $constants = $data['constants'];
        $type = $data['type'];
        $create_time = $data['create_time'];
        
        $dataContent .= $constantsid.",".$constants.",".$type.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmConstants.csv', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/

	/*Table: fpcrm.product
    $sql="select * from product";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$productid = $data['productid'];
        $catalogid = $data['catalogid'];
        $brandid = $data['brandid'];
        $ename = $data['ename'];
        $cname = $data['cname'];
        $spec = $data['spec'];
        $unit = $data['unit'];
        $unitnum = $data['unitnum'];
        $ppricenz = $data['ppricenz'];
        $spricecn = $data['spricecn'];
        $cwholesale = $data['cwholesale'];
        $nwholesale = $data['nwholesale'];
        $swholesale = $data['swholesale'];
        $bwholesale = $data['bwholesale'];
        $weight = $data['weight'];
        $isinventory = $data['isinventory'];
        $inventory = $data['inventory'];
        $supermarket = $data['supermarket'];
        $agentid = $data['agentid'];
        $status = $data['status'];
        $create_time = $data['create_time'];
        //echo $productid."-".$catalogid."-".$brandid."-".$ename."-".$cname."-".$spec."-".$unit."-".$unitnum."-".$ppricenz."-".$spricecn."-".$cwholesale."-".$nwholesale."-".$swholesale."-".$bwholesale."-".$weight."-".$isinventory."-".$inventory."-".$supermarket."-".$agentid."-".$status."-".$create_time."\n";
        
        $dataContent .= $productid.",".$catalogid.",".$brandid.",".$ename.",".$cname.",".$spec.",".$unit.",".$unitnum.",".$ppricenz.",".$spricecn.",".$cwholesale.",".$nwholesale.",".$swholesale.",".$bwholesale.",".$weight.",".$isinventory.",".$inventory.",".$supermarket.",".$agentid.",".$status.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmProduct.txt', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
    */
    
    /*Table: fpcrm.user
    $sql="select * from user";
    $query=$db->query($sql);
    while($data=$db->fetch_array($query)){
		$userid=$data['userid'];
        $realname=$data['realname'];
        $username=$data['username'];
        $password=$data['password'];
        $mobile=$data['mobile'];
        $email=$data['email'];
        $type=$data['type'];
        $create_time=$data['create_time'];
        echo $userid."-".$realname."-".$username."-".$password."-".$mobile."-".$email."-".$type."-".$create_time."\n";
        
        $dataContent .= $userid.",".$realname.",".$username.",".$password.",".$mobile.",".$email.",".$type.",".$create_time."\n";
    }
    
	$fp = fopen('fpcrmUser.txt', 'w');
	fwrite($fp, $dataContent);
	fclose($fp);
	*/
?>