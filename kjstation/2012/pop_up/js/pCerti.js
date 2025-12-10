
function cReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
//alert(myAjax.responseText);
//alert(myAjax.responseXML.documentElement.getElementsByTagName("sql")[0].text);
//alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
	       if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){//

				   //alert(myAjax.responseXML.documentElement.getElementsByTagName("a1").length);
					for(var k=0;k<myAjax.responseXML.documentElement.getElementsByTagName("a1").length;k++){
						document.getElementById('M1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m1")[k].text;
						document.getElementById('M2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m2")[k].text;
							//alert(myAjax.responseXML.documentElement.getElementsByTagName("q_num")[k].text);

							var qnum=myAjax.responseXML.documentElement.getElementsByTagName("q_num")[k].text;
						
						for(var _j=1;_j<6;_j++){
								document.getElementById('A'+_j+'a').value=myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].text;
						}
						for(var _j=11;_j<20;_j++){
								//alert(myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].text);
								document.getElementById('A'+_j+'a').value=myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].text;
						}
						for(var _j=0;_j<3;_j++){
								document.getElementById('da'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m3")[_j].text;//담당자

							for(var _k=0;_k<qnum;_k++){
								if(_j>=1){_q=_j*qnum+_k}else{_q=_k}

								if(_j==0){
								   document.getElementById('M2a'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m5")[_q].text;//연령

								}
									//alert(_q)
								document.getElementById('S1a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m4")[_q].text;//번호
								document.getElementById('S2a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m5")[_q].text;//연령
								document.getElementById('S3a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m6")[_q].text;//인원
								document.getElementById('S4a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m9")[_q].text;//보험회사받는보험료
								document.getElementById('S5a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m10")[_q].text;//인원X보험회사받는보험료
								document.getElementById('S6a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m13")[_q].text;//인원X보험회사받는보험료
								document.getElementById('S7a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m16")[_q].text;//업체로부터받는보험료
							}
							document.getElementById('T1a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m7")[_j].text;//소계인원
							document.getElementById('T3a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m11")[_j].text;//소계 보험료
							document.getElementById('T4a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m14")[_j].text;//10%소계 보험료
							document.getElementById('T5a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m17")[_j].text;//10%소계 보험료
						}
						
						document.getElementById('U1a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m8")[k].text;//합계
						document.getElementById('U3a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m12")[k].text;//합계보험료
						document.getElementById('U4a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m15")[k].text;//10%합보험ㄹ
						document.getElementById('U5a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m18")[k].text;//업체로받은계


						
					}
						alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
					//	document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].text;
						
			}else{
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				for(var k=0;k<myAjax.responseXML.documentElement.getElementsByTagName("a1").length;k++){
						document.getElementById('M1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m1")[k].textContent;
						document.getElementById('M2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m2")[k].textContent;
						var qnum=myAjax.responseXML.documentElement.getElementsByTagName("q_num")[k].textContent;
//alert(myAjax.responseXML.documentElement.getElementsByTagName("q_num")[k].textContent);
						for(var _j=1;_j<6;_j++){
								document.getElementById('A'+_j+'a').value=myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].textContent;
						}
						for(var _j=11;_j<20;_j++){
								//alert(myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].textContent);
								document.getElementById('A'+_j+'a').value=myAjax.responseXML.documentElement.getElementsByTagName("a"+_j)[k].textContent;
						}
						for(var _j=0;_j<3;_j++){
								document.getElementById('da'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m3")[_j].textContent;//담당자
							for(var _k=0;_k<qnum;_k++){
								if(_j>=1){_q=_j*qnum+_k}else{_q=_k}

								if(_j==0){
									//alert(myAjax.responseXML.documentElement.getElementsByTagName("m5")[_q].textContent);
								   document.getElementById('M2a'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m5")[_q].textContent;//연령

								}	
								//alert(_q)
								document.getElementById('S1a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m4")[_q].textContent;//번호
								document.getElementById('S2a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m5")[_q].textContent;//연령
								document.getElementById('S3a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m6")[_q].textContent;//인원
								document.getElementById('S4a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m9")[_q].textContent;//보험회사받는보험료
								document.getElementById('S5a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m10")[_q].textContent;//인원X보험회사받는보험료
								document.getElementById('S6a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m13")[_q].textContent;//인원X보험회사받는보험료
								document.getElementById('S7a'+_j+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m16")[_q].textContent;//업체로부터받는보험료
							}
							document.getElementById('T1a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m7")[_j].textContent;//소계인원
							document.getElementById('T3a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m11")[_j].textContent;//소계 보험료
							document.getElementById('T4a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m14")[_j].textContent;//10%소계 보험료
							document.getElementById('T5a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m17")[_j].textContent;//10%소계 보험료
						}
						
						document.getElementById('U1a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m8")[k].textContent;//합계
						document.getElementById('U3a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m12")[k].textContent;//합계보험료
						document.getElementById('U4a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m15")[k].textContent;//10%합보험ㄹ
						document.getElementById('U5a'+_j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("m18")[k].textContent;//업체로받은계


						
					}
						alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					//	document.getElementById('m1_1').value=myAjax.responseXML.documentElement.getElementsByTagName("mStore")[0].textContent;
			}
	}else{


	}

}

function checkSele(k,k2,k3){

	 var aButton=document.getElementById('day'+k);
	 //aButton.className='input5';
	 //aButton.value=val1;
	 aButton.style.cursor='hand';
	 aButton.style.width = '40px';
	// aButton.innerHTML='일일';
	// aButton.onclick=ssang_preminum;

	 var bButton=document.getElementById('but'+k);
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
	// dButton.onclick=MothpDatile;//각보험회사별 보험료
}

function changeV(){

	var certi=document.getElementById('M1a').innerHTML;
	var A1a=encodeURIComponent(document.getElementById('A1a').value);
	var A2a=encodeURIComponent(document.getElementById('A2a').value);
	var A3a=encodeURIComponent(document.getElementById('A3a').value);
	var A4a=encodeURIComponent(document.getElementById('A4a').value);
	var A5a=encodeURIComponent(document.getElementById('A5a').value);
	//var A6a=encodeURIComponent(document.getElementById('A6a').value);
	//var A7a=encodeURIComponent(document.getElementById('A7a').value);
	//var A8a=encodeURIComponent(document.getElementById('A8a').value);

	//var A9a=encodeURIComponent(document.getElementById('A9a').value);
	var A11a=encodeURIComponent(document.getElementById('A11a').value);
	var A12a=encodeURIComponent(document.getElementById('A12a').value);
	var A13a=document.getElementById('A13a').value;//19~25세
	var A14a=document.getElementById('A14a').value;//26~44세
	var A15a=document.getElementById('A15a').value;//45~49세
	var A16a=document.getElementById('A16a').value;//50~59세
	var A17a=document.getElementById('A17a').value;//60세이상
	var A18a=document.getElementById('A18a').value;//66세이상
	var A19a=document.getElementById('A19a').value;//66세이상

	//alert(A14a+'/'+A15a)

	var sj=1;//13에서 수정하는게 아닌 
		if(confirm('수정')){
			var toSend = "../intro/ajax/update2012C.php?certi="+certi 
						  +"&A1a="+A1a
						  +"&A2a="+A2a
						  +"&A3a="+A3a
						  +"&A4a="+A4a  //계약일
						  +"&A5a="+A5a
						//  +"&A6a="+A6a
						//  +"&A7a="+A7a
						//  +"&A8a="+A8a
						  +"&A11a="+A11a
					      +"&A12a="+A12a
						  +"&A13a="+A13a
						  +"&A14a="+A14a
						  +"&A15a="+A15a
						  +"&A16a="+A16a
						  +"&A17a="+A17a
						  +"&A18a="+A18a
						   +"&A19a="+A19a
						  +"&sj="+sj

						//	+"&insuranceComNum="+insuranceComNum
						//   +"&s_contents="+s_contents
						  // +"&you="+you
						   //+"&manGi="+manGi
						//   +"&page="+page;
			//alert(toSend)
			//document.getElementById("url").innerHTML = toSend;
			
			//alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=cReceive;
			myAjax.send('');
		}else{

		}	  

}
function serch(val){
	

	myAjax=createAjax();
	var Certi=document.getElementById("Certi").value;
	
	
	var toSend = "./ajax/aCerti.php?Certi="+Certi
			  
	//alert(toSend)
	//document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=cReceive;
	myAjax.send('');
}

addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
//증권번호 입력-->
function changeEtagResponse() {
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
function Allchange(){

	
var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
var s_contents2=encodeURIComponent(document.getElementById("s_contents2").value);
if(s_contents2 && s_contents){
		if(confirm(s_contents2+'으로 변경합니다 !!')){
		var toSend = "./ajax/certiDriverChange.php?s_contents="+s_contents
					+"&s_contents2="+s_contents2
				  
				  
		
		//document.getElementById("url").innerHTML = toSend;
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


	var s_co=document.getElementById("Certi").value;

	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		
//alert(mNab);
		window.open('../intro/php/sjExcel17.php?num='+s_co,'excel','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}


function compare(){

	var s_co=document.getElementById("Certi").value;

	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		
//alert(mNab);
		window.open('./ExcelUpload_3.php?num='+s_co,'compare','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}
function pClick(){
    var val=document.getElementById("end").value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	//window.open('/newAdmin/dongbu2/pop_up/driverSerch.php?driver_num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
	window.open('/kibs_admin/pdf/fpdf153/Allcerti3.php?num='+val,'print','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
}