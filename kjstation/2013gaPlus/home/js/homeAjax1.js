function pageList(){/*페이지 버튼 만들기*/

	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
		var page=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text;
		var Total=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		var totalPage=myAjax.responseXML.documentElement.getElementsByTagName("totalpage")[0].text;
		document.getElementById("page").value=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text;
		var pageD=document.getElementById("sql3");
		/************************************************************************/
		/* 전체 페이지 1인 경우와 아닌경우
		/* 첫번재 페이지 인경와 마직막 페이지인 경우 그 가운데인 경우            */
		/*************************************************************************/
		if(totalPage==1){
			pageD.innerHTML=page+"/"+totalPage;
		}else{
			if(page==1){
				pageD.innerHTML=page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}else if(page==totalPage){
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage;
			}else{
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}
		}
	}else{

		var page=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].textContent;
		var Total=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		var totalPage=myAjax.responseXML.documentElement.getElementsByTagName("totalpage")[0].textContent;
		document.getElementById("page").value=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].textContent;
		var pageD=document.getElementById("sql3");
		if(totalPage==1){
			pageD.innerHTML=page+"/"+totalPage;
		}else{
			if(page==1){
				pageD.innerHTML=page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}else if(page==totalPage){
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"page";
			}else{
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}
		}
	}
}
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		
	   	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
			var last=myAjax.responseXML.documentElement.getElementsByTagName("last")[0].text;
		}else{
			pageList();	
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
			var last=myAjax.responseXML.documentElement.getElementsByTagName("last")[0].textContent;
		}
	if(sumTotal>0){	
			var bone=myAjax.responseXML.documentElement.getElementsByTagName("bone");
			var cName=myAjax.responseXML.documentElement.getElementsByTagName("msg");
			var hp=myAjax.responseXML.documentElement.getElementsByTagName("hphone");/*가입일*/
		
		
			for(var k=0;k<eval(last);k++){	
				if(bone[k].text!=undefined){
					
					document.getElementById('Msg'+k).innerHTML=cName[k].text;
					document.getElementById('bonaeDate'+k).innerHTML=bone[k].text;
					document.getElementById('ReceivePhone'+k).innerHTML=hp[k].text;
				}else{
					document.getElementById('bonaeDate'+k).innerHTML=bone[k].textContent;
					document.getElementById('Msg'+k).innerHTML=cName[k].textContent;
					document.getElementById('ReceivePhone'+k).innerHTML=hp[k].textContent;
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

			document.getElementById('bonaeDate'+k).innerHTML='';
			document.getElementById('Msg'+k).innerHTML='';
			document.getElementById('ReceivePhone'+k).innerHTML='';
					
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
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function phonecheck(){

	var phone=document.getElementById("s_contents").value;
	switch(phone.length){
		case 10 :
			var phone_first		=phone.substring(0,3);
			var phone_second    =phone.substring(3,6);
			var phone_third	 	=phone.substring(6,10);
			document.getElementById("s_contents").value=phone_first+"-"+phone_second+"-"+phone_third;

		break;

		case 11:
		
			var phone_first		=phone.substring(0,3);
			var phone_second    =phone.substring(3,7);
			var phone_third	 	=phone.substring(7,11);
			document.getElementById("s_contents").value=phone_first+"-"+phone_second+"-"+phone_third;

		break;

		default:
			alert('번호 확인 하세요11');
			document.getElementById("s_contents").focus();
			return false;

			break;
		}
}
function phonecheck_2(){
	
	var telbunho=document.getElementById("s_contents").value;
	if(telbunho.length){
		switch(telbunho.length){
			case 12:
			var phone_first		=telbunho.substring(0,3);
			var phone_second    =telbunho.substring(4,7);
			var phone_third	 	=telbunho.substring(8,12);
			document.getElementById("s_contents").value=phone_first+""+phone_second+""+phone_third;

			break;
			case 13 :
			var phone_first		=telbunho.substring(0,3);
			var phone_second    =telbunho.substring(4,8);
			var phone_third	 	=telbunho.substring(9,13);
			document.getElementById("s_contents").value=phone_first+""+phone_second+""+phone_third;
			break;
			default:
			alert('번호를 확인 하세요!!11')
			return false;
		}
	}
}


