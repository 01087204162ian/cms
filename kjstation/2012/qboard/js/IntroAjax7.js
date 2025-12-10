
function order(){
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../db/db.php','new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
}
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
						//checkSele(k);bunho
						document.getElementById('bunho'+k).innerHTML=k+1;
						detailSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text;
						
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].text;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						var cheong=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text;

						var first=myAjax.responseXML.documentElement.getElementsByTagName("a41")[k].text; //1회차보험료
						//alert(first)
						switch(eval(cheong)){

							case 1 :	
								document.getElementById('A4a'+k).innerHTML='청약';
								document.getElementById('A4a'+k).style.color='#E4690C';
								//document.getElementById('A9a'+k).style.color='#E4690C';
								//document.getElementById('A11a'+k).style.color='#E4690C';

								
								CertiSj(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text,cheong,first);//증권번호 
								// 전증권번호
						//document.getElementById('A39a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a39")[k].text;
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
								document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;

							break;
							case 3 :
								document.getElementById('A4a'+k).innerHTML='해지';
								document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지';
							    document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text
							break;
							default :

								sele(k,myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text);//상태를 만들기 위해
								break;
						}
						//document.getElementById('A5a'+k).innerHTML=
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text)==1){//흥국화재는 탁송이 없다
							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text);
						}
						var sago=myAjax.responseXML.documentElement.getElementsByTagName("a20")[k].text;

						var hp=myAjax.responseXML.documentElement.getElementsByTagName("a34")[k].text;; //핸드폰
						var shp=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].text; //통신사
						var nhp=myAjax.responseXML.documentElement.getElementsByTagName("a31")[k].text; //명의자
						var address=myAjax.responseXML.documentElement.getElementsByTagName("a32")[k].text; //주소

						var injeong=myAjax.responseXML.documentElement.getElementsByTagName("a33")[k].text; //주소
						hpHone(k,hp,shp,nhp,address,injeong);//순서,핸드폰,통신사,명의자,주소,인증
							checkSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].text,myAjax.responseXML.documentElement.getElementsByTagName("a18")[k].text,sago);
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;

						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text))
						{
								case 1 :
									document.getElementById('A8a'+k).innerHTML='흥국';
								    document.getElementById('A8a'+k).style.color='#F345FC';

									
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='동부';
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
									 document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='한화';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A8a'+k).innerHTML='더 케이';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;


						}
						document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						//document.getElementById('A9a'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].text;
						//document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].text;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].text;
						document.getElementById('eNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("eNum")[k].text;
						//document.getElementById('endorseDay'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].text;
						seongSelect(myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].text,k,myAjax.responseXML.documentElement.getElementsByTagName("rate")[k].text);
						document.getElementById("A15a"+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a15")[k].text;
						document.getElementById("A19a"+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a19")[k].text;
							//moSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a17")[k].text);
						if(myAjax.responseXML.documentElement.getElementsByTagName("a16")[k].text){
							document.getElementById("A16a"+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a16")[k].text;
							document.getElementById('A16a'+k).style.color='#E4690C';
					
						}

						//	alert(myAjax.responseXML.documentElement.getElementsByTagName("a39")[k].text);
						
					}else{
						document.getElementById('bunho'+k).innerHTML=k+1;
						detailSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						var cheong=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent;
						var first=myAjax.responseXML.documentElement.getElementsByTagName("a41")[k].textContent; //1회차보험료
						//alert(cheong)
						switch(eval(cheong)){

							case 1 :	
								document.getElementById('A4a'+k).innerHTML='청약';
								document.getElementById('A4a'+k).style.color='#E4690C';
								//document.getElementById('A9a'+k).style.color='#E4690C';
								//document.getElementById('A11a'+k).style.color='#E4690C';
								CertiSj(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent,cheong,first);//증권번호 
								//document.getElementById('A39a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a39")[k].textContent;
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
								document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;

							break;
							case 3 :
								document.getElementById('A4a'+k).innerHTML='해지';
								document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지';
							    document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent
							break;
							default :

								sele(k,myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent);//상태를 만들기 위해
								break;
						}
						//document.getElementById('A5a'+k).innerHTML=
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent)==1){//흥국화재는 탁송이 없다
							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent);
						}
						var sago=myAjax.responseXML.documentElement.getElementsByTagName("a20")[k].textContent;
						
						var hp=myAjax.responseXML.documentElement.getElementsByTagName("a34")[k].textContent;; //핸드폰
						var shp=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].textContent; //통신사
						var nhp=myAjax.responseXML.documentElement.getElementsByTagName("a31")[k].textContent; //명의자
						var address=myAjax.responseXML.documentElement.getElementsByTagName("a32")[k].textContent; //주소

						var injeong=myAjax.responseXML.documentElement.getElementsByTagName("a33")[k].textContent; //주소
						hpHone(k,hp,shp,nhp,address,injeong);//순서,핸드폰,통신사,명의자,주소,인증
							checkSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].textContent,myAjax.responseXML.documentElement.getElementsByTagName("a18")[k].textContent,sago);
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;

						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent))
						{
								case 1 :
									document.getElementById('A8a'+k).innerHTML='흥국';
								    document.getElementById('A8a'+k).style.color='#F345FC';

									
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='동부';
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
									 document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='한화';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A8a'+k).innerHTML='더 케이';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 9 :
									document.getElementById('A8a'+k).innerHTML='메리츠';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;


						}
						document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						//document.getElementById('A9a'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].textContent;
						//document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].textContent;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].textContent;
						document.getElementById('eNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("eNum")[k].textContent;
						//document.getElementById('endorseDay'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].textContent;
						seongSelect(myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].textContent,k,myAjax.responseXML.documentElement.getElementsByTagName("rate")[k].textContent);
						document.getElementById("A15a"+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a15")[k].textContent;
						//document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].textContent;
						if(myAjax.responseXML.documentElement.getElementsByTagName("a16")[k].textContent){
						document.getElementById("A16a"+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a16")[k].textContent;
						document.getElementById('A16a'+k).style.color='#E4690C';
						}
						
					}
				
				}
		
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}

function moSele(k,k2){
	 var aButton=document.getElementById('A17a'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 aButton.style.cursor='hand';
	 aButton.style.width = '40px';
	 aButton.innerHTML=k2;
	 aButton.onclick=endorseExcel;

	
}

function endorseExcel(){

//alert(this.id)
	if(this.id.length==5){
		val1=this.id.substr(4,5);
	}else{
		val1=this.id.substr(4,6);
	}
	//var driver_num='num'+val1;

	//alert(val1);
 var mNum=  document.getElementById('num'+val1).value
 var mCertiNum=document.getElementById('A19a'+val1).value; //모계약의 certiNum

// alert(mCertiNum);

if(mCertiNum){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/mgEx.php?mNum='+mNum+'&mCertiNum='+mCertiNum,'meEx','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}

	
}
function seongSelect(Pna_b,k,rate__){

	 var sjInput3=document.createElement("select");
	 var sjJumin=document.getElementById('A14a'+k);
     sjInput3.id='msang'+k;
	 sjInput3.style.width = '60px';
	 sjInput3.className='selectbox';
	 sjInput3.onchange=changeInputSangtae;//회차별 입금을 표시하기 위해
	 var opts3=sjInput3.options;
		opts3.length=3;
		for(var i=1;i<opts3.length;i++){
			
		opts3[i].value=i;

			switch(i){
			
				case 1 :
					opts3[i].text='미처리';
				break;
				case 2 :
					opts3[i].text='처리';
				break;
				
				
			}
			/*(if(i==0){
				
				opts3[i].text='선택';
			}else{
			
				opts3[i].text=i+'회차';
			}*/
			if(opts3[i].value==eval(Pna_b)){
				sjInput3.selectedIndex=i;
			}
		}
		sjJumin.appendChild(sjInput3);



		//개인별 요율
	//	alert(rate__);
	 var sjInput4=document.createElement("select");
	 var rate=document.getElementById('A44a'+k);
     sjInput4.id='rate_'+k;
	 sjInput4.style.width = '60px';
	 sjInput4.className='selectbox';
	 sjInput4.onchange=changeRate;//개인별 요율 
	 var opts3=sjInput4.options;
		opts3.length=5;
		for(var i=0;i<opts3.length;i++){
			
		//opts3[i].value=i;

			switch(i){
				case 0 :
					opts3[i].text='선택';
					opts3[i].value='-1'
				break;
				case 1 :
					opts3[i].text='1';
					opts3[i].value='1'
				break;
				case 2 :
					opts3[i].text='0.9';
					opts3[i].value='2'
				break;
			/*	case 3 :
					opts3[i].text='심사';
				break;
				case 4 :
					opts3[i].text='배서';
				break;*/
			}
			/*(if(i==0){
				
				opts3[i].text='선택';
			}else{
			
				opts3[i].text=i+'회차';
			}*/
			if(opts3[i].value==eval(rate__)){
				sjInput4.selectedIndex=i;
			}
		}
		rate.appendChild(sjInput4);

}
function nabResponse() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		
	   
		alert('처리완료!!');
	}else{
		//	
	}
}
/*개인별 요율을 조정하기 위해 */
function changeRate(){
	var nn=this.getAttribute("id");/*'rate_'+k*/
	//alert(nn)
	if(nn.length==7){
		val1=nn.substr(5,6);
	}else{
		val1=nn.substr(5,7);
	}
	var driver_num='num'+val1;

	//alert(val1);

	
 // alert(document.getElementById('peck'+val1).innerHTML);
  //var driver_num=document.getElementById(driver_num).value;
	var jumin=document.getElementById('A3a'+val1).innerHTML;
	var val2=document.getElementById("rate_"+val1).options[document.getElementById("rate_"+val1).selectedIndex].value;
	if(document.getElementById('A4a'+val1).innerHTML=='해지'){
		var policy=document.getElementById('A9a'+val1).innerHTML
	}else{
		var policy=document.getElementById('peck'+val1).value;
	}
	if(val2>=1){
		if(confirm('처리  합니까?')){
			var toSend = "./ajax/boardRateChange.php?policy=" + policy
					+"&policy="+policy
					+"&jumin="+jumin
					+"&rate_="+val2;
			//alert(toSend);
			//displayLoading(document.getElementById("imgkor"));
			//document.getElementById("url").innerHTML = toSend;
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=nabResponse;
			myAjax.send('');
		}
	  }else{

			alert('회차를 선택하세요');
	  }

}
/*납입 회차에 따라  */
function changeInputSangtae(){
	//var val2=eval(this.getAttribute("value"));//1회차면 1,2회차이면2
	var nn=this.getAttribute("id");/*'msang'+k*/
	//alert(nn)
	if(nn.length==7){
		val1=nn.substr(5,6);
	}else{
		val1=nn.substr(5,7);
	}
	var driver_num='num'+val1;
  
  var driver_num=document.getElementById(driver_num).value
	
var val2=document.getElementById("msang"+val1).options[document.getElementById("msang"+val1).selectedIndex].value;
/*if(bank==0){
		alert('입금 은행 부터!!!')
		document.getElementById("bsang"+val1).focus();
		return false;
	}else{*/
	//alert(val2)
      if(val2>=1){
		//if(confirm('처리  합니까?')){
			var toSend = "./ajax/ChangeCh.php?driver_num=" + driver_num
					//+"&endorse_day="+endorse_day
					//+"&useruid="+useruid
					+"&sunbun="+val2;
			//alert(toSend);
			//displayLoading(document.getElementById("imgkor"));
			//document.getElementById("url").innerHTML = toSend;
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=nabResponse;
			myAjax.send('');
		//}
	  }else{

			alert('회차를 선택하세요');
	  }
}
function detailSele(k){
	
	/*일일 버튼 */
		 var bButton=document.getElementById('bunho'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		
		 bButton.innerHTML=k+1;
		 bButton.onclick=detailEndorse;
	
}
function detailEndorse(){//상세보기
	var nn=this.getAttribute("id");
//alert(nn);
	if(nn.length==6){
		nn=nn.substr(5,6);
	}else{
		nn=nn.substr(5,7);
	}

	//alert(nn);
	var daeriComNum=document.getElementById('cNum'+nn).value;	
	var CertiTableNum=document.getElementById('pNum'+nn).value;
	var eNum=document.getElementById('eNum'+nn).value;//배서번호
	var endorseDay=document.getElementById('A10a'+nn).innerHTML;//배서번호
	//var daeriComNum=document.getElementById('cNum'+nn).value;
	
	if(eNum){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/boardDaeriEndorse.php?daeriComNum='+daeriComNum+'&CertiTableNum='+CertiTableNum+'&eNum='+eNum+'&endorseDay='+endorseDay,'new_22Union','left='+winl+',top='+wint+',resizable=yes,width=990,height=540,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}
function CertiSj(k,inwo,cheon,first){//청약이면 

	var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A7a'+k);
	 newInput2.type='checkbox';
	 newInput2.id='Check'+k;
	 //newInput2.onclick=bonagi_3;//핸드폰 번호를 보내기 위해(smaAjac.js)에 있습니다
	 newInput2.style.width = '10px';
	 aJumin.appendChild(newInput2);



	//alert('1')
	 var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A9a'+k);
	 newInput2.type='text';
	 if(k%2==0){
		 newInput2.className='handphoneInput0';
	 }else{
		newInput2.className='handphoneInput';
	 }
	 newInput2.id='peck'+k;
	 if(cheon==1){
	 newInput2.style.color="#E4690C";
	 }
	 newInput2.value=inwo;

	// alert(inwo)
	 newInput2.onblur=certiInput;
	 newInput2.onclick=certi;//
	 //newInput2.style.width = '75px';
	 
	 aJumin.appendChild(newInput2);
	//alert(document.getElementById('peck'+k));
//1회차보험료 입력하기 위해 
	  var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A41a'+k);
	 newInput2.type='text';
	 if(k%2==0){
		 newInput2.className='handphoneInput0';
	 }else{
		newInput2.className='handphoneInput';
	 }
	 newInput2.id='firs'+k;
	 if(cheon==1){
	 newInput2.style.color="#E4690C";
	 }
	 newInput2.value=first;
	 newInput2.onblur=preminum1Input;
	 //newInput2.onclick=certi;//
	 //newInput2.style.width = '75px';
	 
	 aJumin.appendChild(newInput2);



}
function daeriSerch(){

	//alert(this.id)
	if(this.id.length==4){
		var val=this.id.substr(3,4);
	}else{
		var val=this.id.substr(3,5);
	}
	var num=document.getElementById("cNum"+val).value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('pNum'+val).value;
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/daeriList.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=1000,height=800,scrollbars=yes,status=yes')
}
function Etage(k,etag){

	switch(eval(etag)){
		case 1 :
			$('#A5a'+k).html('일반');

			break;
		case 2 :
			$('#A5a'+k).html('탁송');

			break;
		case 3 :
			$('#A5a'+k).html('확탁송');

			break;

	}
/*
	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A5a'+k);
	 newInput2.id='etag'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	//newInput2.onchange=changeCh;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=4;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(etag)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 1 :
					  opts[i].text='일반';
						break;
					case 2 :
						opts[i].text='탁송';
					break;
					case 3 :
						opts[i].text='전차랑';
					break;
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);*/


}
function serch(val){
	
	var s_contents=document.getElementById("s_contents").value;
    var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
			myAjax=createAjax();
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
			
			for(var i=0;i<20;i++){
				document.getElementById("bunho"+i).innerHTML='';
				document.getElementById("A2a"+i).innerHTML='';
				document.getElementById("A3a"+i).innerHTML='';
				document.getElementById("A4a"+i).innerHTML='';
				document.getElementById("A5a"+i).innerHTML='';
				document.getElementById("A6a"+i).innerHTML='';
				document.getElementById("A7a"+i).innerHTML='';
				document.getElementById("A8a"+i).innerHTML='';
				document.getElementById("A9a"+i).innerHTML='';
				document.getElementById("A10a"+i).innerHTML='';
			//	document.getElementById("A11a"+i).innerHTML='';
			//	document.getElementById("A13a"+i).innerHTML='';
				document.getElementById("A14a"+i).innerHTML='';
				document.getElementById("A15a"+i).innerHTML='';
				document.getElementById("A16a"+i).innerHTML='';
			//	document.getElementById("A17a"+i).innerHTML='';
			//	document.getElementById("A18a"+i).innerHTML='';
				//document.getElementById("A39a"+i).innerHTML='';
				document.getElementById("A29a"+i).innerHTML='';
				document.getElementById("A30a"+i).innerHTML='';
				document.getElementById("A31a"+i).innerHTML='';
				document.getElementById("A32a"+i).innerHTML='';
				document.getElementById("A41a"+i).innerHTML='';
				document.getElementById("A44a"+i).innerHTML='';
				document.getElementById("insCom"+i).value='';
				document.getElementById("num"+i).value='';
				document.getElementById("cNum"+i).value='';
				document.getElementById("iNum"+i).value='';
				document.getElementById("pNum"+i).value='';
				document.getElementById("A19a"+i).value='';
				document.getElementById("endorseDay"+i).value='';


			}
			var damdanja=document.getElementById('insuranceComNum').options[document.getElementById('damdanja').selectedIndex].value;
			var chr=document.getElementById('chr').options[document.getElementById('chr').selectedIndex].value;
			var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
			var toSend = "./ajax/ajax7.php?sigi="+sigi
						+"&end="+end
						+"&chr="+chr
						+"&s_contents="+s_contents
				        +"&insuranceComNum="+insuranceComNum
				        +"&damdanja="+damdanja
					    +"&page="+page
						+"&dongbuCerti="+$("#liggC").val();
	
//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');


	  certiList(sigi,end,'',s_contents,insuranceComNum,damdanja,page);
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function checkSele(k,k2,k3,sago){//증권자체가 탁송인지 아닌지

	//document.getElementById('A6a'+k).innerHTML
	 var aButton=document.getElementById('A6a'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 aButton.style.cursor='hand';
	 aButton.style.width = '40px';
	 aButton.innerHTML=k2;
	 aButton.onclick=DaeriMemberCompany;

	/* var bButton=document.getElementById('A18a'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;

	//alert(k3);
	 bButton.style.cursor='hand';
	 bButton.style.width = '40px';
		var genName='';
	 switch(eval(k3)){
		 case 1 :
			 bButton.innerHTML='일반';
			 break;
		 case 2 :
			bButton.innerHTML='탁송';
			bButton.style.color='#E4690C';
			 break;
		 case 3 :
			 bButton.innerHTML='전차량';
			 break;
	 }
	 
		//사고기록
	//alert(sago)
/*	if(eval(sago)==1){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A13a'+k);
	 newInput2.id='etag'+k;
	 newInput2.style.width = '60px';
	 newInput2.className='selectbox';
	newInput2.onchange=claimg;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=3;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(sago)){	
			newInput2.selectedIndex=i;
		}
					
				switch(i){
				   case 1 :
					  opts[i].text='사고없음';
						break;
					case 2 :
						opts[i].text='사고있음';
					break;
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);
	}else{

		var aButton=document.getElementById('A13a'+k);
		aButton.className='stn-b';
	 //aButton.value=val1;
	// aButton.style.cursor='hand';
	// aButton.style.width = '40px';
	 aButton.innerHTML='사고있음';
	 aButton.onclick=claimg;


	}*/
	 //bButton.onclick=comDetail10;*/



}


function hpHone(k,hphone){//핸드폰번호

	 var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A29a'+k);
	 newInput2.type='text';
	 newInput2.className='input2';
	 newInput2.id='heck'+k;
	
	 newInput2.value=hphone;
    newInput2.onblur=HphoneInput;
	 newInput2.onclick=clickHphone;//
	 newInput2.style.width = '80px';
	 
	 aJumin.appendChild(newInput2);


}
 function hphoneReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	    	if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		
		}

	}
}

function HphoneInput(){
	var nn=this.getAttribute("id");

	if(nn.length==5){
		bunho=nn.substr(4,5);
	}else{
		bunho=nn.substr(4,6);
	}

	var memberNum=document.getElementById('num'+bunho).value;
	var val=this.value;
	if(val.length==11){
			var first=val.substr(0,3);
			var second=val.substr(3,4);
			var third=val.substr(7,4);

			//alert(second);
			this.value=first+'-'+second+'-'+third;
		
			var toSend = "./ajax/hphone.php?hphone="+this.value
						//+"&insuranceComNum="+insuranceComNum
						+"&memberNum="+memberNum;
					 

	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=hphoneReceive;
	myAjax.send('');
	}


			

}
function clickHphone(){

	var nn=this.getAttribute("id");

	if(nn.length==5){
		bunho=nn.substr(4,5);
	}else{
		bunho=nn.substr(4,6);
	}
	var val=this.value;
	if(val.length==13){
			var first=val.substr(0,3);
			var second=val.substr(4,4);
			var third=val.substr(9,4);

			//alert(second);
			this.value=first+second+third;

	}
}


/*상태를 만들어주는 모쥴*/
function sele(k,k2){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A4a'+k);
	 newInput2.id='sang'+k;
	 newInput2.style.width = '65px';
	 newInput2.className='selectbox';
	 newInput2.onchange=changeSangtae;
	 var opts=newInput2.options;
	//alert(opts);
	var si;
/*	if(nab==4){///실효중일 때는 pus_h를 7실효로 표시
		pus_h=7;
	}*/

	switch(eval(k2)){
		case 4 ://정상
			opts.length=5;
			si=2;
		break;
		
		case 2 ://해지
			opts.length=3;
			si=2
		break;
		case 1 ://청약
			opts.length=2;
			si=1;
		break;
		case 5 ://거절
			opts.length=6;
			si=5;
		break;
		case 6 ://취소
			opts.length=7;
			si=6;
		break;
		case 7 ://실효
			opts.length=8;
			si=7;
		break;

	}
	
	 for(var i=si;i<opts.length;i++){
		opts[i].value=i;
		if(opts[i].value==eval(k2)){
			newInput2.selectedIndex=i;
		}
	switch(eval(i)){
			case 4 :
			opts[i].text='정상';
			break;
			/*case 3 :
			opts[i].text='교체';
			break;*/

			case 2 :
			opts[i].text='해지';
			break;
			case 1 :
			opts[i].text='청약중';
			break;
			case 5 :
			opts[i].text='거절';
			break;
			case 6 :
			opts[i].text='취소';
			break;
			case 7 :
			opts[i].text='실효';
			break;
		}	
	 }
	if(document.getElementById('sang'+k)){
		aJumin.removeChild(aJumin.lastChild);
	}
	 aJumin.appendChild(newInput2);
}

function DaeriMemberCompany(){//보험회사별 인원 전체 찾기
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==4){
		nn=nn.substr(3,4);
	}else{
		nn=nn.substr(3,5);
	}

	var ssC='cNum'+nn;
	var ssang_c_num=document.getElementById(ssC).value;
	
	if(ssang_c_num){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/DaeriCompany.php?num='+ssang_c_num+'&Pcompany='+Pcompany,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}

function daeriMemberReceive()
{
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
function changeSangtae()
{

	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}

	//var val=this.getAttribute("value");
	var val=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;
	//alert(val)
	if(eval(val)==2){

		if(confirm("해지신청 !!")){
		var DariMemberNum='num'+nn;
		var DariMemberNum=document.getElementById(DariMemberNum).value;

		
		var userId=document.getElementById('userId').value;
		//alert(userId);
		var endorseDay=document.getElementById('endorseDay').value;
		var DaeriCompanyNum=document.getElementById('cNum'+nn).value; //			
		var CertiTableNum=document.getElementById('pNum'+nn).value;//certiTableNum
		var policyNum=document.getElementById('A9a'+nn).innerHTML;//
		
		var insuranceNum=document.getElementById('iNum'+nn).value;//보험회사번호
		
		var toSend = "../pop_up/ajax/Endorse.php?DariMemberNum="+DariMemberNum
						+"&DaeriCompanyNum="+DaeriCompanyNum
					   +"&CertiTableNum="+CertiTableNum
						+"&endorseDay="+endorseDay
						+"&policyNum="+policyNum
						+"&userId="+userId
					   +"&insuranceNum="+insuranceNum;

		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=daeriMemberReceive;
		myAjax.send('');
		}
	}else{
		alert('해지신청만 가능합니다');

	}
}

//증권번호 병경 결과 
function changeEtagResponse() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
			//self.document.getElementById("kor_str").value = myAjax.responseText;

		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

			var bunho=myAjax.responseXML.documentElement.getElementsByTagName("bunho")[0].text;

			var mchange=myAjax.responseXML.documentElement.getElementsByTagName("mchange")[0].text;

			if(mchange!=1){
				$("#pNum"+bunho).val(myAjax.responseXML.documentElement.getElementsByTagName("CertiTableNum")[0].text);
			}
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);

			var bunho=myAjax.responseXML.documentElement.getElementsByTagName("bunho")[0].textContent;
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("CertiTableNum")[0].textContent+'/'+);
				var mchange=myAjax.responseXML.documentElement.getElementsByTagName("mchange")[0].textContent;

			if(mchange!=1){
				$("#pNum"+bunho).val(myAjax.responseXML.documentElement.getElementsByTagName("CertiTableNum")[0].textContent);
			}
		}
	
		
		
	}else{
		//	
	}
}

function certi(){
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		bunho=nn.substr(4,5);
	}else{
		bunho=nn.substr(4,6);
	}


	var val=this.value;
	switch(eval(document.getElementById('insCom'+bunho).value)){

		case 1 : //흥국
			
			if(val.length==10){
				
				//alert(val.substring(5,12))
				this.value=val.substring(6,12);
			}
			break;
		case 2 :	//동부
			if(val.length==10){
					
					//alert(val.substring(5,12))
					this.value=val.substring(3,10);
				}
			break;
		case 3 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					this.value=val.substring(5,12);
				}			
			break;
		case 4 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					this.value=val.substring(5,12);
				}			
		break;
		case 5 :

			break;
		case 6:

			break;
		case 8:
			if(val.length==14){

				this.value=val.substring(3,14);
		}
			break;

	}
		

	
}

// 동부화재 1회차 보험료 입력 하기 위해 

function preminum1Input(){
	//alert('1')
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		bunho=nn.substr(4,5);
	}else{
		bunho=nn.substr(4,6);
	}
	//alert(this.value +'/'+bunho)

	//alert($('#num'+bunho).val());

	//var memberNum=$('#num'+bunho).val();
	//var preminum1=$('#firs'+bunho).val();

	//alert(memberNum +'/'+  preminum1);]

	if($('#insCom'+bunho).val()==2){
	    var send_url = "./ajax/_preminum1Input.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"preminum1", 
				     memberNum:$('#num'+bunho).val(),
					 preminum1:$('#firs'+bunho).val()
				    }
		}).done(function( xml ) {

			//values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {

					//store			= $(this).find('store').text();
            	   
            		$('#firs'+bunho).val($(this).find('signal').text());
	   				
				 });
			});
			
		});
	}
		

}

function certiInput(){
	//alert('1')
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		bunho=nn.substr(4,5);
	}else{
		bunho=nn.substr(4,6);
	}
	//alert(this.value)
	
	switch(eval(document.getElementById('insCom'+bunho).value)){

		case 1 :

			if(this.value.length==4){
				if(document.getElementById('Check'+bunho).checked==true){
				   this.value=eval(document.getElementById('end').value.substring(2,4)-1)+'-700'+this.value;
				}else{
					 this.value=eval(document.getElementById('end').value.substring(2,4))+'-700'+this.value;
				}
			}else{
			alert('네자리 !!')
		//	document.getElementById('A9a'+bunho).focus();
				return false;
			
			}
		

			break;
		case 2 :
			if(this.value.length==7){
				if(document.getElementById('Check'+bunho).checked==true){
				    this.value=eval(document.getElementById('end').value.substring(2,4)-1)+'-'+this.value;
				}else{
					this.value=eval(document.getElementById('end').value.substring(2,4))+'-'+this.value;
				}
			}else{

				alert('일곱자리 !!')
			//	document.getElementById('A9a'+bunho).focus();
					return false;
			}
			break;
		case 3 :
		if(this.value.length==7){
			if(document.getElementById('Check'+bunho).checked==true){
				this.value=eval(document.getElementById('end').value.substring(0,4)-1)+'-'+this.value;
			}else{
				this.value=eval(document.getElementById('end').value.substring(0,4))+'-'+this.value;
			}
		}else{

			alert('일곱자리 !!')
		//	document.getElementById('A9a'+bunho).focus();
				return false;
		}
			break;
		case 4 :
		if(this.value.length==7){
			if(document.getElementById('Check'+bunho).checked==true){
				this.value=eval(document.getElementById('end').value.substring(0,4)-1)+'-'+this.value;
			}else{
				this.value=eval(document.getElementById('end').value.substring(0,4))+'-'+this.value;
			}
		}else{

			alert('일곱자리 !!')
		//	document.getElementById('A9a'+bunho).focus();
				return false;
		}
			break;
		case 5 :

			break;
		case 6:

			break;
		
		case 8:
					if(this.value.length==14){
						
							//this.value='114'+this.value;
						this.value=this.value;
					//}else{

				//		alert('열한자리 !!')
					//	document.getElementById('A9a'+bunho).focus();
					//		return false;
				}
			break;

	}

		myAjax=createAjax();//청약번호 저장을 하기위해
		var  pCerti=this.value;
		var  dirverNum=document.getElementById('num'+bunho).value;
		var toSend = "./ajax/appInput.php?appNumber="+pCerti
				   +"&bunho="+bunho
				   +"&driverNum="+dirverNum
				  // +"&page="+page
				  // +"&tableNum="+tableNum;
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=changeEtagResponse;
		myAjax.send('');
	
}
function AllReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//	alert(myAjax.responseText);
			//self.document.getElementById("kor_str").value = myAjax.responseText;

		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		
			}
	
		
		
	}else{
		//	
	}
}

function claimg(){  //사고 기록하기 위해 
		var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}
		var DariMemberNum='num'+nn;
			var DariMemberNum=document.getElementById(DariMemberNum).value;
			var jumin=document.getElementById('A3a'+nn).innerHTML;
			
			var userId=document.getElementById('userId').value;
			var daeriComNum=document.getElementById('cNum'+nn).value;	
			var CertiTableNum=document.getElementById('pNum'+nn).value;

			//alert( DariMemberNum +'/'+ userId+'/'+daeriComNum+'/'+CertiTableNum);


			var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/Claim.php?daeriComNum='+daeriComNum+'&CertiTableNum='+CertiTableNum+'&DariMemberNum='+DariMemberNum+'&userId='+userId+'&jumin='+jumin,'claim','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')

}

//배서리스트의 증권번호를 조회하여 리스트로 만들기 위해 
//2015-04-01
function certiList(sigi,end,chr,s_contents,insuranceComNum,damdanja,page){
		$("#dj").children().remove();
		var send_url = "./ajax/certilist.php";
				//alert(s_contents);
	      $.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ //proc:"daeri_C",daeriNum:daeriNum,policynum:policynum,d_ate:d_ate
						sigi:"sigi",end:end,chr:chr,s_contents:s_contents,insuranceComNum:insuranceComNum,damdanja:damdanja,page:page
						
					}
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

								//	alert($(this).find('sj').text());
						//certi Table 조회 결과
							certiTableValue = new Array();
							$(xml).find('item').each(function() {
								
						
							certiTableValue.push( {	"policynum":$(this).find('policynum').text()
										

									} );
							});//certi Table 조회 결과
					
							//policy 리스트 화면에 표시하기 위해 */
						certi_max_num = certiTableValue.length;	
						
						 dp_certiList();
						
		            });
						
				});
}

function dp_certiList(){

	   var newInput2=document.createElement("select");
		// var aJumin=document.getElementById('B16b'+k);
		 newInput2.id='liggC';
		 newInput2.style.width = '80px';
		 newInput2.className='kj';
		 newInput2.onchange=certiChange;/// 증권번호로 조회하기
		 //newInput2.onchange=DnumChange;/// 주간분석
		 var opts=newInput2.options;
		 opts.length=certi_max_num;
		for(var _i=0;_i<opts.length;_i++){	
		
		  
		 if(certiTableValue[_i].policynum=="전체"){
				newInput2.selectedIndex=_i;
				opts[_i].value=99;
				opts[_i].text=certiTableValue[_i].policynum;

		  }else{

				opts[_i].value=certiTableValue[_i].policynum;
				opts[_i].text=certiTableValue[_i].policynum;
		  }
		}
	
		$("#dj").append(newInput2);


}

//배서리스트 중 증권번호로 엑셀로 다운받기
//2019-11-03
function endorseExcel_(){
	var certi=document.getElementById('liggC').options[document.getElementById('liggC').selectedIndex].value;
	var sigi=document.getElementById("sigi").value;
	var end=document.getElementById("end").value;
	if(certi==99){
		document.getElementById('liggC').focus();
		alert('증권번호 선택부터!!');
		return false;
	}
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/endorseExcel_.php?certi='+certi
							+'&sigi='+sigi
							+'&end='+end
							,'endExcel_','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}
function certiChange(val){

	

//	alert($("#liggC").val());
	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
    var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
			myAjax=createAjax();
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
			
			for(var i=0;i<20;i++){
				document.getElementById("bunho"+i).innerHTML='';
				document.getElementById("A2a"+i).innerHTML='';
				document.getElementById("A3a"+i).innerHTML='';
				document.getElementById("A4a"+i).innerHTML='';
				document.getElementById("A5a"+i).innerHTML='';
				document.getElementById("A6a"+i).innerHTML='';
				document.getElementById("A7a"+i).innerHTML='';
				document.getElementById("A8a"+i).innerHTML='';
				document.getElementById("A9a"+i).innerHTML='';
				document.getElementById("A10a"+i).innerHTML='';
			//	document.getElementById("A11a"+i).innerHTML='';
				//document.getElementById("A13a"+i).innerHTML='';
				document.getElementById("A14a"+i).innerHTML='';
				document.getElementById("A15a"+i).innerHTML='';
				document.getElementById("A16a"+i).innerHTML='';
				//document.getElementById("A17a"+i).innerHTML='';
				//document.getElementById("A18a"+i).innerHTML='';
				document.getElementById("A29a"+i).innerHTML='';
				document.getElementById("A41a"+i).innerHTML='';
				document.getElementById("A44a"+i).innerHTML='';
				document.getElementById("A30a"+i).innerHTML='';
				document.getElementById("A31a"+i).innerHTML='';
				document.getElementById("A32a"+i).innerHTML='';

			//	document.getElementById("A39a"+i).innerHTML='';
				document.getElementById("insCom"+i).value='';
				document.getElementById("num"+i).value='';
				document.getElementById("cNum"+i).value='';
				document.getElementById("iNum"+i).value='';
				document.getElementById("pNum"+i).value='';
				document.getElementById("A19a"+i).value='';
				document.getElementById("endorseDay"+i).value='';


			}
			var damdanja=document.getElementById('insuranceComNum').options[document.getElementById('damdanja').selectedIndex].value;
			//var chr=document.getElementById('chr').options[document.getElementById('chr').selectedIndex].value;
			var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
			var toSend = "./ajax/ajax7.php?sigi="+sigi
						+"&end="+end
						//+"&chr="+chr
						+"&s_contents="+s_contents
				        +"&insuranceComNum="+insuranceComNum
				        +"&damdanja="+damdanja
					   +"&page="+page
				        +"&dongbuCerti="+$("#liggC").val();
			          
	;
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');


	//  certiList(sigi,end,chr,s_contents,insuranceComNum,damdanja,page);


}

function chuga(j){

	var insCom=$('#insCom'+j).val(); //보험회사
	var cNum=$('#cNum'+j).val(); //대리운전회사
	var num=$('#num'+j).val(); //daerimembernum
	var endorseDay=$('#A10a'+j).html();//배서기준일
	var CertiTableNum=$('#pNum'+j).val();
	//alert($('#A2a'+j).html());
	var mNum=$('#num'+j).val(); //대리기사성명
	//alert($('#eNum'+j).val());
	if(insCom==2){
		//alert(insCom+'/'+cNum+'/'+num);
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
	
		window.open('../pop_up/MemberEndorse.php?DaeriCompanyNum='+cNum+'&endorseDay='+endorseDay
			+'&InsuraneCompany='+insCom
			+'&CertiTableNum='+CertiTableNum
			+'&mNum='+mNum,
			
		    'prem','left='+winl+',top='+wint+',resizable=yes,width=930,height=560,scrollbars=yes,status=yes')

	}

//DaeriCompanyNum=488&CertiTableNum=1352&InsuraneCompany=3&policyNum=2015-2020109


}
function preminum(j){ //Eslbs 동부 보험료 정리 하기 위해
	var insCom=$('#insCom'+j).val(); //보험회사
	var cNum=$('#cNum'+j).val(); //대리운전회사
	var num=$('#num'+j).val(); //daerimembernum
	var endorseDay=$('#A10a'+j).html();//배서기준일
	if(cNum==494){
		//alert(insCom+'/'+cNum+'/'+num);
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
	
		window.open('../pop_up/dongbuPre.php?num='+num+'&endorseDay='+endorseDay,'prem','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')

	}
}


function certiM(j){ // mg화재 가입리스트 만들기 위해

	var insCom=$('#insCom'+j).val(); //보험회사
	var cNum=$('#cNum'+j).val(); //대리운전회사
	var num=$('#num'+j).val(); //daerimembernum
	var endorseDay=$('#A10a'+j).html();//배서기준일
	//if(insCom==2){
		//alert(insCom+'/'+cNum+'/'+num);
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
	
		window.open('../pop_up/print/mgcertiList.php?num='+num+'&endorseDay='+endorseDay,'prem','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')

	//}

}

//탁송 리스트 프린트 만들기 위해 


function tarsong_excel(){


	var s_co=document.getElementById("s_contents").value;

	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		
//alert(mNab);
		window.open('./php/tarsongList.php?num='+s_co,'excel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}