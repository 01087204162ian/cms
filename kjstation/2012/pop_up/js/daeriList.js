function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);
	   	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
			//운전자 추가  버튼 만들기
				if(myAjax.responseXML.documentElement.getElementsByTagName("insuranceComNum")[0].text!=99){
					var pUnjeon=document.getElementById("pUnjeon");
					pUnjeon.innerHTML="<input type='button' value='운전자추가' class='input' title='운전자추가' onclick='newUnjeon()' />"
					document.getElementById('uCerti').value=myAjax.responseXML.documentElement.getElementsByTagName("CertiTableNum")[0].text;
					//document.getElementById('uPolicy').value=myAjax.responseXML.documentElement.getElementsByTagName("CertiTableNum")[0].text;

				}
				if(eval(myAjax.responseXML.documentElement.getElementsByTagName("insuranceComNum")[0].text)==1){
					
					var pageD=document.getElementById("pCerti");
					pageD.innerHTML="<input type='button' value='증권발급' class='input' title='증권발급' onclick='pClick()' />"
				}
				//document.getElementById('sql').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sql")[0].text;
				document.getElementById('inwonTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a12")[0].text+sumTotal
		}else{
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
			//운전자 추가  버튼 만들기
				if(myAjax.responseXML.documentElement.getElementsByTagName("insuranceComNum")[0].textContent!=99){
					var pUnjeon=document.getElementById("pUnjeon");
					pUnjeon.innerHTML="<input type='button' value='운전자추가' class='input' title='운전자추가' onclick='newUnjeon()' />"
					document.getElementById('uCerti').value=myAjax.responseXML.documentElement.getElementsByTagName("CertiTableNum")[0].textContent;
					//document.getElementById('uPolicy').value=myAjax.responseXML.documentElement.getElementsByTagName("CertiTableNum")[0].textContent;

				}
				if(eval(myAjax.responseXML.documentElement.getElementsByTagName("insuranceComNum")[0].textContent)==1){
					
					var pageD=document.getElementById("pCerti");
					pageD.innerHTML="<input type='button' value='증권발급' class='input' title='증권발급' onclick='pClick()' />"
				}
				//document.getElementById('sql').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sql")[0].textContent;
				document.getElementById('inwonTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a12")[0].textContent+sumTotal;
		}

		
if(sumTotal>0){
				
				for(var k=0;k<myAjax.responseXML.documentElement.getElementsByTagName("a1").length;k++){

					if(myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text!=undefined){
						//checkSele(k);bunho
						document.getElementById('bunho'+k).innerHTML=k+1;
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text;
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].text;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text)){

							case 1 :	
								document.getElementById('A4a'+k).innerHTML='청약중';
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 3 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지중';
							break;
							default :

								sele(k,myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text);//상태를 만들기 위해
								break;
						}
						//document.getElementById('A5a'+k).innerHTML=
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text)==1){//흥국화재는 탁송이 없다


							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text);
						}
							//checkSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].text);
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						

							
						
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text))
						{
								case 1 :
									document.getElementById('A8a'+k).innerHTML='흥국';
									document.getElementById('A8a'+k).style.color='#F345FC';
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='DB';
									cheriNab(k,myAjax.responseXML.documentElement.getElementsByTagName("a15")[k].text)//회차
									document.getElementById('A8a'+k).style.color='#a4690e';
									//document.getElementById('A14a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a16")[k].text;//납입유에
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
								    document.getElementById('A8a'+k).style.color='#E4690C';
								   // document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='롯데';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A8a'+k).innerHTML='더 케이';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;

						}
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text;
					//	document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text;
					//	document.getElementById('A12a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].text;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].text;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].text;

						var hp=myAjax.responseXML.documentElement.getElementsByTagName("a34")[k].text; //핸드폰
						
						var shp=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].text; //통신사
						var nhp=myAjax.responseXML.documentElement.getElementsByTagName("a31")[k].text; //명의자
						var address=myAjax.responseXML.documentElement.getElementsByTagName("a32")[k].text; //주소
						var injeong=myAjax.responseXML.documentElement.getElementsByTagName("a33")[k].text; //인증
						hpHone(k,hp,shp,nhp,address,injeong);//순서,핸드폰,통신사,명의자,주소
						//만기시에 보험회사를 변경하는 모줄을 만들기 위해 
						///sItage(k,myAjax.responseXML.documentElement.getElementsByTagName("c")[k].text);
					}else{
						//checkSele(k);bunho
						document.getElementById('bunho'+k).innerHTML=k+1;
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;
						
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent)){

							case 1 :	
								document.getElementById('A4a'+k).innerHTML='청약중';
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 3 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지중';
							break;
							default :

								sele(k,myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent);//상태를 만들기 위해
								break;
						}
						//document.getElementById('A5a'+k).innerHTML=
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent)==1){//흥국화재는 탁송이 없다


							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent);
						}
							//checkSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].textContent);
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent))
						{
								case 1 :
									document.getElementById('A8a'+k).innerHTML='흥국';
									document.getElementById('A8a'+k).style.color='#F345FC';
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='DB';
									cheriNab(k,myAjax.responseXML.documentElement.getElementsByTagName("a15")[k].textContent)//회차
									document.getElementById('A8a'+k).style.color='#a4690e';
									//document.getElementById('A14a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a16")[k].textContent;//납입유에
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
								    document.getElementById('A8a'+k).style.color='#E4690C';
								   // document.getElementById('A3a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='롯데';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A8a'+k).innerHTML='더 케이';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;

						}
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].textContent;
						//document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent;
						//document.getElementById('A12a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].textContent;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].textContent;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].textContent;
						var hp=myAjax.responseXML.documentElement.getElementsByTagName("a34")[k].textContent; //핸드폰
						
						var shp=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].textContent; //통신사
						var nhp=myAjax.responseXML.documentElement.getElementsByTagName("a31")[k].textContent; //명의자
						var address=myAjax.responseXML.documentElement.getElementsByTagName("a32")[k].textContent; //주소
						var injeong=myAjax.responseXML.documentElement.getElementsByTagName("a33")[k].textContent; //인증
						hpHone(k,hp,shp,nhp,address,injeong);//순서,핸드폰,통신사,명의자,주소
					}
			
				}
			/*if(totalM[0].text!=undefined){
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
function sItage(k,ck){
	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A14a'+k);
	 newInput2.id='sIs'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	//newInput2.onchange=changeCh;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=eval(ck)+1;
	
	 for(var i=0;i<opts.length;i++){	 
		//opts[i].value=i;
		if(i<opts.length-1){
			if(myAjax.responseXML.documentElement.getElementsByTagName("c"+k+i)[0].text!=undefined){

				var inScom=myAjax.responseXML.documentElement.getElementsByTagName("c"+k+i)[0].text;
				var inC=myAjax.responseXML.documentElement.getElementsByTagName("d"+k+i)[0].text;
			}else{

				var inScom=myAjax.responseXML.documentElement.getElementsByTagName("c"+k+i)[0].textContent;
				var inC=myAjax.responseXML.documentElement.getElementsByTagName("d"+k+i)[0].textContent;
			}
		}else{
			var inScom='';

		}
		switch(eval(inScom)){
					default :
					opts[i].text='선택';
					  //  newInput2.selectedIndex='99999999';
						break;
					case 1 :
						opts[i].text='흥국'+inC;
					break;
					case 2 :
						opts[i].text='DB'+inC;
					break;
					case 3 :
						opts[i].text='KB'+inC;
					break;
					case 4 :
						opts[i].text='현대'+inC;
					break;
					case 5 :
						opts[i].text='한화'+inC;
					break;
					case 6 :
						opts[i].text='더케이'+inC;
					break;
					case 7 :
						opts[i].text='MG'+inC;
					break;
					case 7 :
						opts[i].text='삼성'+inC;
					break;
				}
			
				
			
	
	 }

	 aJumin.appendChild(newInput2);


}
function pClick(){
    var val=document.getElementById("pCerti2").value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	//window.open('/newAdmin/dongbu2/pop_up/driverSerch.php?driver_num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
	window.open('/kibs_admin/pdf/fpdf153/Allcerti2.php?num='+val,'print','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
}
function Etage(k,etag){
	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A5a'+k);
	 newInput2.id='etag'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeCh;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=6;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(etag)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 1 :
					  opts[i].text='일반';
						break;
					case 2 :
						opts[i].text='탁송';
					break;
					case 3 :
						opts[i].text='일반/렌트';
					break;
						case 4 :
						opts[i].text='탁송/렌트';
					break;
						case 5 :
						opts[i].text='전차량';
					break;
					
				}
			
	
	 }

	 aJumin.appendChild(newInput2);


}
function serch(val){
	
	
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
			document.getElementById("pUnjeon").innerHTML='';
			document.getElementById("pCerti").innerHTML='';
			for(var i=0;i<20;i++){
				document.getElementById("bunho"+i).innerHTML='';
				document.getElementById("A2a"+i).innerHTML='';
				document.getElementById("A3a"+i).innerHTML='';
				document.getElementById("A4a"+i).innerHTML='';
				document.getElementById("A5a"+i).innerHTML='';
				//document.getElementById("A6a"+i).innerHTML='';
				//document.getElementById("A7a"+i).innerHTML='';
				document.getElementById("A8a"+i).innerHTML='';
				document.getElementById("A9a"+i).innerHTML='';
				document.getElementById("A10a"+i).innerHTML='';
				//document.getElementById("A11a"+i).innerHTML='';
				//document.getElementById("A12a"+i).innerHTML='';
				//document.getElementById("A13a"+i).innerHTML='';
				//document.getElementById("A14a"+i).innerHTML='';


				document.getElementById("A29a"+i).innerHTML='';
				document.getElementById("A30a"+i).innerHTML='';
				document.getElementById("A31a"+i).innerHTML='';
				document.getElementById("A32a"+i).innerHTML='';

				document.getElementById("num"+i).value='';
				document.getElementById("cNum"+i).value='';
				document.getElementById("iNum"+i).value='';
				document.getElementById("pNum"+i).value='';
			}
	var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
	var CertiTableNum=document.getElementById('CertiTableNum').value;
	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	var DaeriCompanyNum=document.getElementById('DaeriCompanyNum').value;
	var iNum=document.getElementById('iNum').value;
	if(insuranceComNum!=iNum){
		CertiTableNum='';
	}
	
	if(s_contents){
			insuranceComNum=99;
		var toSend = "../intro/ajax/DaeriCompanySerch.php?s_contents="+s_contents
					   +"&insuranceComNum="+insuranceComNum
					   +"&page="+page;


	}else{
		
		var toSend = "../intro/ajax/DaeriCompanySerch.php?CertiTableNum="+CertiTableNum
					   +"&DaeriCompanyNum="+DaeriCompanyNum
					   +"&insuranceComNum="+insuranceComNum
					   +"&page="+page;

	}
			
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해


function checkSele(k,k2){

	//document.getElementById('A6a'+k).innerHTML
	 var aButton=document.getElementById('A6a'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 aButton.style.cursor='hand';
	 aButton.style.width = '40px';
	 aButton.innerHTML=k2;
	 aButton.onclick=DaeriMemberCompany;

	/* var bButton=document.getElementById('but'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 bButton.style.cursor='hand';
	 bButton.style.width = '40px';
	 bButton.innerHTML='현황';
	 bButton.onclick=comDetail10;*/
}
/*상태를 만들어주는 모쥴*/
function sele(k,k2){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A4a'+k);
	 newInput2.id='sang'+k;
	 newInput2.style.width = '65px';
	 newInput2.className='selectbox';
	 newInput2.onchange=changeSangtae;
	 var opts=newInput2.options;
	//alert(opts);
	var si;
/*	if(nab==4){///실효중일 때는 pus_h를 7실효로 표시
		pus_h=7;
	}*/

	switch(eval(k2)){
		case 4 ://정상
			opts.length=5;
			si=2;
		break;
		
		case 2 ://해지
			opts.length=3;
			si=2
		break;
		case 1 ://청약
			opts.length=2;
			si=1;
		break;
		case 5 ://거절
			opts.length=6;
			si=5;
		break;
		case 6 ://취소
			opts.length=7;
			si=6;
		break;
		case 7 ://실효
			opts.length=8;
			si=7;
		break;

	}
	
	 for(var i=si;i<opts.length;i++){
		opts[i].value=i;
		if(opts[i].value==eval(k2)){
			newInput2.selectedIndex=i;
		}
	switch(eval(i)){
			case 4 :
			opts[i].text='정상';
			break;
			/*case 3 :
			opts[i].text='교체';
			break;*/

			case 2 :
			opts[i].text='해지';
			break;
			case 1 :
			opts[i].text='청약중';
			break;
			case 5 :
			opts[i].text='거절';
			break;
			case 6 :
			opts[i].text='취소';
			break;
			case 7 :
			opts[i].text='실효';
			break;
		}	
	 }
	if(document.getElementById('sang'+k)){
		aJumin.removeChild(aJumin.lastChild);
	}
	 aJumin.appendChild(newInput2);
}

function changeSangtae()
{

	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}

	//var val=this.getAttribute("value");
	var val=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;
	//alert(val)
	if(eval(val)==2){

		if(confirm("해지신청 !!")){
		var DariMemberNum='num'+nn;
		var DariMemberNum=document.getElementById(DariMemberNum).value;

		
		var userId=document.getElementById('userId').value;
		//alert(userId);
		var endorseDay=document.getElementById('endorseDay').value;
		var DaeriCompanyNum=document.getElementById('cNum'+nn).value; //			
		var CertiTableNum=document.getElementById('pNum'+nn).value;//certiTableNum
		var policyNum=document.getElementById('A9a'+nn).innerHTML;//
		
		var insuranceNum=document.getElementById('iNum'+nn).value;//보험회사번호
		
		var toSend = "../pop_up/ajax/Endorse.php?DariMemberNum="+DariMemberNum
						+"&DaeriCompanyNum="+DaeriCompanyNum
					   +"&CertiTableNum="+CertiTableNum
						+"&endorseDay="+endorseDay
						+"&policyNum="+policyNum
						+"&userId="+userId
					   +"&insuranceNum="+insuranceNum;

		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=daeriMemberReceive;
		myAjax.send('');
		}else{

			document.getElementById("sang"+nn).value=4;
			return false;

		}
	}else{
		alert('이미 해지 신청 하였습니다');
		document.getElementById("sang"+nn).value=2;

	}
}
function etagMemberReceive()
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
function changeCh(){//탁송을 일반으로 일반을 탁송으로 

	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}

	//var val=this.getAttribute("value");
	var val=document.getElementById('etag'+nn).options[document.getElementById('etag'+nn).selectedIndex].value;
	var DariMemberNum='num'+nn;
	var DariMemberNum=document.getElementById(DariMemberNum).value;

	if(confirm('변경합니다 ??')){
		var toSend = "../pop_up/ajax/etageEndorse.php?DariMemberNum="+DariMemberNum
						+"&etag="+val;
					  

		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=etagMemberReceive;
		myAjax.send('');

	}else{

		if(val==1){
			document.getElementById('etag'+nn).value=2;
		}else{

			document.getElementById('etag'+nn).value=1;
		}
			return false;
	}
}
function DaeriMemberCompany(){//보험회사별 인원 전체 찾기
	var nn=this.getAttribute("id");
	alert(nn);
	if(nn.length==4){
		nn=nn.substr(3,4);
	}else{
		nn=nn.substr(3,5);
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
function newUnjeon(){
	
	var num=document.getElementById("DaeriCompanyNum").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('uCerti').value;
	
	var a2=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
	
	//var a4=document.getElementById('A9a0').innerHTML;
	if(certiNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./MemberEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
		//window.open('./MemberEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}
	   
}


function cheriNab(k,nabang_1){//납입회차 
	
var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A13a'+k);
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
		if(opts[i].value==eval(nabang_1)){	
			newInput2.selectedIndex=i;
		}
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
	// aJumin.appendChild(newInput2);
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

function inWonList(){

	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./MemberList.php?DaeriCompanyNum='+DaeriCompanyNum+'&CertiTableNum='+certiNum+'&InsuraneCompany='+insuranceComNum,'plist','left='+winl+',top='+wint+',resizable=yes,width=400,height=600,scrollbars=no,status=yes')
	
}
/*$(document).ready(function(){

	$(".kj").blur(function(){

		alert(this.id);

	});

});*/
//certinum 변경을 위하여2016-11-19
function changeCerti(i){
	var val=$("#A33a"+i).val();

	
	if(val.length>0){

		   var send_url = "/_db/_sago.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"certiChange",
						     pNum:$("#num"+i).val(),
						     cNum: val
						}
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						$(xml).find('item').each(function() {
							
							alert($(this).find('message').text());
						});	 
		            });
						
						
				});

	}
}