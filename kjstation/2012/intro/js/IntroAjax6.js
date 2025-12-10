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
			document.getElementById('serchTotal').value=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("kCount")[0].text);
		}else{
			pageList();
			document.getElementById('serchTotal').value=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		}
		var postTable=myAjax.responseXML.documentElement.getElementsByTagName("sjNum");//print_name_num
		var postTablekj=myAjax.responseXML.documentElement.getElementsByTagName("kjNum");//print_num

		//alert(postTable);alert(postTablekj);
		var cert=myAjax.responseXML.documentElement.getElementsByTagName("certi");
		var cName=myAjax.responseXML.documentElement.getElementsByTagName("c_name");
		var ssangC=myAjax.responseXML.documentElement.getElementsByTagName("ssang_c_num");//print talbe의 num값에 해당
		var company=myAjax.responseXML.documentElement.getElementsByTagName("company");
		var wdat=myAjax.responseXML.documentElement.getElementsByTagName("wdate");
		var oun_name=myAjax.responseXML.documentElement.getElementsByTagName("oun_name");
		var Conname=myAjax.responseXML.documentElement.getElementsByTagName("com_name");
		//**********************************************************************************************/
		/* 조회결과 개수를 저장하여 나중에 우편물 프린트하는 자바함수에서 사용합니다                   */
		/***********************************************************************************************/
		document.getElementById('currentTotal').value=cName.length;
		//alert(cName.length);
		if(cName.length<16){
			var toLength=cName.length;
		}else{
			var toLength=16;
		}
		for(var k=0;k<toLength;k++){
			
			if(cert[k].text!=undefined){
				document.getElementById('bunho'+k).innerHTML=k+1;
				
				document.getElementById('company'+k).innerHTML=company[k].text;
				document.getElementById('cert'+k).innerHTML=cert[k].text;
				document.getElementById('cname'+k).innerHTML=cName[k].text;
				document.getElementById('wda'+k).innerHTML=wdat[k].text;
				document.getElementById('ssang_c'+k).value=ssangC[k].text;
				document.getElementById('oun'+k).innerHTML=oun_name[k].text;
				//document.getElementById('post_'+k).value=postTable[k].text;
				document.getElementById('post_2_'+k).value=postTablekj[k].text;
				checkSele(k,postTable[k].text);
				document.getElementById('but'+k).innerHTML=Conname[k].text;
				
				//데이타 개수를 체트 하기 위해 
			}else{
				document.getElementById('bunho'+k).innerHTML=k+1;
				document.getElementById('company'+k).innerHTML=company[k].textContent;
				document.getElementById('cert'+k).innerHTML=cert[k].textContent;
				document.getElementById('cname'+k).innerHTML=cName[k].textContent;
				document.getElementById('wda'+k).innerHTML=wdat[k].textContent;
				document.getElementById('ssang_c'+k).value=ssangC[k].textContent;
				document.getElementById('oun'+k).innerHTML=oun_name[k].textContent;
				//document.getElementById('post_'+k).value=postTable[k].textConten;
				document.getElementById('post_2_'+k).value=postTablekj[k].textContent;
				checkSele(k,postTable[k].textContent);
				document.getElementById('but'+k).innerHTML=Conname[k].textContent;
			}
		    
		}

	}else{
		//	
	}
}


function serch(val){
	if(document.getElementById('currentTotal').value<16){
			var toLength=document.getElementById('currentTotal').value;
		}else{
			var toLength=16;
		}
	for(var k=0;k<toLength;k++){
			if(document.getElementById('bunho'+k).innerHTML){
				
				changClear(document.getElementById('naban'+k));
			}
			document.getElementById('bunho'+k).innerHTML='';
			document.getElementById('company'+k).innerHTML='';
			document.getElementById('cert'+k).innerHTML='';
			document.getElementById('cname'+k).innerHTML='';
			document.getElementById('oun'+k).innerHTML='';
			document.getElementById('wda'+k).innerHTML='';
			document.getElementById('ssang_c'+k).value='';
			document.getElementById('post_2_'+k).value='';
			document.getElementById('but'+k).innerHTML='';
			
	}

	myAjax=createAjax();
	var sigi=document.getElementById("sigi").value;
	var end=document.getElementById("end").value;
	var tableNum=document.getElementById("table_name_num").options[document.getElementById("table_name_num").selectedIndex].value;
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

	//var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	var toSend = "./ajax/ajax6Serch.php?sigi="+sigi
			   +"&end="+end
			   +"&page="+page
			   +"&tableNum="+tableNum;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function checkSele(k,k2){
	var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('naban'+k);
	 newInput2.type='checkbox';
	 newInput2.id='post_'+k;
	 newInput2.name='post_'+k;
	 newInput2.value=k2;
	 newInput2.style.width = '50px';
	 aJumin.appendChild(newInput2);
}

function changClear(element){

	while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
}


function allCheck(){
	var sech=document.getElementById('serchTotal').value;
	if(sech>16){
		sech=16;
	}

	if(document.getElementById("aicheck").checked==true){
		for(var i=0;i<document.getElementById('currentTotal').value;i++){
			document.getElementById("post_"+i).checked=true;
		}
	}else{
		for(var i=0;i<document.getElementById('currentTotal').value;i++){
			document.getElementById("post_"+i).checked=false;
		}
	}
}


function admin_certi(val){
	
	for(var i=0;i<16;i++){
		if(document.getElementById("sang"+i).checked==true){
		var endr=1;
		}
	}

    if(endr==1){
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/kibs_admin/post/new_certi.php?val='+val,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes');	
	}else{
		alert('우편물 체크를 하나라도')
		return false;
	}
}
function AdminPost(){
	
	for(var i=0;i<document.getElementById('currentTotal').value;i++){
		
		if(document.getElementById("post_"+i).checked==true){
		
		var endr=1;
		}
	}

    if(endr==1){
	window.open('','post','width=900,height=540,top=150,left=150 resizable=yes,scrollbars=no,status=yes' )
	document.form1.target = 'post'
	document.form1.action = '/kibs_admin/post/new_post.php'
	document.form1.submit();	
	}else{
		alert('우편물 체크를 하나라도')
		return false;
	}
}