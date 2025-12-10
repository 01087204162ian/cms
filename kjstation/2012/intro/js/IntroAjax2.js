function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	   	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		}else{
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		}

		
if(sumTotal>0){
				
				for(var k=0;k<myAjax.responseXML.documentElement.getElementsByTagName("a1").length;k++){

					if(myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text!=undefined){
						checkSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text;
						//월보험료 산출
					monthPreminum(k,myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].text);
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text;
						document.getElementById('A6a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text;
						document.getElementById('A7a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						sMs(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text);
						Bank(k,myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text);
						allInwon(k,myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text);
						//document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text;
						document.getElementById('pBankNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("pBankNum")[k].text;
						
						
					}else{
						checkSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						monthPreminum(k,document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent);
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent;
						document.getElementById('A6a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent;
						document.getElementById('A7a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						sMs(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent);
						Bank(k,myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].textContent);
						allInwon(k,myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent);
						document.getElementById('pBankNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("pBankNum")[k].textContent;
						


					}
					/*if(this_month[k].text!=undefined){
						switch(eval(this_month[k].text)){
							case 1 : 
								document.getElementById('month'+k).style.color='#666666';
								break;
							case 2 :
								document.getElementById('month'+k).style.color='#0A8FC1';
								break;
							case 3 :
								document.getElementById('month'+k).style.color='red';
								break;
							case 4 :
								document.getElementById('month'+k).style.color='red';
								break;
						}
					}else{
						switch( eval(this_month[k].textContent)){
							case 1 : 
								document.getElementById('month'+k).style.color='#666666';
								break;
							case 2 :
								document.getElementById('month'+k).style.color='#0A8FC16';
								break;
							case 3 :
								document.getElementById('month'+k).style.color='red';
								break;
							case 4 :
								document.getElementById('month'+k).style.color='red';
								break;
						}
					}*/
				}
		/*	if(totalM[0].text!=undefined){
				document.getElementById("tot").innerHTML=totalM[0].text;
			}else{
				document.getElementById("tot").innerHTML=totalM[0].textContent;
			}*/
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}

function serch(val){
	//alert('1')
	for(var k=0;k<20;k++){
		document.getElementById('bunho'+k).innerHTML='';
		document.getElementById('num'+k).value='';
		document.getElementById('A2a'+k).innerHTML='';
		document.getElementById('A3a'+k).innerHTML='';
		document.getElementById('A4a'+k).innerHTML='';
		document.getElementById('A5a'+k).innerHTML='';
		document.getElementById('A6a'+k).innerHTML='';
		document.getElementById('A7a'+k).innerHTML='';
		document.getElementById('A8a'+k).innerHTML='';
		document.getElementById('A9a'+k).innerHTML='';
		document.getElementById('A9a'+k).innerHTML='';
		document.getElementById('A10a'+k).innerHTML='';
		document.getElementById('pBankNum'+k).value='';


	}
	
		myAjax=createAjax();
		var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
		
		switch(val){
			case 1 :/* 다음*/
				var page=document.getElementById("page").value;
				page=eval(page)+1;
			break;
			case 2: /*이전*/
				var page=document.getElementById("page").value;
				page=eval(page)-1;
			break;
			default:/* 그냥 조회*/

				var page='';
			break;
		}
		var getDay=document.getElementById('getDay').options[document.getElementById('getDay').selectedIndex].value;
		var damdanja=document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		if(s_contents){

			document.getElementById('getDay').options[document.getElementById('getDay').selectedIndex].value='9999';
			document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value='9999';

		}
		var toSend = "./ajax/ajax2.php?sigi="+sigi
				   +"&end="+end
				   +"&getDay="+getDay
			       +"&damdanja="+damdanja
				   +"&s_contents="+s_contents
				   +"&page="+page;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해


function Bank(k,BankName){

	//은행명

	var bButton=document.getElementById('A9a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=BankName;
		 bButton.onclick=BankCheck;


}

function BankReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){	
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					
			}	
	}else{
		//	
	}


}
function BankCheck()
{
	myAjax=createAjax();
	if(this.id.length==4){
		var nn=this.id.substr(3,4);
	}else{
		var nn=this.id.substr(3,5);
	}

	var pBankNum=document.getElementById('pBankNum'+nn).value;

	
		var toSend = "./ajax/BankSerch.php?pBankNum="+pBankNum;
				//   +"&end="+end
				//   +"&s_contents="+s_contents
				//   +"&page="+page;
	alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=BankReceive;
	myAjax.send('');
}