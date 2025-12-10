function pageList(){/*페이지 버튼 만들기*/

	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
		var page=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text;
		var Total=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		var totalPage=myAjax.responseXML.documentElement.getElementsByTagName("totalpage")[0].text;
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
			var cert=myAjax.responseXML.documentElement.getElementsByTagName("certi");
			var cName=myAjax.responseXML.documentElement.getElementsByTagName("c_name");
			var gaDate=myAjax.responseXML.documentElement.getElementsByTagName("ga_date");/*가입일*/
			var nab=myAjax.responseXML.documentElement.getElementsByTagName("nabang");/*납방*/
			var drcount=myAjax.responseXML.documentElement.getElementsByTagName("driverCount");/*대리운전회사별 인원수*/
			var dongCount=myAjax.responseXML.documentElement.getElementsByTagName("dongCount");//*동부2인원수*/
			var dongbuNum=myAjax.responseXML.documentElement.getElementsByTagName("dnum");/*동부num*/
			var ssangC=myAjax.responseXML.documentElement.getElementsByTagName("ssang_c_num");
			var comP=myAjax.responseXML.documentElement.getElementsByTagName("company");
			var this_nab=myAjax.responseXML.documentElement.getElementsByTagName("this_nab");
			var this_month=myAjax.responseXML.documentElement.getElementsByTagName("this_month");
			var totalM=myAjax.responseXML.documentElement.getElementsByTagName("totalMember");
			//alert(totalM[0].text);
			
			for(var k=0;k<cert.length;k++){
				document.getElementById('bunho'+k).innerHTML=eval(k)+1;
				if(this_nab[k].text!=undefined){
					document.getElementById('month'+k).innerHTML=this_nab[k].text;
					//document.getElementById('company'+k).innerHTML=;
					document.getElementById('cert'+k).value=cert[k].text;
					document.getElementById('cname'+k).innerHTML=cName[k].text;
					document.getElementById('si'+k).innerHTML=gaDate[k].text;
					//document.getElementById('naban'+k).innerHTML=nab[k].text;
					//document.getElementById('mem'+k).innerHTML=drcount[k].text;
					document.getElementById('ssang_c'+k).value=ssangC[k].text;
					document.getElementById('dongb_c'+k).value=dongbuNum[k].text;
					checkSele(k,dongCount[k].text,comP[k].text);

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

						}
					
					document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
				}else{
					document.getElementById('month'+k).innerHTML=this_nab[k].textContent;
					//document.getElementById('company'+k).innerHTML=;
					document.getElementById('cert'+k).value=cert[k].textContent;
					document.getElementById('cname'+k).innerHTML=cName[k].textContent;
					document.getElementById('si'+k).innerHTML=gaDate[k].textContent;
					//document.getElementById('naban'+k).innerHTML=nab[k].textContent;
					//document.getElementById('mem'+k).innerHTML=drcount[k].textContent;
					document.getElementById('ssang_c'+k).value=ssangC[k].textContent;
					document.getElementById('dongb_c'+k).value=dongbuNum[k].textContent;
					checkSele(k,dongCount[k].textContent,comP[k].textContent);

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

						}
					
					document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
				}
				
		
				//alert(eval(k)+1);
				
			}
			if(totalM[0].text!=undefined){
				document.getElementById("tot").innerHTML=totalM[0].text;
			}else{
				document.getElementById("tot").innerHTML=totalM[0].textContent;
			}
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}

function checkSele(k,k2,k3){

	 var aButton=document.getElementById('day'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 aButton.style.cursor='hand';
	 aButton.style.width = '40px';
	// aButton.innerHTML='일일';
	// aButton.onclick=ssang_preminum;

	 var bButton=document.getElementById('but'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	// bButton.style.cursor='hand';
	// bButton.style.width = '40px';
	// bButton.innerHTML='현황';
	// bButton.onclick=detail12;

	/* var cButton=document.getElementById('dong'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 cButton.style.cursor='hand';
	 cButton.style.width = '40px';
	 cButton.innerHTML=k2;
	 cButton.onclick=dongDetail;*/

	 var dButton=document.getElementById('company'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 dButton.style.cursor='hand';
	 dButton.style.width = '40px';
	 dButton.innerHTML=k3;
	// dButton.onclick=MothpDatile;//각보험회사별 보험료
}
function serch(val){
	
	for(var k=0;k<20;k++){
		
			//document.getElementById('bunho'+k).innerHTML='';
			document.getElementById('company'+k).innerHTML='';
			document.getElementById('cert'+k).value='';
			document.getElementById('cname'+k).innerHTML='';
			document.getElementById('si'+k).innerHTML='';
			document.getElementById('A3a'+k).innerHTML='';
			document.getElementById('mem'+k).innerHTML='';
		/*	document.getElementById(user).innerHTML='';*/
		    document.getElementById('insCom'+k).value='';
			document.getElementById('day'+k).innerHTML='';
			document.getElementById('ssang_c'+k).value='';
			document.getElementById('month'+k).innerHTML='';
			document.getElementById('but'+k).innerHTML='';
			//document.getElementById('dong'+k).innerHTML='';
	}
	document.getElementById("tot").innerHTML='';
	myAjax=createAjax();
//	var sigi=document.getElementById("sigi").value;
//	var end=document.getElementById("end").value;
	
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

	var end=document.getElementById("end").value;
	//var state=document.getElementById('state').options[document.getElementById('state').options.selectedIndex].value;
	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	var toSend = "../intro/ajax/ajax10.php?s_contents="+s_contents
			   +"&end="+end
			   +"&s_contents="+s_contents
			   +"&page="+page;
			   //+"&state="+state;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}

addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
//증권번호 입력-->
function changeEtagResponse() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
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
function certi(bunho){
	var val=document.getElementById('cert'+bunho).value;
	switch(eval(document.getElementById('insCom'+bunho).value)){

		case 1 :
			
			if(val.length==10){
				
				//alert(val.substring(5,12))
				document.getElementById('cert'+bunho).value=val.substring(6,12);
			}
			break;
		case 2 :

			break;
		case 3 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					document.getElementById('cert'+bunho).value=val.substring(5,12);
				}			
			break;
		case 4 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					document.getElementById('cert'+bunho).value=val.substring(5,12);
				}			
		break;
		case 5 :

			break;
		case 6:

			break;

	}
		

	
}
function certiInput(bunho){

	//alert(document.getElementById('cert'+bunho).value.length);
	switch(eval(document.getElementById('insCom'+bunho).value)){

		case 1 :
			if(document.getElementById('cert'+bunho).value.length==4){
				document.getElementById('cert'+bunho).value=eval(document.getElementById('end').value.substring(2,4))+'-700'+document.getElementById('cert'+bunho).value;
			}else{
			alert('네자리 !!')
		//	document.getElementById('cert'+bunho).focus();
				return false;
			
			}
		

			break;
		case 2 :

			break;
		case 3 :
		if(document.getElementById('cert'+bunho).value.length==7){
			document.getElementById('cert'+bunho).value=eval(document.getElementById('end').value.substring(0,4))+'-'+document.getElementById('cert'+bunho).value;
		}else{

			alert('일곱자리 !!')
		//	document.getElementById('cert'+bunho).focus();
				return false;
		}
			break;
		case 4 :
		if(document.getElementById('cert'+bunho).value.length==7){
			document.getElementById('cert'+bunho).value=eval(document.getElementById('end').value.substring(0,4))+'-'+document.getElementById('cert'+bunho).value;
		}else{

			alert('일곱자리 !!')
		//	document.getElementById('cert'+bunho).focus();
				return false;
		}
			break;
		case 5 :

			break;
		case 6:

			break;

	}

		myAjax=createAjax();//청약번호 저장을 하기위해
		var  pCerti=document.getElementById('cert'+bunho).value;
		var  dirverNum=document.getElementById('dongb_c'+bunho).value;
		var toSend = "./ajax/appInput.php?appNumber="+pCerti
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
function Allchange(){

	
var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
var s_contents2=encodeURIComponent(document.getElementById("s_contents2").value);
if(s_contents2 && s_contents){
		if(confirm(s_contents2+'으로 변경합니다 !!')){
		var toSend = "./ajax/certiDriverChange.php?s_contents="+s_contents
					+"&s_contents2="+s_contents2
				  
				  
		
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=AllReceive;
		myAjax.send('');
	}else{


	}
	}else{

		alert('증권번호 입력후 !!')
	}


}
function certi_excel(){


	var s_co=document.getElementById("s_contents").value;

	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		
//alert(mNab);
		window.open('./php/sjExcel17.php?num='+s_co,'excel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}

function pClick(){
    var val=document.getElementById("end").value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	//window.open('/newAdmin/dongbu2/pop_up/driverSerch.php?driver_num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
	window.open('/kibs_admin/pdf/fpdf153/Allcerti3.php?num='+val,'print','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
}