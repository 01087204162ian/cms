//***********************************/
/*문자 보내는 관련 js함수들 *********/
/************************************/
function updateChar(length_limit){

	var length = calculate_msglen(document.getElementById('comment').value);
	document.getElementById('textlimit').innerHTML = length;
	if(length > length_limit){
		alert("최대"+length_limit+"byte이므로 초과된 글자수는 자동으로 삭제됩니다.");
		document.getElementById('comment').value = document.getElementById('comment').value(/\r\n$/, "");
		document.getElementById('comment').value= assert_msglen(form.comment.value, length_limit);
	}
}
function calculate_msglen(message){
	var nbytes = 0;

	for(i=0;i<message.length;i++){
		var ch=message.charAt(i);
		if(escape(ch).length>4){
			nbytes += 2;
		}else if(ch=='\n'){
			if(message.charAt(i-1)!='\r'){
				nbytes += 1;
			}
		}else if(ch=='<'||ch=='>'){
			nbytes += 4;
		}else{
			nbytes += 1;
		}
	}

	return nbytes;
}

function buncheck(val){
	var bunho='bun'+val;
	var telbunho=document.getElementById(bunho).value;
	if(telbunho.length<10 && telbunho.length>0 ){
		alert('번호를 확인 하세요!!11')
		return false;
	}else if(telbunho.length==10){
		var phone_first		=telbunho.substring(0,3);
		var phone_second    =telbunho.substring(3,6);
		var phone_third	 	=telbunho.substring(6,10);
		document.getElementById(bunho).value=phone_first+"-"+phone_second+"-"+phone_third;
	}else if(telbunho.length==11){
		var phone_first		=telbunho.substring(0,3);
		var phone_second    =telbunho.substring(3,7);
		var phone_third	 	=telbunho.substring(7,11);
		document.getElementById(bunho).value=phone_first+"-"+phone_second+"-"+phone_third;
	}else if(telbunho.length>11) {

		alert('번호를 확인 하세요!!')
		return false;
	}



}
function buncheck_2(val){
	var bunho='bun'+val;
	var telbunho=document.getElementById(bunho).value;
	if(telbunho.length<10 && telbunho.length>0){
		alert('번호를 확인 하세요!!11')
		return false;
	}else if(telbunho.length==12){
		var phone_first		=telbunho.substring(0,3);
		var phone_second    =telbunho.substring(4,7);
		var phone_third	 	=telbunho.substring(8,12);
		document.getElementById(bunho).value=phone_first+""+phone_second+""+phone_third;
	}else if(telbunho.length==13){
		var phone_first		=telbunho.substring(0,3);
		var phone_second    =telbunho.substring(4,8);
		var phone_third	 	=telbunho.substring(9,13);
		document.getElementById(bunho).value=phone_first+""+phone_second+""+phone_third;
	}else if(telbunho.length>13){
		alert('번호를 확인 하세요!!11')
		return false;
	}

}
function smsReceive() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
			//self.document.getElementById("kor_str").value = myAjax.responseText;

		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);

		
			}
	
		if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!=undefined){
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}
		
	}else{
		//	
	}
}
function smsGo(){
	
	myAjax=createAjax();
	var comm=encodeURIComponent(document.getElementById('comment').value);
	
	if(comm){
		var our=document.getElementById('our_phone').value;
		var telbun=new Array();
		var bun=new Array();
		for(var k=1;k<11;k++){
			var bunho='bun'+k;
			
			if(!document.getElementById(bunho).value){
				continue;
			}
			telbun[k]=document.getElementById(bunho).value;
			
		}
		var query='';
		for(var j=1;j<telbun.length;j++){
				query+="bun"+j;
				query+="=";
				query+=telbun[j];
				query+="&";
		}
		//displayLoading(self.document.getElementById("imgkor"));
		var toSend = "../sms/smsAjax.php?"+query
				   +"comment="+comm
				   +"&our_phone="+our;
		//alert(toSend);
		
		//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=smsReceive;
		myAjax.send('');
	}else{
		alert('보낼 내용이 없군요!!');
		document.getElementById('comment').focus();
		return false;

	}
}
function smsGo2(){
	
	myAjax=createAjax();
	var comm=encodeURIComponent(document.getElementById('comment').value);
	
	if(comm){
		var our=document.getElementById('our_phone').value;
		var telbun=new Array();
		var bun=new Array();
		for(var k=1;k<11;k++){
			var bunho='bun'+k;
			
			if(!document.getElementById(bunho).value){
				continue;
			}
			telbun[k]=document.getElementById(bunho).value;
			
		}
		var query='';
		for(var j=1;j<telbun.length;j++){
				query+="bun"+j;
				query+="=";
				query+=telbun[j];
				query+="&";
		}
		//displayLoading(self.document.getElementById("imgkor"));
		var toSend = "../../sms/smsAjax.php?"+query
				   +"comment="+comm
				   +"&our_phone="+our;
		//alert(toSend);
		
		//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=smsReceive;
		myAjax.send('');
	}else{
		alert('보낼 내용이 없군요!!');
		document.getElementById('comment').focus();
		return false;

	}
}
/*******************************************************************************/
/* 본문에서 핸드폰으로 문자 보내기
/*******************************************************************************/
function bonagi_3(){//pop_up에서 사용
	
	var nnId=this.getAttribute("id");
	if(nnId.length==7){
		nn=nnId.substr(6,7);
	}else{
		nn=nnId.substr(6,8);
	}
	
	if(document.getElementById('com_phone'+nn).value){
		if(document.getElementById(nnId).checked==true){
			
			for(var i=1;i<11;i++){
				if(!document.getElementById('bun'+i).value){
					document.getElementById('bun'+i).value=document.getElementById('com_phone'+nn).value;
					document.getElementById('comment').value=document.getElementById('ounname'+nn).innerHTML+
						'님';
					break;
				}
			}
		}else{
			for(var i=1;i<11;i++){
				if(document.getElementById('com_phone'+nn).value==document.getElementById('bun'+i).value){
					document.getElementById('bun'+i).value='';
					document.getElementById('comment').value='';
					break;
				}
			}
		}
	}else{

		alert('핸드폰 번호가 있을 때만 가능합니다!!');
		return false;
	}

}
/*******************************************************************************/
/* 본문에서 핸드폰으로 문자 보내기
/*******************************************************************************/
function bonagi(){
	
	var nnId=this.getAttribute("id");
	if(nnId.length==7){
		nn=nnId.substr(6,7);
	}else{
		nn=nnId.substr(6,8);
	}
	
	if(document.getElementById('com_phone'+nn).innerHTML){
		if(document.getElementById(nnId).checked==true){
			
			for(var i=1;i<11;i++){
				if(!document.getElementById('bun'+i).value){
					document.getElementById('bun'+i).value=document.getElementById('com_phone'+nn).innerHTML;
					
					break;
				}
			}
		}else{
			for(var i=1;i<11;i++){
				if(document.getElementById('com_phone'+nn).innerHTML==document.getElementById('bun'+i).value){
					document.getElementById('bun'+i).value='';
					break;
				}
			}
		}
	}else{

		alert('핸드폰 번호가 있을 때만 가능합니다!!');
		return false;
	}

}
/*******************************************************************************/
/* 본문에서 핸드폰으로 문자 보내기 동부화재에서 번호가 히드피일드 일때
/*******************************************************************************/
function bonagi_2(){
	var nnId=this.getAttribute("id");
	if(nnId.length==7){
		nn=nnId.substr(6,7);
	}else{
		nn=nnId.substr(6,8);
	}
	if(document.getElementById('com_phone'+nn).value){
		if(document.getElementById(nnId).checked==true){
			
			for(var i=1;i<11;i++){
				if(!document.getElementById('bun'+i).value){
					
					document.getElementById('bun'+i).value=document.getElementById('com_phone'+nn).value;
					switch(eval(document.getElementById('nai'+nn).value)){
						case 1 :

							var pre='32,290';
							break;
						case 2 :

							var pre='28,590';
							break;
						case 3:
							var pre='32,290';
							break;
					}
					
					document.getElementById('comment').value=document.getElementById('name'+nn).innerHTML+
						'님 동부화재  국민은행 보험료'+pre;
					break;
				}
			}
		}else{
			for(var i=1;i<11;i++){
				if(document.getElementById('com_phone'+nn).value==document.getElementById('bun'+i).value){
					document.getElementById('bun'+i).value='';
					document.getElementById('comment').value='';
					break;
				}
			}
		}
	}else{

		alert('핸드폰 번호가 있을 때만 가능합니다!!');
		return false;
	}

}
function our_check(){

	var phone=document.getElementById('our_phone').value;
	//var bar=phone.substring(3,4);
	//var bar2=phone.substring(2,3);

	switch(phone.length){

		case 9 :

			var bar=phone.substring(2,3);
			break;
		default :

			var bar=phone.substring(3,4);
			break;


	}

	//alert(phone.length);
	if(phone.length>=1 && bar!='-'){
		switch(phone.length){
			case 9:
				var phone_first		=phone.substring(0,2);
				var phone_second    =phone.substring(2,5);
				var phone_third	 	=phone.substring(5,9);
				document.getElementById('our_phone').value=phone_first+"-"+phone_second+"-"+phone_third;
				//document.getElementById('oun6').focus();
				break;
			case 10:
				var phone_first		=phone.substring(0,3);
				var phone_second    =phone.substring(3,6);
				var phone_third	 	=phone.substring(6,10);
				document.getElementById('our_phone').value=phone_first+"-"+phone_second+"-"+phone_third;
				//document.getElementById('oun6').focus();
				break;
			case 11 :
				var phone_first		=phone.substring(0,3);
				var phone_second    =phone.substring(3,7);
				var phone_third	 	=phone.substring(7,11);
				document.getElementById('our_phone').value=phone_first+"-"+phone_second+"-"+phone_third;
				//document.getElementById('oun6').focus();
			break;

			default:
				alert('전화번호 확인하세요!!');
				document.getElementById('our_phone').focus();
				return false;
			break;
		}
	}
}


function our_check_2(){
	var phone=document.getElementById('our_phone').value;
	switch(phone.length){

			case 9 :

				var bar=phone.substring(2,3);
				break;
			default :

				var bar=phone.substring(3,4);
				break;


		}
	if(bar=='-'){
	
		switch(phone.length){
			case 11:
				var phone_first		=phone.substring(0,2);
				var phone_second    =phone.substring(3,6);
				var phone_third	 	=phone.substring(7,11);
				document.getElementById('our_phone').value=phone_first+phone_second+phone_third;
				//document.getElementById('oun6').focus();
				break;
			case 12:
				var phone_first		=phone.substring(0,3);
				var phone_second    =phone.substring(4,7);
				var phone_third	 	=phone.substring(8,12);
				document.getElementById('our_phone').value=phone_first+phone_second+phone_third;
				//document.getElementById('oun6').focus();
				break;
			case 13 :
				var phone_first		=phone.substring(0,3);
				var phone_second    =phone.substring(4,8);
				var phone_third	 	=phone.substring(9,13);
				document.getElementById('our_phone').value=phone_first+phone_second+phone_third;
				//document.getElementById('oun6').focus();
			break;
		}

	}

}
function clearText(thefield){
	if(thefield.defaultValue==thefield.value){
		thefield.value="";
	}
}