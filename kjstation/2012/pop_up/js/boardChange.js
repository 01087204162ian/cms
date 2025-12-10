function companyReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				//document.getElementById("test").value=myAjax.responseXML.documentElement.getElementsByTagName("certiNum")[0].text
				
				//document.getElementById("oldDay").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[0].text;
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					
			}
			
	}else{
		//	
	}


}

function change()
{
	myAjax=createAjax();
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var eNum=document.getElementById("eNum").value;
	var endorseDay=document.getElementById("endorseDay").value;
	if(confirm('배서기준일 변경합니다 !!')){	
		var toSend = "../ajax/boardAjaxEndorseDayChange.php?endorseDay="+endorseDay
												 +"&CertiTableNum="+CertiTableNum
												 +"&eNum="+eNum;
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}

}

function sReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
			if(myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].text!=undefined){
				document.getElementById('a1').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].text;
				document.getElementById('oldDay').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorse_day")[0].text;
				document.getElementById('endorseDay').value=myAjax.responseXML.documentElement.getElementsByTagName("endorse_day2")[0].text;
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			}else{
				document.getElementById('a1').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].textContent;
				document.getElementById('oldDay').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorse_day")[0].textContent;
				document.getElementById('endorseDay').value=myAjax.responseXML.documentElement.getElementsByTagName("endorse_day")[0].textContent;
				
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			}

			
	}else{
		//	
	}
}
function serch(val){
	myAjax=createAjax();
	var num=document.getElementById("num").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var eNum=document.getElementById("eNum").value;

	//alert(document.getElementById("oldEndorseDay").innerHTML);

	if(num){	
		var toSend = "../ajax/boardEndorseAjaxSerch.php?num="+num
												 +"&CertiTableNum="+CertiTableNum
												 +"&eNum="+eNum;
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=sReceive;
		myAjax.send('');
	}
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해