
function order(){
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../db/db.php','new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
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
						var sago=myAjax.responseXML.documentElement.getElementsByTagName("a20")[k].text;
						//document.getElementById('A5a'+k).innerHTML=
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text)==1){//흥국화재는 탁송이 없다
							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text);
						}
							checkSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].text,sago);
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;

							insComSelect(k,myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text);
						
						certi(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text);
						//document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text;
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].text;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].text;
						
					}else{
							document.getElementById('bunho'+k).innerHTML=k+1;
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;
						var sago=myAjax.responseXML.documentElement.getElementsByTagName("a20")[k].textContent;
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
							checkSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].textContent,sago);
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						insComSelect(k,myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent);
						
						certi(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent);
						//document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].textContent;
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].textContent;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].textContent;

						
					}
				
				}
		
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
}
function insComSelect(k,k2){
	 var aButton=document.getElementById('A8a'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 aButton.style.cursor='hand';
	 aButton.style.width = '40px';
	 switch(eval(k2))
		{
				case 1 :
					aButton.innerHTML='흥국';
					aButton.style.color='#F345FC';
					break;
				case 2 :
					aButton.innerHTML='DB';
					break;
				case 3 :
					aButton.innerHTML='KB';
					break;
				case 4 :
					aButton.innerHTML='현대';
					 aButton.style.color='#E4690C';
					break;
				case 5 :
					aButton.innerHTML='한화';
					aButton.style.color='#E4690C';
					break;
				case 6 :
					aButton.innerHTML='더 케이';
					aButton.style.color='#E4690C';
					break;
				case 7 :
					aButton.innerHTML='MG';
					aButton.style.color='#E4690C';
					break;
				case 8 :
					aButton.innerHTML='삼성';
					aButton.style.color='#E4690C';
					break;


		}
	 
	aButton.onclick=newUnjeon;
}

function newUnjeon(){
	if(this.id.length==4){
		var val=this.id.substr(3,4);
	}else{
		var val=this.id.substr(3,5);
	}
	var num=document.getElementById("cNum"+val).value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('pNum'+val).value;
	var iNum=document.getElementById('iNum'+val).value;//보험회사 1흥국,2DB 3,KB 4 현대	
	var a9=document.getElementById('A9a'+val).innerHTML;
	if(certiNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/MemberEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+iNum+'&policyNum='+a9,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}
	   
}
function certi(k,inwo){
	

	var bButton=document.getElementById('A9a'+k);
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
	//alert('1')
	if(this.id.length==4){
		var val=this.id.substr(3,4);
	}else{
		var val=this.id.substr(3,5);
	}
	var num=document.getElementById("cNum"+val).value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('pNum'+val).value;
	var iNum=document.getElementById('iNum'+val).value;
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/daeriList.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&iNum='+iNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=1000,height=800,scrollbars=yes,status=yes')
}
function Etage(k,etag){
	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A5a'+k);
	 newInput2.id='etag'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	//newInput2.onchange=changeCh;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=4;
	
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
						opts[i].text='전차량';
					break;
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);


}
function serch(val){
	//alert(document.getElementById('bunho'+k).innerHTML)
	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
	//if(s_contents){
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
			var push=document.getElementById('push').options[document.getElementById('push').selectedIndex].value;
			for(var i=0;i<20;i++){
				document.getElementById("bunho"+i).innerHTML='';
				document.getElementById("A2a"+i).innerHTML='';
				document.getElementById("A3a"+i).innerHTML='';
				document.getElementById("A4a"+i).innerHTML='';
				document.getElementById("A5a"+i).innerHTML='';
				document.getElementById("A6a"+i).innerHTML='';
				//document.getElementById("A7a"+i).innerHTML='';
				document.getElementById("A8a"+i).innerHTML='';
				document.getElementById("A9a"+i).innerHTML='';
				document.getElementById("A10a"+i).innerHTML='';
				document.getElementById("A11a"+i).innerHTML='';
				document.getElementById("A13a"+i).innerHTML='';

				document.getElementById("num"+i).value='';
				document.getElementById("cNum"+i).value='';
				document.getElementById("iNum"+i).value='';
				document.getElementById("pNum"+i).value='';



			}
			var toSend = "./ajax/ajax16.php?"
			//var toSend = "./ajax/ajax16.php?s_contents="+s_contents
			//		   +"&push="+push
				   +"&page="+page;
	/*}else{
		alert('성명부터 입력하시고!!')
		document.getElementById("s_contents").value.focus();
		return false;
		
	}*/
//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function checkSele(k,k2,sago){
	//alert(sago)
	//document.getElementById('A6a'+k).innerHTML
	 var aButton=document.getElementById('A6a'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 aButton.style.cursor='hand';
	 aButton.style.width = '40px';
	 aButton.innerHTML=k2;
	 aButton.onclick=DaeriMemberCompany;

	//사고기록
		//alert(sago)
	if(eval(sago)==1){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A13a'+k);
	 newInput2.id='etag'+k;
	 newInput2.style.width = '90px';
	 newInput2.className='selectbox';
	newInput2.onchange=claimg;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=3;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(sago)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 1 :
					  opts[i].text='사고없음';
						break;
					case 2 :
						opts[i].text='사고있음';
					break;
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);
	}else{

		var aButton=document.getElementById('A13a'+k);
		aButton.className='stn-b';
	 //aButton.value=val1;
	// aButton.style.cursor='hand';
	// aButton.style.width = '40px';
	 aButton.innerHTML='사고있음';
	 aButton.onclick=claimg;


	}


	/*var aButton=document.getElementById('A13a'+k);
	aButton.className='stn-b';
	 //aButton.value=val1;
	// aButton.style.cursor='hand';
	// aButton.style.width = '40px';
	 aButton.innerHTML='사고기록';
	 aButton.onclick=claimg;
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

function DaeriMemberCompany(){//보험회사별 인원 전체 찾기
	var nn=this.getAttribute("id");
	//alert(nn);
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

function claimg(){  //사고 기록하기 위해 
		var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}
		var DariMemberNum='num'+nn;
			var DariMemberNum=document.getElementById(DariMemberNum).value;
			var jumin=document.getElementById('A3a'+nn).innerHTML;
			
			var userId=document.getElementById('userId').value;
			var daeriComNum=document.getElementById('cNum'+nn).value;	
			var CertiTableNum=document.getElementById('pNum'+nn).value;

			//alert( DariMemberNum +'/'+ userId+'/'+daeriComNum+'/'+CertiTableNum);


			var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/Claim.php?daeriComNum='+daeriComNum+'&CertiTableNum='+CertiTableNum+'&DariMemberNum='+DariMemberNum+'&userId='+userId+'&jumin='+jumin,'claim','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')

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
				//alert('1')
				document.getElementById('sang'+nn).value=4;
				return false;
			}
	}else{
		alert('이미 해지 신청했습니다');
		document.getElementById("sang"+nn).value=2;
	}
}

