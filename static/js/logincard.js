function initLogin(){
	var c = parseCookie("UNIQ_IHAOMY");
	var htmlstr = "";
	if (c){
		htmlstr = "<span>欢迎您，"+ c.nickName +"</span><span onclick='window.location=\""+SW_URL_HOME +"buy/coupons.php\";' style='cursor:pointer;'> 我的好买</span><span  style='cursor:pointer;' onclick='window.location=\""+SW_URL_HOME + "logout.php\";'>退出</span>";
		$(".navbox_right").html(htmlstr);
	}else{
		htmlstr = "<span onclick='window.location=\""+SW_URL_HOME +"account/login.html\";' style='cursor:pointer;'>登陆</span><span onclick='window.location=\""+SW_URL_HOME +"account/signup.html\";' style='cursor:pointer;'> 注册</span>";
		$(".navbox_right").html(htmlstr);
	}
}
function hideTips(){
	$('#div_howtobuy').slideUp("slow");
}
function setWeather(html){
	var w = html.split("|");
	$(".red").html(w[0]);
	$(".blue").html(w[1]);
	$("#img_weather").attr("src", SW_URL_IMG + "w/" + w[2]); 
	$("#img_weather").attr("title", w[3]); 
}
function getWeather(){
	var cityId = getCookie("UNIQ_IHAOMY_CITYID");
	if (cityId == null)	cityId = "beijing";
	var html = getCookie("UNIQ_IHAOMY_WEATHER_"+cityId);
	if (html != null){
		setWeather(html);
	}else{
		var second = new Date().getSeconds();
		//var url = weatherUrl + "ajax/getWeather.php";
		var url = SW_URL_HOME + "ajax/getWeather.php";
		try{
			$.getJSON(
				url,
				{s:second},
				function(json){
					try{
						if (json.html.length > 0){
							setWeather(json.html);
						}
					} catch(e){}
				} 
			);  
		}catch(e){ alert(e); }
	}
}
function copyToClipboard(txt){
	if(window.clipboardData){ 
		window.clipboardData.clearData();        
		window.clipboardData.setData("Text", txt);
		var txt = window.clipboardData.getData("Text");   
		if  (txt.length > 0){
			alert("复制成功，你可以粘贴到QQ、MSN上或通过其他方式发给好友");
		}else{
			alert("被浏览器拒绝！"); 
		}
	}else if(navigator.userAgent.indexOf("Opera") != -1){        
		window.location = txt;        
	}else if (window.netscape){        
		try {        
               		netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");        
          	} catch (e) {        
               		alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");        
          	}        
		var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);        
		if (!clip) return;        
          	var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);        
          	if (!trans) return;        
          	trans.addDataFlavor('text/unicode');        
          	var str = new Object();        
          	var len = new Object();        
          	var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);        
         	var copytext = txt;        
          	str.data = copytext;        
          	trans.setTransferData("text/unicode",str,copytext.length*2);        
          	var clipid = Components.interfaces.nsIClipboard;        
          	if (!clip) return false;        
          	clip.setData(trans,null,clipid.kGlobalClipboard);        
          	alert("复制成功，你可以粘贴到QQ、MSN上或通过其他方式发给好友");  
	}        
}
//////////////////
initLogin();
getWeather();