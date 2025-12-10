function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	
			if(myAjax.responseXML.documentElement.getElementsByTagName("name0")[0].text!=undefined){
					document.getElementById('a0').value=myAjax.responseXML.documentElement.getElementsByTagName("name0")[0].text;
				}else{
					document.getElementById('a0').value=myAjax.responseXML.documentElement.getElementsByTagName("name0")[0].textContent;
										
				}
			for(var j=1;j<4;j++){
				if(myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text!=undefined){
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;
				}else{
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
										
				}
			}
			if(myAjax.responseXML.documentElement.getElementsByTagName("name5")[0].text!=undefined){
					permit(myAjax.responseXML.documentElement.getElementsByTagName("name5")[0].text);
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				}else{
					permit(myAjax.responseXML.documentElement.getElementsByTagName("name5")[0].textContent);
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);					
				}
	}else{
		//	
	}
}
function permit(name4){
		
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('a5');
	 newInput2.id='sang';
	 newInput2.style.width = '60px';
	 newInput2.className='selectbox';
	 newInput2.onchange=permitChange;
	 var opts=newInput2.options;
	opts.length=3;	
	 for(var i=0;i<opts.length;i++){
		opts[i].value=i;	
		if(opts[i].value==eval(name4)){	
			newInput2.selectedIndex=i;
		}	
				switch(i){
					case 0 :
						opts[i].text='=선택=';
					break;
					case 1 :
						opts[i].text='허용';
					break;
					case 2 :
						opts[i].text='차단';
					break;				
				}	
	 }
	 aJumin.appendChild(newInput2);
}
function smsReceive() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
			//self.document.getElementById("kor_str").value = myAjax.responseText;

		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			document.getElementById("a0").value=myAjax.responseXML.documentElement.getElementsByTagName("CostomerNum")[0].text;
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			document.getElementById("a4").value='';//저장또는 update 후 비밀번호 지운다
		}else{
			document.getElementById("a0").value=myAjax.responseXML.documentElement.getElementsByTagName("CostomerNum")[0].textContent;
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			document.getElementById("a4").value='';//저장또는 update 후 비밀번호 지운다
		
		}
	
	}else{
		//	
	}
}
function permitChange(){
	var permit=document.getElementById('sang').options[document.getElementById('sang').selectedIndex].value;
	var check=document.getElementById("check").value;
	var id=document.getElementById("a2").value;
	var DaeriCompanyNum=document.getElementById('num').value;
	var DaeriCompanyName=encodeURIComponent(document.getElementById('a1').value);
	var check=document.getElementById('check').value;
	var hphone=document.getElementById('a3').value;
	if(check==1){
		alert('아이디가 사용 불가능 다시 !!')
		document.getElementById("a2").focus();
		document.getElementById('sang').value=0;
		return false;
	}else{

		//아이디 처음 저장할때만 비빌번호 저장 할수 있고
		//아이디 수정일 때는 비밀번호가 수정 할 수 없다
		var CostomerNum=document.getElementById('a0').value;
		if(CostomerNum){
			var pass=document.getElementById('a4').value;
			//alert(pass);
			//if(pass!=''){
			var toSend = "./ajax/idStore.php?permit="+permit
						   +"&check="+check
						   +"&id="+id
						   +"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&DaeriCompanyName="+DaeriCompanyName
						   +"&hphone="+hphone
						   +"&CostomerNum="+CostomerNum
						   +"&pass="+pass;;
						  
				//alert(toSend)
				//self.document.getElementById("url").innerHTML = toSend;
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=smsReceive;
				myAjax.send('');
			//}else{

			//	alert('비빌번호를 넣으셔야지요!!');
			//	document.getElementById('a4').focus();
			//	return false;
			//}
		}else{
			var pass=document.getElementById('a4').value;
			//alert(pass);
			if(pass!=''){
			var toSend = "./ajax/idStore.php?permit="+permit
						   +"&check="+check
						   +"&id="+id
						   +"&DaeriCompanyNum="+DaeriCompanyNum
						   //+"&CostomerNum="+CostomerNum
						   +"&DaeriCompanyName="+DaeriCompanyName
						   +"&hphone="+hphone
						   +"&pass="+pass;
						  
				//alert(toSend)
				//self.document.getElementById("url").innerHTML = toSend;
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=smsReceive;
				myAjax.send('');
			}else{

				alert('비빌번호를 넣으셔야지요!!');
				document.getElementById('a4').focus();
				return false;
			}


		}
	}

}


	function receiveResponse() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert( myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			document.getElementById("check").value=myAjax.responseXML.documentElement.getElementsByTagName("check")[0].text;
			
			switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("check")[0].text)){
						
						case 1 : 
							
							document.getElementById("idcheck").style.color='red';
							break;
						case 2 :
							document.getElementById("idcheck").style.color='#666666';

							break;
						default :
							document.getElementById("idcheck").style.color='#cccccc';

							break;
					}

			document.getElementById("idcheck").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		}else{
			
			document.getElementById("check").value=myAjax.responseXML.documentElement.getElementsByTagName("check")[0].textContent;
			switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("check")[0].textContent)){
						
						case 1 : 
							
							document.getElementById("idcheck").style.color='red';
							break;
						case 2 :
							document.getElementById("idcheck").style.color='#666666';

							break;
						default :
							document.getElementById("idcheck").style.color='#cccccc';

							break;
					}


			document.getElementById("idcheck").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		}
	

	}else{
		//	
	}
}
function idcheck(){//id check

	myAjax=createAjax();
	
	//alert('1')
	var mem_id=document.getElementById('a2').value;
	
	
	if(mem_id){

		var toSend = "./ajax/idcheck.php?mem_id="+mem_id;
				  // +"&cord="+cord;
				  
		//alert(toSend)
		//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=receiveResponse;
		myAjax.send('');
	}
}
function serch(val){
	myAjax=createAjax();
	var num=document.getElementById("num").value;
	//alert(num);
	if(num){	
		var toSend = "./ajax/idSerch.php?num="+num;
	
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해