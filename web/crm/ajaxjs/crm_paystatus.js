var request;
var queryString;

function sendData(orderid,paystatus){
  setQueryString(orderid,paystatus);
  var url = "http://crm.nzshop.cn/ajaxserver/paystatus.php";
  //var url = "http://182.92.0.81/crm/ajaxserver/paystatus.php";
  httpRequest("POST",url,true);
}

function setQueryString(orderid,paystatus){
  queryString = "";
  /*var frm = document.forms[orderid];
  var numberElements = frm.elements.length;
  for(var i=0; i<numberElements; i++){
    if(i < numberElements-1){
      queryString += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value)+"&";
    }else{
      queryString += frm.elements[i].name+"="+encodeURIComponent(frm.elements[i].value);
    }
  }*/
  queryString = "orderid="+orderid+"&paystatus="+paystatus;
}

function httpRequest(reqType, url, asynch){
  request = new XMLHttpRequest();
  if(request){
    initReq(reqType, url, asynch);
  }else{
    alter("Your browser does not permit the use of all of this application's features!");
  }
}

function initReq(reqType, url, isAsynch){
  request.onreadystatechange = handleResponse;
  request.open(reqType, url, isAsynch);
  request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
  request.send(queryString);
}

function handleResponse(){
  if(request.readyState == 4){
    if(request.status == 200){
      var respText = request.responseText;
      //document.getElementById(respText).innerHTML = respText;
      document.getElementById(respText).innerHTML = "修改成功";
      //alert(request.responseText);
    }else{
      alert("A problem occurred with communicating between the XMLHttpRequest object and the server program.");
    }
  }
}
