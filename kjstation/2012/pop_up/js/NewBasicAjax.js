function bagoJo(k,k2){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('C5b'+k);
	 newInput2.id='gett'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeget;
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
/*
function get(k,k2){
	//alert(k)
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('C5b'+k);
	 newInput2.id='gett'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeget;
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


}*/
function sjRe(){

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
//받음 미수
function changeget(){
	//alert('1')
	myAjax=createAjax();
	var nn=this.getAttribute("id");
	//alert(nn)
	if(this.value.length==5){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}
	//alert(nn)
		//alert(document.getElementById("C0b"+nn).value);
		var Seg=document.getElementById("C0b"+nn).value;
		//alert(document.getElementById('gett'+nn).options[document.getElementById('gett'+nn).selectedIndex].value);
		var Gett=document.getElementById('gett'+nn).options[document.getElementById('gett'+nn).selectedIndex].value
		var toSend = "./ajax/canCelUpdate.php?cNum="+Seg
											 +"&get="+Gett;
		//alert(toSend)
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=sjRe;
		myAjax.send('');


											
}
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
/*
function CertiCheck(id,val){
	//alert(id);
	(this.value)

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

						document.getElementById(id).value=document.getElementById('B3b'+nn).value.substring(2,4)+'-700'+val;
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
		//alert(insuranceNum+'/'+val)
		
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
*/
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

			//
	document.getElementById('tot').innerHTML='';
	for(var _m=0;_m<9;_m++){

		document.getElementById('B0b'+_m).value='';
		document.getElementById('B1b'+_m).innerHTML='';
		document.getElementById('B2b'+_m).innerHTML='';
		document.getElementById('B3b'+_m).value='';
		document.getElementById('B4b'+_m).value='';
		document.getElementById('B5b'+_m).value='';
		document.getElementById('B6b'+_m).innerHTML='';
		document.getElementById('B7b'+_m).value='';
		document.getElementById('B8b'+_m).value='';
		document.getElementById('B9b'+_m).innerHTML='';
		document.getElementById('B10b'+_m).innerHTML='';
		document.getElementById('B11b'+_m).innerHTML='';
		document.getElementById('B12b'+_m).innerHTML='';
		document.getElementById('B13b'+_m).innerHTML='';
		document.getElementById('B14b'+_m).value='';
		document.getElementById('B15b'+_m).innerHTML='';
		document.getElementById('m1_2').innerHTML='';

	}

	for(var _q=0;_q<10;_q++){

		document.getElementById('C0b'+_q).value='';
		document.getElementById('C1b'+_q).innerHTML='';
		document.getElementById('C2b'+_q).innerHTML='';
		document.getElementById('C3b'+_q).innerHTML='';
		document.getElementById('C4b'+_q).innerHTML='';
		document.getElementById('C5b'+_q).innerHTML='';
		

	}

			myAjax=createAjax();
			var toSend = "./ajax/popRentSerch.php?jumin="+jumin;
	
			//alert(toSend);
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
function smsGo(val)
{
		//alert(val)
	myAjax=createAjax();
	var checkPhone=document.getElementById("checkPhone").value;
	var preminum=document.getElementById("preminum").value;
	
	
	if(val==1){//배서일떼
		var eNum=document.getElementById("eNum").value;
		//var preminum=document.getElementById("preminum").value;
		var CertiTableNum=document.getElementById("CertiTableNum").value;
		var InsuraneCompany=document.getElementById('a14').value;
	}else{

		var InsuraneCompany=document.getElementById('num').value;

	}
	
	var comment=encodeURIComponent(document.getElementById("comment").value);
	var userId=document.getElementById("userId").value;
	if(confirm('문자보내입니다!!')){
		
		if(comment){
			var toSend = "./ajax/smsAjax.php?checkPhone="+checkPhone
										 +"&eNum="+eNum
													 +"&InsuraneCompany="+InsuraneCompany
													 +"&CertiTableNum="+CertiTableNum 
													
													 +"&comment="+comment
													 +"&preminum="+preminum
													 +"&userId="+userId;
			//alert(toSend);
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


function total_excel(num){

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		
		window.open('./sjExceltotal.php?num='+num,'excelTo','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}

function misuExcel(num){

	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		var mYear=document.getElementById('mYear').options[document.getElementById('mYear').selectedIndex].value;
		

		//alert(YM);
		window.open('./misuExcel.php?num='+num+'&YM='+mYear,'MiexcelTo','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}

function toExcel(){
	//alert('1')
	var num=document.getElementById("DaeriCompanyNum").value;
	
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	//alert(CertiTableNum)
	var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	if(InsuraneCompany){CertiTableNum='';};
	////if(num){	
		//var toSend = "./ajax/memberListAjax.php?CertiTableNum="+CertiTableNum
										//	+"&InsuraneCompany="+InsuraneCompany
			                             //    +"&DaeriCompanyNum="+num;
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		
		window.open('./sjExceltotal.php?num='+num+'&CertiTableNum='+CertiTableNum+'&InsuraneCompany='+InsuraneCompany,'excelTo','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');


}
function certi(bunho,val){

	switch(eval(document.getElementById('sang'+bunho).value)){

		case 1 :
			
			if(val.length==10){
				
				//alert(val.substring(5,12))
				document.getElementById('B4b'+bunho).value=val.substring(6,12);
			}
			break;
		case 2 :	
			if(val.length==10){
					
					//alert(val.substring(5,12))
					document.getElementById('B4b'+bunho).value=val.substring(3,10);
				}
			break;
		case 3 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					document.getElementById('B4b'+bunho).value=val.substring(5,12);
				}			
			break;
		case 4 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					document.getElementById('B4b'+bunho).value=val.substring(5,12);
				}			
		break;
		case 5 :

			break;
		case 6:

			break;

	}
		

	
}
function certiInput(bunho,val){
	//alert('1')

	//alert(this.value)
	//alert(document.getElementById('sang'+bunho).value)
	
	switch(eval(document.getElementById('sang'+bunho).value)){

		case 1 :
			if(val.length==4){
				
				   document.getElementById('B4b'+bunho).value=eval(document.getElementById('B3b'+bunho).value.substring(2,4))+'-700'+val;
				
			}else{
			alert('네자리 !!')
			document.getElementById('B4b'+bunho).focus();
				return false;
			
			}
		

			break;
		case 2 :
		/*	if(val.length==7){
			
				    document.getElementById('B4b'+bunho).value=eval(document.getElementById('B3b'+bunho).value.substring(2,4))+'-'+val;
				
			}else{

				alert('일곱자리 !!')
			//	document.getElementById('B4b'+bunho).focus();
					return false;
			}*/
			break;
		case 3 :
		if(val.length==7){
			
				document.getElementById('B4b'+bunho).value=eval(document.getElementById('B3b'+bunho).value.substring(0,4))+'-'+val;
			
		}else{

			alert('일곱자리 !!')
			document.getElementById('B4b'+bunho).focus();
				return false;
		}
			break;
		case 4 :
		if(val.length==7){
			
				document.getElementById('B4b'+bunho).value=eval(document.getElementById('B3b'+bunho).value.substring(0,4))+'-'+val;
			
		}else{

			alert('일곱자리 !!')
			document.getElementById('B4b'+bunho).focus();
				return false;
		}
			break;
		case 5 :

			break;
		case 6:

			break;

	}

	/*	myAjax=createAjax();//청약번호 저장을 하기위해
		var  pCerti=this.value;
		var  dirverNum=document.getElementById('num'+bunho).value;
		var toSend = "./ajax/appInput.php?appNumber="+pCerti
				   +"&driverNum="+dirverNum
				  // +"&page="+page
				  // +"&tableNum="+tableNum;
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=changeEtagResponse;
		myAjax.send('');*/
	
}
