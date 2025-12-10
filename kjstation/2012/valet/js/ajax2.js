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
			var dNumber=myAjax.responseXML.documentElement.getElementsByTagName("num");
			var oName=myAjax.responseXML.documentElement.getElementsByTagName("images");
			//var oJumin=myAjax.responseXML.documentElement.getElementsByTagName("ounjuimin");
			var cName=myAjax.responseXML.documentElement.getElementsByTagName("PhoneNumber");
			var com_phone=myAjax.responseXML.documentElement.getElementsByTagName("wdate");
			//var cJumin=myAjax.responseXML.documentElement.getElementsByTagName("conjuimin");
			var company=myAjax.responseXML.documentElement.getElementsByTagName("wdate2");
		/*	var cert=myAjax.responseXML.documentElement.getElementsByTagName("certi");
			var user=myAjax.responseXML.documentElement.getElementsByTagName("user_id");
			var sangtae=myAjax.responseXML.documentElement.getElementsByTagName("sangtae");
			var wdat=myAjax.responseXML.documentElement.getElementsByTagName("wdate");
			var prem=myAjax.responseXML.documentElement.getElementsByTagName("preminum");
			var sta=myAjax.responseXML.documentElement.getElementsByTagName("start");*/

			//alert(cert.length)
			for(var k=0;k<dNumber.length;k++){
				
		
				//document.getElementById(bun).innerHTML=eval(k)+1;
				if(dNumber[k].text!=undefined){
					
					document.getElementById('driver_num'+k).value=dNumber[k].text;
					document.getElementById('ounname'+k).innerHTML=oName[k].text;
					document.getElementById('conname'+k).innerHTML=cName[k].text;
					document.getElementById('com_phone'+k).value=com_phone[k].text;
					document.getElementById('company'+k).innerHTML=company[k].text;
				
					checkSele(k);
				}else{
					document.getElementById('driver_num'+k).value=dNumber[k].textContent;
					document.getElementById('ounname'+k).innerHTML=oName[k].textContent;
					document.getElementById('conname'+k).innerHTML=cName[k].textContent;
					document.getElementById('com_phone'+k).value=com_phone[k].textContent;
					document.getElementById('company'+k).innerHTML=company[k].textContent;
					
					//checkSele(k,cName[k].textContent,oName[k].textContent,dNumber[k].textContent);
					checkSele(k);
				}	
			}
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}

function checkSele(k){
	
	/*일일 버튼 */
		 var bButton=document.getElementById('bunho'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=k+1;
		 bButton.onclick=imageSerch;
	
}

function imageSerch(){
	//alert(this.id);

	if(this.id.length==6){
		nn=this.id.substr(5,6);
	}else{
		nn=this.id.substr(5,7);
	}

	var PhoneNumber=document.getElementById('conname'+nn).innerHTML;


	if(PhoneNumber){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		//window.open('../pop_up/imageView.php?PhoneNumber='+PhoneNumber,'imgae','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
			window.open('../pop_up/imageView3.php?PhoneNumber='+PhoneNumber,'imgae','left='+winl+',top='+wint+',resizable=yes,width=1000,height=740,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}

	//alert(pImageNum);
}
function serch(val){
	/*********************************************/
	/* 조회할 때 먼저 있는 자료 삭제 하기 위해   */
	/*********************************************/
	for(var j=0;j<20;j++){
		if(document.getElementById('ounname'+j).innerHTML){
			changClear(document.getElementById('but'+j));
			changClear(document.getElementById('pun'+j));
		}
		document.getElementById('ounname'+j).innerHTML='';
		//document.getElementById('ounjumin'+j).innerHTML='';
		document.getElementById('certi'+j).innerHTML='';
		document.getElementById('preminum'+j).innerHTML='';
		document.getElementById('sangtae'+j).innerHTML='';
		document.getElementById('gai'+j).innerHTML='';
		document.getElementById('conname'+j).innerHTML='';
		//document.getElementById('conjumin'+j).innerHTML='';
		document.getElementById('company'+j).innerHTML='';
		document.getElementById('but'+j).innerHTML='';
		document.getElementById('driver_num'+j).value='';
		document.getElementById('wdate'+j).innerHTML='';
		document.getElementById('user'+j).innerHTML='';
		
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
		var sigi=document.getElementById('sigi').value;
		var end=document.getElementById('end').value;
		var p_ush=document.getElementById("gi").options[document.getElementById("gi").selectedIndex].value;
		var toSend = "./ajax/valetClaimSerch.php?sigi="+sigi
				   +"&p_ush="+p_ush
				   +"&page="+page
				   +"&end="+end;
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	
}

addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
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