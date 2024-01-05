<?php
  require_once("../include/config.inc.php");
  require_once("../include/mysql.inc.php");
  require_once("../include/function.inc.php");
  require_once("../include/user.inc.php");
  CheckCookies();

	//require_once("../include/config.inc.php");
	session_start();
	if (!isset($_SESSION["SESS_USERID"])){
		Header("Location:index.php");
		exit();
	}
	//require_once("../include/mysql.inc.php");
	require_once("../include/corderpeer.inc.php");
	require_once("../include/cconsumerpeer.inc.php");
    
    $orderid = trim($_GET["orderid"]);
    
    $db = new cDatabase;
    //mysql_query('set names utf8');
    $orderManager = new cOrderPeer;
    $order = $orderManager->getOrder($orderid);
    $logisticscode = $order->getLogisticscode();
    
    //Agent判断
    $consumerid = $order->getConsumerid();
    $consumerManager = new cConsumerPeer;
    $consumer = $consumerManager->getConsumer($consumerid);
    $consumerAgentid = $consumer->getAgentid();
    if($_SESSION['SESS_TYPE'] == AGENT && $consumerAgentid != $_SESSION['SESS_USERID']) exit();
  
    //判断日期，秘书
    $create_time = $order->getCreatetime();
    if($_SESSION['SESS_TYPE'] == SECRETARY){
      if(substr($create_time,0,4) == "2013") exit();
      if(substr($create_time,0,4) == "2014"){
        if(substr($create_time,5,2) == "1" || substr($create_time,5,2) == "2" || substr($create_time,5,2) == "3" || substr($create_time,5,2) == "4" || substr($create_time,5,2) == "5") exit();
        if(substr($create_time,5,2) == "6"){
          if(substr($create_time,8,2) == "01") exit();
        }
      }
    }
?>
<?php
  //$logisticscode = trim($_GET["logisticscode"]);
  //$fp = fopen('http://www.800bestex.com/idcode/image.jsp?8888','r');
?>
<!--body onload="document.getElementById('800bestex').submit(true);">
<form id="800bestex" name="800bestex" method="post" action="http://www.800bestex.com/track.do">
<input name='inputNumber' type='hidden' value='<?=$logisticscode;?>'>
<input name='checkcode' type='hidden' value='8888'>
<input name='checkinput' type='hidden' value='8888'>
</form>
</body-->
<meta charset="utf-8">
<body>
<script language="javascript" src="http://www.800bestex.com/js/checkinput.js"></script>
<form action="http://www.800bestex.com/Bill/Track" method="post" id="trackAction" onSubmit="return Validator.Validate(this,1);">
  <!--textarea rows="6" id="inputNumber" name="inputNumber" dataType="Custom" regexp="^[A-Za-z0-9,\s]{11,}$" msg="请输入正确的运单号" onfocus="showcheck(); this.onfocus=null";><?php echo $logisticscode;?></textarea><br/-->
  运单号：<?php echo $logisticscode;?>
  <div id="div_checkcode">
    <!--span style="color:#333;">验证码：</span><input name="checkcode" type="text" class="short" id="checkcode" maxlength="6" dataType="Require" msg="请输入验证码" />
    <img src="http://www.800bestex.com/idcode/image.jsp" name="checkinput" id="checkinput" /-->
    <input type="hidden" name="code" value=<?php echo $logisticscode;?>>
  </div>
  <input name="btn_track" type="submit" id="btn_track" value="查询"/>
</form>
</body>
