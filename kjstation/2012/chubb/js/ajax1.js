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
			var cName=myAjax.responseXML.documentElement.getElementsByTagName("comName");
			var nuValue=myAjax.responseXML.documentElement.getElementsByTagName("num");
			

			//alert(cert.length)
			for(var k=0;k<cName.length;k++){
					if(cName[k].text!=undefined){
						document.getElementById('num'+k).value=nuValue[k].text;
						checkSele(k,cName[k].text);
					}else{
						document.getElementById('num'+k).value=nuValue[k].textContent;
						checkSele(k,cName[k].textContent);
					}	
				}
			}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}
/*상태를 만들어주는 모쥴*/
function checkSele(k,oName){
	
	/*조회 버튼 */
		 var bButton=document.getElementById('comP'+k);
		 var j=k+1;
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		 bButton.style.width = '300px';
		 bButton.innerHTML=oName;
		 bButton.onclick=chkmodify;
}
function serch(){
	/*********************************************/
	/* 조회할 때 먼저 있는 자료 삭제 하기 위해   */
	/*********************************************/
	/*for(var j=0;j<20;j++){
		for(var k=0;k<5;k++_{
		if(document.getElementById('jcom'+j+k).innerHTML){
			changClear(document.getElementById('sele'+j));
			}
		}
		
		
	}*/
	myAjax=createAjax();
	var com_name=encodeURIComponent(document.getElementById("com_name").value);
	if(com_name){
		
		var toSend = "./ajax/comNameSerch.php?com_name="+com_name;
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}else{

		alert('보낼 내용이 없군요!!');
		document.getElementById('driver_name').focus();
		return false;

	}
}


function changClear(element){

	while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
}

function chkmodify(){
	var nn=this.getAttribute("id");

	switch(nn.length){
		case 5:
			val1=nn.substr(4,5);
		break;
		case 6:
			val1=nn.substr(4,6);
		break;
		case 6:
			val1=nn.substr(4,7);
		break;
	}
		var u_id=document.getElementById('num'+val1).value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./pop_up/list_2.php?num='+u_id,'Union','left='+winl+',top='+wint+',resizable=yes,width=900,height=540,scrollbars=no,status=no')
}