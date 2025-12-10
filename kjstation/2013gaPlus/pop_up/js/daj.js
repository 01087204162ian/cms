function Receive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				//document.getElementById('B11b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("insert_sql")[0].text;
				document.getElementById('jang').value=myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].text;//설계번호
				document.getElementById('preminum').value=myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].text;//보험료
				document.getElementById('etag2').value=myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].text;
			    document.getElementById('virtual').value=myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].text;//가상계좌
				document.getElementById('jang2').value=myAjax.responseXML.documentElement.getElementsByTagName("b6b")[0].text;//증권번호
				document.getElementById('inputDay').value=myAjax.responseXML.documentElement.getElementsByTagName("b7b")[0].text;//증권번호
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('jang').value=myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].textContent;//설계번호
				document.getElementById('preminum').value=myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].textContent;//보험료
				document.getElementById('etag2').value=myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].textContent;
			    document.getElementById('virtual').value=myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].textContent;//가상계좌
				document.getElementById('jang2').value=myAjax.responseXML.documentElement.getElementsByTagName("b6b")[0].textContent;//증권번호
				document.getElementById('inputDay').value=myAjax.responseXML.documentElement.getElementsByTagName("b7b")[0].textContent;//계약일
			}
			
	}else{
		//	
	}


}
function callReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

				//if(eval(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text)==6){
					//document.getElementById('diffC').value=1;
				//}
				//document.getElementById('B11b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("insert_sql")[0].text;
			/*	document.getElementById('jang').value=myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].text;//설계번호
				document.getElementById('preminum').value=myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].text;//보험료
				document.getElementById('etag2').value=myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].text;
			    document.getElementById('virtual').value=myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].text;//가상계좌
				document.getElementById('jang2').value=myAjax.responseXML.documentElement.getElementsByTagName("b6b")[0].text;//증권번호*/
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					
			}
			
	}else{
		//	
	}


}

function companyReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				
				document.getElementById('B1b').value=myAjax.responseXML.documentElement.getElementsByTagName("a1a")[0].text;

				if(myAjax.responseXML.documentElement.getElementsByTagName("a1a")[0].text){

					document.getElementById('a32').value='수정';
				}
				document.getElementById('B2b').value=myAjax.responseXML.documentElement.getElementsByTagName("a2a")[0].text;
				document.getElementById('B3b').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].text;
				document.getElementById('B4b').value=myAjax.responseXML.documentElement.getElementsByTagName("a4a")[0].text;
				document.getElementById('B16b').value=myAjax.responseXML.documentElement.getElementsByTagName("a16a")[0].text;
				document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].text;
				document.getElementById('B5b').value=myAjax.responseXML.documentElement.getElementsByTagName("a5a")[0].text;
				document.getElementById('B6b').value=myAjax.responseXML.documentElement.getElementsByTagName("a6a")[0].text;
				document.getElementById('B7b').value=myAjax.responseXML.documentElement.getElementsByTagName("a7a")[0].text;
				company(myAjax.responseXML.documentElement.getElementsByTagName("a8a")[0].text);
				document.getElementById('B9b').value=myAjax.responseXML.documentElement.getElementsByTagName("a9a")[0].text;
				document.getElementById('B15b').value=myAjax.responseXML.documentElement.getElementsByTagName("a15a")[0].text;
				document.getElementById('B10b').value=myAjax.responseXML.documentElement.getElementsByTagName("a10a")[0].text;
				document.getElementById('c10c').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("c10c")[0].text;
				document.getElementById('B11b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a11a")[0].text;
				//우편물

				document.getElementById('a28').value=myAjax.responseXML.documentElement.getElementsByTagName("a28a")[0].text;
				document.getElementById('a29').value=myAjax.responseXML.documentElement.getElementsByTagName("a29a")[0].text;
				document.getElementById('a30').value=myAjax.responseXML.documentElement.getElementsByTagName("a30a")[0].text;

				document.getElementById('a31').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a31a")[0].text;
				document.getElementById('a33').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a33a")[0].text;
				document.getElementById('a34').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a34a")[0].text;
				document.getElementById('a54').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a54a")[0].text;//소방방재청상호
				document.getElementById('a55').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a55a")[0].text;//소방방재청주소
				document.getElementById('a56').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a56a")[0].text;//소방방재청주소
				jinhang(myAjax.responseXML.documentElement.getElementsByTagName("a12a")[0].text);//진행상황
			//	document.getElementById('B12b').value=myAjax.responseXML.documentElement.getElementsByTagName("a12a")[0].text;


				inCom(myAjax.responseXML.documentElement.getElementsByTagName("b1b")[0].text);//보험회사
				selNum(myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].text,myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].text)//설계번호 보험료
				bankCom(myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].text,myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].text);
				certi(myAjax.responseXML.documentElement.getElementsByTagName("b6b")[0].text);//증권번호

				sele(myAjax.responseXML.documentElement.getElementsByTagName("b7b")[0].text);//처리유무
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("b8b")[0].text);
				document.getElementById('D1d').value=myAjax.responseXML.documentElement.getElementsByTagName("b8b")[0].text;//계약일


				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].text;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].text;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].text;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].text;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].text;
					/*if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)){
						bagoJo(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}*/
				}

			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('B1b').value=myAjax.responseXML.documentElement.getElementsByTagName("a1a")[0].textContent;

				if(myAjax.responseXML.documentElement.getElementsByTagName("a1a")[0].textContent){

					document.getElementById('a32').value='수정';
				}
				document.getElementById('B2b').value=myAjax.responseXML.documentElement.getElementsByTagName("a2a")[0].textContent;
				document.getElementById('B3b').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].textContent;
				document.getElementById('B4b').value=myAjax.responseXML.documentElement.getElementsByTagName("a4a")[0].textContent;
				document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].textContent;
				document.getElementById('B5b').value=myAjax.responseXML.documentElement.getElementsByTagName("a5a")[0].textContent;
				document.getElementById('B6b').value=myAjax.responseXML.documentElement.getElementsByTagName("a6a")[0].textContent;
				document.getElementById('B7b').value=myAjax.responseXML.documentElement.getElementsByTagName("a7a")[0].textContent;
				company(myAjax.responseXML.documentElement.getElementsByTagName("a8a")[0].textContent);
				document.getElementById('B9b').value=myAjax.responseXML.documentElement.getElementsByTagName("a9a")[0].textContent;
				document.getElementById('B15b').value=myAjax.responseXML.documentElement.getElementsByTagName("a15a")[0].textContent;
				document.getElementById('B10b').value=myAjax.responseXML.documentElement.getElementsByTagName("a10a")[0].textContent;
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("c10c")[0].textContent);
				document.getElementById('c10c').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("c10c")[0].textContent;
				document.getElementById('B11b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a11a")[0].textContent;
				//우편물

				document.getElementById('a28').value=myAjax.responseXML.documentElement.getElementsByTagName("a28a")[0].textContent;
				document.getElementById('a29').value=myAjax.responseXML.documentElement.getElementsByTagName("a29a")[0].textContent;
				document.getElementById('a30').value=myAjax.responseXML.documentElement.getElementsByTagName("a30a")[0].textContent;
				document.getElementById('a34').value=myAjax.responseXML.documentElement.getElementsByTagName("a34a")[0].textContent;
				document.getElementById('a54').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a54a")[0].textContent;//소방방재청상호
				document.getElementById('a55').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a55a")[0].textContent;//소방방재청주소
				document.getElementById('a56').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a56a")[0].textContent;//소방방재청주소
				jinhang(myAjax.responseXML.documentElement.getElementsByTagName("a12a")[0].textContent);//진행상황
			//	document.getElementById('B12b').value=myAjax.responseXML.documentElement.getElementsByTagName("a12a")[0].textContent;


				inCom(myAjax.responseXML.documentElement.getElementsByTagName("b1b")[0].textContent);//보험회사
				selNum(myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].textContent,myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].textContent)//설계번호 보험료
				bankCom(myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].textContent,myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].textContent);
				certi(myAjax.responseXML.documentElement.getElementsByTagName("b6b")[0].textContent);//증권번호

				sele(myAjax.responseXML.documentElement.getElementsByTagName("b7b")[0].textContent);//처리유무

				document.getElementById('D1d').value=myAjax.responseXML.documentElement.getElementsByTagName("b8b")[0].textContent;//계약일
				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].textContent);_k++)
				{
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].textContent;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].textContent;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].textContent;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].textContent;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].textContent;
					/*if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)){
						bagoJo(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}*/
				}	
			}
			
	}else{
		//	
	}


}

function jinhang(k){
//alert(k)
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('jinhang');
	 newInput2.id='diffC';
	 newInput2.style.width = '90px';
	 newInput2.className='selectbox';
	newInput2.onchange=call;//문자보내기
	 var opts=newInput2.options;
	opts.length=16;
	
	 for(var i=0;i<opts.length;i++){	
		 
	
		opts[i].value=i;
		
		 if(opts[i].value==eval(k)){	
			newInput2.selectedIndex=i;
		}

				switch(i){
					
					case 1 :
						opts[i].text='신청';

					break;
					case 7 :
						opts[i].text='등록대기';
					break;
					case 2 :
						opts[i].text='팩스받음';	
					break;
					case 3 :
						opts[i].text='입금대기';
					break;
					case 4 :
						opts[i].text='입금학인함';
					break;
					case 5 :
						opts[i].text='처리완료';
					break;
					case 6 :
						opts[i].text='팩스 재요청';
					break;
					
					case 8 :
						opts[i].text='통화';
					break;
					case 9 :
						opts[i].text='장기보험방문요청';
					break;
					case 10 :
						opts[i].text='취소';
					break;
					case 11 :
						opts[i].text='설계';
					break;
					case 12 :
						opts[i].text='증권발행';
					break;
					case 13 :
						opts[i].text='오류';
					break;
					
					case 14 :
						opts[i].text='수납대기';
					break;
					case 15 :
						opts[i].text='수납학인';
					break;
					
				}
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);


}

function call(){ //진행상황에 
	///alert(this.value);
	
	if(eval(this.value)==3){

		if(!document.getElementById('virtual').value){

			alert('가상계좌 입력 후 !!')

			document.getElementById('virtual').focus()
			this.value=1;
			return false;
		}

	}
	var num=document.getElementById('num').value;

	var toSend = "/2012/dajoong/ajax/call.php?num="+num
				   +"&val="+this.value
				  // +"&end="+end
				  // +"&page="+page;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
    //alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=callReceive;
	myAjax.send('');

	//콜을 보낸후 재요청인 경우 


}
function sele(val){////순번,sangtae,cancel

	//설계번호 입력 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('D7b');
			 newInput2.id='fax';
			 newInput2.style.width = '80px';
			 newInput2.className='phone2';
			 newInput2.value=val;
			 newInput2.onblur=faxInput;
			 //newInput2.onclick=certiInput2;
			
			 aJumin.appendChild(newInput2);
}
function faxInput(){
	var val=this.value;
	var id=this.id;
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
	
	var selNum=document.getElementById('fax').value;
if(this.value.length>1){
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	
	var toSend = "/2012/pop_up/ajax/faxInput.php?num="+num
				 +"&fax="+this.value;
				 
	
	//alert(toSend)
}
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('')

}
function changeChC(){

	if(!document.getElementById('jang2').value){
		
		alert('증권번호 입력부터 하세요!!')
		document.getElementById('jang2').focus();
		return false;

	}	

	var ch=document.getElementById('cheri').options[document.getElementById('cheri').selectedIndex].value;

	var num=document.getElementById('num').value;
	
	var toSend = "/2012/pop_up/ajax/chInput.php?num="+num
				 +"&ch="+ch;
				 
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('')


}
function certi(val){ //증권번호
	//증권번호 체크하면 전년도 증권번호금년이 12이면11
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('E6b');
			 newInput2.id='kwon2';
			 newInput2.style.width = '15px';
			 //newInput2.className='checkbox';
			 newInput2.type='checkbox';
			 //newInput2.onclick=certicheck;
			 
			 aJumin.appendChild(newInput2);

			//설계번호 입력 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('D6b');
			 newInput2.id='jang2';
			 newInput2.style.width = '80px';
			 newInput2.className='phone2';
			 newInput2.value=val;
			 newInput2.onblur=certiInput;
			 newInput2.onclick=certiInput2;
			
			 aJumin.appendChild(newInput2);
}
function certiInput(){

	//,보험회사를 정하고 설계번호 입력한다
	
	if(eval(document.getElementById('etag1').options[document.getElementById('etag1').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('etag1').focus();
		document.getElementById('jang').value='';
		return false;

	}

	var inSNum=eval(document.getElementById('etag1').options[document.getElementById('etag1').selectedIndex].value);
	var selNum=document.getElementById('jang2').value;
switch(inSNum){
	default :
		if(selNum.length==4){
			
				var cyear=document.getElementById('B11b').innerHTML.substring(0,4);
				if(document.getElementById('kwon2').checked==true){
					
					cyear=eval(cyear)-1;	
					if(eval(cyear)<10){
						cyear="0"+cyear;
					}
					this.value=cyear+"-"+this.value;
				}else{

					this.value=cyear+"-"+this.value;
				}
			}else{
			
					alert('네자리');
					document.getElementById("jang2").focus();
					return false;
			

			}
	break;

	case 4 :
	if(selNum.length==7){
		var cyear=document.getElementById('B11b').innerHTML.substring(0,4);
			if(document.getElementById('kwon').checked==true){
				
				cyear=eval(cyear)-1;	
				if(eval(cyear)<10){
					cyear="0"+cyear;
				}
				this.value=cyear+"-"+this.value;
			}else{

				this.value=cyear+"-"+this.value;
			}
		}else{

			alert('일곱자리');
			document.getElementById("jang2").focus();
			return false;

		}
	break;
}
	
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	
	var toSend = "/2012/pop_up/ajax/certiInput.php?num="+num
				 +"&certi="+this.value;
				 
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('')

}
function certiInput2(){

	if(this.value.length==9){
		
			this.value=this.value.substring(5,9);
		
	}
}
function selNum(val,val2){ //설계번호 //보험료

	
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('E2b');
			 newInput2.id='kwon';
			 newInput2.style.width = '15px';
			 //newInput2.className='checkbox';
			 newInput2.type='checkbox';
			 //newInput2.onclick=certicheck;
			 
			 aJumin.appendChild(newInput2);

			//설계번호 입력 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('D2b');
			 newInput2.id='jang';
			 newInput2.style.width = '80px';
			 newInput2.className='phone2';
			 newInput2.value=val;
			 newInput2.onblur=selcheck;
			 newInput2.onclick=selcheck2;
			
			 aJumin.appendChild(newInput2);

			//보험료 입력 
			 var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('D3b');
			 newInput2.id='preminum';
			 newInput2.style.width = '80px';
			 newInput2.className='phone2';
			 newInput2.value=val2;
			 newInput2.onblur=prem;
			 //newInput2.onclick=premin2;
			
			 aJumin.appendChild(newInput2);


}


function prem(){

	//,보험회사를 정하고 설계번호 입력한다
	
	if(eval(document.getElementById('etag1').options[document.getElementById('etag1').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('etag1').focus();
		document.getElementById('jang').value='';
		return false;

	}
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	

	if(this.value.length>4){
	var toSend = "/2012/pop_up/ajax/preminumChange.php?num="+num
				 +"&preminum="+this.value;
				 
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('')
	}

}
function selcheck(){
	//,보험회사를 정하고 설계번호 입력한다
	
	if(eval(document.getElementById('etag1').options[document.getElementById('etag1').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('etag1').focus();
		document.getElementById('jang').value='';
		return false;

	}

	var inSNum=eval(document.getElementById('etag1').options[document.getElementById('etag1').selectedIndex].value);
	var selNum=document.getElementById('jang').value;

switch(inSNum){
	default :
	if(selNum.length==4){
		var cyear=document.getElementById('B11b').innerHTML.substring(5,7);
			if(document.getElementById('kwon').checked==true){
				
				cyear=eval(cyear)-1;	
				if(eval(cyear)<10){
					cyear="0"+cyear;
				}
				this.value=cyear+"-"+this.value;
			}else{

				this.value=cyear+"-"+this.value;
			}
		}else{

			alert('네자리');
			document.getElementById("jang").focus();
			return false;

		}
	break;
	case 4 :
	if(selNum.length==7){
		var cyear=document.getElementById('B11b').innerHTML.substring(0,4);
			if(document.getElementById('kwon').checked==true){
				
				cyear=eval(cyear)-1;	
				if(eval(cyear)<10){
					cyear="0"+cyear;
				}
				this.value=cyear+"-"+this.value;
			}else{

				this.value=cyear+"-"+this.value;
			}
		}else{

			alert('일곱자리');
			document.getElementById("jang").focus();
			return false;

		}
	break;


}
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	
	var toSend = "/2012/pop_up/ajax/selNumChange.php?num="+num
				 +"&inSNum="+inSNum
				 +"&selNum="+this.value;
				 
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('')
}

function selcheck2(){

	if(this.value.length==7){
		
			this.value=this.value.substring(3,7);
		
	}

	if(this.value.length==12){
		
			this.value=this.value.substring(5,12);
		
	}

}
function bankCom(val,val2){//은행명

	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('D4b');
	 newInput2.id='etag2';
	 newInput2.style.width = '100px';
	 newInput2.className='selectbox';
	 newInput2.onchange=changeBank;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=10;
	
	 for(var i=0;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(val)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
		
			  switch(i){
				default :
					opts[i].text='==선택==';
					break;
				case 1 :
				     opts[i].text='국민';
						break;
				case 2 :
					opts[i].text='농협';
					break;
				case 3 :
					opts[i].text='신한';
					break;
				case 4 :
					opts[i].text='우리';
					break;
				case 5 :
					opts[i].text='경남';
					break;
				case 6 :
					opts[i].text='광주';
					break;

				case 7 :
					opts[i].text='하나';
					break;
				case 8 :
					opts[i].text='외한';
					break;
				case 9 :
					opts[i].text='우체국';
					break;


	   }
		
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

	 //가상계좌
			 var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('D5b');
			 newInput2.id='virtual';
			 newInput2.style.width = '100px';
			 newInput2.className='phosj2';
			 newInput2.value=val2;
			 newInput2.onblur=virtual;
			 //newInput2.onclick=premin2;
			
			 aJumin.appendChild(newInput2);

}
//계약일 입력

function inputD(){

	
	myAjax=createAjax();inputDay

	if(document.getElementById('inputDay').checked==true){
		var num=document.getElementById('num').value;

		var D1d=document.getElementById('D1d').value;
	
		var toSend = "/2012/pop_up/ajax/inPutDay.php?num="+num
					 +"&Did="+D1d;
					 
		
		//alert(toSend)

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=Receive;
		myAjax.send('')
	
	}


}
//가상계좌
function virtual(){

	//,보험회사를 정하고 설계번호 입력한다
	
	if(eval(document.getElementById('etag1').options[document.getElementById('etag1').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('etag1').focus();
		document.getElementById('jang').value='';
		return false;

	}
	if(document.getElementById('etag2').options[document.getElementById('etag2').selectedIndex].value==1){
		
		
			var phone_first	= this.value.substring(0,5)
			var phone_second =this.value.substring(5,10)
			var phone_third	 =this.value.substring(10,15)
			this.value=phone_first+"-"+phone_second+"-"+phone_third;
	}

	//alert(this.value)
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	if(this.value.length>6){
	var toSend = "/2012/pop_up/ajax/virtualChange.php?num="+num
				 +"&virtual="+this.value;
				 
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('')
	}
}
function changeBank(){//은행명 변경 

	//,보험회사를 정하고 설계번호 입력한다
	
	if(eval(document.getElementById('etag1').options[document.getElementById('etag1').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('etag1').focus();
		document.getElementById('jang').value='';
		return false;

	}

	var BankName=document.getElementById('etag2').options[document.getElementById('etag2').selectedIndex].value;
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	
	var toSend = "/2012/pop_up/ajax/bankChange.php?num="+num
				 +"&BankName="+BankName;
				 
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('')
}
function inCom(val){


	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('D1b');
	 newInput2.id='etag1';
	 newInput2.style.width = '100px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeCom;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=5;
	
	 for(var i=0;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(val)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
		
			  switch(i){
				default :
					opts[i].text='==선택==';
					break;
				case 1 :
				     opts[i].text='흥국';
						break;
				case 2 :
					opts[i].text='동부';
					break;
				case 3 :
					opts[i].text='LiG';
					break;
				case 4 :
					opts[i].text='현대';
					break;

	   }
		
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

}

function changeCom(){

	myAjax=createAjax();
	var num=document.getElementById('num').value;
	//var CertiTableNum=document.getElementById('CertiTableNum').value;
	var toSend = "/2012/pop_up/ajax/changeCompany.php?num="+num
				 +"&com="+this.value;
		//alert(toSend)			 
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('');

}

function company(val){

	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B8b');
	 newInput2.id='etag';
	 newInput2.style.width = '160px';
	 newInput2.className='selectbox';
	newInput2.onchange=seleA9b;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=22;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(val)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				
			  switch(i){

				case 1 :
				     opts[i].text='전화방업(화상대화방업)';
						break;
				case 2 :
					opts[i].text='비디오감상실';
					break;
				case 3 :
					opts[i].text='비디오소극장';
					break;
				case 4 :
					opts[i].text='수면방업';
					break;
				case 5 : 
					opts[i].text='산후조리원';
					break;
				case 6 :
					opts[i].text='스크린골프장';
					break;
				case 7 :
					opts[i].text='노래방';
					break;
				case 8 :opts[i].text='유흥주점';
					break;
				case 9 :
					opts[i].text='콜라텍';
					break;
				case 10 :
					opts[i].text='안마시술소';
					break;
				case 11 :
					opts[i].text='영화상영관';
					break;
				case 12 :
					opts[i].text='학원';
					break;
				case 13 :
					opts[i].text='목욕탕(찜질방)';
					break;
				case 14 :
					opts[i].text='고시원';
					break;
				case 15 :
					opts[i].text='일반음식점';
					break;
				case 16 :
					opts[i].text='휴게음식점';
					break;
				case 17 :
					opts[i].text='제과점';
					break;
				case 18 :
					opts[i].text='인터넷컴퓨터게임시설(PC방)';
					break;
				case 19 :
					opts[i].text='게임제공업';
					break;
				case 20 :
					opts[i].text='복합유통제공업';
					break;
				case 21 :
					opts[i].text='실내권총사격장';
					break;	



	   }


	   switch(eval(val)){
		case 14:
				document.getElementById('a12b').innerHTML='객실';
				document.getElementById('a13b').innerHTML='실';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
			break;
		case 18 :	
				document.getElementById('a12b').innerHTML='대수.좌석수';
				document.getElementById('a13b').innerHTML='대';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
			break;
		case 19 :	
				document.getElementById('a12b').innerHTML='대수.좌석수';
				document.getElementById('a13b').innerHTML='대';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
			break;
		case 20 :	
				document.getElementById('a12b').innerHTML='대수.좌석수';
				document.getElementById('a13b').innerHTML='대';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
			break;
		default :
			document.getElementById('a12b').innerHTML='면적';
			document.getElementById('a13b').innerHTML='㎡';
			break;

	}



		
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

}
function seleA9b(){

	var val=document.getElementById('etag').options[document.getElementById('etag').selectedIndex].value;

	document.getElementById('a12b').innerHTML='';
	document.getElementById('a13b').innerHTML='';
	document.getElementById('a14b').innerHTML='';
	document.getElementById('a16b').innerHTML='';

	switch(eval(val)){

		case 14:
				document.getElementById('a12b').innerHTML='객실';
				document.getElementById('a13b').innerHTML='실';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
	
			break;
		case 18 :	
				document.getElementById('a12b').innerHTML='대수.좌석수';
				document.getElementById('a13b').innerHTML='대';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
			break;
		case 19 :	
				document.getElementById('a12b').innerHTML='대수.좌석수';
				document.getElementById('a13b').innerHTML='대';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
			break;
		case 20 :	
				document.getElementById('a12b').innerHTML='대수.좌석수';
				document.getElementById('a13b').innerHTML='대';
			break;
		default :
				document.getElementById('a12b').innerHTML='면적';
				document.getElementById('a13b').innerHTML='㎡';
				document.getElementById('a14b').innerHTML='면적';
				document.getElementById('a16b').innerHTML='㎡';
			break;
	

		
	}
	
}
function ShinButton(B6b){
	
	 var bButton=document.getElementById('sjButton');
		 bButton.className='input6';
		 //aButton.value=val1;
		// bButton.id='ch'+k;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=B6b;
		 bButton.onclick=vStore;

}
function serch(val){
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	//var CertiTableNum=document.getElementById('CertiTableNum').value;
	var toSend = "/2012/pop_up/ajax/dajSerch.php?num="+num
												// +"&CertiTableNum="+CertiTableNum;
		//alert(toSend)			 
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');

	
}

	addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해f


function store(){

	myAjax=createAjax();
	var query='';
	//alert('1')	
	var a1=encodeURIComponent(document.getElementById("B1b").value);
	var num=document.getElementById('num').value;
	if(!a1){

			alert('성명!!');
			document.getElementById('B1b').focus();
			return false;
		}

	//alert(a2)
	var a2=document.getElementById("B2b").value;
	if(!a2){

			alert('주민번호!');
			document.getElementById('B2b').focus();
			return false;
		}
	var a3=document.getElementById("B3b").value;
	if(!a3){

			alert('핸드폰번호');
			document.getElementById('B3b').focus();
			return false;
		}
	var a16=document.getElementById("B16b").value;
	if(!a16){

			alert('업소전화번호');
			document.getElementById('B16b').focus();
			return false;
		}
	var a4=document.getElementById("B4b").value;
	var a5=document.getElementById("B5b").value;
	if(!a5){

			alert('E-mail');
			document.getElementById('B5b').focus();
			return false;
		}
	var a6=document.getElementById("B6b").value;
	if(!a6){

			alert('사업자번호!!');
			document.getElementById('B6b').focus();
			return false;
		}
	var a7=encodeURIComponent(document.getElementById("B7b").value);
	if(!a7){

			alert('상호!!');
			document.getElementById('B7b').focus();
			return false;
		}
	var a8=val=document.getElementById('etag').options[document.getElementById('etag').selectedIndex].value;
	if(!a8){

			alert('업종 !!');
			document.getElementById('etag').focus();
			return false;
		}
	var a9=document.getElementById("B9b").value;
	if(!a9){

			alert('기초산출수 입력하세요!!');
			document.getElementById('B9b').focus();
			return false;
		}

	var a15=document.getElementById("B15b").value;
	if(a8==14 || a8==18 ||a8==19 ||a8==20  ){
			
		if(!a15){

			alert('기초산출수 면적을 입력하세요!!');
			document.getElementById('B15b').focus();
			return false;
			}
		}
	var a10=document.getElementById("B10b").value;
	if(!a10){

			alert('일련번호 !!');
			document.getElementById('B11b').focus();
			return false;
		}

	//우편물 관련

	var a28=document.getElementById("a28").value;
	var a29=encodeURIComponent(document.getElementById("a29").value);
	var a30=encodeURIComponent(document.getElementById("a30").value);
			if(num){

				var mess='수정하시겠습니까';

			}else{

				var mess='저장 하시겠습니까'

			}


	if(confirm(mess)){
		var toSend = "/2012/pop_up/ajax/dajoongAjax.php?a1="+a1
					   +"&a2="+a2
					   +"&a3="+a3
					   +"&a4="+a4
					   +"&a16="+a16
					   +"&a5="+a5
					   +"&a6="+a6
					   +"&a7="+a7
					   +"&a8="+a8
					   +"&a9="+a9
					   +"&a15="+a15
					   +"&a10="+a10
					   +"&num="+num
					   +"&a28="+a28
					   +"&a29="+a29
					   +"&a30="+a30
					//   +"&num="+num
					//   +"&a2="+a2+"&"
					//   +query;
//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=Receive;
		myAjax.send('');
	}else{


		return false;
	}


}


function jumiN_check(id,val){
	
	if(eval(val.length)==13){
			var phone_first	=val.substring(0,6)
			var phone_second =val.substring(6,13)
			document.getElementById(id).value=phone_first+"-"+phone_second;
			var jumin=phone_first+"-"+phone_second
	}
}
function jumiN_check_2(id,val){
	
	    if(val.length=='14'){
			
			var phone_first	=val.substring(0,6)
			var phone_second=val.substring(7,14)
			

			document.getElementById(id).value=phone_first+phone_second;
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