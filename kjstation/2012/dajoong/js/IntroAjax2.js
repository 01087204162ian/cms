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
				
				for(var k=0;k<myAjax.responseXML.documentElement.getElementsByTagName("a1").length;k++){

					if(myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text!=undefined){
						koimaSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text;
	
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].text;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text;
						document.getElementById('A6a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text;
					/*
						*document.getElementById('A7a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						document.getElementById('A8a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text;
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].text;
						jinhang(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].text);//진행상황*/
					}else{
						koimaSele(k);
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
				
					document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
						document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;
						document.getElementById('A5a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent;
						document.getElementById('A6a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent;
						document.getElementById('A7a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						document.getElementById('A8a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].textContent;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent;
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].textContent;
						jinhang(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].textContent);//진행상황
						
					}
					
				}
		/*	if(totalM[0].text!=undefined){
				document.getElementById("tot").innerHTML=totalM[0].text;
			}else{
				document.getElementById("tot").innerHTML=totalM[0].textContent;
			}*/
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}

function jinhang(k,k2){//진행상황
	
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A12a'+k);
	 newInput2.id='diffC'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=call;//보험회사 변경
	 var opts=newInput2.options;
	opts.length=8;
	
	
	if(eval(k2)==1){
			newInput2.style.color='#E4690C';	
		}else{
			newInput2.style.color='black';		
		}
	 for(var i=0;i<opts.length;i++){	
		 
	
		opts[i].value=i;
		
		 if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;

		}
		
				switch(i){
					
					case 1 :
						opts[i].text='신청';
						
					break;
					case 2 :
						opts[i].text='팩스받음';	

					break;
					case 3 :
						opts[i].text='입금대기';
					break;
					case 4 :
						opts[i].text='입금학인함';
					break;
					case 5 :
						opts[i].text='처리완료';
					break;
					case 6 :
						opts[i].text='팩스 재요청';
					break;
					case 7 :
						opts[i].text='등록대기';
					break;
				}
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);
}
function Receive(){

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

function call(){ //진행상황에 
	//alert(nn);
	myAjax=createAjax();
	if(this.id.length==6){
		nn=this.id.substr(5,6);
	}else{
		nn=this.id.substr(5,7);
	}

	var num=document.getElementById('num'+nn).value;

	var toSend = "./ajax/call.php?num="+num
				   +"&val="+this.value
				  // +"&end="+end
				  // +"&page="+page;
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
    //alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=Receive;
	myAjax.send('');
}
function koimaSele(k){
	
	/*일일 버튼 */
		 var bButton=document.getElementById('bunho'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=k+1;
		 bButton.onclick=dajoong;
	
}

function dajoong(){//대리운전회사 기본정보
	//alert('1')
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==6){
		nn=nn.substr(5,6);
	}else{
		nn=nn.substr(5,7);
	}

	var ssC='num'+nn;
	var ssang_c_num=document.getElementById(ssC).value;
	
	if(ssang_c_num){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/dajoong.php?num='+ssang_c_num+'&Pcompany='+Pcompany,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}

function kindChange(){

	
	var cokind=document.getElementById('cokind').options[document.getElementById('cokind').selectedIndex].value;//업종조회


	
    switch(eval(cokind)){
			
	/*	case 1 :

			    var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co1');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p2eck';
				 newInput2.style.width = '25px';
				 newInput2.onkeyup=chang1;

				break;*/

		case 2 :
				document.getElementById('co1').innerHTML='';
				document.getElementById('co2').innerHTML='';
				document.getElementById('co3').innerHTML='';
				document.getElementById('co4').innerHTML='';
				document.getElementById('co1').innerHTML='MU';
			 var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co2');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p2eck';
				 newInput2.style.width = '25px';
				  newInput2.onkeyup=chang1;
				  aJumin.appendChild(newInput2);


				 var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co3');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p3eck';
				 newInput2.style.width = '30px'; 
				 newInput2.onkeyup=chang2;
				 aJumin.appendChild(newInput2);

			
				 aJumin.appendChild(newInput2);
				 var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co4');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p4eck';
				 newInput2.style.width = '40px'; 
				 aJumin.appendChild(newInput2);
			break;
		case 3 :
				document.getElementById('co1').innerHTML='';
				document.getElementById('co2').innerHTML='';
				document.getElementById('co3').innerHTML='';
				document.getElementById('co4').innerHTML='';


				 var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co1');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p2eck';
				 newInput2.style.width = '90px';
				  newInput2.onkeyup=chang1;
				  aJumin.appendChild(newInput2);
		break;
	}

}

function chang1(){

	if(this.value.length==2){

		document.getElementById('p3eck').focus()
	}
}

function chang2(){

	if(this.value.length==4){

		document.getElementById('p4eck').focus()
	}
}
function serch(val){
	//alert('1')
	for(var k=0;k<20;k++){
		document.getElementById('bunho'+k).innerHTML='';
		document.getElementById('num'+k).value='';
		document.getElementById('A2a'+k).innerHTML='';
		document.getElementById('A3a'+k).innerHTML='';
		document.getElementById('A4a'+k).innerHTML='';
		document.getElementById('A5a'+k).innerHTML='';
		document.getElementById('A6a'+k).innerHTML='';
		document.getElementById('A7a'+k).innerHTML='';
		document.getElementById('A8a'+k).innerHTML='';
		document.getElementById('A9a'+k).innerHTML='';
		document.getElementById('A9a'+k).innerHTML='';
		document.getElementById('A10a'+k).innerHTML='';
		document.getElementById('A11a'+k).innerHTML='';
		document.getElementById('A12a'+k).innerHTML='';
	//	document.getElementById('pBankNum'+k).value='';


	}
	
		myAjax=createAjax();
		//var sigi=document.getElementById("sigi").value;
		//var end=document.getElementById("end").value;
		
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
		//var sigi=document.getElementById("sigi").value;
		//var end=document.getElementById("end").value;
		var jin=document.getElementById('jin').options[document.getElementById('jin').selectedIndex].value;
		var kind=document.getElementById('kind').options[document.getElementById('kind').selectedIndex].value;
		var cokind=document.getElementById('cokind').options[document.getElementById('cokind').selectedIndex].value;//업종조회할대

		switch(eval(cokind)){
				

				case 2 :

					var s_contents="MU/"+document.getElementById('p2eck').value+"/"+document.getElementById('p3eck').value+"/"+document.getElementById('p4eck').value;

					//alert(s_contents)
					break;


				case 3 :

					var s_contents=encodeURIComponent(document.getElementById('p2eck').value);

					break;

				
		}
		
		var sido=encodeURIComponent(document.getElementById('sido').options[document.getElementById('sido').selectedIndex].value);
		//var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	//	if(s_contents){

			//document.getElementById('getDay').options[document.getElementById('getDay').selectedIndex].value='9999';
			//document.getElementById('damdanja').options[document.getElementById('damdanja').selectedIndex].value='9999';

	//	}
	//alert(contents);
		var toSend = "./ajax/ajax2.php?cokind="+cokind
				//   +"&sigi="+sigi
				   +"&kind="+kind
				   +"&sido="+sido
				   +"&s_contents="+s_contents 
				   +"&page="+page;
//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
//addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해


function kindC(){ //로딩후 바로 

	document.getElementById('co1').innerHTML='MU';
			 var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co2');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p2eck';
				 newInput2.style.width = '25px';
				  newInput2.onkeyup=chang1;
				 
			 aJumin.appendChild(newInput2);
				 var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co3');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p3eck';
				 newInput2.style.width = '30px'; 
				 newInput2.onkeyup=chang2;
				 aJumin.appendChild(newInput2);

			
				 aJumin.appendChild(newInput2);
				 var newInput2=document.createElement("input");
				 var aJumin=document.getElementById('co4');
				 newInput2.type='text';
				 newInput2.className='input2';
				 newInput2.id='p4eck';
				 newInput2.style.width = '40px'; 
				 aJumin.appendChild(newInput2);

}

addLoadEvent(kindC)


function Bank(k,BankName){

	//은행명

	var bButton=document.getElementById('A9a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=BankName;
		 bButton.onclick=BankCheck;


}

function BankReceive(){

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
function BankCheck()
{
	myAjax=createAjax();
	if(this.id.length==4){
		var nn=this.id.substr(3,4);
	}else{
		var nn=this.id.substr(3,5);
	}

	var pBankNum=document.getElementById('pBankNum'+nn).value;

	
		var toSend = "./ajax/BankSerch.php?pBankNum="+pBankNum;
				//   +"&end="+end
				//   +"&s_contents="+s_contents
				//   +"&page="+page;
	alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=BankReceive;
	myAjax.send('');
}

function koima(){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		
		
		window.open('../pop_up/koimaList.php','idmake','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')


}