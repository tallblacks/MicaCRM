function getQuery(names){
	var querystr = window.location.href.split("?");
	if(querystr[1]){
		var tmp_arr, key;
		var GETs = querystr[1].split("&");
		var GET = new Array();
		for(i = 0; i < GETs.length; i++){
			tmp_arr = GETs[i].split("=");
			key = tmp_arr[0]
			if (key == names){
				  return tmp_arr[1];
			}
		}
	}
	return "";
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