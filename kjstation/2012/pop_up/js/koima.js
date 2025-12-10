
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
alert(myAjax.responseText);
//alert(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text);
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				
				//document.getElementById('a32').value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].text;
				//증권번호 저장하기 위해
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].text);
			
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
					  document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
						for(var j=1;j<14;j++){
								document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;						
							}
						for(var j=28;j<31;j++){
								document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;
						}
				
			}else{
				document.getElementById('a32').value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].textContent;
				//증권번호 저장하기 위해
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].textContent);
				if(eval(myAjax.responseXML.documentElement.getElementsByTagName("store")[0].textContent)==1){
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
				}else{
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					  document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
						for(var j=1;j<14;j++){
								document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;						
							}
						for(var j=28;j<31;j++){
								document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
						}
					for(var _m=0;_m<eval(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].textContent);_m++)
					{	document.getElementById('B1b'+_m).innerHTML=_m+1;
						dongbuSele(_m,myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany"+_m)[0].textContent);
						document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("certiNum"+_m)[0].textContent;
						document.getElementById('B3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("startyDay"+_m)[0].textContent;
						document.getElementById('B4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("policyNum"+_m)[0].textContent;
						
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany"+_m)[0].textContent)!=2){
							//동부화재가 아닌 경우에 ...
						document.getElementById('B5b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nabang"+_m)[0].textContent;
						cheriNab(_m,myAjax.responseXML.documentElement.getElementsByTagName("nabang_1"+_m)[0].textContent);
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("naColor"+_m)[0].textContent)){
								case 1 : 
									document.getElementById('B7b'+_m).style.color='#666666';
									break;
								case 2 :
									document.getElementById('B7b'+_m).style.color='red';
									break;
								
								default :
									document.getElementById('B7b'+_m).style.color='#666666';
									break;
							}
						document.getElementById('B7b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("naState"+_m)[0].textContent;
						}
						//인원을 클릭하면
						MemberInwon(_m,myAjax.responseXML.documentElement.getElementsByTagName("inWon"+_m)[0].textContent);
						//document.getElementById('B8b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("inWon"+_m)[0].textContent;
						//
						jejang(_m,myAjax.responseXML.documentElement.getElementsByTagName("sujeong"+_m)[0].textContent);
						shin(_m,myAjax.responseXML.documentElement.getElementsByTagName("input"+_m)[0].textContent);//신규 입력
						unjun(_m,myAjax.responseXML.documentElement.getElementsByTagName("unjen"+_m)[0].textContent); //운전자 추가
						
						cfo(_m,myAjax.responseXML.documentElement.getElementsByTagName("diviCo"+_m)[0].textContent,myAjax.responseXML.documentElement.getElementsByTagName("divi"+_m)[0].textContent);//결제 방식
						prem(_m,myAjax.responseXML.documentElement.getElementsByTagName("moth"+_m)[0].textContent);//보험료 입력
						
						document.getElementById('B14b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("moth"+_m)[0].textContent;
						charCter(_m,myAjax.responseXML.documentElement.getElementsByTagName("b15"+_m)[0].textContent);
						
					}
					for(var _n=eval(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].textContent);_n<9;_n++)
					{

						document.getElementById('B1b'+_n).innerHTML=_n+1;
						dongbuSele(_n,0);

					}
			
				//대리기사 인원 
				document.getElementById('tot').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inWonTotal")[0].textContent
				
				
					for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].textContent);_k++)
					{
						document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].textContent;
						document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].textContent;
						document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].textContent;
						document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].textContent;
						document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].textContent;
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)){
							bagoJo(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent);
							//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent))
							if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)==2)

							{	
								document.getElementById('C1b'+_k).style.color='#0A8FC1';
								document.getElementById('C2b'+_k).style.color='#0A8FC1';
								document.getElementById('C3b'+_k).style.color='#0A8FC1';
								document.getElementById('C4b'+_k).style.color='#0A8FC1';
							}
						}
					}

					// 메모 내용 저장을 위해 
					if(myAjax.responseXML.documentElement.getElementsByTagName("sNum")[0].textContent>10){
					myAjax.responseXML.documentElement.getElementsByTagName("sNum")[0].textContent=10;
				}
					for(var _p=0;_p<eval(myAjax.responseXML.documentElement.getElementsByTagName("sNum")[0].textContent);_p++)
						{
						  memoBuho(_p);
						  document.getElementById('m3_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mWdate")[_p].textContent;
						  document.getElementById('m4_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mKind")[_p].textContent;
						  document.getElementById('m5_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mContent")[_p].textContent;
						  document.getElementById('m6_'+_p).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[_p].textContent;
						}
					
					
					for(var v=0;v<myAjax.responseXML.documentElement.getElementsByTagName("content").length;v++)
					{
						//alert(myAjax.responseXML.documentElement.getElementsByTagName("content")[v].text);
						document.getElementById('m1_2').innerHTML+=myAjax.responseXML.documentElement.getElementsByTagName("content")[v].textContent+'\r\n';

					}
				}
			}
			
	}else{
		//	
	}
}
function MemberInwon(k,memberInwon){
	//alert(k)
	    var bButton=document.getElementById('B8b'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		// bButton.id='ch'+k;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.value=memberInwon;
		 bButton.onclick=mVank;
}
//가상계좌
function mVank(){
		//alert(this.id)
		myAjax=createAjax();
	if(this.id.length==4){
		nn=this.id.substr(3,4);
	}else{
		nn=this.id.substr(3,5);
	}
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	//var certiNum=document.getElementById('B0b'+val).value;
	
	//var tex;
	var certiNum=document.getElementById('B0b'+nn).value;

	if(certiNum){
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./vankList.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum,'vBank','left='+winl+',top='+wint+',resizable=yes,width=800,height=400,scrollbars=yes,status=yes')
	}else{

		alert('증권번호 부터 저징 후 !!')
			return false;
	}
}
function memoBuho(k){
	var k=eval(k);
	var bButton=document.getElementById('m2_'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		// bButton.id='ch'+k;
		// bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=k+1;
		 bButton.onclick=memoSerch;
}
function memoReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);
//alert(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text);
	       if(myAjax.responseXML.documentElement.getElementsByTagName("mNum")[0].text!=undefined){//
			    document.getElementById('memoNum').value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[0].text;
				document.getElementById('memokind').value=myAjax.responseXML.documentElement.getElementsByTagName("mKind")[0].text;
				document.getElementById('m1_0').value=myAjax.responseXML.documentElement.getElementsByTagName("mContent")[0].text;
				document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].text;
			}else{
				document.getElementById('memoNum').value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[0].textContent;
				document.getElementById('memokind').value=myAjax.responseXML.documentElement.getElementsByTagName("mKind")[0].textContent;
				document.getElementById('m1_0').value=myAjax.responseXML.documentElement.getElementsByTagName("mContent")[0].textContent;
				document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].textContent;
			}
	}else{


	}
}
function memoReceive2(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);
//alert(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text);
	       if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){//
			   // 메모 내용 저장을 위해 
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].text;
				for(var _p=0;_p<eval(myAjax.responseXML.documentElement.getElementsByTagName("sNum")[0].text);_p++)
					{
					  memoBuho(_p);
					  document.getElementById('m3_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mWdate")[_p].text;
					  document.getElementById('m4_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mKind")[_p].text;
					  document.getElementById('m5_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mContent")[_p].text;
					  document.getElementById('m6_'+_p).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[_p].text;
					}
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].textContent;
				for(var _p=0;_p<eval(myAjax.responseXML.documentElement.getElementsByTagName("sNum")[0].textContent);_p++)
					{
					  memoBuho(_p);
					  document.getElementById('m3_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mWdate")[_p].textContent;
					  document.getElementById('m4_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mKind")[_p].textContent;
					  document.getElementById('m5_'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mContent")[_p].textContent;
					  document.getElementById('m6_'+_p).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[_p].textContent;
					}
			}
	}else{


	}

}
function memoStore(){
	var c_number=document.getElementById('a9').value;
	var jumin=document.getElementById('a3').value;
	var memoNum=document.getElementById('memoNum').value;
	var memoKind=document.getElementById('memokind').options[document.getElementById('memokind').selectedIndex].value;
	var memoContent=encodeURIComponent(document.getElementById('m1_0').value);
	if(!memoNum){

		if(!memoContent){
			alert('메모 내용이 없습니다 !!');
			document.getElementById('m1_0').focus();
			return false;
		}

	}


	var toSend = "./ajax/memoStore.php?memoNum="+memoNum
									+"&memoKind="+memoKind
									+"&memoContent="+memoContent
									+"&a9="+c_number
									+"&a3="+jumin;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=memoReceive2;
				myAjax.send('');
			document.getElementById('memoNum').value='';
			document.getElementById('m1_0').value='';
			document.getElementById('memokind').value=1;

}
function memoSerch(){
	//alert(this.id)
	if(this.id.length==4){
		nn=this.id.substr(3,4);
	}else{
		nn=this.id.substr(3,5);
	}
	var memoNum=document.getElementById('m6_'+nn).value;
	var toSend = "./ajax/memoSerch.php?memoNum="+memoNum
											//		+"&sunso="+nn
											//		+"&certiTableNum="+certiTableNum;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=memoReceive;
				myAjax.send('');
	document.getElementById('m2_'+nn).innerHTML='';
	document.getElementById('m3_'+nn).innerHTML='';
	document.getElementById('m4_'+nn).innerHTML='';
	document.getElementById('m5_'+nn).innerHTML='';
	document.getElementById('m6_'+nn).value='';
	
}


function jejang(k,je){//저장 또는 수정
var newInput2=document.createElement("input");
	var aJumin=document.getElementById('B9b'+k);
	 newInput2.type='button';
	 newInput2.className='btn-b';
	 newInput2.id='jerm'+k;
	 newInput2.style.width = '40px';
	newInput2.value=je;
	 newInput2.onclick=CertiStore;

	 
	 aJumin.appendChild(newInput2);


}
function prem(k,mothPremin){// 보험료 입력
	var newInput2=document.createElement("input");
	var aJumin=document.getElementById('B13b'+k);
	 newInput2.type='button';
	 newInput2.className='btn-b';
	 newInput2.id='prem'+k;
	 newInput2.style.width = '50px';
	
	 newInput2.onclick=moth;

	 switch(eval(mothPremin)){
			case 1 : 
				newInput2.style.color='#666666';
				newInput2.value='입력';
				break;
			case 2 :
				newInput2.style.color='#0A8FC1';
				newInput2.value='보험료';
				break;
			
			default :
				newInput2.style.color='#666666';
				newInput2.value='입력';
				break;
		}
	 aJumin.appendChild(newInput2);


}
function cfo(k,diviColor,diviValue){//결제방식
	//alert(diviColor)
	var newInput2=document.createElement("input");
	var aJumin=document.getElementById('B12b'+k);
	 newInput2.type='button';
	 newInput2.className='btn-b';
	 newInput2.id='colo'+k;
	 newInput2.style.width = '40px';
	 newInput2.value=diviValue;
	 newInput2.onclick=divi;

	 switch(eval(diviColor)){
			case 1 : 
				newInput2.style.color='#666666';
				break;
			case 2 :
				newInput2.style.color='#0A8FC1';
				break;
			
			default :
				newInput2.style.color='#666666';
				break;
		}
	 aJumin.appendChild(newInput2);


}
function unjun(k,unjen){//운전자추가

	var newInput2=document.createElement("input");
	var aJumin=document.getElementById('B11b'+k);
	 newInput2.type='button';
	 newInput2.className='btn-b';
	 newInput2.id='unje'+k;
	 newInput2.style.width = '40px';
	 newInput2.value=unjen;
	 newInput2.onclick=memberEndorse;
	 aJumin.appendChild(newInput2);

}
function shin(k,shinku){//신규 추가

	var newInput2=document.createElement("input");
	var aJumin=document.getElementById('B10b'+k);
	 newInput2.type='button';
	 newInput2.className='btn-b';
	 newInput2.id='shin'+k;
	 newInput2.style.width = '40px';
	 newInput2.value=shinku;
	 newInput2.onclick=memberStore;
	 aJumin.appendChild(newInput2);
}

function charCter(k,k2){

	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B15b'+k);
	 newInput2.id='cheriic'+k;
	 newInput2.style.width = '50px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeChar;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=4;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(k2)){	
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
						opts[i].text='3회차';
					break;
					
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);


}
function changeChar(){
	//alert(this.id.length);
	//alert(this.value);
	myAjax=createAjax();
	if(this.id.length==8){
		nn=this.id.substr(7,8);
	}else{
		nn=this.id.substr(7,9);
	}

	var tex;
	var certiTableNum=document.getElementById('B0b'+nn).value;
	switch(eval(this.value))
	{
			case 1 : 
				tex='일반';
				break;
			case 2 :

				tex='탁송'
				break;
			case 3 :
				tex='탁송'
				break;
	}

	if(confirm(tex+'으로 수정합니다!!')){
			if(certiTableNum){	
				var toSend = "./ajax/charChange.php?nabsunso="+this.value
													+"&sunso="+nn
													+"&certiTableNum="+certiTableNum;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=changeNabReceive;
				myAjax.send('');
			}
		}else{
			this.value=this.value-1;
			return false;
		}
}
function cheriNab(k,k2){//납입회차 
	
var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B6b'+k);
	 newInput2.id='cheriii'+k;
	 newInput2.style.width = '50px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeNab;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=11;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(k2)){	
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
//증권번호별 결제 방식 
function changeNabReceive()
{
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//	alert(myAjax.responseText);
			
				if(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text!=undefined){
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
					var val=eval(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text);
					switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("naColor")[0].text)){
								case 1 : 
									document.getElementById('B7b'+val).style.color='#666666';
									break;
								case 2 :
									document.getElementById('B7b'+val).style.color='red';
									break;
								
								default :
									document.getElementById('B7b'+val).style.color='#666666';
									break;
							}
					document.getElementById('B7b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("naState")[0].text;
				}else{
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);		
					var val=eval(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].textContent);
				}
				//alert(val);
				
			
			
	}else{
		//	
	}


}
function changeNab(){
	//alert(this.id.length);
	//alert(this.value);
	myAjax=createAjax();
	if(this.id.length==8){
		nn=this.id.substr(7,8);
	}else{
		nn=this.id.substr(7,9);
	}
	var certiTableNum=document.getElementById('B0b'+nn).value;

	if(confirm(this.value+'회차 납부 합니까!!')){
			if(certiTableNum){	
				var toSend = "./ajax/nabSunsoChange.php?nabsunso="+this.value
													+"&sunso="+nn
													+"&certiTableNum="+certiTableNum;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=changeNabReceive;
				myAjax.send('');
			}
		}else{
			this.value=this.value-1;
			return false;
		}
}
function dongbuSele(k,k2){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B2b'+k);
	 newInput2.id='sang'+k;
	 newInput2.style.width = '50px';
	 newInput2.className='selectbox';
	 newInput2.onchange=sujeongBotton;
	 var opts=newInput2.options;
	opts.length=7;	
	 for(var i=0;i<opts.length;i++){
		opts[i].value=i;	
		if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;
		}	
				switch(i){
					case 0 :
						opts[i].text='=선택=';
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
					case 5 :
						opts[i].text='한화';
					break;
					case 6 :
						opts[i].text='더 케이';
					break;
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);
}

function sujeongBotton(){// 보험회사 선택하고 나면 저장 버튼을 만들기 위해 
	if(this.id.length==5){
		var val=this.id.substr(4,5);
	}else{
		var val=this.id.substr(4,6);
	}

	if(!document.getElementById('B0b'+val).value){
		var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;
		//alert(a2)
		if(a2>=1 && a2<=6){
			document.getElementById('B9b'+val).innerHTML='';
			var newInput2=document.createElement("input");
			var aJumin=document.getElementById('B9b'+val);
			 newInput2.type='button';
			 newInput2.className='btn-b';
			 newInput2.id='jerm'+val;
			 newInput2.style.width = '40px';
			 newInput2.value='저장';
			 newInput2.onclick=CertiStore;
			 aJumin.appendChild(newInput2);
		}else{

			document.getElementById('B9b'+val).innerHTML='';

		}

	}

}
function ceReceive() {
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
function DataCheckS(id,val){

	if(eval(val.length)==8){
			var phone_first	=val.substring(0,4)
			var phone_second =val.substring(4,6)
			var phone_third	 =val.substring(6,8)
			document.getElementById(id).value=phone_first+"-"+phone_second+"-"+phone_third;
			var num=document.getElementById("num").value;

			var FirstartDay=document.getElementById(id).value;
				if(num){
					myAjax=createAjax();
				
					
					var toSend = "./ajax/GetDateSujeong.php?num="+num
								+"&FirstartDay="+FirstartDay;
					//alert(toSend);
					myAjax.open("get",toSend,true);
					myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
					myAjax.onreadystatechange=ceReceive;
					myAjax.send('');
				}else{


				}
				/*}else{


					alert('대리운전회사 등록부터!'');
					return false;

					}*/

	}else if(eval(val.length)>0 && eval(val.length)<9){
			alert('번호 !!')

			document.getElementById(id).focus();
			document.getElementById(id).value='';
			return false;

	}

}
function serch(val){
	myAjax=createAjax();
	var num=document.getElementById("num").value;
	//alert(num);
	if(num){	
		var toSend = "./ajax/koimaSerch.php?num="+num;
	
		alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}else{

		alert('대리운전 회사 기본정보 입력하세요 !!!'+"\n"+ '주민번호부터');
		document.getElementById('a3').focus();
		return false;

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
function web_sangdam_drive(val){//조회할때
	var snum=document.getElementById("num"+val).value
  var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('/newAdmin/ssangyoung/pop_up/web_sangdamCompany.php?num='+ snum,'sangdam','left='+winl+',top='+wint+',resizable=yes,width=950,height=550,scrollbars=yes,status=yes')

}

function store(){
	myAjax=createAjax();
	var num=document.getElementById("num").value;
	var a1=encodeURIComponent(document.getElementById("a1").value);
	var a2=encodeURIComponent(document.getElementById("a2").value);
	var a28=document.getElementById("a28").value;
	var a29=encodeURIComponent(document.getElementById("a29").value);
	var a30=encodeURIComponent(document.getElementById("a30").value);

	
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
	var query='';
		
		
		for(j=3;j<13;j++){
			query+="a"+j;
			query+="=";
			query+=document.getElementById('a'+j).value;
			query+="&";
			
		}
	

		if(!document.getElementById("a3").value && !num){//주민번호가 없으면 

			alert(' 주민번호부터');
			document.getElementById('a3').focus();
			return false;


		}

		if(!document.getElementById("a1").value){

			alert(' 대리운전회사명!!');
			document.getElementById('a1').focus();
			return false;
		}
		if(!document.getElementById("a2").value){

			alert('성명!!');
			document.getElementById('a2').focus();
			return false;
		}
		if(!document.getElementById("a4").value){

			alert('핸드폰번호!!');
			document.getElementById('a4').focus();
			return false;
		}
		var toSend = "./ajax/popAjax.php?a1="+a1
					   
					   +"&a28="+a28
					   +"&a29="+a29
					   +"&a30="+a30
					   +"&num="+num
					   +"&a2="+a2+"&"
					   +query;
		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
}


function divid(){
	var num=document.getElementById("num").value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2

	if(num){
		window.open('./id.php?num='+num,'id','left='+winl+',top='+wint+',resizable=yes,width=500,height=200,scrollbars=no,status=yes')
	}else{
			alert('대리운전회사 부터 등록하세요!!')
				return false;
		}
}
function persolnal(){//담당자 지정
	var num=document.getElementById("num").value;

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
	if(num){
		window.open('./person.php?num='+num,'prso22l','left='+winl+',top='+wint+',resizable=yes,width=930,height=300,scrollbars=no,status=yes')
				
	}else{
		alert('대리운전회사 부터 등록하세요!!')
			return false;
	}
}
function findAddr()
{
	var url
	url = "/csd/cs_member/member/find_address2.php?mode=def"
	f_OpenModalPop("AddressFind",url,1,456,380)
}
function f_OpenModal(strURL,arrArguVal,nHeight,nWidth,nTop,nLeft,bEdge,bCenter,bHelp,bResiz,bStatus,bScroll,bUnadorned) {
var strTop = '';
if(nTop > 0) {
	strTop = 'dialogTop:'+nTop+'px;';
}
var strLeft = '';
if(nLeft > 0) {
	strLeft = 'dialogLeft:'+nLeft+'px;';
}
var strEdge = '';
if(bEdge != '') {
	strEdge = "edge:";
	strEdge += (bEdge > 0) ? 'raised' : 'sunken';
	strEdge += ';';
}
var objArgu = new Object();
objArgu.objWin = self;
objArgu.objParam = arrArguVal;
var objWin = window.showModalDialog(strURL,objArgu,"dialogHeight:"+nHeight+"px;dialogWidth:"+nWidth+"px;"+strTop+strLeft+strEdge+"center:"+bCenter+";help:"+bHelp+";resizable:"+bResiz+";status:"+bStatus+";scroll:"+bScroll+";unadorned:"+bUnadorned+";");
return objWin;
}
function f_OpenModalPop(Name,Action,bScroll,width, height) {
		var w, h;
		w = width;
		h = height;
		var strURL = Action;
		var objArgu = new Object();
		objArgu.Width = w;
		objArgu.Height = h;
		objArgu.Action = Action;
		var objWin = f_OpenModal(strURL,objArgu,h,w,0,0,0,1,0,0,0,bScroll,0);
		return objWin;
	}
function certirReceive(){
		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	
			if(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text!=undefined){
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
				}else{
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
										
				}
			for(var j=1;j<14;j++){
				if(myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text!=undefined){
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;
				}else{
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
										
				}
			}
			
			for(var j=28;j<31;j++){
				if(myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text!=undefined){
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;

				}else{
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
										
				}
			}
			
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('a32').value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].text;
				//증권번호 저장하기 위해
				for(var _m=0;_m<9;_m++)
				{
					document.getElementById('B1b'+_m).innerHTML=_m+1;
					dongbuSele(_m,1);

				}

			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					document.getElementById('a32').value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].textContent;						
			}
			
	}else{
		//	
	}


}
function certiReceive()
{
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
			document.getElementById('sql2').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sql")[0].text;
				if(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text!=undefined){
					var val=eval(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text);

				}else{

					var val=eval(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].textContent);
				}
				//alert(val);
				if(myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].text!=undefined){
				
					document.getElementById('B0b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
					document.getElementById('B1b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].text;
					//document.getElementById('B2b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name2")[0].text;
					document.getElementById('B3b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name3")[0].text;
					document.getElementById('B4b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name4")[0].text;
					//document.getElementById('B5b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name5")[0].text;
					document.getElementById('B6b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name6")[0].text;
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("name2")[0].text)!=2){
							//동부화재가 아닌 경우에 ...
						document.getElementById('B5b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name5")[0].text;
						cheriNab(val,myAjax.responseXML.documentElement.getElementsByTagName("name6")[0].text);
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("naColor")[0].text)){
								case 1 : 
									document.getElementById('B7b'+val).style.color='#666666';
									break;
								case 2 :
									document.getElementById('B7b'+val).style.color='red';
									break;
								
								default :
									document.getElementById('B7b'+val).style.color='#666666';
									break;
							}
						document.getElementById('B7b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("naState")[0].text;
						}
					document.getElementById('B9b'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].text;
				}else{
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
										
				}
			
			
		
			
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				

			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				
			}
			
	}else{
		//	
	}


}

function CertiStore(){
	if(this.id.length==5){
		var val=this.id.substr(4,5);
	}else{
		var val=this.id.substr(4,6);
	}
	


	var a1=document.getElementById("num").value;
	var certiNum=document.getElementById('B0b'+val).value;
	var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;
	//0선택,1흥국,2동부3.lig 4.현대

	var a3=document.getElementById('B3b'+val).value;
	var a4=document.getElementById('B4b'+val).value;
	var a5=document.getElementById('B5b'+val).value;
	if(a2==2){//동부화재 인경우
			if(!a3){
					alert('시작일 입력하세요 !!');
					document.getElementById('B3b'+val).focus();
					return false;
				}
		var toSend = "./ajax/certiAjax.php?a1="+a1
						+"&a2="+a2
					   +"&a3="+a3
						+"&a4="+a4
						+"&a5="+a5
						//+"&a6="+a6
						+"&certiNum="+certiNum
						+"&val="+val;

	}else{
		if(certiNum){// certiNum 있으면 
			
			var a6=document.getElementById('cheriii'+val).options[document.getElementById('cheriii'+val).selectedIndex].value;
			var toSend = "./ajax/certiAjax.php?a1="+a1
							+"&a2="+a2
						    +"&a3="+a3
							+"&a4="+a4
							+"&a5="+a5
							+"&a6="+a6
							+"&certiNum="+certiNum
							+"&val="+val;
			//document.getElementById('B5b'+val).innerHTML='';
			document.getElementById('B6b'+val).innerHTML='';
		}else{
			if(a2>=1){

				if(!a3){
					alert('시작일 입력하세요 !!');
					document.getElementById('B3b'+val).focus();
					return false;
				}
				if(!a4){
					alert('증권번호 입력하시고 !!');
					document.getElementById('B4b'+val).focus();
					return false;
				}
				if(!a5){
					alert('몇분납이죠 !!');
					document.getElementById('B5b'+val).focus();
					return false;
				}
				var toSend = "./ajax/certiAjax.php?a1="+a1
						+"&a2="+a2
					    +"&a3="+a3
						+"&a4="+a4
						+"&a5="+a5
						//+"&a6="+a6
						+"&certiNum="+certiNum
						+"&val="+val;
			}else{
				//보험뢰사를 선택하시고
				alert('보험회사를 정하시고 !!');
				return false;


			}
		}


	}				   
		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=certiReceive;
		myAjax.send('');
}

function memberStore(){
	//alert(this.id);
	if(this.id.length==5){
		var val=this.id.substr(4,5);
	}else{
		var val=this.id.substr(4,6);
	}

	//alert(nn);
	var num=document.getElementById("num").value;//2012DaeriCompanyNum

	var certiNum=document.getElementById('B0b'+val).value;
	var a4=document.getElementById('B4b'+val).value;
	var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;
	if(certiNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./DaeriMember.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'preso22l','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}
	
}
function memberEndorse(val){
	if(this.id.length==5){
		var val=this.id.substr(4,5);
	}else{
		var val=this.id.substr(4,6);
	}
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('B0b'+val).value;
	var a4=document.getElementById('B4b'+val).value;
	var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;
	if(certiNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./MemberEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}
	   
}




//증권번호별 결제 방식 
function diviReceive()
{
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);
			//alert('1')
				if(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text!=undefined){
					var val=eval(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text);

				}else{

					var val=eval(myAjax.responseXML.documentElement.getElementsByTagName("val")[0].textContent);
				}
				//alert(val);
				if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
					
					switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("messageCo")[0].text)){
							case 1 : 
								document.getElementById('colo'+val).style.color='#0A8FC1';
							    document.getElementById('prem'+val).style.color='#0A8FC1';
							
								break;
							case 2 :
								document.getElementById('colo'+val).style.color='#666666';
							    document.getElementById('prem'+val).style.color='#666666';
								break;
							
							default :
								document.getElementById('colo'+val).style.color='#0A8FC1';
							   document.getElementById('prem'+val).style.color='#0A8FC1';
								break;
						}

					//alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
					document.getElementById('colo'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text;
					document.getElementById('prem'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("message2")[0].text;
				}else{
					switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("messageCo")[0].textContent)){
							case 1 : 
								document.getElementById('colo'+val).style.color='#0A8FC1';
							    document.getElementById('prem'+val).style.color='#0A8FC1';
							
								break;
							case 2 :
								document.getElementById('colo'+val).style.color='#666666';
							    document.getElementById('prem'+val).style.color='#666666';
								break;
							
							default :
								document.getElementById('colo'+val).style.color='#0A8FC1';
							   document.getElementById('prem'+val).style.color='#0A8FC1';
								break;
						}

					//alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					document.getElementById('colo'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent;
					document.getElementById('prem'+val).value=myAjax.responseXML.documentElement.getElementsByTagName("message2")[0].textContent;
										
				}
			
			
		
		/*	
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				

			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				
			}*/
			
	}else{
		//	
	}


}
function divi(){
	//alert(this.id)
	if(this.id.length==5){
		var val=this.id.substr(4,5);
	}else{
		var val=this.id.substr(4,6);
	}
	var a1=document.getElementById("num").value;
	var certiNum=document.getElementById('B0b'+val).value;
	

	if(certiNum){
			if(confirm('결제방식을 변경합니다!!')){
				var toSend = "./ajax/diviAjax.php?certiNum="+certiNum
								+"&val="+val;
							   
				//alert(toSend);

				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=diviReceive;
				myAjax.send('');
			}	

	}else{

		alert('증권번호부터 입력하세요!!');
		return false;


	}
}

function moth(){
	if(this.id.length==5){
		var val=this.id.substr(4,5);
	}else{
		var val=this.id.substr(4,6);
	}
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('B0b'+val).value;
	var a4=document.getElementById('B4b'+val).value;
	var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;
	var a11=document.getElementById('a11').value;
	if(!a11){

		alert('월 보험료 받는 날 입력 부터 하셔야 함');
		document.getElementById('a11').focus();
		return false;
	}

	//alert(certiNum)
	if(certiNum){
		 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'cPre','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
		/*
		switch(eval(a2)){
			case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				
				window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'cPre','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
			    //window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'2012mr','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 2 :
				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'cPre','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				//window.open('./preminum2Dongbu.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'2012mr','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 3 :
				
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'cPre','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				//window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'2012mr','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;


				break;
			case 4:

				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'cPre','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				//window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'2012mr','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 5:

				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'cPre','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				//window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'2012mr','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
				
		}*/
	}else{
		alert('증권번호 저장 부터 하시고 !!')
			return false;
		}
}


function daeriSerch(val){
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('B0b'+val).value;
	var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;

	if(certiNum){
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./daeriList.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&iNum='+a2,'memberList','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	}else{

		alert('증권번호 부터 저징 후 !!')
			return false;
	}
}


function daeriSerchAll(){
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	//var certiNum=document.getElementById('B0b'+val).value;
	if(num){
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./daeriList.php?DaeriCompanyNum='+num,'allDriverMember','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	}else{

		alert('증권번호 부터 저징 후 !!')
			return false;
	}
}