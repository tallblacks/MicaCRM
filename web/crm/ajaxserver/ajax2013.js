var formObj = null;
var formObjTyp = "";
var request = null;

window.onload = function(){
  var txtA = document.getElementById("needajax");
  //if(txtA != null){
    txtA.onblur = function{if(this.value){getInfo(this);}};
  //}
}

function getInfo(obj){
    alter("1");
  if(obj == null){
    return;
  }
  /*formObj = obj;
  formObjTyp = obj.tagName;
  if(formObjTyp == "input" || formObjTyp == "INPUT"){
    formObjTyp = formObjTyp + " " + formObj.type
  }
  formObjTyp = formObjTyp.toLowerCase();
  var url = "http://crm.nzshop.cn/ajaxserver/userserver.php?objtype="+encodeURIComponent(formObjTyp)+"&val="+(obj.value);*/
  var url = "http://crm.nzshop.cn/ajaxserver/userserver.php?val="+encodeURIComponent(obj.value);
  httpRequest("GET",url,true);
}

function handleResponse(){
  try{
    if(request.readyState == 4){
      if(request.status == 200){
        var resp.request.responseText;
        var func = new Function("return " + resp);
        var objt = func();
        alter(objt.Server_info);
      }
    }
  }
}

