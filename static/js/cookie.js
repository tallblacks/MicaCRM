function parseCookie(name){
	var cookieStr = getCookie(name);
	if (cookieStr == null){
		return;
	}
	var e = cookieStr.split("%26");
	var cookieObject = new Object(), s;
	for(var i = 0;i < e.length;i++){
		s = e[i].split("%3D");
		if(s.length > 0){
			cookieObject[s[0]] = s[1];
		}
	}
	return cookieObject;
}
function getCookie(name){
	var start = document.cookie.indexOf(name + "=" );
	var len = start + name.length + 1;
	if ((!start )&& (name != document.cookie.substring(0, name.length ))){
		return null;
	}
	if (start == -1 )return null;
	var end = document.cookie.indexOf(';', len );
	if (end == -1 )end = document.cookie.length;
	return decodeURI(document.cookie.substring(len, end ));
}
function setCookie(name, value, expires, path, domain, secure ){
	var today = new Date();
	today.setTime(today.getTime());
	if (expires ){
		expires = expires * 60 * 60;
	}

	var expires_date = new Date(today.getTime()+ (expires));

	document.cookie = name+'='+escape(value )+
	((expires )? ';expires='+expires_date.toGMTString(): '' )+ //expires.toGMTString()
	((path )? ';path=' + path : '' )+
	((domain )? ';domain=' + domain : '' )+
	((secure )? ';secure' : '' );
}
function deleteCookie(name, path, domain ){
	if (getCookie(name ))document.cookie = name + '=' +
	((path )? ';path=' + path : '')+
	((domain )? ';domain=' + domain : '' )+
	';expires=Thu, 01-Jan-1970 00:00:01 GMT';
} 