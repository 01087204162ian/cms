
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
								document.getElementById('A4a'+k).style.color='#E4690C';
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
								document.getElementById('A4a'+k).style.color='#E4690C';
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지중';
							    document.getElementById('A4a'+k).style.color='#E4690C';
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
								    document.getElementById('A8a'+k).style.color='#AF8AC1';
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='DB';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
								    document.getElementById('A8a'+k).style.color='#0A8FC1';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='롯데';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;

						}
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].text;
						document.getElementById('A51a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a51")[k].text; //할인할증
						//document.getElementById('A12a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].text;
						var hp=myAjax.responseXML.documentElement.getElementsByTagName("a34")[k].text; //핸드폰
						var shp=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].text; //통신사
						var nhp=myAjax.responseXML.documentElement.getElementsByTagName("a31")[k].text; //명의자
						var address=myAjax.responseXML.documentElement.getElementsByTagName("a32")[k].text; //주소
						var injeong=myAjax.responseXML.documentElement.getElementsByTagName("a33")[k].text; //인증
						
						hpHone(k,hp,shp,nhp,address,injeong);//순서,핸드폰,통신사,명의자,주소
						//document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].text;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].text;	
						
					}else{
						document.getElementById('bunho'+k).innerHTML=k+1;
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;

						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent)){

							case 1 :	
								document.getElementById('A4a'+k).innerHTML='청약중';
								document.getElementById('A4a'+k).style.color='#E4690C';
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
								document.getElementById('A4a'+k).style.color='#E4690C';
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지중';
							    document.getElementById('A4a'+k).style.color='#E4690C';
							break;
							default :

								sele(k,myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent);//상태를 만들기 위해
								break;
						}
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent)==1){//흥국화재는 탁송이 없다
							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent);
						}
							//checkSele(k,myAjax.responseXML.documentElement.getElementsByTagName("a12")[k].text);
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
				
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent))
						{
								case 1 :
									document.getElementById('A8a'+k).innerHTML='흥국';
								    document.getElementById('A8a'+k).style.color='#AF8AC1';
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='DB';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
								    document.getElementById('A8a'+k).style.color='#0A8FC1';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='롯데';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
									document.getElementById('A8a'+k).style.color='#FA8FC1';
									break;


						}
						
						
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a9")[k].textContent;
						document.getElementById('A51a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a51")[k].textContent; //할인할증
						//document.getElementById('A12a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].textContent;
						var hp=myAjax.responseXML.documentElement.getElementsByTagName("a34")[k].textContent; //핸드폰
						var shp=myAjax.responseXML.documentElement.getElementsByTagName("a30")[k].textContent; //통신사
						var nhp=myAjax.responseXML.documentElement.getElementsByTagName("a31")[k].textContent; //명의자
						var address=myAjax.responseXML.documentElement.getElementsByTagName("a32")[k].textContent; //주소
						var injeong=myAjax.responseXML.documentElement.getElementsByTagName("a33")[k].textContent; //인증
						
						hpHone(k,hp,shp,nhp,address,injeong);//순서,핸드폰,통신사,명의자,주소
						//document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].textContent;
						document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].textContent;

						
					}
				
				}
			if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
				document.getElementById("memberTotal").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
				for(var _p=1;_p<9;_p++){
				
					if(myAjax.responseXML.documentElement.getElementsByTagName("n"+_p)[0].text!=0){
						document.getElementById('n'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("n"+_p)[0].text;
						document.getElementById('n'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].text;
						document.getElementById('o'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("o"+_p)[0].text;
						document.getElementById('o'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].text;
						document.getElementById('m'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m"+_p)[0].text;
						document.getElementById('m'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].text;
					}
				
				}
				
				
			}else{
				document.getElementById("memberTotal").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
				for(var _p=1;_p<9;_p++){
				
					if(myAjax.responseXML.documentElement.getElementsByTagName("n"+_p)[0].textContent!=0){
						document.getElementById('n'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("n"+_p)[0].textContent;
						document.getElementById('n'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].textContent;
						document.getElementById('o'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("o"+_p)[0].textContent;
						document.getElementById('o'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].textContent;
						document.getElementById('m'+_p).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m"+_p)[0].textContent;
						document.getElementById('m'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].textContent;
					}
				}

				/*
				for(var _p=1;_p<3;_p++){
				
					for(var _q=0;_q<7;_q++){
						document.getElementById('me'+_p+_q).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("me"+_p+_q)[0].textContent;
						document.getElementById('sNum'+_p+_q).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sNum"+_p+_q)[0].textContent+'명';

					}
				}*/
			}
		}else{

			alert('조회 결과 없음!!');
		}
	}else{
		//	
	}
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
	opts.length=6;
	if(eval(etag)==1){
			newInput2.style.color='#cccccc';	
		}else{
			newInput2.style.color='#E4690C';		
		}
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
					//newInput2.style.color='#cccccc'
						break;
					case 2 :
						opts[i].text='탁송';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 3 :
						opts[i].text='일반/렌트';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 4 :
						opts[i].text='탁송/렌트';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 5 :
						opts[i].text='확대탁송';
					    // newInput2.style.color='#FA8FC1'
					break;
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);


}
function pserch(val){
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

			var kind_state2=document.getElementById("kind_state2").value;
			//var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
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
				document.getElementById("A29a"+i).innerHTML='';

				document.getElementById("num"+i).value='';
				document.getElementById("cNum"+i).value='';
				document.getElementById("iNum"+i).value='';
				document.getElementById("pNum"+i).value='';

				document.getElementById("A30a"+i).innerHTML='';
				document.getElementById("A31a"+i).innerHTML='';
				document.getElementById("A32a"+i).innerHTML='';
				document.getElementById("A51a"+i).innerHTML='';

			}
			var cNum=document.getElementById("cNum").value;
			var toSend = "./ajax/ajax1.php?s_contents="+s_contents
						//+"&insuranceComNum="+insuranceComNum
						+"&kind_state2="+kind_state2
						+"&cNum="+cNum
					   +"&page="+page;
	//}else{
	//	alert('성명부터 입력하시고!!')
	//	document.getElementById("s_contents").value.focus();
	//	return false;
		
	//}
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
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
				document.getElementById("A29a"+i).innerHTML='';
				document.getElementById("num"+i).value='';
				document.getElementById("cNum"+i).value='';
				document.getElementById("iNum"+i).value='';
				document.getElementById("pNum"+i).value='';


				document.getElementById("A30a"+i).innerHTML='';
				document.getElementById("A31a"+i).innerHTML='';
				document.getElementById("A32a"+i).innerHTML='';
				document.getElementById("A51a"+i).innerHTML='';


			}
			document.getElementById("memberTotal").innerHTML='';
			
			for(var _p=1;_p<7;_p++){
				
					
						document.getElementById('n'+_p).innerHTML=''
						//document.getElementById('n'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].text;
						document.getElementById('o'+_p).innerHTML='';
						//document.getElementById('o'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].text;
						document.getElementById('m'+_p).innerHTML='';
						//document.getElementById('m'+_p).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+_p)[0].text;
					
				}
			var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
			var cNum=document.getElementById("cNum").value;
			var kind_state=document.getElementById("kind_state").value;
			var toSend = "./ajax/ajax1.php?cNum="+cNum
						+"&insuranceComNum="+insuranceComNum
						+"&kind_state="+kind_state
					   +"&page="+page;
	
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
	 aButton.style.width = '10px';
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

/*function DaeriMemberCompany(){//보험회사별 인원 전체 찾기
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
}*/

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
		//alert(endorseDay);
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
		}
	}else{
		alert('이미 해지 신청 되어 있습니다  \n 취소하실려면 유선으로 하세요');
		document.getElementById("sang"+nn).value=2;



	}
}

