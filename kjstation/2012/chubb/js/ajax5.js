
function receiveResponse() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
			//document.getElementById("kor_str").value = myAjax.responseText;

		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		
			}
	
		if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!=undefined){
			document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}
		
	}else{
		//	
	}
}
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
function searchReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		//document.getElementById('dd').innerHTML=myAjax.responseText;
		if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		}else{
			pageList();	
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		}
		if(sumTotal>0){	
			var com=myAjax.responseXML.documentElement.getElementsByTagName("com_name");
			var cCerti=myAjax.responseXML.documentElement.getElementsByTagName("cancel_certi");
			var vdat=myAjax.responseXML.documentElement.getElementsByTagName("vessel");
			var wdat=myAjax.responseXML.documentElement.getElementsByTagName("wdate");
			var userid=myAjax.responseXML.documentElement.getElementsByTagName("user_id");
			var numC=myAjax.responseXML.documentElement.getElementsByTagName("num");
			var sEle=myAjax.responseXML.documentElement.getElementsByTagName("sele");
			//alert(pnum.length)
			for(var k=0;k<wdat.length;k++){
				if(com[k].text!=undefined){
					//document.getElementById('company'+k).innerHTML=com[k].text;
					document.getElementById('oldCert'+k).innerHTML=cCerti[k].text;
					
					document.getElementById('ved'+k).innerHTML=vdat[k].text;
					document.getElementById('wed'+k).innerHTML=wdat[k].text;
					
					document.getElementById("num"+k).value=numC[k].text;
					checkSele(k,com[k].text,sEle[k].text);
				}else{
					//document.getElementById('company'+k).innerHTML=com[k].textContent;
					document.getElementById('oldCert'+k).innerHTML=cCerti[k].textContent;
					document.getElementById('ved'+k).innerHTML=vdat[k].textContent;
					document.getElementById('wed'+k).innerHTML=wdat[k].textContent;
					
					document.getElementById("num"+k).value=numC[k].textContent;
					checkSele(k,com[k].textContent,sEle[k].textContent);
				}
				
				
			}

		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}
function Receive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
			
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			
				}
		

		}
	}
function checkSele(k,company,get){
	 /*삭제 버튼 */
		 var aButton=document.getElementById('up'+k);
		 //aButton.className='input5';
		 //aButton.value=val1;
		 aButton.style.cursor='hand';
		 //aButton.style.width = '40px';
		 aButton.innerHTML='상품설명서';
		 aButton.onclick=explainSujeong;


		 var bButton=document.getElementById('company'+k);
		  bButton.style.cursor='hand';
		 //aButton.style.width = '40px';
		 bButton.innerHTML=company;
		 bButton.onclick=explainCompany;
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('sele'+k);
	 newInput2.id='sang'+k;
	 newInput2.style.width = '100px';
	 newInput2.className='selectbox';
	
	 var opts=newInput2.options;
	// alert(push);
	var si;
	//alert();
	opts.length=5;
	
	if(!get){ get=1;}
	
	 for(var i=1;i<opts.length;i++){
		opts[i].value=i;
		
		//alert(i); 
		
		
		//alert(opts[i].value);
		
		switch(i){
			case 1 : 
				opts[i].text='==선택==';
			break;
			case 2 : 
				opts[i].text='==업체팩스==';
			break;
			case 3 : 
				opts[i].text='==업체메일==';
			break;
			case 4 : 
				opts[i].text='==처브==';
			break;
			case 5 : 
				opts[i].text='====';
			break;
		}
		if(opts[i].value==get){
			
			newInput2.selectedIndex=i;
	
		}
	
	 }

	if(document.getElementById('sang'+k)){
		aJumin.removeChild(aJumin.lastChild);
	}
	newInput2.onchange=changeSangtae;
	 aJumin.appendChild(newInput2);
}
function serch(val){
		for(var k=0;k<20;k++){
			document.getElementById('company'+k).innerHTML='';
			document.getElementById('oldCert'+k).innerHTML='';
			document.getElementById('wed'+k).innerHTML='';
			document.getElementById('ved'+k).innerHTML='';
			document.getElementById('up'+k).innerHTML='';
	}
	myAjax=createAjax();
	var sigi=document.getElementById("sigi").value;
	var end=document.getElementById("end").value;
	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	var gi=document.getElementById('gi').options[document.getElementById('gi').options.selectedIndex].value;
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
	var toSend = "./ajax/ajax5Search.php?sigi="+sigi
			   +"&end="+end
		       +"&page="+page
			   +"&gi="+gi
			   +"&s_contents="+s_contents;

	
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=searchReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해

function explainSujeong(){
	var nn=this.getAttribute("id");
	if(nn.length==3){
		nn=nn.substr(2,3);
	}else{
		nn=nn.substr(2,4);
	}
	var val=document.getElementById('num'+nn).value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./pop_up/print/explain.php?num='+val,'explain','left='+winl+',top='+wint+',resizable=yes,width=700,height=300,scrollbars=yes,status=yes')
}

function explainCompany(){
	var nn=this.getAttribute("id");
	if(nn.length==8){
		nn=nn.substr(7,8);
	}else{
		nn=nn.substr(7,9);
	}
	var val=document.getElementById('num'+nn).value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./pop_up/list_2.php?num='+val,'company','left='+winl+',top='+wint+',resizable=yes,width=900,height=600,scrollbars=yes,status=yes')
}
function noclaim_sujeong(){
	var nn=this.getAttribute("id");
	if(nn.length==3){
		nn=nn.substr(2,3);
	}else{
		nn=nn.substr(2,4);
	}
	var val=document.getElementById('num'+nn).value;
	window.open('./pop_up/print/noclaim.php?cal_num='+val,'ssang','left='+winl+',top='+wint+',resizable=yes,width=700,height=300,scrollbars=yes,status=yes')
}
function change_search(){
	document.getElementById('s_contents').value='';
}


/*받음,미수로 정하기 위해 함수 */
function changeSangtae(){
	myAjax=createAjax();
	var nn=this.getAttribute("id");
	if(nn.length==4){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}
	var val=document.getElementById('num'+nn).value;
	//alert(nn);

	var mm=this.getAttribute("id");
	val2=document.getElementById(mm).options[document.getElementById(mm).options.selectedIndex].value;
		
		
		//displayLoading(self.document.getElementById("imgkor"));

		var toSend = "./ajax/explainUpdate.php?val="+val
				   +"&val2="+val2;
		//alert(toSend)
		//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=Receive;
		myAjax.send('');

}