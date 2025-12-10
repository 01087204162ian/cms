function DataCheck(id,val){

	if(eval(val.length)==8){
			var phone_first	=val.substring(0,4)
			var phone_second =val.substring(4,6)
			var phone_third	 =val.substring(6,8)
			document.getElementById(id).value=phone_first+"-"+phone_second+"-"+phone_third;
	}else if(eval(val.length)>0 && eval(val.length)<9){
			alert('번호 !!')

			document.getElementById(id).focus();
			document.getElementById(id).value='';
			return false;

	}

}

function DataCheck2(id,val){

	if(val.length=='10'){
			
			var phone_first	=val.substring(0,4)
			var phone_second=val.substring(5,7)
			var phone_third	 =val.substring(8,10)

			document.getElementById(id).value=phone_first+phone_second+phone_third;
	}
}

function CertiCheck(id,val){
	//alert(id);


	if(id.length==4){
		nn=id.substr(3,4);
	}else{
		nn=id.substr(3,5);
	}
	if(document.getElementById('B3b'+nn).value.length==10){

		//alert(document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value);
		var insuranceNum=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;
		switch(eval(insuranceNum)){
				case 1: //흥국

						document.getElementById(id).value=document.getElementById('B3b'+nn).value.substring(2,4)+'-7000'+val;
					break;
				case 2: 
					document.getElementById(id).value='';

					break;
				case 3: 
						document.getElementById(id).value=document.getElementById('B3b'+nn).value.substring(0,4)+'-'+val;

					break;
				case 4: 

						document.getElementById(id).value=document.getElementById('B3b'+nn).value.substring(0,4)+'-'+val;
					break;

		}
	}else{

		alert('보험시작일 부터 !!');
		
		document.getElementById(id).value='';
		document.getElementById('B3b'+nn).focus();
	}
}
function CertiCheck2(id,val){

		if(id.length==4){
			nn=id.substr(3,4);
		}else{
			nn=id.substr(3,5);
		}
		var insuranceNum=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;
		switch(eval(insuranceNum)){
			case 1: //흥국
					
					document.getElementById(id).value=val.substring(11,15);
				break;
			case 2: 


				break;
			case 3: 
					document.getElementById(id).value=document.getElementById('B3b'+nn).value.substring(0,4)+'-'+val;

				break;
			case 4: 

					document.getElementById(id).value=document.getElementById('B3b'+nn).value.substring(0,4)+'-'+val;
				break;

		}
		
}

function con_phone1_check(id,val){
	
	if(eval(val.length)==9){
			var phone_first	=val.substring(0,2)
			var phone_second =val.substring(2,5)
			var phone_third	 =val.substring(5,9)
			document.getElementById(id).value=phone_first+"-"+phone_second+"-"+phone_third;
	}else if(eval(val.length)==10){
    		var phone_first		=val.substring(0,3)
			var phone_second   =val.substring(3,6)
			var phone_third	 	=val.substring(6,10)

			document.getElementById(id).value=phone_first+"-"+phone_second+"-"+phone_third;

	}if(eval(val.length)==11){

			var phone_first		=val.substring(0,3)
			var phone_second   =val.substring(3,7)
			var phone_third	 	=val.substring(7,11)			
			document.getElementById(id).value=phone_first+"-"+phone_second+"-"+phone_third;

	}else if(eval(val.length)>0 && eval(val.length)<9){
			alert('번호 !!')

			document.getElementById(id).focus();
			document.getElementById(id).value='';
			return false;

	}
}


function con_phone1_check_2(id,val){
	
	    if(val.length=='11'){
			
			var phone_first	=val.substring(0,2)
			var phone_second=val.substring(3,6)
			var phone_third	 =val.substring(7,12)

			document.getElementById(id).value=phone_first+phone_second+phone_third;
			
	 }else if(val.length=='12'){
			
			var phone_first		=val.substring(0,3)
			var phone_second		=val.substring(4,7)
			var phone_third	 	=val.substring(8,13)

			document.getElementById(id).value=phone_first+phone_second+phone_third;

	 }else if(val.length=='13'){
			

			var phone_first		=val.substring(0,3)
			var phone_second	=val.substring(4,8)
			var phone_third	 	=val.substring(9,13)

			document.getElementById(id).value=phone_first+phone_second+phone_third;
		
	  }
	}

function jumiN_check(id,val){
	
	if(eval(val.length)==13){
			var phone_first	=val.substring(0,6)
			var phone_second =val.substring(6,13)
			document.getElementById(id).value=phone_first+"-"+phone_second;
			var jumin=phone_first+"-"+phone_second
			myAjax=createAjax();
			var toSend = "./ajax/popAjaxSerch.php?jumin="+jumin;
	
			alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=companyReceive;
			myAjax.send('');
	

	}else if(eval(val.length)>0 && eval(val.length)<13){
			alert('번호 !!')

			document.getElementById(id).focus();
			document.getElementById(id).value='';
			return false;

	}
}


function jumiN_check_2(id,val){
	
	    if(val.length=='14'){
			
			var phone_first	=val.substring(0,6)
			var phone_second=val.substring(7,14)
			

			document.getElementById(id).value=phone_first+phone_second;
	 }
	}

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
function smsReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

				//문자 메세지 새로 표시 되게 하기 위해 ...기본문자 clear 후 새문자 표시하기 위해
				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{
					document.getElementById('C1b'+_k).innerHTML='';
					document.getElementById('C2b'+_k).innerHTML='';
					document.getElementById('C3b'+_k).innerHTML='';
					document.getElementById('C4b'+_k).innerHTML='';
					document.getElementById('C5b'+_k).innerHTML='';
					
							document.getElementById('C1b'+_k).style.color='';
							document.getElementById('C2b'+_k).style.color='';
							document.getElementById('C3b'+_k).style.color='';
							document.getElementById('C4b'+_k).style.color='';
						
				}
				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].text;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].text;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].text;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].text;
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)){
						get(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}
				}
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					document.getElementById('a32').value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].textContent;						
			}
			
	}else{
		//	
	}
}
function smsGo()
{

	myAjax=createAjax();
	var checkPhone=document.getElementById("checkPhone").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var eNum=document.getElementById("eNum").value;
	var InsuraneCompany=document.getElementById('a14').value
	var preminum=document.getElementById("preminum").value;
	var comment=encodeURIComponent(document.getElementById("comment").value);
	var userId=document.getElementById("userId").value;
	if(confirm('문자보내입니다!!')){
		
		if(comment){
			var toSend = "./ajax/smsAjax.php?checkPhone="+checkPhone
													 +"&InsuraneCompany="+InsuraneCompany
													 +"&preminum="+preminum
													 +"&CertiTableNum="+CertiTableNum
													 +"&comment="+comment
													 +"&userId="+userId
													 +"&eNum="+eNum;
			alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=smsReceive;
			myAjax.send('');
		}else{

				alert('보낼 내용이 없습니다 !!')
				return false;
		}
	}


}

function get(k,k2){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('C5b'+k);
	 newInput2.id='gett'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeSangtae;
	 var opts=newInput2.options;
	
	opts.length=3;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		
		if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(eval(i)){
					
					case 1 :
						opts[i].text='받음';
					break;
					case 2 :
						opts[i].text='미수';
					break;
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);


}

function changeSangtae()
{
	myAjax=createAjax();
	var nn=this.getAttribute("id");
	//alert(nn)
	if(this.value.length==6){
		nn=nn.substr(5,6);
	}else{
		nn=nn.substr(5,7);
	}
	//alert(nn);
	var DaeriCompanyNum=document.getElementById("num").value;
	
	var memberNum=document.getElementById("B0b"+nn).value;
	var val=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;
	//alert(memberNum);
	
	//var num=document.getElementById("num").value;//2012DaeriCompanyNum;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var insuranceCompany=document.getElementById('a14').value;
	var eNum=document.getElementById("eNum").value;
	if(eval(insuranceCompany)==2){//동부화재 일때 만 
	//설계번호.증권번호 저장을위해

		var certiNum=document.getElementById("jang"+nn).value;
		var selNum=document.getElementById("kang"+nn).value;
		if(confirm('처리 합니다')){
			if(memberNum){	
				var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum 
														 +"&insuranceCompany="+insuranceCompany
														 +"&certiNum="+certiNum
														 +"&selNum="+selNum
														 +"&eNum="+eNum
														 +"&nn="+nn
														 +"&val="+val	;
				alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=pushReceive;
				myAjax.send('');
			}
		}
	}else{
		
		
		if(confirm('처리 합니다')){
			if(memberNum){	
				var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum														  +"&eNum="+eNum
														 +"&nn="+nn
														 +"&val="+val	;
				alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=pushReceive;
				myAjax.send('');
			}
		}
	}
}


function exclell__(i){
		
		var num=document.getElementById("daeriCompanyNum").value
	
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./ExcelUpload_2.php?DaeriCompanyNum='+num+'&etage='+i,'dp___','left='+winl+',top='+wint+',resizable=yes,width=900,height=500,scrollbars=no,status=yes')
}
function exclell2__(i){
		
		var num=document.getElementById("daeriCompanyNum").value
	
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./ExcelUpload_2_2.php?DaeriCompanyNum='+num+'&etage='+i,'dp___','left='+winl+',top='+wint+',resizable=yes,width=900,height=500,scrollbars=no,status=yes')
}