function pageList(){/*페이지 버튼 만들기*/

	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
		var page=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text;
		
		var Total=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		var totalPage=myAjax.responseXML.documentElement.getElementsByTagName("totalpage")[0].text;
		//alert(myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text);
		document.getElementById("page").value=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text;
		
		var pageD=document.getElementById("sql3");
		/************************************************************************/
		/* 전체 페이지 1인 경우와 아닌경우
		/* 첫번재 페이지 인경와 마직막 페이지인 경우 그 가운데인 경우            */
		/*************************************************************************/
		if(totalPage==1){
			pageD.innerHTML=page+"/"+totalPage;
		}else{
			if(page==1){
				pageD.innerHTML=page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}else if(page==totalPage){
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage;
			}else{
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}
		}
	}else{

		var page=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].textContent;
		var Total=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		var totalPage=myAjax.responseXML.documentElement.getElementsByTagName("totalpage")[0].textContent;
		document.getElementById("page").value=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].textContent;
		var pageD=document.getElementById("sql3");
		//alert(myAjax.responseXML.documentElement.getElementsByTagName("page")[0].textContent);
		if(totalPage==1){
			pageD.innerHTML=page+"/"+totalPage;
		}else{
			if(page==1){
				pageD.innerHTML=page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}else if(page==totalPage){
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"page";
			}else{
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}
		}
	}
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
			var dNumber=myAjax.responseXML.documentElement.getElementsByTagName("driveNum");
			var oName=myAjax.responseXML.documentElement.getElementsByTagName("ounname");
			var oJumin=myAjax.responseXML.documentElement.getElementsByTagName("ounjuimin");
			var app=myAjax.responseXML.documentElement.getElementsByTagName("app");
			var push=myAjax.responseXML.documentElement.getElementsByTagName("push");
			var com_phone=myAjax.responseXML.documentElement.getElementsByTagName("com_phone");
			//var cJumin=myAjax.responseXML.documentElement.getElementsByTagName("conjuimin");
			var company=myAjax.responseXML.documentElement.getElementsByTagName("company");
			var cert=myAjax.responseXML.documentElement.getElementsByTagName("certi");
			var user=myAjax.responseXML.documentElement.getElementsByTagName("user_id");
			var sangtae=myAjax.responseXML.documentElement.getElementsByTagName("sangtae");
			var wdat=myAjax.responseXML.documentElement.getElementsByTagName("wdate");
			//var prem=myAjax.responseXML.documentElement.getElementsByTagName("preminum");
			var sta=myAjax.responseXML.documentElement.getElementsByTagName("start");
			var Pnab=myAjax.responseXML.documentElement.getElementsByTagName("Pnab");//납입회차
			var Dam=myAjax.responseXML.documentElement.getElementsByTagName("damdanja");//납입회차
			if(eval(cert.length)>20){
				var tab=20;
			}else{
				var tab=cert.length;
			}	
			for(var k=0;k<tab;k++){

		
				//document.getElementById(bun).innerHTML=eval(k)+1;
				if(dNumber[k].text!=undefined){
					document.getElementById('com_phone'+k).value=com_phone[k].text;
					document.getElementById('driver_num'+k).value=dNumber[k].text;
					document.getElementById('ounname'+k).innerHTML=oName[k].text;
				    //document.getElementById('jumin'+k).innerHTML=oJumin[k].text;
					//document.getElementById('conname'+k).innerHTML=cName[k].text;
					document.getElementById('company'+k).innerHTML=company[k].text;
					document.getElementById('certi'+k).innerHTML=cert[k].text;
					//document.getElementById('sangtae'+k).innerHTML=sangtae[k].text;
					document.getElementById('appNum'+k).innerHTML=app[k].text;
					//document.getElementById('preminum'+k).innerHTML=prem[k].text;
					document.getElementById('gai'+k).innerHTML=wdat[k].text;
					//document.getElementById('user'+k).innerHTML=user[k].text;
					document.getElementById('ssi'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("SSsigi")[k].text;
					switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("state")[k].text)){
								
								case 1 :
									document.getElementById('ssi'+k).style.color='blue';
									break;
								
								case 2 :
									document.getElementById('ssi'+k).style.color='red';
									break;
								default : 
									document.getElementById('ssi'+k).style.color='#666666';
									break;
							}
					
					checkSele(k,oName[k].text,oJumin[k].text);
					checkPhone(k,myAjax.responseXML.documentElement.getElementsByTagName("p_buho")[k].text);
					var Pna_b=eval(Pnab[k].text);
					var p_ush=eval(push[k].text);
					seongSelect(Pna_b,k);//1회~10회차를 
					nabSelect(p_ush,k);//정상,해지
					document.getElementById('Ak'+k).innerHTML=Dam[k].text;
				}else{
					document.getElementById('com_phone'+k).value=com_phone[k].textContent;
					document.getElementById('driver_num'+k).value=dNumber[k].textContent;
					document.getElementById('ounname'+k).innerHTML=oName[k].textContent;
				    //document.getElementById('jumin'+k).innerHTML=oJumin[k].textContent;
					//document.getElementById('conname'+k).innerHTML=cName[k].textContent;
					document.getElementById('company'+k).innerHTML=company[k].textContent;
					document.getElementById('certi'+k).innerHTML=cert[k].textContent;
					//document.getElementById('sangtae'+k).innerHTML=sangtae[k].textContent;
					document.getElementById('appNum'+k).innerHTML=app[k].textContent;
					//document.getElementById('preminum'+k).innerHTML=prem[k].textContent;
					document.getElementById('gai'+k).innerHTML=wdat[k].textContent;
					//document.getElementById('user'+k).innerHTML=user[k].textContent;
					document.getElementById('ssi'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("SSsigi")[k].textContent;
					switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("state")[k].text)){
								
								case 1 :
									document.getElementById('ssi'+k).style.color='blue';
									break;
								
								case 2 :
									document.getElementById('ssi'+k).style.color='red';
									break;
								default : 
									document.getElementById('ssi'+k).style.color='#666666';
									break;
							}
					checkSele(k,oName[k].textContent,oJumin[k].textContent);
					checkPhone(k,myAjax.responseXML.documentElement.getElementsByTagName("p_buho")[k].textContent);
					var Pna_b=eval(Pnab[k].textContent);
					var p_ush=eval(push[k].textContent);
					seongSelect(Pna_b,k);//1회~10회차를 
					nabSelect(p_ush,k);//정상,해지
					document.getElementById('Ak'+k).innerHTML=Dam[k].textContent;
				}	
				
				
				
				
			}
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}
function nabResponse2() {
	
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!==undefined){
		
	   
		   alert('처리완료');
		}else{
			alert('처리완료');
		}
	}else{
		//	
	}
}
function nabResponse() {
	
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("SSsigi")[0].text!==undefined){
			var sunso=eval(myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text);
			switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("state")[0].text)){
								
				case 1 :
					document.getElementById('ssi'+sunso).style.color='blue';
					break;
				
				case 2 :
					document.getElementById('ssi'+sunso).style.color='red';
					break;
				default : 
					document.getElementById('ssi'+sunso).style.color='#666666';
					break;
			}
		   document.getElementById('ssi'+sunso).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("SSsigi")[0].text;
	   
		   alert('처리완료!!');
		}else{
			var sunso=eval(myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].textContent);
			switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("state")[0].textContent)){
								
				case 1 :
					document.getElementById('ssi'+sunso).style.color='blue';
					break;
				
				case 2 :
					document.getElementById('ssi'+sunso).style.color='red';
					break;
				default : 
					document.getElementById('ssi'+sunso).style.color='#666666';
					break;
			}
		   document.getElementById('ssi'+sunso).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("SSsigi")[0].textContent;

		}
	}else{
		//	
	}
}
function checkSele(k,oName,oJumin){
	/*대리기사 조회*/
		var cButton=document.getElementById('cNum'+k);
		 cButton.style.cursor='hand';
		 cButton.style.width = '90px';
		 cButton.innerHTML=k+1;
		 cButton.onclick=dSerch;
	/*조회 버튼 */
		 var bButton=document.getElementById('ounname'+k);
		 var j=k+1;
		 //bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		 bButton.style.width = '90px';
		 bButton.innerHTML=oName;
		 bButton.onclick=popup_drive;
	 /*퇴직 버튼 */
		 var aButton=document.getElementById('but'+k);
		 //aButton.className='input5';
		 //aButton.value=val1;
		 aButton.style.cursor='hand';
		 aButton.style.width = '30px';
		 aButton.innerHTML='퇴직';
		 aButton.onclick=certiIssue2;
	//주민번호조회

		var dButton=document.getElementById('jumin'+k);
		 //aButton.className='input5';
		 //aButton.value=val1;
		 dButton.style.cursor='hand';
		 dButton.style.width = '30px';
		 dButton.innerHTML=oJumin;
		 dButton.onclick=popupDrive2;//주민번호 눌러서조회하기 
}
function checkPhone(k,p_bunho){
	//alert(p_bunho)

	 var sjInput3=document.createElement("select");
	 var sjJumin=document.getElementById('pun'+k);
     sjInput3.id='pbunho'+k;
	 sjInput3.style.width = '80px';
	 sjInput3.className='selectbox';
	 sjInput3.onchange=changePbunho;//회차별 입금을 표시하기 위해
	 var opts3=sjInput3.options;
		opts3.length=7;
		for(var i=1;i<opts3.length;i++){
			
		opts3[i].value=i;
			switch(i){
				case 1 :
					opts3[i].text='조홍기';
					break;
				case 2 :
					opts3[i].text='오성준';
					break;
				case 3 :
					opts3[i].text='이근재';
					break;
				case 4 :
					opts3[i].text='오성엽';
					break;
				case 5 :
					opts3[i].text='박종민';
					break;
				case 6 :
					opts3[i].text='오경선';
					break;
				
			}

			if(opts3[i].value==eval(p_bunho)){
					sjInput3.selectedIndex=i;
				}
		}
		sjJumin.appendChild(sjInput3);
	/*var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('pun'+k);
	 newInput2.type='checkbox';
	 newInput2.id='pcheck'+k;
	 newInput2.onclick=bonagi_3;//핸드폰 번호를 보내기 위해(smaAjac.js)에 있습니다
	 newInput2.style.width = '20px';
	 aJumin.appendChild(newInput2);*/

	
}


function changePbunho(){
	//var val2=eval(this.getAttribute("value"));//1회차면 1,2회차이면2
	var nn=this.getAttribute("id");/*'msang'+k*/
	//alert(nn)
	if(nn.length==7){
		val1=nn.substr(6,7);
	}else{
		val1=nn.substr(6,8);
	}
	var driver_num='driver_num'+val1;
 // alert(val1);
	var driver_num=document.getElementById(driver_num).value;//교체전 운전자 ssang_drive_num
			
			//alert(driver_num)
	//val2=document.getElementById(nn).options[document.getElementById(nn).options.selectedIndex].value;
	//var useruid=document.getElementById("useruid").value;
	//var endorse_day=document.getElementById("endorse_day").value;
	//var ssangCnum=document.getElementById("ssang_c_num").value;
	
			
	var val2=document.getElementById("pbunho"+val1).options[document.getElementById("pbunho"+val1).selectedIndex].value;
	var val3=document.getElementById("pbunho"+val1).options[document.getElementById("pbunho"+val1).selectedIndex].text;
/*if(bank==0){
		alert('입금 은행 부터!!!')
		document.getElementById("bsang"+val1).focus();
		return false;
	}else{*/
	//alert(val2)
      
		if(confirm(val3+'님을 계약자로 지정합니다?')){
			var toSend = "./ajax/ChangePbunho.php?driver_num=" + driver_num
				//	+"&useruid="+useruid
				//	+"&endorse_day="+endorse_day
				//	+"&bank="+bank
					//+"&ssangCnum="+ssangCnum
					+"&sunbun="+val2;
			//alert(toSend);
			//displayLoading(document.getElementById("imgkor"));
			//document.getElementById("url").innerHTML = toSend;
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=nabResponse2;
			myAjax.send('');
		}
	 
	//}

}
function serch(val){
	//alert('1')
	/*********************************************/
	/* 조회할 때 먼저 있는 자료 삭제 하기 위해   */
	/*********************************************/
	for(var j=0;j<20;j++){
		if(document.getElementById('ounname'+j).innerHTML){
			changClear(document.getElementById('but'+j));
			changClear(document.getElementById('pun'+j));
			changClear(document.getElementById('pro2'+j));
			changClear(document.getElementById('pro'+j));
		}
		document.getElementById('company'+j).innerHTML='';
		document.getElementById('ounname'+j).innerHTML='';
		document.getElementById('jumin'+j).innerHTML='';
		document.getElementById('certi'+j).innerHTML='';
		//document.getElementById('preminum'+j).innerHTML='';
		//document.getElementById('sangtae'+j).innerHTML='';
		document.getElementById('gai'+j).innerHTML='';
		document.getElementById('cNum'+j).innerHTML='';
		//document.getElementById('conjumin'+j).innerHTML='';
		//document.getElementById('company'+j).innerHTML='';
		//document.getElementById('but'+j).innerHTML='';
		document.getElementById('com_phone'+j).value='';
		document.getElementById('driver_num'+j).value='';
		document.getElementById('appNum'+j).innerHTML='';
		document.getElementById('Ak'+j).innerHTML='';
		document.getElementById('ssi'+j).innerHTML='';
		
	}
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
		//var ssang_c_num=document.getElementById("ssang_c_num").value;
		var driverName=encodeURIComponent(document.getElementById("driver_name").value);

	//	var mem=document.getElementById("mem").options[document.getElementById("mem").selectedIndex].value;//담당자
		//var p_ush=document.getElementById("gi").options[document.getElementById("gi").selectedIndex].value;
		if(driverName){
			var mNab=12;//납입회차
		}else{
			var mNab=document.getElementById("mNab").options[document.getElementById("mNab").selectedIndex].value;//납입회차
		}
	    var p_bunho=document.getElementById("p_bunho").options[document.getElementById("p_bunho").selectedIndex].value;
		var insCompany=document.getElementById("insCompany").options[document.getElementById("insCompany").selectedIndex].value;
		var c_name=document.getElementById("c_name").options[document.getElementById("c_name").selectedIndex].value;
		var damdanja=document.getElementById("damdanja").options[document.getElementById("damdanja").selectedIndex].value;
		var state=document.getElementById("state").options[document.getElementById("state").selectedIndex].value;
		var manGi=document.getElementById('manGi').options[document.getElementById('manGi').selectedIndex].value;//가입월
		var toSend = "./ajax/dongbu2DriverSerch.php?mNab="+mNab
				  +"&damdanja="+damdanja
				  +"&manGi="+ manGi
				  +"&state="+state
				   +"&insCompany="+insCompany
			     +"&p_bunho="+p_bunho
				  +"&c_name="+c_name
				  +"&page="+page
				   +"&driverName="+driverName;
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	
}

addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해


/*function mNabChange(val,val2){
	//alert('1')

	for(var j=0;j<20;j++){
		if(document.getElementById('ounname'+j).innerHTML){
			changClear(document.getElementById('but'+j));
			changClear(document.getElementById('pun'+j));
			changClear(document.getElementById('pro2'+j));
			changClear(document.getElementById('pro'+j));
		}
		document.getElementById('company'+j).innerHTML='';
		document.getElementById('ounname'+j).innerHTML='';
		document.getElementById('jumin'+j).innerHTML='';
		document.getElementById('certi'+j).innerHTML='';
		//document.getElementById('preminum'+j).innerHTML='';
		//document.getElementById('sangtae'+j).innerHTML='';
		document.getElementById('gai'+j).innerHTML='';
		document.getElementById('cNum'+j).innerHTML='';
		//document.getElementById('conjumin'+j).innerHTML='';
		//document.getElementById('company'+j).innerHTML='';
		//document.getElementById('but'+j).innerHTML='';
		document.getElementById('com_phone'+j).value='';
		document.getElementById('driver_num'+j).value='';
		document.getElementById('appNum'+j).innerHTML='';
		document.getElementById('Ak'+j).innerHTML='';
		document.getElementById('ssi'+j).innerHTML='';
	}
	myAjax=createAjax();
	/*	switch(val){
		case 1 :/* 다음*/
	//		var page=document.getElementById("page").value;
	/*		page=eval(page)+1;
		break;
		case 2: /*이전*/
	/*		var page=document.getElementById("page").value;
			page=eval(page)-1;
		break;
		default:/* 그냥 조회*/
	/*
			var page='';
		break;
	}
		//var ssang_c_num=document.getElementById("ssang_c_num").value;
		//var sigi=document.getElementById('sigi').value;
		//var end=document.getElementById('end').value;
		//var p_ush=document.getElementById("gi").options[document.getElementById("gi").selectedIndex].value;
		var mNab=document.getElementById("mNab").options[document.getElementById("mNab").selectedIndex].value;//납입회차
		var damdanja=document.getElementById("damdanja").options[document.getElementById("damdanja").selectedIndex].value;
		//var mem=document.getElementById("mem").options[document.getElementById("mem").selectedIndex].value;//담당자
		var state=document.getElementById("state").options[document.getElementById("state").selectedIndex].value;
		var manGi=document.getElementById('manGi').options[document.getElementById('manGi').selectedIndex].value;//가입월
		var toSend = "./ajax/dongbu2DriverSerch.php?mNab="+mNab
				+"&manGi="+manGi
				 +"&state="+state
				 +"&damdanja="+damdanja
				  +"&page="+page
			      
				 //  +"&end="+end;
		alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');

}*/
function changClear(element){

	while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
}









function certi_check(){
	var  cName=document.getElementById("c_name").options[document.getElementById("c_name").selectedIndex].value;

		if(cName=='4'){
			
			var bar_1=document.getElementById("driver_name").value.substr(2,3);
			
			if(document.getElementById("driver_name").value.length <'9' && document.getElementById("driver_name").value.length >='1' &&  bar_1!='-' ){
					alert('증권번호는 9자리입니다')
						document.getElementById("driver_name").focus();
						return false;
					}
			
			/*	if(!IsNumber2(document.getElementById("driver_name").name)){
					 alert("증권번호는 숫자이어야 합니다");
					 document.getElementById("driver_name").focus();
					 return false;
				  }*/
			
			if(document.getElementById("driver_name").value.length >='10' && bar_1!='-' ){
					alert('증권번호는 9자리입니다1')
						document.getElementById("driver_name").focus();
						return false;
					}
			if(document.getElementById("driver_name").value.length =='9'){
				var start_year		=document.getElementById("driver_name").value.substr(0,2)
				var start_month	    =document.getElementById("driver_name").value.substr(2,9)
		

				
					if(start_year <05){
						alert('입력값이 잘못 되었습니다')
							document.getElementById("driver_name").focus();
						return;
					}
				
				document.getElementById("driver_name").value=start_year+"-"+start_month;
				
			}
		}
}
function certi_check_reverse(){
		var  cName=document.getElementById("c_name").options[document.getElementById("c_name").selectedIndex].value;
		if(cName=='4'){
			var bar=document.getElementById("driver_name").value.substr(2,3)
				
			if(document.getElementById("driver_name").value.length =='10' && bar=='-'){
				var start_year		=document.getElementById("driver_name").value.substr(0,2)
				var start_month	=document.getElementById("driver_name").value.substr(3,10)
				
					document.getElementById("driver_name").value=start_year+start_month;
			}
	}
}
function jump(){
	
	document.getElementById("c_name").focus();
	
}
/*정상,해지 만들어주는 모쥴*/
function nabSelect(p_ush,k){

	 var sjInput3=document.createElement("select");
	 var sjJumin=document.getElementById('pro2'+k);
     sjInput3.id='msangk'+k;
	 sjInput3.style.width = '60px';
	 sjInput3.className='selectbox';
	 sjInput3.onchange=changeInputSangtae2;//회차별 입금을 표시하기 위해
	 var opts3=sjInput3.options;
		opts3.length=6;
		for(var i=4;i<opts3.length;i++){
			
		opts3[i].value=i;
			if(i==5){ opts3[i].value=2;} 
			if(i==4){
				
				opts3[i].text='정상';
			}else{
			
				opts3[i].text='해지';
			}
			if(opts3[i].value==eval(p_ush)){
				sjInput3.selectedIndex=i;
			}
		}
		sjJumin.appendChild(sjInput3);

}
/*납입회차를 만들어주는 모쥴*/
function seongSelect(Pna_b,k){

	 var sjInput3=document.createElement("select");
	 var sjJumin=document.getElementById('pro'+k);
     sjInput3.id='msang'+k;
	 sjInput3.style.width = '60px';
	 sjInput3.className='selectbox';
	 sjInput3.onchange=changeInputSangtae;//회차별 입금을 표시하기 위해
	 var opts3=sjInput3.options;
		opts3.length=11;
		for(var i=0;i<opts3.length;i++){
			
		opts3[i].value=i;
			if(i==0){
				
				opts3[i].text='선택';
			}else{
			
				opts3[i].text=i+'회차';
			}
			if(opts3[i].value==eval(Pna_b)){
				sjInput3.selectedIndex=i;
			}
		}
		sjJumin.appendChild(sjInput3);

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
	var driver_num='driver_num'+val1;
  //alert(val1);
	var driver_num=document.getElementById(driver_num).value;//교체전 운전자 ssang_drive_num
			
	//val2=document.getElementById(nn).options[document.getElementById(nn).options.selectedIndex].value;
	var useruid=document.getElementById("useruid").value;
	var endorse_day=document.getElementById("end").value;
	//var ssangCnum=document.getElementById("ssang_c"+val1).value;
	//var bank=document.getElementById('bsang'+val1).value;
			
	var val2=document.getElementById("msang"+val1).options[document.getElementById("msang"+val1).selectedIndex].value;
/*if(bank==0){
		alert('입금 은행 부터!!!')
		document.getElementById("bsang"+val1).focus();
		return false;
	}else{*/
	//alert(val2)
	myAjax=createAjax();
      if(val2>=1){
		if(confirm(val2+'회차를 납입 합니까?')){
			var toSend = "./ajax/ChangeNab.php?driver_num=" + driver_num
					+"&endorse_day="+endorse_day
					+"&useruid="+useruid
					+"&sunso="+val1
					+"&sunbun="+val2;//(회차)

			document.getElementById('ssi'+val1).innerHTML='';//납입유예
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
	//}

}

/*q배서 회차에 따라  */
function changeInputSangtae2(){
	//var val2=eval(this.getAttribute("value"));//1회차면 1,2회차이면2
	var nn=this.getAttribute("id");/*'msang'+k*/
	//alert(nn)
	if(nn.length==7){
		val1=nn.substr(6,7);
	}else{
		val1=nn.substr(6,8);
	}
	var driver_num='driver_num'+val1;
 // alert(val1);
	var driver_num=document.getElementById(driver_num).value;//교체전 운전자 ssang_drive_num
			
			alert(driver_num)
	//val2=document.getElementById(nn).options[document.getElementById(nn).options.selectedIndex].value;
	var useruid=document.getElementById("useruid").value;
	var endorse_day=document.getElementById("endorse_day").value;
	//var ssangCnum=document.getElementById("ssang_c_num").value;
	var bank=document.getElementById('bsang'+val1).value;
			
	var val2=document.getElementById("msang"+val1).options[document.getElementById("msang"+val1).selectedIndex].value;
/*if(bank==0){
		alert('입금 은행 부터!!!')
		document.getElementById("bsang"+val1).focus();
		return false;
	}else{*/
	//alert(val2)
      if(val2>=1){
		if(confirm(val2+'회차를 납입 합니까?')){
			var toSend = "./ajax/ChangeNab.php?driver_num=" + driver_num
					+"&useruid="+useruid
					+"&endorse_day="+endorse_day
					+"&bank="+bank
					//+"&ssangCnum="+ssangCnum
					+"&sunbun="+val2;
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
	//}

}


function newAdminExcell(ssang_c_num){
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		var mNab=document.getElementById("mNab").options[document.getElementById("mNab").selectedIndex].value;//납입회차
	    var damdanja=document.getElementById("damdanja").options[document.getElementById("damdanja").selectedIndex].value;//담당자
//alert(mNab);
	    var state=document.getElementById("state").options[document.getElementById("state").selectedIndex].value;
		//alert(state)
		var manGi=document.getElementById('manGi').options[document.getElementById('manGi').selectedIndex].value;//가입월
		 var p_bunho=document.getElementById("p_bunho").options[document.getElementById("p_bunho").selectedIndex].value;
		var insCompany=document.getElementById("insCompany").options[document.getElementById("insCompany").selectedIndex].value;
		//alert(manGi)
		window.open('./php/sjExcel2.php?damdanja='+damdanja+'&mNab='+mNab+'&state='+state+'&manGi='+manGi+'&insCompany='+insCompany+'&p_bunho='+p_bunho,'excel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
		
		
}