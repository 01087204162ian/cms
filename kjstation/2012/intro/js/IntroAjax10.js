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
					document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[k].text;
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
									document.getElementById('A3a'+k).innerHTML='메리트';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A3a'+k).innerHTML='MG';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
																	
								case 8 :
									document.getElementById('A3a'+k).innerHTML='삼성';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;

						}
					
					document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
					diffChangeCompany2(k,myAjax.responseXML.documentElement.getElementsByTagName("Cnum")[k].text)//
				}else{
					document.getElementById('month'+k).innerHTML=this_nab[k].textContent;
					document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[k].textContent;
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
								case 7 :
									document.getElementById('A3a'+k).innerHTML='MG';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A3a'+k).innerHTML='삼성';
								    document.getElementById('A3a'+k).style.color='#E4690C';
									break;

						}
					
					document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
				}
				
		
				//alert(eval(k)+1);
				
			}
			if(totalM[0].text!=undefined){
				document.getElementById("tot").innerHTML=totalM[0].text;
				document.getElementById("DaeriCompanyNewNum").value=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNewNum")[0].text;
			}else{
				document.getElementById("tot").innerHTML=totalM[0].textContent;
				document.getElementById("DaeriCompanyNewNum").value=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNewNum")[0].textContent;
			}
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}
function diffChangeCompany2(k,length){
//alert(length)
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('month'+k);
	 newInput2.id='diffC'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changCompany;//보험회사 변경
	 var opts=newInput2.options;
	opts.length=eval(length);
	//if(//myAjax.responseXML.documentElement.getElementsByTagName("Qnum"+k)[0].text!=''){
	//var Qnum=(eval(myAjax.responseXML.documentElement.getElementsByTagName("Qnum"+k)[0].text));
	//}
//alert(Qnum)
	 for(var i=0;i<opts.length;i++){	
		 
		//alert( myAjax.responseXML.documentElement.getElementsByTagName("Ctnum"+i)[0].text)
		opts[i].value=myAjax.responseXML.documentElement.getElementsByTagName("Ctnum"+k+i)[0].text;
		//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("snum"+k+i)[0].text))
		//alert('보험회사'+myAjax.responseXML.documentElement.getElementsByTagName("inum"+k+i)[0].text)
		//alert(myAjax.responseXML.documentElement.getElementsByTagName("Qnum"+k)[0].text);
		/*if(opts[i].value==Qnum){

			//alert(i)
			newInput2.selectedIndex=i;

		}*/
		 if(opts[i].value==eval(99999999)){	
			newInput2.selectedIndex=i;
		}

				switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("inum"+k+i)[0].text)){
					default :
					opts[i].text='선택';
					  //  newInput2.selectedIndex='99999999';
						break;
					case 1 :
						opts[i].text='흥국';
					break;
					case 2 :
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("gita"+k+i)[0].text)==2){
						opts[i].text='동탁';
						}else{
						opts[i].text='동부';
						}
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
						opts[i].text='메리츠';
					break;
					case 7 :
						opts[i].text='MG';
					break;
				    case 8 :
						opts[i].text='삼성';
					break;
				}
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

}

function changeReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);

			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text
				document.getElementById('mem'+sunso).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("policyNum")[0].text;
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text)


				
				if(myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text==2||myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text==8){//동부화재 일때만

						cheriNab(sunso);
						document.getElementById('chanCom'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				}
				if(myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text==7){//MG
						cheriNab2(sunso);
						document.getElementById('chanCom'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				}

				
			}else{

				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].textContent
				document.getElementById('mem'+sunso).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("policyNum")[0].textContent;

			}

	}else{




	}





}
function cheriNab2(k){//납입회차 
	
    var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('day'+k);
	 newInput2.type='checkbox';
	 newInput2.id='Check2'+k;
	 //newInput2.onclick=bonagi_3;//
	 newInput2.style.width = '20px';
	 aJumin.appendChild(newInput2);


	 var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A9a'+k);
	 newInput2.type='text';
	 newInput2.className='input2';
	 newInput2.id='p2eck'+k;
	// if(cheon==1){
	// newInput2.style.color="#E4690C";
	// }
	// newInput2.value=inwo;
	 newInput2.onblur=certiInput2;
	 newInput2.onclick=certi2;//
	 newInput2.style.width = '75px';
	 
	 aJumin.appendChild(newInput2);



}
function cheriNab(k){//납입회차 
	
    var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('day'+k);
	 newInput2.type='checkbox';
	 newInput2.id='Check2'+k;
	 //newInput2.onclick=bonagi_3;//핸드폰 번호를 보내기 위해(smaAjac.js)에 있습니다
	 newInput2.style.width = '20px';
	 aJumin.appendChild(newInput2);


	 var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A9a'+k);
	 newInput2.type='text';
	 newInput2.className='input2';
	 newInput2.id='p2eck'+k;
	// if(cheon==1){
	// newInput2.style.color="#E4690C";
	// }
	// newInput2.value=inwo;
	 newInput2.onblur=certiInput2;
	 newInput2.onclick=certi2;//
	 newInput2.style.width = '75px';
	 
	 aJumin.appendChild(newInput2);


var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('but'+k);
	 newInput2.id='cheriii'+k;
	 newInput2.style.width = '60px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeNab;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=11;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		//if(opts[i].value==eval(nabang_1)){	
		//	newInput2.selectedIndex=i;
		//}
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


function changeNab()
{

	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==8){
		nn=nn.substr(7,8);
	}else{
		nn=nn.substr(7,9);
	}

	//var val=this.getAttribute("value");
	var val=document.getElementById('cheriii'+nn).options[document.getElementById('cheriii'+nn).selectedIndex].value;
	//alert(val)
	if(eval(val)<=10){

		if(confirm(val+"회차를 납입합니다 !!")){
		var DariMemberNum='num'+nn;
		var DariMemberNum=document.getElementById(DariMemberNum).value;

		
		var userId=document.getElementById('userId').value;
		//alert(userId);
		var endorseDay=document.getElementById('endorseDay').value;
		var DaeriCompanyNum=document.getElementById('cNum'+nn).value; //			
		var CertiTableNum=document.getElementById('pNum'+nn).value;//certiTableNum
		var policyNum=document.getElementById('A9a'+nn).innerHTML;//
		
		var insuranceNum=document.getElementById('iNum'+nn).value;//보험회사번호
		
		var toSend = "../pop_up/ajax/changeNab.php?DariMemberNum="+DariMemberNum
						+"&DaeriCompanyNum="+DaeriCompanyNum
					   +"&CertiTableNum="+CertiTableNum
						+"&endorseDay="+endorseDay
						+"&policyNum="+policyNum
						+"&userId="+userId
					   +"&insuranceNum="+insuranceNum
						+"&val="+val;

		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=daeriMemberReceive;
		myAjax.send('');
		}else{
			document.getElementById('cheriii'+nn).value=eval(val)-1;
			return false;
		}
	}else{
		alert('해지신청만 가능합니다');

	}
}
function certiInput2(){
	//alert('1')
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==6){
		bunho=nn.substr(5,6);
	}else{
		bunho=nn.substr(5,7);
	}
	//alert(document.getElementById('chanCom'+bunho).value)
	
	switch(eval(document.getElementById('chanCom'+bunho).value)){

		case 1 :

			if(this.value.length==4){
				if(document.getElementById('Check2'+bunho).checked==true){
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
				if(document.getElementById('Check2'+bunho).checked==true){
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
				if(document.getElementById('Check2'+bunho).checked==true){
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
					if(document.getElementById('Check2'+bunho).checked==true){
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
		case 7:

			break;
		case 8:
		//if(this.value.length==14){
			
			//	this.value='114'+this.value;
			this.value=this.value;
	//	}else{

	//		alert('열네자리 !!')
		//	document.getElementById('A9a'+bunho).focus();
	//			return false;
	//	}
			break;


	}

		myAjax=createAjax();//청약번호 저장을 하기위해

		//alert(bunho)
		var  pCerti=this.value;
		//alert(
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

function certi2(){


	var nn=this.getAttribute("id");
	//alert(nn)
	if(nn.length==6){
		bunho=nn.substr(5,6);
	}else{
		bunho=nn.substr(5,7);
	}

	//alert(bunho)
	var val=document.getElementById('p2eck'+bunho).value;
	//alert(document.getElementById('chanCom'+bunho).value);
	switch(eval(document.getElementById('chanCom'+bunho).value)){

		case 1 :
			
			if(val.length==10){
				
				//alert(val.substring(5,12))
				document.getElementById('p2eck'+bunho).value=val.substring(6,12);
			}
			break;
		case 2 :
			if(val.length==10){
					
					//alert(val.substring(5,12))
					document.getElementById('p2eck'+bunho).value=val.substring(3,10);
				}			
			break;
			break;
		case 3 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					document.getElementById('p2eck'+bunho).value=val.substring(5,12);
				}			
			break;
		case 4 :
			if(val.length==12){
					
					//alert(val.substring(5,12))
					document.getElementById('p2eck'+bunho).value=val.substring(5,12);
				}			
		break;
		case 5 :

			break;
		case 6:

			break;

		case 7:

			break;

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
		case 7:

			break;
		case 8:
		if(this.value.length==14){
			
			//	this.value='114'+this.value;
			this.value=this.value;
		}else{

			alert('열한자리11 !!')
		//	document.getElementById('A9a'+bunho).focus();
				return false;
		}
			break;
	}

		myAjax=createAjax();//청약번호 저장을 하기위해
		var  pCerti=this.value;
		var  dirverNum=document.getElementById('num'+bunho).value;
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
function changeNab()
{

	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==8){
		nn=nn.substr(7,8);
	}else{
		nn=nn.substr(7,9);
	}

	//var val=this.getAttribute("value");
	var val=document.getElementById('cheriii'+nn).options[document.getElementById('cheriii'+nn).selectedIndex].value;
	//alert(val)
	if(eval(val)<=10){

		if(confirm(val+"회차를 납입합니다 !!")){
		//var DariMemberNum='num'+nn;
		//var DariMemberNum=document.getElementById(DariMemberNum).value;
var   DariMemberNum=document.getElementById('dongb_c'+nn).value;
		//var userId=document.getElementById('userId').value;
		//alert(userId);
		//var endorseDay=document.getElementById('endorseDay').value;
		//var DaeriCompanyNum=document.getElementById('cNum'+nn).value; //			
		//var CertiTableNum=document.getElementById('pNum'+nn).value;//certiTableNum
		//var policyNum=document.getElementById('A9a'+nn).innerHTML;//
		
		//var insuranceNum=document.getElementById('iNum'+nn).value;//보험회사번호
		
		var toSend = "../pop_up/ajax/changeNab.php?DariMemberNum="+DariMemberNum
						//+"&DaeriCompanyNum="+DaeriCompanyNum
					   //+"&CertiTableNum="+CertiTableNum
						//+"&endorseDay="+endorseDay
						//+"&policyNum="+policyNum
						//+"&userId="+userId
					  // +"&insuranceNum="+insuranceNum
						+"&val="+val;

		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=daeriMemberReceive;
		myAjax.send('');
		}else{
			document.getElementById('cheriii'+nn).value=eval(val)-1;
			return false;
		}
	}else{
		alert('해지신청만 가능합니다');

	}
}

function changCompany(){
	this.value;//2012CertiTable 의 num
	//alert(this.value)
	var nn=this.getAttribute("id");
	//alert(nn)
	if(this.value.length==6){
		nn=nn.substr(5,6);
	}else{
		nn=nn.substr(5,7);
	}
	//alert(nn)

	var driverNum=document.getElementById("dongb_c"+nn).value;//2012DaeriMember 

	if(confirm('변경합니다')){
		var toSend = "./ajax/changeInCom.php?memberNum="+driverNum
									+"&sunso="+nn
									+"&CertiTableNum="+this.value; 
										
	//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=changeReceive;
				myAjax.send('');
	}else{

			
		this.value=99999999;
		return false;
	}


}
function checkSele(k,k2,k3){

	// var aButton=document.getElementById('day'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	// aButton.style.cursor='hand';
	// aButton.style.width = '40px';
	// aButton.innerHTML='일일';
	// aButton.onclick=ssang_preminum;

	 //var bButton=document.getElementById('but'+k);
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
	 dButton.onclick=DaeriMemberCompany;//대리운전회사찾기
}
function DaeriMemberCompany(){//대리운전회사찾기
	
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==8){
		nn=nn.substr(7,8);
	}else{
		nn=nn.substr(7,9);
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
function serch(val){
	
	for(var k=0;k<20;k++){
		
			//document.getElementById('bunho'+k).innerHTML='';
			document.getElementById('company'+k).innerHTML='';
			document.getElementById('cert'+k).value='';
			document.getElementById('cNum'+k).value='';
			document.getElementById('cname'+k).innerHTML='';
			document.getElementById('si'+k).innerHTML='';
			document.getElementById('A3a'+k).innerHTML='';
			document.getElementById('mem'+k).innerHTML='';
		/*	document.getElementById(user).innerHTML='';*/
		    document.getElementById('insCom'+k).value='';
			document.getElementById('day'+k).innerHTML='';
			document.getElementById('ssang_c'+k).value='';
			document.getElementById('month'+k).innerHTML='';
			document.getElementById('A9a'+k).innerHTML='';
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
	var d_contents=encodeURIComponent(document.getElementById("d_contents").value);
	var toSend = "./ajax/ajax10.php?s_contents="+s_contents
			   +"&end="+end
			   +"&d_contents="+d_contents
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

//addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
//증권번호 입력-->
function changeEtagResponse() {
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

		case 8:
			if(val.length==14){
					
					//alert(val.substring(5,12))
					document.getElementById('cert'+bunho).value=val.substring(4,14);
				}	
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

		case 8:
		//if(document.getElementById('cert'+bunho).value.length==14){
		//	document.getElementById('cert'+bunho).value='114'+document.getElementById('cert'+bunho).value;
				document.getElementById('cert'+bunho).value=document.getElementById('cert'+bunho).value;
			
		//}else{

	//		alert('열네자리 !!')
		//	document.getElementById('A9a'+bunho).focus();
	//			return false;
	//	}
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
function Allchange(){

var DaeriCompanyNewNum=document.getElementById("DaeriCompanyNewNum").value
var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
var s_contents2=encodeURIComponent(document.getElementById("s_contents2").value);

var d_contents=document.getElementById("DaeriCompanyNewNum").value;

if(!s_contents){ //대리운전회사명이 없으면
	alert('대리운전회사명과 증권번호를 동시에 !!')
	document.getElementById("s_contents2").focus();
	return false;

}
if(s_contents2 && s_contents){
			if(confirm(document.getElementById("s_contents").value+'을'+s_contents2+'으로 변경합니다 !!')){
			var toSend = "./ajax/certiDriverChange.php?s_contents="+s_contents
						+"&s_contents2="+s_contents2
						+"&DaeriCompanyNewNum="+DaeriCompanyNewNum;
					  
					  
			
			//document.getElementById("url").innerHTML = toSend;

			//alert(toSend)
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
function ratechange(){

	var s_co=document.getElementById("s_contents").value;

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		
	if(s_co){
//alert(mNab);
		window.open('../pop_up/ExcelRateInput.php?num='+s_co,'rateExcel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
	}else{

		alert('증권번호가 입력 후 !!');
		document.getElementById("s_contents").focus();
		return false;
	}
}
function pClick(){
    var val=document.getElementById("end").value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	//window.open('/newAdmin/dongbu2/pop_up/driverSerch.php?driver_num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
	window.open('/kibs_admin/pdf/fpdf153/Allcerti3.php?num='+val,'print','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
}

function agreehy(){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		
		window.open('../pop_up/agreeInput.php?','agree','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
	
}

function sigi(){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		
		window.open('../pop_up/sigi.php?','agree','sigi='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
	
}