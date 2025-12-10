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
		}else{
			pageList();	
		}
		
		var e_name=myAjax.responseXML.documentElement.getElementsByTagName("e_name");
		var company=myAjax.responseXML.documentElement.getElementsByTagName("company");
		//var member=myAjax.responseXML.documentElement.getElementsByTagName("member");
		var com_phone=myAjax.responseXML.documentElement.getElementsByTagName("com_phone");
		var sangdamday=myAjax.responseXML.documentElement.getElementsByTagName("sangdamday");
		var sangdamtime=myAjax.responseXML.documentElement.getElementsByTagName("sangdamtime");
		var wdat=myAjax.responseXML.documentElement.getElementsByTagName("wdate");
		var email=myAjax.responseXML.documentElement.getElementsByTagName("email");
		var user_id=myAjax.responseXML.documentElement.getElementsByTagName("user_id");
		var num=myAjax.responseXML.documentElement.getElementsByTagName("num");
		for(var k=0;k<wdat.length;k++){
			if(e_name[k].text!=undefined){
				document.getElementById('e_name'+k).innerHTML=e_name[k].text;
				document.getElementById('company'+k).innerHTML=company[k].text;
				document.getElementById('com_phone'+k).innerHTML=com_phone[k].text;
				document.getElementById('sangdamday'+k).innerHTML=sangdamday[k].text;
				document.getElementById('sangTime'+k).innerHTML=sangdamtime[k].text;
				document.getElementById('email'+k).innerHTML=email[k].text;
				document.getElementById('user_id'+k).innerHTML=user_id[k].text;
				document.getElementById('wdate'+k).innerHTML=wdat[k].text;
				document.getElementById('num'+k).value=num[k].text;
				checkSele(k);
			}else{
				document.getElementById('e_name'+k).innerHTML=e_name[k].textContent;
				document.getElementById('company'+k).innerHTML=company[k].textContent;
				document.getElementById('com_phone'+k).innerHTML=com_phone[k].textContent;
				document.getElementById('sangdamday'+k).innerHTML=sangdamday[k].textContent;
				document.getElementById('sangTime'+k).innerHTML=sangdamtime[k].textContent;
				document.getElementById('email'+k).innerHTML=email[k].textContent;
				document.getElementById('user_id'+k).innerHTML=user_id[k].textContent;
				document.getElementById('wdate'+k).innerHTML=wdat[k].textContent;
				document.getElementById('num'+k).value=num[k].textContent;
				checkSele(k);
			}
						
		}

	}else{
		//	
	}
}


function serch(val){
	
	for(var k=0;k<20;k++){
			if(document.getElementById('com_phone'+k).innerHTML){
				
				changClear(document.getElementById('pun'+k));
			}
			document.getElementById('e_name'+k).innerHTML='';
			document.getElementById('company'+k).innerHTML='';
			//document.getElementById(mem).innerHTML='';
			document.getElementById('com_phone'+k).innerHTML='';
			document.getElementById('sangdamday'+k).innerHTML='';
			document.getElementById('sangTime'+k).innerHTML='';
			document.getElementById('email'+k).innerHTML='';
			document.getElementById('user_id'+k).innerHTML='';
			document.getElementById('wdate'+k).innerHTML='';
	}
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

	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	var toSend = "./ajax/aj3.php?sigi="+sigi
			   +"&end="+end
			   +"&s_contents="+s_contents
			   +"&page="+page;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
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

function chkmodify_dong_bu(j){

	
	var u_id=document.getElementById("num"+j).value;
	var u_id_2=4;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./pop_up/chkmodify_dong_bu.php?num='+u_id+'&num_2='+u_id_2,'u','left='+winl+',top='+wint+',resizable=yes,width=900,height=550,scrollbars=no,status=yes')
}

function chkmodify_dong_bu2(j){

	
	var u_id=document.getElementById("num"+j).value;
	var u_id_2=4;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/dongbudrive/dong_cal3.php?num='+u_id+'&num_2='+u_id_2,'Union_bu','left='+winl+',top='+wint+',resizable=yes,width=900,height=550,scrollbars=no,status=yes')
}