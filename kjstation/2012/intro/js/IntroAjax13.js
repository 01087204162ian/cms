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

						if(!myAjax.responseXML.documentElement.getElementsByTagName("m1")[k].text){continue;}
						checkCerti(k,myAjax.responseXML.documentElement.getElementsByTagName("m1")[k].text);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("num")[k].text;
						//document.getElementById('M1a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m1")[k].text;
						document.getElementById('M3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m3")[k].text;
						document.getElementById('M4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m4")[k].text;
						document.getElementById('M5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m5")[k].text;
						document.getElementById('m11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a_11")[k].text;
						//document.getElementById('M5a'+k).innerHTML=
						for(var _j=1;_j<10;_j++){
						document.getElementById('A'+_j+'a'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].text;
						}
						document.getElementById('insNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("m6")[k].text;
							switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("m6")[k].text))
						{
								case 1 :
									document.getElementById('M2a'+k).innerHTML='흥국';
								    document.getElementById('M2a'+k).style.color='#F345FC';
									break;
								case 2 :
									document.getElementById('M2a'+k).innerHTML='DB';
									document.getElementById('M2a'+k).style.color='#a4690e';
									break;
								case 3 :
									document.getElementById('M2a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('M2a'+k).innerHTML='현대';
									 document.getElementById('M2a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('M2a'+k).innerHTML='롯데';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('M2a'+k).innerHTML='더 케이';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;
							    case 7 :
									document.getElementById('M2a'+k).innerHTML='MG';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('M2a'+k).innerHTML='삼성';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;

						}
					/*	monthPreminum(k,myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].text);

						document.getElementById('A12a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].text;
						document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text))
						{
								case 1 :
									document.getElementById('A3a'+k).innerHTML='흥국';
								    document.getElementById('A3a'+k).style.color='#F345FC';
									break;
								case 2 :
									document.getElementById('A3a'+k).innerHTML='동부';
									document.getElementById('A3a'+k).style.color='#a4690e';
									break;
								case 3 :
									document.getElementById('A3a'+k).innerHTML='LiG';
									break;
								case 4 :
									document.getElementById('A3a'+k).innerHTML='현대';
									 document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A3a'+k).innerHTML='롯데';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A3a'+k).innerHTML='더 케이';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;

						}
					
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text;
						document.getElementById('A6a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text;
						
						
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
						document.getElementById('A7a'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						//document.getElementById('A8a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						inwon(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text);
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("cNum")[k].text;
						//(k);
						//document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;//pnum

					*/
						
					}else{
										checkCerti(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("num")[k].textContent;
						document.getElementById('M1a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m1")[k].textContent;
						document.getElementById('M3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m3")[k].textContent;
						document.getElementById('M4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m4")[k].textContent;
						document.getElementById('M5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m5")[k].textContent;
						document.getElementById('m11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a_11")[k].textContent;
						for(var _j=1;_j<10;_j++){
						document.getElementById('A'+_j+'a'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].textContent;
						}

						document.getElementById('insNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("m6")[k].textContent;
							switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("m6")[k].textContent))
						{
								case 1 :
									document.getElementById('M2a'+k).innerHTML='흥국';
								    document.getElementById('M2a'+k).style.color='#F345FC';
									break;
								case 2 :
									document.getElementById('M2a'+k).innerHTML='DB';
									document.getElementById('M2a'+k).style.color='#a4690e';
									break;
								case 3 :
									document.getElementById('M2a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('M2a'+k).innerHTML='현대';
									 document.getElementById('M2a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('M2a'+k).innerHTML='롯데';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('M2a'+k).innerHTML='더 케이';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;
							    case 7 :
									document.getElementById('M2a'+k).innerHTML='MG';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('M2a'+k).innerHTML='삼성';
								    document.getElementById('M2a'+k).style.color='#E4690C';
									break;

						}

					}
					
				}
			if(myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].text!=undefined){
				document.getElementById("tot").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].text;
				document.getElementById("tot2").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember2")[0].text;

			}else{
				document.getElementById("tot").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember")[0].textContent;
				document.getElementById("tot2").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalMember2")[0].textContent;
			}
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}


function cReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
//alert(myAjax.responseText);
//alert(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text);
	       if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){//
			   // 메모 내용 저장을 위해 msg3
			   //document.getElementById("M3a0").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("msg3")[0].text
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].text;
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].textContent;
				
			}
	}else{


	}

}
function checkCerti(k,policy){
	
	/*일일 버튼 */
		 var bButton=document.getElementById('bunho'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=k+1;
		 bButton.onclick=certiUpate;


	//증권번호클릭

		var bButton=document.getElementById('M1a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=policy;
		// bButton.onclick=naiCheck;
	//document.getElementById('M1a'+k).innerHTML*/
	
}
function certiUpate(){
	//alert(this.id)
	if(this.id.length==6){
		var val=this.id.substr(5,6);
	}else{
		var val=this.id.substr(5,7);
	}
	var insNum=document.getElementById('insNum'+val).value;

	var Certi=document.getElementById('M1a'+val).innerHTML;
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/certiM.php?Certi='+Certi+'&insNum='+insNum,'certiList2','left='+winl+',top='+wint+',resizable=yes,width=1100,height=800,scrollbars=yes,status=yes')
}
function naiCheck(){  //증권번호별 나이 구분및 보험료 입력
	//alert(this.id)
	if(this.id.length==4){
		var val=this.id.substr(3,4);
	}else{
		var val=this.id.substr(3,5);
	}
	var A20a=document.getElementById('A20a'+val).value; //증권번호
	 var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	alert(A20a);
	window.open('../pop_up/certiNaiP.php?A20a='+A20a,'certiList3','left='+winl+',top='+wint+',resizable=yes,width=1100,height=800,scrollbars=yes,status=yes')

}
function change(k){
	//alert('1')
	//alert(k)
	var certi=document.getElementById('M1a'+k).innerHTML;
	var A1a=encodeURIComponent(document.getElementById('A1a'+k).value);
	var A2a=encodeURIComponent(document.getElementById('A2a'+k).value);
	var A3a=encodeURIComponent(document.getElementById('A3a'+k).value);
	var A4a=encodeURIComponent(document.getElementById('A4a'+k).value);
	var A5a=encodeURIComponent(document.getElementById('A5a'+k).value);
	var A6a=encodeURIComponent(document.getElementById('A6a'+k).value);
	var A7a=encodeURIComponent(document.getElementById('A7a'+k).value);
	var A8a=encodeURIComponent(document.getElementById('A8a'+k).value);

//	var A9a=encodeURIComponent(document.getElementById('A9a'+k).value);
//	var A10a=encodeURIComponent(document.getElementById('A10a'+k).value);
	
		if(confirm('수정')){
			var toSend = "./ajax/update2012C.php?certi="+certi 
						  +"&A1a="+A1a
						  +"&A2a="+A2a
						  +"&A3a="+A3a
						  +"&A4a="+A4a
						  +"&A5a="+A5a
						  +"&A6a="+A6a
						  +"&A7a="+A7a
						  +"&A8a="+A8a
				//		 +"&A9a="+A9a
				//		 +"&A10a="+A10a
						 //+"&sj="+A11a

						//	+"&insuranceComNum="+insuranceComNum
						//   +"&s_contents="+s_contents
						  // +"&you="+you
						   //+"&manGi="+manGi
						//   +"&page="+page;
			//alert(toSend)
			//document.getElementById("url").innerHTML = toSend;
			
			//alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=cReceive;
			myAjax.send('');
		}else{

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
	//var num=document.getElementById("num"+val).value;//2012DaeriCompanyNum
	//var certiNum=document.getElementById('cNum'+val).value;
	//var certiNum=document.getElementById('cNum'+val).value;
	var pNum=document.getElementById('A4a'+val).innerHTML;
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/pList.php?pNum='+pNum,'memberList2','left='+winl+',top='+wint+',resizable=yes,width=1100,height=800,scrollbars=yes,status=yes')
}

function serch(val){
		
		for(var k=0;k<20;k++){	
				document.getElementById('bunho'+k).innerHTML='';
				document.getElementById('num'+k).value=''
				document.getElementById('M1a'+k).innerHTML='';
				document.getElementById('M2a'+k).innerHTML='';
				document.getElementById('M3a'+k).innerHTML='';
				document.getElementById('M4a'+k).innerHTML='';
				document.getElementById('M5a'+k).innerHTML='';
				document.getElementById('A1a'+k).value='';
				document.getElementById('A2a'+k).value='';
				document.getElementById('A3a'+k).value='';
				document.getElementById('A4a'+k).value='';
				document.getElementById('A5a'+k).value='';
				document.getElementById('A6a'+k).value='';
				document.getElementById('A7a'+k).value='';
				document.getElementById('A8a'+k).value='';
				document.getElementById('A9a'+k).value='';
				document.getElementById('m11a'+k).innerHTML='';
					
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
		//var manGi=document.getElementById('manGi').options[document.getElementById('manGi').selectedIndex].value;
		var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
		var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
		//var you=document.getElementById('you').options[document.getElementById('you').selectedIndex].value; //유예
		var toSend = "./ajax/ajax13.php?sigi="+sigi
				   +"&end="+end
					+"&insuranceComNum="+insuranceComNum
				   +"&s_contents="+s_contents
				  // +"&you="+you
				   //+"&manGi="+manGi
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
		var toSend = "./ajax/ajax12.php?sigi="+sigi
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
/*
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

// 인원 추가 
function maxInwon(j){

	//alert($("#A11a"+j).val()+'/'+$("#num"+j).val());

	var send_url = "/2012/intro/ajax/_maxInwon.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"maxInwon", 
				     maxInwon:$("#A9a"+j).val(),
					 num:$("#num"+j).val()
				    }
		}).done(function( xml ) {

			//values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {

					alert( $(this).find('message').text());
            	  /*  name32		= $(this).find('name32').text();
					message	= $(this).find('message').text();
					company	= $(this).find('company').text();
					policyNum = $(this).find('policyNum').text();
					gita			= $(this).find('gita').text();	
					insurane    = $(this).find('insurane').text();	
					minNai = $(this).find('min').text();
					mNum = $(this).find('mNum').text(); // 전체인원
					ga_member = $(this).find('ga_member').text(); //26세미만 가입가능인원 5%
					ga_member2 = $(this).find('ga_member2').text(); // 가입가능인원
					cCount= $(this).find('cCount').text();//기가입인원 26세미만
					insNum = $(this).find('insNum').text();	*/
            	
	   				
				 });
			});
				
				//$("#min").val(minNai); //최소연령
				//alert(insNum);
				//$("#InCom").html("["+insurane+"]"+company+"["+policyNum+"]["+gita+"]");
				
			//dp_certiList(insNum);

			
		});
	}


	function cord(j){

		var send_url = "/2012/intro/ajax/_cord.php";
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"cord", 
				     cord:$("#A6a"+j).val(),
					 num:$("#num"+j).val()
				    }
		}).done(function( xml ) {

			//values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {

					message			= $(this).find('message').text();
            	
            	
	   				
				 });
			});
				alert(message);
				

			
		});

	}

