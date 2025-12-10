function Receive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				document.getElementById('a31').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a11")[0].text
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				//document.getElementById('B11b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("insert_sql")[0].text;
				document.getElementById('jang').value=myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].text;//설계번호
				document.getElementById('preminum').value=myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].text;//보험료
				document.getElementById('etag2').value=myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].text;
			    document.getElementById('virtual').value=myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].text;//2012hiauto 
				document.getElementById('jang2').value=myAjax.responseXML.documentElement.getElementsByTagName("b6b")[0].text;//증권번호
				document.getElementById('inputDay').value=myAjax.responseXML.documentElement.getElementsByTagName("b7b")[0].text;//증권번호
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('jang').value=myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].textContent;//설계번호
				document.getElementById('preminum').value=myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].textContent;//보험료
				document.getElementById('etag2').value=myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].textContent;
			    document.getElementById('virtual').value=myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].textContent;//2012hiauto 
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
			    document.getElementById('virtual').value=myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].text;//2012hiauto 
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

				var h1=myAjax.responseXML.documentElement.getElementsByTagName("h1")[0].text;//제조사
				var a2=myAjax.responseXML.documentElement.getElementsByTagName("a2")[0].text;//차종
				
				var a4=myAjax.responseXML.documentElement.getElementsByTagName("a4")[0].text;//타이어
				var a5=myAjax.responseXML.documentElement.getElementsByTagName("a5")[0].text;//1년,2년,3년기간
				var a6=myAjax.responseXML.documentElement.getElementsByTagName("a6")[0].text;//분납
				var hiautoCarNum=myAjax.responseXML.documentElement.getElementsByTagName("a7")[0].text;//선택된 차량명의 num
				document.getElementById('hiautoCarNum').value=hiautoCarNum;
				document.getElementById('ak4').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("tName")[0].text;
				document.getElementById('ak5').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bPreminum")[0].text;
				kind(h1,a2,a4,a5,a6,hiautoCarNum);
				document.getElementById('a8b').value=myAjax.responseXML.documentElement.getElementsByTagName("a8")[0].text;//차량번호
				document.getElementById('a9b').value=myAjax.responseXML.documentElement.getElementsByTagName("a9")[0].text;//차대번호
				document.getElementById('a10b').value=myAjax.responseXML.documentElement.getElementsByTagName("a10")[0].text;//최초등록일
				document.getElementById('a6b').value=myAjax.responseXML.documentElement.getElementsByTagName("a6b")[0].text;//주행거리
				document.getElementById('a7').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].text;
				document.getElementById('B2b').value=myAjax.responseXML.documentElement.getElementsByTagName("a2a")[0].text;
				document.getElementById('B3b').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].text;
				document.getElementById('B4b').value=myAjax.responseXML.documentElement.getElementsByTagName("a4a")[0].text;
				
				document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].text;
				document.getElementById('B5b').value=myAjax.responseXML.documentElement.getElementsByTagName("a5a")[0].text;
				
				document.getElementById('a29').value=myAjax.responseXML.documentElement.getElementsByTagName("a29a")[0].text;
		
				jinhang(myAjax.responseXML.documentElement.getElementsByTagName("a12a")[0].text);//진행상황
		

				inCom(myAjax.responseXML.documentElement.getElementsByTagName("b1b")[0].text);//보험회사
				selNum(myAjax.responseXML.documentElement.getElementsByTagName("b2b")[0].text,myAjax.responseXML.documentElement.getElementsByTagName("b3b")[0].text)//설계번호 보험료
				bankCom(myAjax.responseXML.documentElement.getElementsByTagName("b4b")[0].text,myAjax.responseXML.documentElement.getElementsByTagName("b5b")[0].text);
				certi(myAjax.responseXML.documentElement.getElementsByTagName("b6b")[0].text);//증권번호

				sele(myAjax.responseXML.documentElement.getElementsByTagName("b7b")[0].text);//처리유무
				
				document.getElementById('D1d').value=myAjax.responseXML.documentElement.getElementsByTagName("b8b")[0].text;//계약일


				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].text;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].text;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].text;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].text;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].text;
					
				}

			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				
				document.getElementById('B1b').value=myAjax.responseXML.documentElement.getElementsByTagName("a1a")[0].textContent;

				if(myAjax.responseXML.documentElement.getElementsByTagName("a1a")[0].textContent){

					document.getElementById('a32').value='수정';
				}

				var h1=myAjax.responseXML.documentElement.getElementsByTagName("h1")[0].textContent;//제조사
				var a2=myAjax.responseXML.documentElement.getElementsByTagName("a2")[0].textContent;//차종
				
				var a4=myAjax.responseXML.documentElement.getElementsByTagName("a4")[0].textContent;//타이어
				var a5=myAjax.responseXML.documentElement.getElementsByTagName("a5")[0].textContent;//1년,2년,3년기간
				var a6=myAjax.responseXML.documentElement.getElementsByTagName("a6")[0].textContent;//분납
				var hiautoCarNum=myAjax.responseXML.documentElement.getElementsByTagName("a7")[0].textContent;//선택된 차량명의 num
				document.getElementById('hiautoCarNum').value=hiautoCarNum;
				document.getElementById('ak4').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("tName")[0].textContent;
				document.getElementById('ak5').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bPreminum")[0].textContent;
				kind(h1,a2,a4,a5,a6,hiautoCarNum);
				document.getElementById('a8b').value=myAjax.responseXML.documentElement.getElementsByTagName("a8")[0].textContent;//차량번호
				document.getElementById('a9b').value=myAjax.responseXML.documentElement.getElementsByTagName("a9")[0].textContent;//차대번호
				document.getElementById('a10b').value=myAjax.responseXML.documentElement.getElementsByTagName("a10")[0].textContent;//최초등록일
				document.getElementById('a6b').value=myAjax.responseXML.documentElement.getElementsByTagName("a6b")[0].textContent;//주행거리
				document.getElementById('a7').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].textContent;
				document.getElementById('B2b').value=myAjax.responseXML.documentElement.getElementsByTagName("a2a")[0].textContent;
				document.getElementById('B3b').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].textContent;
				document.getElementById('B4b').value=myAjax.responseXML.documentElement.getElementsByTagName("a4a")[0].textContent;
				
				document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("a3a")[0].textContent;
				document.getElementById('B5b').value=myAjax.responseXML.documentElement.getElementsByTagName("a5a")[0].textContent;
				
				document.getElementById('a29').value=myAjax.responseXML.documentElement.getElementsByTagName("a29a")[0].textContent;
		
				jinhang(myAjax.responseXML.documentElement.getElementsByTagName("a12a")[0].textContent);//진행상황
		

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
					
				}	
			}
			
	}else{
		//	
	}


}
function kind(h1,a2,a4,a5,a6,a7){

			//제조사 
			//alert('2')
			var newInput2=document.createElement("select");
			 var aJumin=document.getElementById('h1');
			 newInput2.id='etag';
			 newInput2.style.width = '120px';
			 newInput2.className='selectbox';
			newInput2.onchange=changeMaker;
			 var opts=newInput2.options;
			//alert(k2)
			opts.length=7;
			
			 for(var i=1;i<opts.length;i++){	 
				opts[i].value=i;
					if(opts[i].value==eval(h1)){	
					newInput2.selectedIndex=i;
					}
						switch(i){
						   case 6 :
							  opts[i].text='==제조사 선택==';
								
								break;
							case 1 :
								opts[i].text='현대';
							break;
							case 2 :
								opts[i].text='기아';
							break;
							case 3 :
								opts[i].text='쉐보레';
							break;
							case 4 :
								opts[i].text='쌍용';
							break;
							case 5 :
								opts[i].text='르노삼성';
							break;

							
						}
					
			
			 }
	
	 aJumin.appendChild(newInput2);
	 //승용또는RV 
			var newInput2=document.createElement("select");
			 var aJumin=document.getElementById('a2');
			 newInput2.id='etag2';
			 newInput2.style.width = '120px';
			 newInput2.className='selectbox';
			 newInput2.onchange=changeKind;
			 var opts=newInput2.options;
			//alert(k2)
			opts.length=4;
			 for(var i=1;i<opts.length;i++){	 
				opts[i].value=i;	
					if(opts[i].value==eval(a2)){	
					newInput2.selectedIndex=i;
					}
						switch(i){
						   case 3 :
							  opts[i].text='==차종선택==';
						   
								break;
							case 1 :
								opts[i].text='승용';
							break;
							case 2 :
								opts[i].text='RV';
							break;
	
						}
			 }
	
	 aJumin.appendChild(newInput2);
	 //타이어 교체 비용 
	
	 var newInput2=document.createElement("select");
		 var aJumin=document.getElementById('a4');
		 newInput2.id='taie';
		 newInput2.style.width = '170px';
		 newInput2.className='selectbox';
		 newInput2.onchange=changeCar;
		 var opts=newInput2.options;
		//alert(k2)
		opts.length=3;
		 for(var i=1;i<opts.length;i++){	 
			opts[i].value=i;
					if(opts[i].value==eval(a4)){	
					newInput2.selectedIndex=i;
					}
					switch(i){
					   case 2 :
						  opts[i].text='타이어 교체 비용 미가입';
					  
							break;
						case 1 :
							opts[i].text='타이어 교체 비용 가입';
						break;
						

					}
		 }
	
	 aJumin.appendChild(newInput2);

	//보험기간
	 var newInput2=document.createElement("select");
		 var aJumin=document.getElementById('a5');
		 newInput2.id='gigan';
		 newInput2.style.width = '100px';
		 newInput2.className='selectbox';
		newInput2.onchange=changeCar;
		 var opts=newInput2.options;
		//alert(k2)
		opts.length=4;
		 for(var i=1;i<opts.length;i++){	 
			opts[i].value=i;	
				if(opts[i].value==eval(a5)){	
					newInput2.selectedIndex=i;
					}
					switch(i){
					   case 1 :
						  opts[i].text='1년';
					   newInput2.selectedIndex=i;
							break;
						case 2 :
							opts[i].text='2년';
						break;
						case 3 :
							opts[i].text='3년';
						break;
						

					}
		 }
	
	 aJumin.appendChild(newInput2);
	 //보험기간
	 var newInput2=document.createElement("select");
		 var aJumin=document.getElementById('a6');
		 newInput2.id='bunnab';
		 newInput2.style.width = '100px';
		 newInput2.className='selectbox';
		 newInput2.onchange=changeCar;
		 var opts=newInput2.options;
		//alert(k2)
		opts.length=5;
		 for(var i=1;i<opts.length;i++){	 
				
					switch(i){
					   case 1 :
						  opts[i].value=1;	
						  opts[i].text='일시납';
					      newInput2.selectedIndex=i;
							break;
						case 2 :
							opts[i].value=1.02;
							opts[i].text='연 2회납';
						break;
						case 3 :
							opts[i].value=1.03;
							opts[i].text='연 4회납';
						break;
						case 4 :
							opts[i].value=1.0333;
							opts[i].text='연 12회납';
						break;
						

					}

			if(opts[i].value==eval(a6)){	
					newInput2.selectedIndex=i;
					}
		 }
	
	 aJumin.appendChild(newInput2);

	 //차명 정보 
	  var newInput2=document.createElement("select");
			 var aJumin=document.getElementById('ak3');
			 newInput2.id='car';
			 newInput2.style.width = '120px';
			 newInput2.className='selectbox';
			 newInput2.onchange=changeCar;
			 var opts=newInput2.options;
			//alert(k2)
			opts.length=eval(myAjax.responseXML.documentElement.getElementsByTagName("carName").length);
			
			 for(var i=0;i<opts.length;i++){	
				 if(myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[i].text!=undefined){
					opts[i].value=myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[i].text;
					opts[i].text=myAjax.responseXML.documentElement.getElementsByTagName("carName")[i].text;
				 }else{
					opts[i].value=myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[i].textContent;
					opts[i].text=myAjax.responseXML.documentElement.getElementsByTagName("carName")[i].textContent;

				 }
				if(opts[i].value==eval(a7)){	
					newInput2.selectedIndex=i;
					}
			
			 }
	
	 aJumin.appendChild(newInput2);
}
function changeReceive(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//	alert(myAjax.responseText);
				

		   var newInput2=document.createElement("select");
			 var aJumin=document.getElementById('ak3');
			 newInput2.id='car';
			 newInput2.style.width = '120px';
			 newInput2.className='selectbox';
			 newInput2.onchange=changeCar;
			 var opts=newInput2.options;
			//alert(k2)
			opts.length=eval(myAjax.responseXML.documentElement.getElementsByTagName("carName").length);
			
			 for(var i=0;i<opts.length;i++){	
				 if(myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[i].text!=undefined){
					opts[i].value=myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[i].text;
					opts[i].text=myAjax.responseXML.documentElement.getElementsByTagName("carName")[i].text;
				 }else{
					opts[i].value=myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[i].textContent;
					opts[i].text=myAjax.responseXML.documentElement.getElementsByTagName("carName")[i].textContent;

				 }
				/*	if(i==eval(myAjax.responseXML.documentElement.getElementsByTagName("carName").length)){

							opts[i].text='==차종선택==';
						   newInput2.selectedIndex=i;
					}*/
					/*	switch(i){
						   case 3 :
							opts[i].text='==차종선택==';
						   newInput2.selectedIndex=i;
								break;
							case 1 :
								opts[i].text='승용';
							break;
							case 2 :
								opts[i].text='RV';
							break;
							
							
						}*/
					
			
			 }
	
	 aJumin.appendChild(newInput2);
			document.getElementById('car').focus();
			
		/*	if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
								
			}*/
			
	}else{
		//	
	}


}
function CarReceive(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("preminum1")[0].text)
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[0].text)
		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
					
					document.getElementById('hiautoCarNum').value=myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[0].text;
					document.getElementById('a7').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].text;
					document.getElementById('ak4').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("tName")[0].text;
					document.getElementById('ak5').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bPreminum")[0].text;
					document.getElementById('ak6').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("tName")[0].text;
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					document.getElementById('hiautoCarNum').value=myAjax.responseXML.documentElement.getElementsByTagName("hiautoCarNum")[0].textContent;
					document.getElementById('a7').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].textContent;
					document.getElementById('ak4').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("tName")[0].textContent;
					document.getElementById('ak5').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bPreminum")[0].textContent;
					document.getElementById('ak6').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("tName")[0].textContent;
								
			}
			
	}else{
		//	
	}


}
//차종류에 따라 보험료 산출 하기 위해
function changeCar(){
	//alert('1')
	//alert(this.value)
	myAjax=createAjax();
	document.getElementById('hiautoCarNum').value='';
	document.getElementById('a7').innerHTML='';//보험료
	document.getElementById('ak4').innerHTML='';//타이어 교체
	document.getElementById('ak5').innerHTML='';//분납
	//document.getElementById('ak6').innerHTML='';//타이어 교체

	var maker=document.getElementById('etag').options[document.getElementById('etag').selectedIndex].value;//1현대,2기아,3,쉐보레,4쌍용5르노삼성
	var kind=document.getElementById('etag2').options[document.getElementById('etag2').selectedIndex].value;////1승용 2RV
	var CarNum=document.getElementById('car').options[document.getElementById('car').selectedIndex].value;
	var bunanb=document.getElementById('bunnab').options[document.getElementById('bunnab').selectedIndex].value;//1일시납2.2회납3,4.12회납
	var gigan=document.getElementById('gigan').options[document.getElementById('gigan').selectedIndex].value;//1년2년3년
	var taie=document.getElementById('taie').options[document.getElementById('taie').selectedIndex].value;//타이어 1 가입 ,2미가입
	//if(taie==1){

	

		if(maker==6){

			document.getElementById('etag').focus();
			return false;
		}

		if(kind==3){

			document.getElementById('etag2').focus();
			return false;
		}

		if(CarNum==0){

			document.getElementById('car').focus();
			return false;
		}

	 var toSend = "../../hiautocare/ajax/carPreminum.php?hiautoCarNum="+CarNum
						+"&bunanb="+bunanb
						+"&gigan="+gigan
						+"&taie="+taie;			  
		//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=CarReceive;
	myAjax.send('')
}

//maker 선택
function changeMaker(){
	
	myAjax=createAjax();
	var maker=document.getElementById('etag').options[document.getElementById('etag').selectedIndex].value;//1현대,2기아,3,쉐보레,4쌍용5르노삼성
	var kind=document.getElementById('etag2').options[document.getElementById('etag2').selectedIndex].value;////1승용 2RV
	

	if(kind==3){
		//alert('차종을 선택하세요!');
		document.getElementById('etag2').focus();
		return false;
	}else{
		document.getElementById("ak3").innerHTML='';
	   var toSend = "../../hiautocare/ajax/maker.php?kind="+kind
						+"&maker="+maker;
					  

		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=changeReceive;
		myAjax.send('')

	}

}
//차종 선택
function changeKind(){
	//alert(this.value);//승용2,RV 3
	myAjax=createAjax();
	var maker=document.getElementById('etag').options[document.getElementById('etag').selectedIndex].value;//1현대,2기아,3,쉐보레,4쌍용5르노삼성
	var kind=document.getElementById('etag2').options[document.getElementById('etag2').selectedIndex].value;////1승용 2RV
	
	
	if(maker==6){
		//alert('제조사 부터 선택하세요!');
		document.getElementById('etag').focus();
		return false;
	}else{
		
		if(kind==3){
			alert("승용 또는 RV를 선택하세요!!");
			document.getElementById("ak3").innerHTML='';
				return false;
		}else{

			document.getElementById("ak3").innerHTML='';
		   var toSend = "../../hiautocare/ajax/maker.php?kind="+kind
							+"&maker="+maker;
						  

			//alert(toSend);

			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=changeReceive;
			myAjax.send('')
		}
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

	var toSend = "../hiautocare/ajax/call.php?num="+num
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
	
	var toSend = "./ajax/hiautofaxInput.php?num="+num
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
	
	var toSend = "./ajax/hiautochInput.php?num="+num
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
	
	if(eval(document.getElementById('insurance').options[document.getElementById('insurance').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('insurance').focus();
		document.getElementById('jang').value='';
		return false;

	}

	var inSNum=eval(document.getElementById('insurance').options[document.getElementById('insurance').selectedIndex].value);
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
	
	var toSend = "./ajax/hiautocertiInput.php?num="+num
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
	
	if(eval(document.getElementById('insurance').options[document.getElementById('insurance').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('insurance').focus();
		document.getElementById('jang').value='';
		return false;

	}
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	

	if(this.value.length>4){
	var toSend = "./ajax/hiautpreminumChange.php?num="+num
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
	
	if(eval(document.getElementById('insurance').options[document.getElementById('insurance').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('insurance').focus();
		document.getElementById('jang').value='';
		return false;

	}

	var inSNum=eval(document.getElementById('insurance').options[document.getElementById('insurance').selectedIndex].value);
	var selNum=document.getElementById('jang').value;

switch(inSNum){
	default :
	if(selNum.length==4){
		var cyear=document.getElementById('B4b').value.substring(5,7);
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
		var cyear=document.getElementById('B4b').value.substring(0,4);
		//alert(cyear)
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
	
	var toSend = "./ajax/hiautoselNumChange.php?num="+num
				 +"&inSNum="+inSNum
				 +"&selNum="+this.value;
				 
	
	alert(toSend)

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
	 newInput2.id='bankName';
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
	
		var toSend = "./ajax/hiautoinPutDay.php?num="+num
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
	
	if(eval(document.getElementById('insurance').options[document.getElementById('insurance').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('insurance').focus();
		document.getElementById('jang').value='';
		return false;

	}
	if(document.getElementById('bankName').options[document.getElementById('bankName').selectedIndex].value==1){
		
		
			var phone_first	= this.value.substring(0,5)
			var phone_second =this.value.substring(5,10)
			var phone_third	 =this.value.substring(10,15)
			this.value=phone_first+"-"+phone_second+"-"+phone_third;
	}

	//alert(this.value)
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	if(this.value.length>6){
	var toSend = "./ajax/hiautovirtualChange.php?num="+num
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
	
	if(eval(document.getElementById('insurance').options[document.getElementById('insurance').selectedIndex].value)<1){
		alert('보험회사 부터 입력후 ')
		document.getElementById('insurance').focus();
		document.getElementById('jang').value='';
		return false;

	}

	var BankName=document.getElementById('bankName').options[document.getElementById('bankName').selectedIndex].value;
	myAjax=createAjax();
	var num=document.getElementById('num').value;
	
	var toSend = "./ajax/hiautobankChange.php?num="+num
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
	 newInput2.id='insurance';
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
	var toSend = "./ajax/hiautochangeCompany.php?num="+num
				 +"&com="+this.value;
		//alert(toSend)			 
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('');

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
	var toSend = "./ajax/hiautoSerch.php?num="+num
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
	var a4=document.getElementById("B5b").value;
	if(!a4){

			alert('E-mail');
			document.getElementById('B5b').focus();
			return false;
		}
	//var a9=document.getElementById("comkind2").value;
	var query='';
	var maker=document.getElementById('etag').options[document.getElementById('etag').selectedIndex].value;//1현대,2기아,3,쉐보레,4쌍용5르노삼성
	if(maker==6){
		//alert('제조사 부터 선택하세요!');
		document.getElementById('etag').focus();
		return false;
	}
	var kind=document.getElementById('etag2').options[document.getElementById('etag2').selectedIndex].value;////1승용 2RV
	if(kind==3){
		//alert('제조사 부터 선택하세요!');
		document.getElementById('etag2').focus();
		return false;
	}
	var CarNum=document.getElementById('car').options[document.getElementById('car').selectedIndex].value;

	
	if(CarNum==0){

		document.getElementById('car').focus();
		return false;

	}

	var hiautoCarNum=document.getElementById("hiautoCarNum").value;
	if(!hiautoCarNum){

		alert('보험료 부터 산출하세요!!')

		return false;
	}
	var bunanb=document.getElementById('bunnab').options[document.getElementById('bunnab').selectedIndex].value;//1일시납2.2회납3,4.12회납
	var gigan=document.getElementById('gigan').options[document.getElementById('gigan').selectedIndex].value;//1년2년3년
	var taie=document.getElementById('taie').options[document.getElementById('taie').selectedIndex].value;//타이어 1 가입 ,2미가입
			
	var a8=encodeURIComponent(document.getElementById("a8b").value);
	if(!a8){

			alert('차량번호!!');
			document.getElementById('a8b').focus();
			return false;
		}
	var a9=encodeURIComponent(document.getElementById("a9b").value);
	if(!a9){

			alert('차대번호!!');
			document.getElementById('a9b').focus();
			return false;
		}
	var a10=encodeURIComponent(document.getElementById("a10b").value);
	if(!a10){

			alert('가입시 주행거리!!');
			document.getElementById('a10b').focus();
			return false;
		}
	var a6=document.getElementById("a6b").value;
	if(!a6){

			alert('차량 최초 등록일!!'); 
			document.getElementById('a6b').focus();
			return false;
		}
		if(num){

				var mess='수정하시겠습니까';

			}else{

				var mess='저장 하시겠습니까'

			}
	var a29=encodeURIComponent(document.getElementById("a29").value);
	if(confirm(mess)){
		var toSend = "./ajax/hiautoAjax.php?a1="+a1
					   +"&a2="+a2
					   +"&a3="+a3
					   +"&a4="+a4
					   +"&maker="+maker
					   +"&kind="+kind
					   +"&CarNum="+CarNum
					   +"&hiautoCarNum="+hiautoCarNum
					   +"&bunanb="+bunanb
					   +"&gigan="+gigan
					   +"&taie="+taie
					   +"&a8="+a8
					   +"&a9="+a9
					   +"&a10="+a10
					   +"&a6="+a6
					   +"&a29="+a29
					   +"&num="+num
					//   +"&a2="+a2+"&"
					//   +query;
alert(toSend);

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