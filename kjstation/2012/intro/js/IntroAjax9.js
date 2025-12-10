function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	   	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text);
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
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text;
						document.getElementById('A6a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text;
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text
	
					}else{
						checkSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						monthPreminum(k,myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent);
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent;
						document.getElementById('A6a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent;
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].textContent;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent

					}
					
				}
		/*	if(myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].text!=undefined){
				document.getElementById("tot").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].text;
			}else{
				document.getElementById("tot").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].textContent;
			}*/
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
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
	window.open('../pop_up/daeriList.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=1000,height=800,scrollbars=yes,status=yes')
}
/*function seleCe(k,ch)
{
	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A6a'+k);
	 newInput2.id='ch'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeCh;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=12;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(ch)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 1 :
					  opts[i].text='접수';
						break;
					case 2 :
						opts[i].text='프린트';
					break;
					case 3 :
						opts[i].text='팩스';
					break;
					case 4 :
						opts[i].text='배서저장';
					break;
					case 5 :
						opts[i].text='스캔';
					break;
					case 6 :
					    opts[i].text='입금예정';
						break;
					case 7 :
						opts[i].text='수납예정';
					break;
					case 8 :
						opts[i].text='문자';
					break;
					case 9 :
						opts[i].text='구배서';
					break;
					case 10 :
						opts[i].text='종결';
					break;
					case 11 :
						opts[i].text='선택';
					break;
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

}
/*function changeChReceive()
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
function changeCh(){

	myAjax=createAjax();
	//alert(this.id)
	if(this.id.length==3){
		nn=this.id.substr(2,3);
	}else{
		nn=this.id.substr(2,4);
	}

	var EndorseListNum=document.getElementById("num"+nn).value;
	var chValue=document.getElementById('ch'+nn).options[document.getElementById('ch'+nn).selectedIndex].value;
	var toSend = "./ajax/chChange.php?EndorseListNum="+EndorseListNum
				   +"&chValue="+chValue;
					
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=changeChReceive;
	myAjax.send('');

	
}*/
function serch(val){
		//alert('1')
		for(var k=0;k<20;k++){	
				document.getElementById('bunho'+k).innerHTML='';
				document.getElementById('num'+k).value=''
				document.getElementById('A2a'+k).innerHTML='';
				//document.getElementById('A12a'+k).innerHTML='';
				//document.getElementById('A3a'+k).innerHTML=''
				//document.getElementById('cNum'+k).value='';				
				//document.getElementById('iNum'+k).value='';				
				//document.getElementById('pNum'+k).value='';
				document.getElementById('A4a'+k).innerHTML=''
				document.getElementById('A5a'+k).innerHTML='';
				document.getElementById('A6a'+k).innerHTML=''
				//document.getElementById('A7a'+k).value='';
				//document.getElementById('A8a'+k).innerHTML='';
				document.getElementById('A9a'+k).innerHTML='';
				//document.getElementById('cNum'+k).value='';				
				document.getElementById('A10a'+k).innerHTML=''//pnum	
			}
		//document.getElementById("tot").innerHTML='';
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
		//var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		var damdanja=document.getElementById("damdanja").options[document.getElementById("damdanja").selectedIndex].value;
		var toSend = "./ajax/ajax9.php?sigi="+sigi
				   +"&end="+end
				   +"&damdanja="+damdanja
				//	+"&insuranceComNum="+insuranceComNum
				   +"&s_contents="+s_contents
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





/*
function changeInsuCompany(){
		for(var k=0;k<20;k++){	
				document.getElementById('bunho'+k).innerHTML='';
				document.getElementById('num'+k).value=''
				document.getElementById('A2a'+k).innerHTML='';
				document.getElementById('A12a'+k).innerHTML='';
				document.getElementById('A3a'+k).innerHTML=''
				//document.getElementById('cNum'+k).value='';				
				//document.getElementById('iNum'+k).value='';				
				//document.getElementById('pNum'+k).value='';
				document.getElementById('A4a'+k).innerHTML=''
				document.getElementById('A5a'+k).innerHTML='';
				document.getElementById('A6a'+k).innerHTML=''
				document.getElementById('A7a'+k).value='';
				document.getElementById('A8a'+k).innerHTML='';
				document.getElementById('A9a'+k).innerHTML='';
				document.getElementById('cNum'+k).value='';				
				document.getElementById('A10a'+k).innerHTML=''//pnum	
			}
		document.getElementById("tot").innerHTML='';
		var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		//var chr=document.getElementById('chr').options[document.getElementById('chr').selectedIndex].value;
		var toSend = "./ajax/ajax4.php?sigi="+sigi
				   +"&end="+end
				   +"&insuranceComNum="+insuranceComNum
				   +"&s_contents="+s_contents
				  // +"&chr="+chr;
				  // +"&page="+page;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');

}

function changeChr(){


	for(var k=0;k<20;k++){	
				document.getElementById('bunho'+k).innerHTML='';
				document.getElementById('num'+k).value=''
				document.getElementById('A2a'+k).innerHTML='';
				document.getElementById('A3a'+k).innerHTML=''
				document.getElementById('cNum'+k).value='';				
				document.getElementById('iNum'+k).value='';				
				document.getElementById('pNum'+k).value='';
				document.getElementById('A4a'+k).innerHTML=''
				document.getElementById('A5a'+k).innerHTML='';
				document.getElementById('A6a'+k).innerHTML=''
				document.getElementById('A7a'+k).innerHTML='';
				document.getElementById('A8a'+k).innerHTML='';
				document.getElementById('A9a'+k).innerHTML=
				document.getElementById('eNum'+k).value='';				
				document.getElementById('A10a'+k).innerHTML=''//pnum	
			}
		var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var chr=document.getElementById('chr').options[document.getElementById('chr').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		
		var toSend = "./ajax/ajax3.php?sigi="+sigi
				   +"&end="+end
				   +"&insuranceComNum="+insuranceComNum
				   +"&s_contents="+s_contents
				   +"&chr="+chr;
				   //+"&page="+page;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}*/
function newAdminExcell(){
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//var mNab=document.getElementById("mNab").options[document.getElementById("mNab").selectedIndex].value;//납입회차
	    var damdanja=document.getElementById("damdanja").options[document.getElementById("damdanja").selectedIndex].value;
//alert(mNab);
		window.open('./php/sjExcel3.php?damdanja='+damdanja,'excel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
		
		
}