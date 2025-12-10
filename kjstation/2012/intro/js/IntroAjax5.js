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
						checkSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text;
						monthPreminum(k,myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].text);

						//alert(myAjax.responseXML.documentElement.getElementsByTagName("p_buho")[k].text);
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text))
						{
								case 1 :
									document.getElementById('A3a'+k).innerHTML='흥국';
								    document.getElementById('A3a'+k).style.color='#F345FC';
									break;
								case 2 :
									document.getElementById('A3a'+k).innerHTML='동부';
									break;
								case 3 :
									document.getElementById('A3a'+k).innerHTML='LiG';
									break;
								case 4 :
									document.getElementById('A3a'+k).innerHTML='현대';
									 document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A3a'+k).innerHTML='한화';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A3a'+k).innerHTML='더 케이';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
							
								case 7 :
									document.getElementById('A3a'+k).innerHTML='MG';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A3a'+k).innerHTML='삼성';
								    document.getElementById('A3a'+k).style.color='#E4690C';

									joong(k,myAjax.responseXML.documentElement.getElementsByTagName("p_buho")[k].text);//계약자삼성화재
									break;


						}
					
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text;
						
						detailSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text);
						
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a20")[k].text)){
								case 1 : 
									document.getElementById('A7a'+k).style.color='#666666';
									break;
								case 2 :
									document.getElementById('A7a'+k).style.color='blue';
									break;
								
								case 3 :
									document.getElementById('A7a'+k).style.color='red';
									break;
							}
						document.getElementById('A17a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a17")[k].text;
						document.getElementById('A7a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						document.getElementById('A8a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						//inwon(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text);
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text
						document.getElementById('pnum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("pnum")[k].text;//배서번호
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].text;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("cNum")[k].text;//certiTableNum
						document.getElementById('A30a'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].text;//SegNo
						var getSamP=myAjax.responseXML.documentElement.getElementsByTagName("a40")[k].text;
						var getp=myAjax.responseXML.documentElement.getElementsByTagName("a50")[k].text;
						//alert(myAjax.responseXML.documentElement.getElementsByTagName("a40")[k].text)
						//document.getElementById('A40a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a40")[k].text;
						get(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].text,getSamP,getp);
						
					}else{
						checkSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						monthPreminum(k,myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent);
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent))
						{
								case 1 :
									document.getElementById('A3a'+k).innerHTML='흥국';
								    document.getElementById('A3a'+k).style.color='#F345FC';
									break;
								case 2 :
									document.getElementById('A3a'+k).innerHTML='동부';
									break;
								case 3 :
									document.getElementById('A3a'+k).innerHTML='LiG';
									break;
								case 4 :
									document.getElementById('A3a'+k).innerHTML='현대';
									 document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A3a'+k).innerHTML='한화';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A3a'+k).innerHTML='더 케이';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A3a'+k).innerHTML='MG';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A3a'+k).innerHTML='삼성';
								    document.getElementById('A3a'+k).style.color='#E4690C';

									joong(k,myAjax.responseXML.documentElement.getElementsByTagName("p_buho")[k].textContent);//계약자삼성화재
									break;

						}
					
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent;
						
						detailSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent);
						document.getElementById('A17a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a17")[k].textContent;
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a20")[k].textContent)){
								case 1 : 
									document.getElementById('A7a'+k).style.color='#666666';
									break;
								case 2 :
									document.getElementById('A7a'+k).style.color='blue';
									break;
								
								case 3 :
									document.getElementById('A7a'+k).style.color='red';
									break;
							}
						document.getElementById('A7a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						document.getElementById('A8a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;
						//inwon(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent);
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].textContent
						document.getElementById('pnum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("pnum")[k].textContent;//배서번호
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].textContent;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("cNum")[k].textContent;//certiTableNum
						document.getElementById('A30a'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].textContent;//SegNo
						var getSamP=myAjax.responseXML.documentElement.getElementsByTagName("a40")[k].textContent;
						//alert()
						var getp=myAjax.responseXML.documentElement.getElementsByTagName("a50")[k].text;
						get(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].textContent,getSamP,getp);
						
					}
					
				}
		/*	if(myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].textContent!=undefined){
				document.getElementById("tot").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].textContent;
			}else{
				document.getElementById("tot").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].textContentContent;
			}*/
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}

function detailSele(k,k2){
	
	/*일일 버튼 */
		 var bButton=document.getElementById('A6a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		
		 bButton.innerHTML=k2;
		 bButton.onclick=detailEndorse;
	
}

function detailEndorse(){//상세보기
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==4){
		nn=nn.substr(3,4);
	}else{
		nn=nn.substr(3,5);
	}

	//alert(nn);
	var daeriComNum=document.getElementById('num'+nn).value;	
	var CertiTableNum=document.getElementById('cNum'+nn).value;
	var eNum=document.getElementById('pnum'+nn).value;//배서번호
	//var endorseDay=document.getElementById('A7a'+nn).innerHTML;//배서번호
	//var daeriComNum=document.getElementById('cNum'+nn).value;
	
	if(eNum){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/DaeriEndorse.php?daeriComNum='+daeriComNum+'&CertiTableNum='+CertiTableNum+'&eNum='+eNum,'new_22Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}
function inwon(k,inwo){
	

	var bButton=document.getElementById('A8a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		// bButton.id='ch'+k;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=inwo;
		 bButton.onclick=daeriSerch;

}

function daeriSerch(){

	//alert(this.id)
	if(this.id.length==4){
		var val=this.id.substr(3,4);
	}else{
		var val=this.id.substr(3,5);
	}
	var num=document.getElementById("num"+val).value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('cNum'+val).value;
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/daeriList.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
}



function serch(val){
		
		for(var k=0;k<20;k++){	
				document.getElementById('bunho'+k).innerHTML='';
				document.getElementById('num'+k).value=''
				document.getElementById('A2a'+k).innerHTML='';
				document.getElementById('A3a'+k).innerHTML=''
				//document.getElementById('cNum'+k).value='';				
				//document.getElementById('iNum'+k).value='';				
				document.getElementById('pnum'+k).value='';
				document.getElementById('A4a'+k).innerHTML=''
				document.getElementById('A5a'+k).innerHTML='';
				document.getElementById('A6a'+k).innerHTML=''
				document.getElementById('A7a'+k).innerHTML='';
				document.getElementById('A8a'+k).innerHTML='';
				document.getElementById('A9a'+k).innerHTML='';		
				document.getElementById('A10a'+k).innerHTML='';			
				document.getElementById('C5b'+k).innerHTML='';//pnum
				document.getElementById('C6b'+k).innerHTML='';//보험사로 부터 받었는지 정산여부
				document.getElementById('A11a'+k).innerHTML='';
				document.getElementById('A17a'+k).innerHTML='';
				document.getElementById('A40a'+k).innerHTML='';
				document.getElementById('A30a'+k).value='';
			}
		document.getElementById("tot").innerHTML='';
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
		//var chr=document.getElementById('chr').options[document.getElementById('chr').selectedIndex].value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var push=document.getElementById('push').options[document.getElementById('push').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		var damdanja=document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value;
		var toSend = "./ajax/ajax5.php?sigi="+sigi
				   +"&end="+end
					+"&insuranceComNum="+insuranceComNum
				   +"&s_contents="+s_contents
			       +"&push="+push
				   +"&damdanja="+damdanja
				  // +"&chr="+chr
				   +"&page="+page;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해

function joong(k,k2){ 

	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A10a'+k);
	 newInput2.id='joongt'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	//newInput2.onchange=changejoong;
	 var opts=newInput2.options;
	
	opts.length=7;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		
		if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;
		}


	
			switch(i){
				case 1 :
					opts[i].text='조홍기';
					break;
				case 2 :
					opts[i].text='오성준';
					break;
				case 3 :
					opts[i].text='이근재';
					break;
				case 4 :
					opts[i].text='오성엽';
					break;
				case 5 :
					opts[i].text='박종민';
					break;
				case 6 :
					opts[i].text='오경선';
					break;
				
			}
		//alert(i)
			/*
				switch(eval(i)){
					
					case 1 :
						opts[i].text='중복';
					break;
					case 2 :
						opts[i].text='배서';
					break;
					
					
				}*/
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);


}
function get(k,k2,samP,k3){
	//alert(k2);
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

	//alert(samP);

	 	//보험회사에내는 보험료 설정 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('A40a'+k);
			 newInput2.id='cPre'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='input40';
			 newInput2.value=samP;//보험회사에내는 보험료 설정을 하면 그것을 계산해서 한다...
			newInput2.onblur=cPremInput;
			 aJumin.appendChild(newInput2);



	var newInput2=document.createElement("select");  // 삼성화재 미수 여부
	 var aJumin=document.getElementById('C6b'+k);
	 newInput2.id='cett'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeget2;
	 var opts=newInput2.options;
	
	opts.length=3;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		
		if(opts[i].value==eval(k3)){	
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

function cPremInput(){  //보험회사 보험료 
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
		var Seg=document.getElementById("A30a"+nn).value;
		//alert(document.getElementById('gett'+nn).options[document.getElementById('gett'+nn).selectedIndex].value);
		var Gett=document.getElementById('cPre'+nn).value;
		var toSend = "../pop_up/ajax/cpreminumUpdate.php?cNum="+Seg
											 +"&get="+Gett;
	//	alert(toSend)
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=sjRe;
		myAjax.send('');


}
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
function changejoong(){
//alert('1')
	myAjax=createAjax();
	var nn=this.getAttribute("id");
	//alert(nn)
	if(this.value.length==7){
		nn=nn.substr(6,7);
	}else{
		nn=nn.substr(6,8);
	}
	//alert(nn)
		//alert(document.getElementById("C0b"+nn).value);
		var Seg=document.getElementById("A30a"+nn).value;
		//alert(document.getElementById('gett'+nn).options[document.getElementById('gett'+nn).selectedIndex].value);
		var joongt=document.getElementById('joongt'+nn).options[document.getElementById('joongt'+nn).selectedIndex].value
		var toSend = "../pop_up/ajax/joonglUpdate.php?cNum="+Seg
											 +"&joongt="+joongt;
		//alert(toSend)
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=sjRe;
		myAjax.send('');


											
}
//정산 , 
function changeget2(){  // 보험사 보험료 해지일 경우 받었는지 안받었는지 
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
		var Seg=document.getElementById("A30a"+nn).value;
		//alert(document.getElementById('gett'+nn).options[document.getElementById('gett'+nn).selectedIndex].value);
		var Gett=document.getElementById('cett'+nn).options[document.getElementById('cett'+nn).selectedIndex].value
		var toSend = "../pop_up/ajax/canCelUpdate2.php?cNum="+Seg  // 보회사 보험료
											 +"&get="+Gett;
		//alert(toSend)
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=sjRe;
		myAjax.send('');


											
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
		var Seg=document.getElementById("A30a"+nn).value;
		//alert(document.getElementById('gett'+nn).options[document.getElementById('gett'+nn).selectedIndex].value);
		var Gett=document.getElementById('gett'+nn).options[document.getElementById('gett'+nn).selectedIndex].value
		var toSend = "../pop_up/ajax/canCelUpdate.php?cNum="+Seg
											 +"&get="+Gett;
		//alert(toSend)
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=sjRe;
		myAjax.send('');


											
}

function endorse_excel(){

//alert('1')

	//var s_co=document.getElementById("s_contents").value;

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var push=document.getElementById('push').options[document.getElementById('push').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		var damdanja=document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value;
		
		window.open('./php/mGendorseExcell.php?insuranceComNum='+insuranceComNum+'&sigi='+sigi+'&end='+end+'&push='+push,'MGexcel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}

function endorse_excel2(){

//alert('1')

	//var s_co=document.getElementById("s_contents").value;

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var push=document.getElementById('push').options[document.getElementById('push').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		var damdanja=document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value;
		
		window.open('./php/samGendorseExcell.php?insuranceComNum='+insuranceComNum+'&s_contents='+s_contents+'&sigi='+sigi+'&end='+end+'&push='+push,'samsungexcel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}


function endorse_excel3(){

//alert('1')

	//var s_co=document.getElementById("s_contents").value;

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var push=document.getElementById('push').options[document.getElementById('push').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		var damdanja=document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value;
		
		window.open('./php/DailyendorseExcell.php?insuranceComNum='+insuranceComNum+'&s_contents='+s_contents+'&sigi='+sigi+'&end='+end+'&push='+push,'samsungexcel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}


function endorse_excel4(){

//alert('1')

	//var s_co=document.getElementById("s_contents").value;

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var push=document.getElementById('push').options[document.getElementById('push').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		var damdanja=document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value;
		
		window.open('./php/endorseJeongLi.php?insuranceComNum='+insuranceComNum+'&s_contents='+s_contents+'&sigi='+sigi+'&end='+end+'&push='+push,'endorseJeongli','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}