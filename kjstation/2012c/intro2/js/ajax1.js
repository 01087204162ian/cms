
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//	alert(myAjax.responseText);
		
	   	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
			var count=myAjax.responseXML.documentElement.getElementsByTagName("count")[0].text;
		}else{
			pageList();	
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
			var count=myAjax.responseXML.documentElement.getElementsByTagName("count")[0].textContent;
		}
	if(sumTotal>0){	
			var bone=myAjax.responseXML.documentElement.getElementsByTagName("bone");
			var cName=myAjax.responseXML.documentElement.getElementsByTagName("msg");
			var hp=myAjax.responseXML.documentElement.getElementsByTagName("hphone");/*가입일*/
			//alert(count)
		
			if(count<20){
				count=count;
			}else{
				count=20;
			}

			for(var k=0;k<count;k++){	
				if(bone[k].text!=undefined){
					
					document.getElementById('M'+k).innerHTML=cName[k].text;
					document.getElementById('B'+k).innerHTML=bone[k].text;
					document.getElementById('R'+k).innerHTML=hp[k].text;
				}else{
					document.getElementById('B'+k).innerHTML=bone[k].textContent;
					document.getElementById('M'+k).innerHTML=cName[k].textContent;
					document.getElementById('R'+k).innerHTML=hp[k].textContent;
				}
		/*		if(cName[k].text!=undefined){
					;
				}else{
					
				}
				if(hp[k].text!=undefined){
					
				}else{
					
				}*/
				
			}
			
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}
function serch(val){
	
	for(var k=0;k<20;k++){

			document.getElementById('B'+k).innerHTML='';
			document.getElementById('M'+k).innerHTML='';
			document.getElementById('R'+k).innerHTML='';
					
	}
	//document.getElementById("tot").innerHTML='';
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

	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	//if(s_contents){
		var toSend = "./ajax/smsSerch.php?sigi="+sigi
				   +"&end="+end
				   +"&s_contents="+s_contents
				   +"&page="+page;

		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	//}
}

addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
