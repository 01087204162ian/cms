
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	
			if(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text!=undefined){
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
					document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("name4")[0].text;
				}else{
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
										
				}
			for(var j=1;j<15;j++){
				if(myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text!=undefined){
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;
				}else{
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
										
				}
			}
			
			
			
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				for(var _m=0;_m<eval(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].text);_m++)
				{	document.getElementById('B1b'+_m).innerHTML=_m+1;
					//dongbuSele(_m,myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany"+_m)[0].text);
					document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("memberNum"+_m)[0].text;
					document.getElementById('B2b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Name"+_m)[0].text;
					document.getElementById('B3b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Jumin"+_m)[0].text;
					document.getElementById('B4b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("etag"+_m)[0].text;

					document.getElementById('B6b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum"+_m)[0].text;//,표시 
					
					document.getElementById('B8b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("Endorse2Preminum"+_m)[0].text;//문자보낼때 표시하기 위한 
					var Cancel=myAjax.responseXML.documentElement.getElementsByTagName("cancel"+_m)[0].text;
					var Push=myAjax.responseXML.documentElement.getElementsByTagName("push"+_m)[0].text;
					var Sangtae=myAjax.responseXML.documentElement.getElementsByTagName("sangtae"+_m)[0].text;
					dongbuSele(_m,Push,Sangtae,Cancel);
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("cancel"+_m)[0].text);
					//처리 미처리
					
					sele(_m,myAjax.responseXML.documentElement.getElementsByTagName("sangtae"+_m)[0].text,Cancel);


					//동부화재 인 경우만 적용 됨
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("name14")[0].text==2)){
						var Certi= myAjax.responseXML.documentElement.getElementsByTagName("dongbuCerti"+_m)[0].text;//증권번호
						var Serti= myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber"+_m)[0].text;//설계번호

						var Sigi=myAjax.responseXML.documentElement.getElementsByTagName("dongbusigi"+_m)[0].text;//동부시기
						var Jeonggi=myAjax.responseXML.documentElement.getElementsByTagName("dongbujeongi"+_m)[0].text;//동부종기
						var nabang_1=myAjax.responseXML.documentElement.getElementsByTagName("nabang_1"+_m)[0].text;//납입회차
						dongbuHaeji(_m,Push,Sangtae,Cancel,Certi,Serti,Sigi,Jeonggi,nabang_1);
					//	var Push=myAjax.responseXML.documentElement.getElementsByTagName("push"+_m)[0].text;
						if(Push==4 || Push==1){
						//document.getElementById('jang'+_m).value=;
						//document.getElementById('kang'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber"+_m)[0].text;
						}
						
					}
					
				}
				document.getElementById('comment').value=myAjax.responseXML.documentElement.getElementsByTagName("smsContent")[0].text
				document.getElementById('endorseDay').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorse_day")[0].text;
				
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

//dongbuHaeji(_m,Push,Sangtae,Cancel,Certi,Serti,Sigi,Jeonggi,nabang_1);
function dongbuHaeji(k,k2,k3,Cancel,Certi,Serti,Sigi,Jeonggi,nabang_1){//순번,push/
	//alert(k2)
	//alert(Cancel)
	//if(k2==4 || k2==1){//정상인경우 즉 청약인 경우만
	if(Cancel!='42'){
	//증권번호 체크하면 전년도 증권번호금년이 12이면11
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B9b'+k);
			 newInput2.id='kwon'+k;
			 newInput2.style.width = '15px';
			 //newInput2.className='checkbox';
			 newInput2.type='checkbox';
			// newInput2.onclick=certicheck;
			 
			 aJumin.appendChild(newInput2);

			//증권번호 입력 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B10b'+k);
			 newInput2.id='jang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			 newInput2.value=Certi;
			 newInput2.onblur=certicheck;
			 newInput2.onclick=certicheck2;
			
			 aJumin.appendChild(newInput2);


			//설계번호 체크 체크하면전월을 표시하기 위해

			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B11b'+k);
			 newInput2.id='lang'+k;
			 newInput2.style.width = '15px';
			// newInput2.className='checkbox';
			 newInput2.type='checkbox';
			// newInput2.onchange=innerHTML;
			
			 aJumin.appendChild(newInput2);

			 
			//설계번호 입력 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B12b'+k);
			 newInput2.id='kang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			  newInput2.value=Serti;
			newInput2.onblur=jcheck;
			newInput2.onclick=jcheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);


			//보험시기 입력하기 위해 

			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B13b'+k);
			 newInput2.id='sigi'+k;
			 newInput2.style.width = '60px';
			 newInput2.className='phone';
			  newInput2.value=Sigi;
			newInput2.onblur=dsigiCheck;
			newInput2.onclick=dsigiCheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);

			//보험종기 입력하기 위해 

			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B14b'+k);
			 newInput2.id='djeonggi'+k;
			 newInput2.style.width = '60px';
			 newInput2.className='phone';
			  newInput2.value=Jeonggi;
			newInput2.onblur=dsigiCheck;
			newInput2.onclick=dsigiCheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);

			cheriNab(k,nabang_1);//회차
			//
	}else{//해지 일경우
			//alert(k2)
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B13b'+k);
			 newInput2.type='button';
			 newInput2.id='sang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='input2';
			 newInput2.onclick=retirePrint;
			 newInput2.value="퇴직증명서";				
			 aJumin.appendChild(newInput2);

			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B10b'+k);
			 newInput2.id='jang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			  newInput2.value=Certi;
			// newInput2.onblur=certicheck;
			// newInput2.onclick=certicheck2;
			
			 aJumin.appendChild(newInput2);

			 var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B12b'+k);
			 newInput2.id='kang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			   newInput2.value=Serti;
			//newInput2.onblur=jcheck;
			//newInput2.onclick=jcheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);



			 cheriNab(k,nabang_1);//회차
	}


}

function cheriNab(k,nabang_1){//납입회차 
	
var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B15b'+k);
	 newInput2.id='cheriii'+k;
	 newInput2.style.width = '60px';
	 newInput2.className='selectbox';
	//newInput2.onchange=changeNab;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=11;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(nabang_1)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 1 :
					  opts[i].text='1회차';
						break;
					case 2 :
						opts[i].text='2회차';
					break;
					case 3 :
						opts[i].text='3회차';
					break;
					case 4 :
						opts[i].text='4회차';
					break;
					case 5 :
						opts[i].text='5회차';
					break;
					case 6 :
					    opts[i].text='6회차';
						break;
					case 7 :
						opts[i].text='7회차';
					break;
					case 8 :
						opts[i].text='8회차';
					break;
					case 9 :
						opts[i].text='9회차';
					break;
					case 10 :
						opts[i].text='10회차';
					break;
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);
}
function dsigiCheck(){//시기 

	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==8){
			
		this.value=this.value.substring(0,4)+"-"+this.value.substring(4,6)+"-"+this.value.substring(6,8);
		document.getElementById('djeonggi'+nn).value=(eval(this.value.substring(0,4))+1)+"-"+this.value.substring(5,7)+"-"+this.value.substring(8,10);
		
	}

}

function dsigiCheck2(){//시기 

	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==10){
		
		this.value=this.value.substring(0,4)+this.value.substring(5,7)+this.value.substring(8,10);
		
	}

}
function jcheck(){//설계번호

	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==4){
		//alert(document.getElementById('kwon'+nn).checked)
		
		if(document.getElementById('lang'+nn).checked==true){
			//alert(this.value)
			if(document.getElementById('endorseDay').innerHTML.substring(5,7)==01){//1월인경우
					document.getElementById('endorseDay').innerHTML.substring(5,7)==12;
				}	
			this.value=document.getElementById('endorseDay').innerHTML.substring(5,7)-1+"-"+this.value;
		}else{

			this.value=document.getElementById('endorseDay').innerHTML.substring(5,7)+"-"+this.value;
		}
	}

}
function jcheck2(){//설계번호

	
	if(this.value.length==7){
		

			this.value=this.value.substring(3,7);
		
	}else if(this.value.length==6){
		

			this.value=this.value.substring(2,6);
	}

}
function certicheck(){//증권번호
	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==7){
		//alert(document.getElementById('kwon'+nn).checked)
		
		if(document.getElementById('kwon'+nn).checked==true){
			//alert(this.value)
			this.value=document.getElementById('endorseDay').innerHTML.substring(2,4)-1+"-"+this.value;
		}else{

			this.value=document.getElementById('endorseDay').innerHTML.substring(2,4)+"-"+this.value;
		}
	}
	
	
}

function certicheck2(){//증권번호
	
	if(this.value.length==10){
		
			this.value=this.value.substring(3,10);
		
	}
	
	
}
function dongbuSele(k,k2,k3,k4){//순번.push,sangtae,cancel
	//alert(k);
	//alert(k2);
	//alert(k3);
	//alert(k4)
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B5b'+k);
	 newInput2.id='sang'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	// newInput2.onchange=innerHTML;
	 var opts=newInput2.options;
	
if(k3==2){//배서완료후 
	switch(eval(k2)){
		case 1 ://
			k2=1;
			opts.length=2;
		break;
		case 2 :
			k2=4;
			opts.length=5;
		break;
		case 4 :  //정상이라는 것은 신규 추가를의미하므로//정상이라는 것은 해지 후 취소도 있을 수 있다
			if(eval(k4)==45){
				k2=4;
				opts.length=5;
			}else{
				k2=1;
				opts.length=2;
			}
		break;
	}

}else{
	switch(eval(k2)){
		case 1 :
			
		   opts.length=4;
		 break;
		case 2 :
			k2=4;
		   opts.length=5;
		 break;
		 case 4 :
			opts.length=7;
		break;
	}


}


	 for(var i=k2;i<opts.length;i++){	 
		opts[i].value=i;
		
		if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(eval(i)){
					
					case 1 :
						opts[i].text='청약';
					break;
					case 2 :
						opts[i].text='취소';
					break;
					case 3 :
						opts[i].text='거절';
					break;
					case 4 :
						opts[i].text='해지'+opts[i].value;
					break;
					case 5 :
						opts[i].text='취소'+opts[i].value;
					break;
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);




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
function sele(k,k2,k3){////순번,sangtae,cancel

	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B7b'+k);
	 newInput2.id='cheri'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeSangtae;
	 var opts=newInput2.options;
	
	opts.length=3;
	
	 for(var i=k2;i<opts.length;i++){	 
		opts[i].value=i;
		
		if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
	/*	if(eval(i)==1){
			newInput2.style.color='red';
		}else{
			
		}*/
				switch(eval(i)){
					
					case 1 :
						newInput2.style.color='red';
						opts[i].text='미처리';
					break;
					case 2 :
						//newInput2.style.color='#0A8FC1';
						switch(eval(k3)){
							case 12:
								opts[i].text='청약취소';
							break;
							case 13:
								opts[i].text='청약거절';
							break;
							case 45:
								opts[i].text='해지취소';
							break;
							default:
								opts[i].text='처리';
							break;
						}
					break;	
				}
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);



}
function pushReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				var nn=eval(myAjax.responseXML.documentElement.getElementsByTagName("nn")[0].text);

				document.getElementById('B6b'+nn).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[0].text;
				document.getElementById('B8b'+nn).value=myAjax.responseXML.documentElement.getElementsByTagName("EndorsePnum")[0].text;

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
				///document.getElementById('sql2').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("num2")[0].text;
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					document.getElementById('a32').value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].textContent;						
			}
			
	}else{
		//	
	}
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
	var val=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;//청약 또는 해지
	alert(val);
	
	//var num=document.getElementById("num").value;//2012DaeriCompanyNum;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var insuranceCompany=document.getElementById('a14').value;
	var eNum=document.getElementById("eNum").value;
	if(eval(insuranceCompany)==2){//동부화재 일때 만 
	//설계번호.증권번호 저장을위해

		var certiNum=document.getElementById("jang"+nn).value;
		var selNum=document.getElementById("kang"+nn).value;

		
		if(confirm('처리 합니다')){

			if(val==1){//정상일 경우만
		

				var sigi=document.getElementById('sigi'+nn).value;
				if(!sigi){
					alert('보험시기!!');
					document.getElementById("sigi"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}

				var jeonggi=document.getElementById('djeonggi'+nn).value;
				if(!jeonggi){
					alert('보험종기!!');
					document.getElementById("djeonggi"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}
				if(!certiNum){
					alert('증권번호!!');
					document.getElementById("jang"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}
				if(!selNum){
					alert('설계번호!!');
					document.getElementById("kang"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}
				
				var cheriii=document.getElementById('cheriii'+nn).options[document.getElementById('cheriii'+nn).selectedIndex].value;//청약 또는 해지

				if(!cheriii){
					alert('납입회차!!');
					document.getElementById('cheriii'+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}

			}

			if(memberNum){	
				

				if(val==1){

					var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum 
														 +"&insuranceCompany="+insuranceCompany
											//if(val==1){ 
														 +"&sigi="+sigi
														 +"&jeonggi="+jeonggi
														 +"&cheriii="+cheriii
													  // }
														 +"&certiNum="+certiNum
														 +"&selNum="+selNum
														 +"&eNum="+eNum
														 +"&nn="+nn
														 +"&val="+val	;



				}else{



				var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum 
														 +"&insuranceCompany="+insuranceCompany
														
														 +"&eNum="+eNum
														 +"&nn="+nn
														 +"&val="+val	;
				}
				alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=pushReceive;
				myAjax.send('');
			}
		}else{
			document.getElementById("cheri"+nn).value=1;
			return false;
		}
	}else{
		
		
		if(confirm('처리 합니다')){
			if(memberNum){	
				var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum														
														 +"&eNum="+eNum
														 +"&nn="+nn
														 +"&val="+val	;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=pushReceive;
				myAjax.send('');
			}
		}else{
			document.getElementById("cheri"+nn).value=1;
			return false;
		}
	}
}
function serch(val){
	myAjax=createAjax();

	var num=document.getElementById("num").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var eNum=document.getElementById("eNum").value;

	if(num){	
		var toSend = "./ajax/endorseAjaxSerch.php?num="+num
												 +"&CertiTableNum="+CertiTableNum
												 +"&eNum="+eNum;
		alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function checkSele(k){
	
	var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('pun'+k);
	 newInput2.type='checkbox';
	 newInput2.id='pcheck'+k;
	 newInput2.onclick=bonagi;//핸드폰 번호를 보내기 위해
	 newInput2.style.width = '30px';
	 aJumin.appendChild(newInput2);

	
}

function changClear(element){

	while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
}












function memberEndorse(val){
	//alert(document.getElementById("num").value);
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('B0b'+val).value;
	var a4=document.getElementById('B4b'+val).value;
	var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;
	if(certiNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./MemberEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'ppss','left='+winl+',top='+wint+',resizable=yes,width=430,height=600,scrollbars=no,status=yes')
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}
	   

	
}
function changeEndorseDay(){
	var num=document.getElementById("num").value;
	
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var eNum=document.getElementById("eNum").value;
	var endorseDay=document.getElementById("endorseDay").value;
	if(eNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./php/EndorseDayChange.php?eNum='+eNum+'&CertiTableNum='+CertiTableNum+'&endorseDay='+endorseDay+'&num='+num,'ed','left='+winl+',top='+wint+',resizable=yes,width=600,height=400,scrollbars=no,status=yes')
	}

}

function dailyPreminum(){//보험회사별 일일보험료 
	
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	var a9=document.getElementById('a9').value;
	var a14=document.getElementById('a14').value

		switch(eval(a14)){
			case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9,'12preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 2 :
				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum2Dongbu.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9,'12preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 3 :
				case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9,'12preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;


				break;
			case 4:

				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
				break;
		}

}
function retirePrint(){//퇴직증명서 


	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}

	var driverNum=document.getElementById('B0b'+nn).value;
	 var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./print/retirePrint.php?driverNum='+driverNum,'2012retire','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
}

function EndorsePrint(){//보험회사별 배서프린트
	var userId=document.getElementById("userId").value;
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	var eNum=document.getElementById('eNum').value;
	var a9=document.getElementById('a9').value;
	var a14=document.getElementById('a14').value;

		switch(eval(a14)){
			case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 2 :
				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 3 :
				case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;


				break;
			case 4:

				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
				break;
		}
}

function SanPrint(){//상품설명서 프린트
	var userId=document.getElementById("userId").value;
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	var eNum=document.getElementById('eNum').value;
	var a9=document.getElementById('a9').value;
	var InsuraneCompany=document.getElementById('a14').value;

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./print/sangPrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+InsuraneCompany+'&userId='+userId+'&eNum='+eNum,'2012san','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
		
		
}

