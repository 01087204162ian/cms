function ceReceive() {
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

function ceReceive2() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		alert(myAjax.responseText);
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

				if(myAjax.responseXML.documentElement.getElementsByTagName("nabang")[0].text){
				var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text;
				document.getElementById('cheriii'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("nabang")[0].text;
				document.getElementById('kang'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[0].text
				}
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				if(myAjax.responseXML.documentElement.getElementsByTagName("nabang")[0].textContent){
				var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].textContent;

				document.getElementById('cheriii'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("nabang")[0].textContent;
				document.getElementById('kang'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[0].textContent;
				}
				
			}
	}else{
		//	
	}
}
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
//alert(myAjax.responseText);
//alert(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text);
		    if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
				//pageList();
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text);
				var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
			}else{
				//pageList();
				var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
			}

			
			if(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text!=undefined){
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
					document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("name4")[0].text;
			}else{
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
					document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("name4")[0].textContent;
										
			}
			for(var j=1;j<15;j++){
				if(myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text!=undefined){
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;
				}else{
					document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
										
				}
			}
			
			
			
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				

				for(var _m=0;_m<eval(myAjax.responseXML.documentElement.getElementsByTagName("last")[0].text);_m++)
				{	document.getElementById('B1b'+_m).innerHTML=_m+1;
					document.getElementById('endorseDay2').value=myAjax.responseXML.documentElement.getElementsByTagName("date")[_m].text;
					document.getElementById('comment2').value=myAjax.responseXML.documentElement.getElementsByTagName("comme")[_m].text;
					document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("memberNum")[_m].text;
					document.getElementById('B2b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Name")[_m].text;
					document.getElementById('B3b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Jumin")[_m].text;
					document.getElementById('B4b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("etag")[_m].text;
					//document.getElementById('B17b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sPrem")[_m].text;//삼성화재보험료
					if(myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[_m].text==0){
						myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[_m].text='';
					}
					document.getElementById('B6b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[_m].text;//,표시 
					
					document.getElementById('B8b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("Endorse2Preminum")[_m].text;//문자보낼때 표시하기 위한 
					var Cancel=myAjax.responseXML.documentElement.getElementsByTagName("cancel")[_m].text;
					var Push=myAjax.responseXML.documentElement.getElementsByTagName("push")[_m].text;
					var Sangtae=myAjax.responseXML.documentElement.getElementsByTagName("sangtae")[_m].text;
					dongbuSele(_m,Push,Sangtae,Cancel);
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("cancel")[_m].text);
					//처리 미처리
					var samP=myAjax.responseXML.documentElement.getElementsByTagName("sPrem")[_m].text;//삼성화재보험료
					sele(_m,myAjax.responseXML.documentElement.getElementsByTagName("sangtae")[_m].text,Cancel,samP);

						var pInsu=myAjax.responseXML.documentElement.getElementsByTagName("name14")[0].text;
					//동부화재,삼성화재인경 인 경우만 적용 됨
					if(eval(pInsu==2)||eval(pInsu==8) ){
						var Certi= myAjax.responseXML.documentElement.getElementsByTagName("dongbuCerti")[_m].text;//증권번호
						var Serti= myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[_m].text;//설계번호
						//if(eval(pInsu==2)){
						var Sigi=myAjax.responseXML.documentElement.getElementsByTagName("dongbusigi")[_m].text;//동부시기
						var Jeonggi=myAjax.responseXML.documentElement.getElementsByTagName("dongbujeongi")[_m].text;//동부종기
						//}
						var nabang_1=myAjax.responseXML.documentElement.getElementsByTagName("nabang_1")[_m].text;//납입회차
						
						dongbuHaeji(_m,Push,Sangtae,Cancel,Certi,Serti,Sigi,Jeonggi,nabang_1);
					//	var Push=myAjax.responseXML.documentElement.getElementsByTagName("push")[_m].text;
						if(Push==4 || Push==1){
						//document.getElementById('jang').value=;
						//document.getElementById('kang').value=myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[_m].text;
						}
						
					}else{
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("name14")[_m].text);

					//현대화재 인 경우만 적용 됨
					//if(eval(pInsu!=2)||eval(pInsu!=8)){
						var Certi= myAjax.responseXML.documentElement.getElementsByTagName("dongbuCerti")[_m].text;//증권번호
						hyunHaeji(_m,Certi);
						if(Push==4 || Push==1){
						//document.getElementById('jang'+_m).value=;
						//document.getElementById('kang'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[_m].text;
						}
						
					}
				//청약 취소 또는 거절인경우에만
				var diffCom=myAjax.responseXML.documentElement.getElementsByTagName("cancel")[_m].text;
						
						//alert(diffCom)
					if(eval(diffCom)=='13' || eval(diffCom)=='12'){
						//alert(myAjax.responseXML.documentElement.getElementsByTagName("Qnum")[_m].text)
						if(myAjax.responseXML.documentElement.getElementsByTagName("Qnum")[_m].text){//변경했으면
							document.getElementById('B16b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("chCom")[_m].text;
						}else{
							//alert(myAjax.responseXML.documentElement.getElementsByTagName("Qnum")[_m].text);
							diffChangeCompany2(_m,myAjax.responseXML.documentElement.getElementsByTagName("Cnum")[_m].text)//
						}

					}
					
				}
				document.getElementById('comment').value=myAjax.responseXML.documentElement.getElementsByTagName("smsContent")[0].text
				
				document.getElementById('endorseDay').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorse_day")[0].text;
				
				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].text;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].text;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].text;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].text;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].text;
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)){
						bagoJo(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}
				}
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				for(var _m=0;_m<eval(myAjax.responseXML.documentElement.getElementsByTagName("last")[0].textContent);_m++)
				{	document.getElementById('B1b'+_m).innerHTML=_m+1;
					document.getElementById('endorseDay2').value=myAjax.responseXML.documentElement.getElementsByTagName("date")[_m].textContent;
					document.getElementById('comment2').value=myAjax.responseXML.documentElement.getElementsByTagName("comme")[_m].textContent;
					document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("memberNum")[_m].textContent;
					document.getElementById('B2b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Name")[_m].textContent;
					document.getElementById('B3b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Jumin")[_m].textContent;
					document.getElementById('B4b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("etag")[_m].textContent;
					document.getElementById('B17b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sPrem")[_m].textContent;//삼성화재보험료

					if(myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[_m].textContent==0){
						myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[_m].textContent='';
					}
					document.getElementById('B6b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[_m].textContent;//,표시 
					
					document.getElementById('B8b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("Endorse2Preminum")[_m].textContent;//문자보낼때 표시하기 위한 
					var Cancel=myAjax.responseXML.documentElement.getElementsByTagName("cancel")[_m].textContent;
					var Push=myAjax.responseXML.documentElement.getElementsByTagName("push")[_m].textContent;
					var Sangtae=myAjax.responseXML.documentElement.getElementsByTagName("sangtae")[_m].textContent;
					dongbuSele(_m,Push,Sangtae,Cancel);
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("cancel")[_m].textContent);
					//처리 미처리
					var samP=myAjax.responseXML.documentElement.getElementsByTagName("sPrem")[_m].textContent;//삼성화재보험료
					sele(_m,myAjax.responseXML.documentElement.getElementsByTagName("sangtae")[_m].textContent,Cancel,samP);

					var pInsu=myAjax.responseXML.documentElement.getElementsByTagName("name14")[0].textContent;

					//동부화재 인 경우만 적용 됨
					if(eval(pInsu==2)||eval(pInsu==8)){
						var Certi= myAjax.responseXML.documentElement.getElementsByTagName("dongbuCerti")[_m].textContent;//증권번호
						var Serti= myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[_m].textContent;//설계번호
						//if(eval(pInsu==2)){
						var Sigi=myAjax.responseXML.documentElement.getElementsByTagName("dongbusigi")[_m].textContent;//동부시기
						var Jeonggi=myAjax.responseXML.documentElement.getElementsByTagName("dongbujeongi")[_m].textContent;//동부종기
					//}
						var nabang_1=myAjax.responseXML.documentElement.getElementsByTagName("nabang_1")[_m].textContent;//납입회차
						
						//alert(samP);
						dongbuHaeji(_m,Push,Sangtae,Cancel,Certi,Serti,Sigi,Jeonggi,nabang_1);
					//	var Push=myAjax.responseXML.documentElement.getElementsByTagName("push")[_m].textContent;
						if(Push==4 || Push==1){
						//document.getElementById('jang').value=;
						//document.getElementById('kang').value=myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[_m].textContent;
						}
						
					}else{
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("name14")[_m].textContent);

					//현대화재 인 경우만 적용 됨
					//if(eval(myAjax.responseXML.documentElement.getElementsByTagName("name14")[0].textContent!=2)){
						var Certi= myAjax.responseXML.documentElement.getElementsByTagName("dongbuCerti")[_m].textContent;//증권번호
						hyunHaeji(_m,Certi);
						if(Push==4 || Push==1){
						//document.getElementById('jang'+_m).value=;
						//document.getElementById('kang'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("dongbuSelNumber")[_m].textContent;
						}
						
					}
				//청약 취소 또는 거절인경우에만
				var diffCom=myAjax.responseXML.documentElement.getElementsByTagName("cancel")[_m].textContent;
			
					if(eval(diffCom)==13 || eval(diffCom)==12){
						if(myAjax.responseXML.documentElement.getElementsByTagName("Qnum")[_m].textContent){//변경했으면
								
								document.getElementById('B16b'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("chCom")[_m].textContent;
						}else{
							diffChangeCompany2(_m,myAjax.responseXML.documentElement.getElementsByTagName("Cnum")[_m].textContent)//
						}

					}
					
				}
				document.getElementById('comment').value=myAjax.responseXML.documentElement.getElementsByTagName("smsContent")[0].textContent
				document.getElementById('endorseDay').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorse_day")[0].textContent;
				
				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].textContent);_k++)
				{
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].textContent;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].textContent;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].textContent;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].textContent;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].textContent;
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)){
						bagoJo(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}
				}					
			}
			
	}else{
		//	
	}
}
function hyunHaeji(k,Certi){

	//증권번호 체크하면 전년도 증권번호금년이 12이면11
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B9b'+k);
			 newInput2.id='kwon'+k;
			 newInput2.style.width = '15px';
			 //newInput2.className='checkbox';
			 newInput2.type='checkbox';
			 //newInput2.onclick=certicheck;
			 
			 aJumin.appendChild(newInput2);

			//증권번호 입력 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B10b'+k);
			 newInput2.id='jang'+k;
			 newInput2.style.width = '80px';
			 newInput2.className='phone2';
			 newInput2.value=Certi;
			 newInput2.onblur=hyuncerticheck;
			 newInput2.onclick=hyuncerticheck2;
			
			 aJumin.appendChild(newInput2);

			//보험회사에내는 보험료 설정 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B17b'+k);
			 newInput2.id='cPrem'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			 newInput2.value=Certi;//보험회사에내는 보험료 설정을 하면 그것을 계산해서 한다.....
			// newInput2.onblur=certicheck;
			// newInput2.onclick=certicheck2;
			
			// aJumin.appendChild(newInput2);

}
//dongbuHaeji(_m,Push,Sangtae,Cancel,Certi,Serti,Sigi,Jeonggi,nabang_1);
function dongbuHaeji(k,k2,k3,Cancel,Certi,Serti,Sigi,Jeonggi,nabang_1){//순번,push/
	//alert(k2)
	//alert(Cancel)
	//if(k2==4 || k2==1){//정상인경우 즉 청약인 경우만
	if(Cancel!='42'){
	//증권번호 체크하면 전년도 증권번호금년이 12이면11
	/*		var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B9b'+k);
			 newInput2.id='kwon'+k;
			 newInput2.style.width = '15px';
			 //newInput2.className='checkbox';
			 newInput2.type='checkbox';
			// newInput2.onclick=certicheck;
			 
			 aJumin.appendChild(newInput2);*/

			//증권번호 입력 
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B10b'+k);
			 newInput2.id='jang'+k;
			 newInput2.style.width = '90px';
			 newInput2.className='phone';
			 newInput2.value=Certi;
			// newInput2.onblur=certicheck;
			// newInput2.onclick=certicheck2;
			
			 aJumin.appendChild(newInput2);


			//설계번호 체크 체크하면전월을 표시하기 위해

		/*	var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B11b'+k);
			 newInput2.id='lang'+k;
			 newInput2.style.width = '15px';
			// newInput2.className='checkbox';
			 newInput2.type='checkbox';
			// newInput2.onchange=innerHTML;
			
			 aJumin.appendChild(newInput2);*

			 
			//설계번호 입력 
		/*	var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B12b'+k);
			 newInput2.id='kang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			  newInput2.value=Serti;
			newInput2.onblur=jcheck;
			newInput2.onclick=jcheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);


			//보험시기 입력하기 위해 

			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B13b'+k);
			 newInput2.id='sigi'+k;
			 newInput2.style.width = '60px';
			 newInput2.className='phone';
			  newInput2.value=Sigi;
			newInput2.onblur=dsigiCheck;
			newInput2.onclick=dsigiCheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);

			//보험종기 입력하기 위해 

			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B14b'+k);
			 newInput2.id='djeonggi'+k;
			 newInput2.style.width = '60px';
			 newInput2.className='phone';
			  newInput2.value=Jeonggi;
			newInput2.onblur=dsigiCheck;
			newInput2.onclick=dsigiCheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);

			cheriNab(k,nabang_1);//회차*/

			
			//
	}else{//해지 일경우
			//alert(k2)
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B13b'+k);
			 newInput2.type='button';
			 newInput2.id='sang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='input2';
			 newInput2.onclick=retirePrint;
			 newInput2.value="퇴직증명서";				
			 aJumin.appendChild(newInput2);

			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B10b'+k);
			 newInput2.id='jang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			  newInput2.value=Certi;
			// newInput2.onblur=certicheck;
			// newInput2.onclick=certicheck2;
			
			 aJumin.appendChild(newInput2);

			 var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B12b'+k);
			 newInput2.id='kang'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			   newInput2.value=Serti;
			//newInput2.onblur=jcheck;
			//newInput2.onclick=jcheck2;
			 var opts=newInput2.options;
			 aJumin.appendChild(newInput2);


			
			 cheriNab(k,nabang_1);//회차

		/*	var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B17b'+k);
			 newInput2.id='cPrem'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			 newInput2.value=samP;//보험회사에내는 보험료 설정을 하면 그것을 계산해서 한다.....
			newInput2.onblur=samSungcheck;
			// newInput2.onclick=certicheck2;
			
			 aJumin.appendChild(newInput2);*/

	}


}
function samSungcheck(){

	//alert(this.id)
	if(this.value.length>1){
		var nn=this.id.substr(5,6);
	}else{
		var nn=this.id.substr(5,7);
	}

	//alert(nn)
	var endorseDay=document.getElementById('endorseDay').innerHTML;

	  myAjax=createAjax();//청약번호 저장을 하기위해

		//var push=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;//청약 또는 해지
		var  pRem=this.value;
		var  dirverNum=document.getElementById('B0b'+nn).value;
		
		var toSend = "./ajax/samPremInput.php?sPrem="+pRem
				   +"&driverNum="+dirverNum
				   +"&endorseDay="+endorseDay
				   //+"&push="+tableNum;
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=changeEtagResponse;
		myAjax.send('');
}

function cheriNab(k,nabang_1){//납입회차 
	
var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B15b'+k);
	 newInput2.id='cheriii'+k;
	 newInput2.style.width = '60px';
	 newInput2.className='selectbox';
	//newInput2.onchange=changeNab;
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
	 aJumin.appendChild(newInput2);
}
function dsigiCheck(){//시기 

	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==8){
			
		this.value=this.value.substring(0,4)+"-"+this.value.substring(4,6)+"-"+this.value.substring(6,8);
		//document.getElementById('djeonggi'+nn).value=(eval(this.value.substring(0,4))+1)+"-"+this.value.substring(5,7)+"-"+this.value.substring(8,10);
		
	}

}

function dsigiCheck2(){//시기 

	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==10){
		
		this.value=this.value.substring(0,4)+this.value.substring(5,7)+this.value.substring(8,10);
		
	}

}
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
function jcheck(){//설계번호

	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==4){
		//alert(document.getElementById('kwon'+nn).checked)
		
		if(document.getElementById('lang'+nn).checked==true){
			//alert(this.value)
			if(document.getElementById('endorseDay').innerHTML.substring(5,7)==01){//1월인경우
					document.getElementById('endorseDay').innerHTML.substring(5,7)==12;
				}	
			this.value=document.getElementById('endorseDay').innerHTML.substring(5,7)-1+"-"+this.value;
		}else{

			this.value=document.getElementById('endorseDay').innerHTML.substring(5,7)+"-"+this.value;
		}
	}

	  myAjax=createAjax();//청약번호 저장을 하기위해
		var  pCerti=this.value;
		var  dirverNum=document.getElementById('B0b'+nn).value;
		var toSend = "./ajax/appInput.php?appNumber="+pCerti
				   +"&driverNum="+dirverNum;
				  // +"&page="+page
				  // +"&tableNum="+tableNum;
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=changeEtagResponse;
		myAjax.send('');

}


function jcheck2(){//설계번호

	
	if(this.value.length==7){
		

			this.value=this.value.substring(3,7);
		
	}else if(this.value.length==6){
		

			this.value=this.value.substring(2,6);
	}

}

function hyuncerticheck(){//증권번호

	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	//alert(nn)
   //LIG,현대
	//alert(this.value)
   if(eval(document.getElementById('a14').value)==3 || eval(document.getElementById('a14').value)==4){
		if(this.value.length==7){
			var cyear=document.getElementById('a9').value.substring(0,4);
			if(document.getElementById('kwon'+nn).checked==true){
				this.value=eval(cyear)-1+"-"+this.value;
			}else{

				this.value=eval(cyear)+"-"+this.value;
			}
		}else{

			alert('일곱자리');
			document.getElementById("jang"+nn).focus();
			return false;

		}
	}
	//흥국
	if(eval(document.getElementById('a14').value)==1 ){
		if(this.value.length==4){
		
			var cyear=document.getElementById('a9').value.substring(0,2);
			
			if(document.getElementById('kwon'+nn).checked==true){
				
				this.value=eval(cyear)-1+"-700"+this.value;
			}else{

				this.value=eval(cyear)+"-700"+this.value;
			}
		}else{

			alert('네자리');
			document.getElementById("jang"+nn).focus();
			return false;

		}
	}

	var memberNum=document.getElementById('B0b'+nn).value;
	var toSend = "./ajax/certiChange.php?memberNum="+memberNum
				 +"&dongbuCerti="+this.value;
				 
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=ceReceive;
	myAjax.send('');
}

function hyuncerticheck2(){//증권번호

	
	
	if(this.value.length==12){
		
			this.value=this.value.substring(5,12);
		
	}
	if(this.value.length==10){
		
			this.value=this.value.substring(6,12);
		
	}
	
	
}
function certicheck(){//증권번호
	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}
	if(this.value.length==7){
		//alert(document.getElementById('kwon'+nn).checked)
		
		if(document.getElementById('kwon'+nn).checked==true){
			//alert(this.value)
			
			this.value=document.getElementById('endorseDay').innerHTML.substring(2,4)-1+"-"+this.value;
		}else{

			this.value=document.getElementById('endorseDay').innerHTML.substring(2,4)+"-"+this.value;
		}
		
			var memberNum=document.getElementById('B0b'+nn).value;
			var sunso=nn;
			var toSend = "./ajax/certiChange.php?memberNum="+memberNum
						 +"&sunso="+nn
						 +"&dongbuCerti="+this.value;
						 
			
			//alert(toSend)

			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=ceReceive2;
			myAjax.send('');
	}else{

		alert('일곱자리 !!');

		document.getElementById("jang"+nn).focus();
		return false;

	}
	
	
}

function certicheck2(){//증권번호
	
	if(this.value.length==10){
		
			this.value=this.value.substring(3,10);
		
	}
	
	
}
function dongbuSele(k,k2,k3,k4){//순번.push,sangtae,cancel
	//alert(k);
	//alert(k2);
	//alert(k3);
	//alert(k4)
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B5b'+k);
	 newInput2.id='sang'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	// newInput2.onchange=innerHTML;
	 var opts=newInput2.options;
	
if(k3==2){//배서완료후 
	switch(eval(k2)){
		case 1 ://
			k2=1;
			opts.length=2;
		break;
		case 2 :
			k2=4;
			opts.length=5;
		break;
		case 4 :  //정상이라는 것은 신규 추가를의미하므로//정상이라는 것은 해지 후 취소도 있을 수 있다
			if(eval(k4)==45){
				k2=4;
				opts.length=5;
			}else{
				k2=1;
				opts.length=2;
			}
		break;
	}

}else{
	switch(eval(k2)){
		case 1 :
			
		   opts.length=4;
		 break;
		case 2 :
			k2=4;
		   opts.length=5;
		 break;
		 case 4 :
			opts.length=7;
		break;
	}


}


	 for(var i=k2;i<opts.length;i++){	 
		opts[i].value=i;
		
		if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(eval(i)){
					
					case 1 :
						opts[i].text='청약';
					break;
					case 2 :
						opts[i].text='취소';
					break;
					case 3 :
						opts[i].text='거절';
					break;
					case 4 :
						opts[i].text='해지'+opts[i].value;
					break;
					case 5 :
						opts[i].text='취소'+opts[i].value;
					break;
					
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);




}

function sele(k,k2,k3,samP){////순번,sangtae,cancel

	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B7b'+k);
	 newInput2.id='cheri'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeSangtae;
	 var opts=newInput2.options;
	
	opts.length=3;
	
	 for(var i=k2;i<opts.length;i++){	 
		opts[i].value=i;
		
		if(opts[i].value==eval(k2)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
	/*	if(eval(i)==1){
			newInput2.style.color='red';
		}else{
			
		}*/
				switch(eval(i)){
					
					case 1 :
						newInput2.style.color='red';
						opts[i].text='미처리';
					break;
					case 2 :
						//newInput2.style.color='#0A8FC1';
						switch(eval(k3)){
							case 12:
								opts[i].text='청약취소';
							break;
							case 13:
								opts[i].text='청약거절';
							break;
							case 45:
								opts[i].text='해지취소';
							break;
							default:
								opts[i].text='처리';
							break;
						}
					break;	
				}
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

	//보험회사에내는 보험료 설정 
			
			var newInput2=document.createElement("input");
			 var aJumin=document.getElementById('B17b'+k);
			 newInput2.id='cPrem'+k;
			 newInput2.style.width = '70px';
			 newInput2.className='phone';
			 newInput2.value=samP;//보험회사에내는 보험료 설정을 하면 그것을 계산해서 한다.....
			newInput2.onblur=samSungcheck;
			// newInput2.onclick=certicheck2;
			
			 aJumin.appendChild(newInput2);

}
function pushReceive() {//처리 시

	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);	
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text);
			//document.getElementById('sql').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				var nn=eval(myAjax.responseXML.documentElement.getElementsByTagName("nn")[0].text);
				var diffCom=myAjax.responseXML.documentElement.getElementsByTagName("val")[0].text;//청약 취소 또는 거절인경우에만

				//alert(diffCom)
				if(eval(diffCom)==2 || eval(diffCom)==3){
					
					diffChangeCompany(nn,myAjax.responseXML.documentElement.getElementsByTagName("Cnum")[0].text)//

				}
				
				document.getElementById('B6b'+nn).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("EndorsePreminum")[0].text;
				document.getElementById('B8b'+nn).value=myAjax.responseXML.documentElement.getElementsByTagName("EndorsePnum")[0].text;

				//문자 메세지 새로 표시 되게 하기 위해 ...기본문자 clear 후 새문자 표시하기 위해
				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{
					document.getElementById('C1b'+_k).value='';
					document.getElementById('C1b'+_k).innerHTML='';
					document.getElementById('C2b'+_k).innerHTML='';
					document.getElementById('C3b'+_k).innerHTML='';
					document.getElementById('C4b'+_k).innerHTML='';
					document.getElementById('C5b'+_k).innerHTML='';
					
							document.getElementById('C1b'+_k).style.color='';
							document.getElementById('C2b'+_k).style.color='';
							document.getElementById('C3b'+_k).style.color='';
							document.getElementById('C4b'+_k).style.color='';
						
				}
				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{

					//alert(myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].text)
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].text;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].text;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].text;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].text;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].text;
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)){
						get(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}
				}
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				///document.getElementById('sql2').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("num2")[0].text;
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
					document.getElementById('a32').value=myAjax.responseXML.documentElement.getElementsByTagName("name32")[0].textContent;						
			}
			
	}else{
		//	
	}
}
function diffChangeCompany2(k,length){
//alert(length)
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B16b'+k);
	 newInput2.id='diffC'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changCompany;//보험회사 변경
	 var opts=newInput2.options;
	
	opts.length=eval(length);
	//if(//myAjax.responseXML.documentElement.getElementsByTagName("Qnum"+k)[0].text!=''){
	var Qnum=(eval(myAjax.responseXML.documentElement.getElementsByTagName("Qnum"+k)[0].text));
	//}
//alert(Qnum)
	 for(var i=0;i<opts.length;i++){	
		 
		//alert( myAjax.responseXML.documentElement.getElementsByTagName("Ctnum"+i)[0].text)
		opts[i].value=myAjax.responseXML.documentElement.getElementsByTagName("Ctnum"+k+i)[0].text;
		//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("snum"+k+i)[0].text))
		//alert('보험회사'+myAjax.responseXML.documentElement.getElementsByTagName("inum"+k+i)[0].text)
		//alert(myAjax.responseXML.documentElement.getElementsByTagName("Qnum"+k)[0].text);
		if(opts[i].value==Qnum){

			//alert(i)
			newInput2.selectedIndex=i;

		}

				switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("inum"+k+i)[0].text)){
					default :
						opts[i].text='선택';
					  //  newInput2.selectedIndex='99999999';
						break;
					case 1 :
						opts[i].text='흥국';
					break;
					case 2 :
						opts[i].text='동부';
					break;
					case 3 :
						opts[i].text='LiG';
					break;
					case 4 :
						opts[i].text='현대';
					break;
					case 5 :
						opts[i].text='한화';
					break;
					case 6 :
						opts[i].text='더 케이';
					break;

					case 7 :
						opts[i].text='MG';
					break;
					case 7 :
						opts[i].text='삼성';
					break;
				}
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

}
function diffChangeCompany(k,length){

		var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B16b'+k);
	 newInput2.id='diffC'+k;
	 newInput2.style.width = '70px';
	 newInput2.className='selectbox';
	newInput2.onchange=changCompany;//보험회사 변경
	 var opts=newInput2.options;
	
	opts.length=length;
	
	//alert(length)
	 for(var i=0;i<opts.length;i++){	
		 
		//alert( myAjax.responseXML.documentElement.getElementsByTagName("Ctnum"+i)[0].text)
		opts[i].value=myAjax.responseXML.documentElement.getElementsByTagName("Ctnum"+i)[0].text;
		
	    if(opts[i].value==eval(99999999)){	
			newInput2.selectedIndex=i;
		}
	

				switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("inum"+i)[0].text)){
					default :
						opts[i].text='선택';
					  //  newInput2.selectedIndex='99999999';
						break;
					case 1 :
						opts[i].text='흥국';
					break;
					case 2 :
						opts[i].text='동부';
					break;
					case 3 :
						opts[i].text='LiG';
					break;
					case 4 :
						opts[i].text='현대';
					break;
					case 5 :
						opts[i].text='한화';
					break;
					case 6 :
						opts[i].text='더케이';
					break;
					case 7 :
						opts[i].text='MG';
					break;
					case 7 :
						opts[i].text='삼성';
					break;
				}
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

}


function changCompany(){

	this.value;//2012CertiTable 의 num
	var nn=this.getAttribute("id");
	//alert(nn)
	if(this.value.length==6){
		nn=nn.substr(5,6);
	}else{
		nn=nn.substr(5,7);
	}
	//alert(nn)

	var driverNum=document.getElementById("B0b"+nn).value;

	var toSend = "./ajax/changeCom.php?memberNum="+driverNum
									+"&CertiTableNum="+this.value; 
										
	//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=ceReceive;
				myAjax.send('');
	
}
function changeSangtae()
{
	myAjax=createAjax();
	var nn=this.getAttribute("id");
	//alert(nn)
	if(this.value.length==6){
		nn=nn.substr(5,6);
	}else{
		nn=nn.substr(5,7);
	}
	//alert(nn);
	var DaeriCompanyNum=document.getElementById("num").value;
	
	var memberNum=document.getElementById("B0b"+nn).value;
	var val=document.getElementById('sang'+nn).options[document.getElementById('sang'+nn).selectedIndex].value;//청약 또는 해지
	//alert(val);
//	var c_preminum=document.getElementById('B17b'+nn).innerHTML; //보험회사 보험료

	var c_preminum=document.getElementById('cPrem'+nn).value;
	//var num=document.getElementById("num").value;//2012DaeriCompanyNum;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var insuranceCompany=document.getElementById('a14').value;
	var eNum=document.getElementById("eNum").value;
	var certiNum=document.getElementById("jang"+nn).value;
	//alert(eval(insuranceCompany));
if(eval(insuranceCompany)==2){//동부화재 일때 만 
	//설계번호.증권번호 저장을위해

		
		var selNum=document.getElementById("kang"+nn).value;

		
		if(confirm('처리 합니다')){

			if(val==1){//정상일 경우만
		

				var sigi=document.getElementById('sigi'+nn).value;
				if(!sigi){
					alert('보험시기!!');
					document.getElementById("sigi"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}

				var jeonggi=document.getElementById('djeonggi'+nn).value;
				if(!jeonggi){
					alert('보험종기!!');
					document.getElementById("djeonggi"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}
				if(!certiNum){
					alert('증권번호!!');
					document.getElementById("jang"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}
				if(!selNum){
					alert('설계번호!!');
					document.getElementById("kang"+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}
				
				var cheriii=document.getElementById('cheriii'+nn).options[document.getElementById('cheriii'+nn).selectedIndex].value;//청약 또는 해지

				if(!cheriii){
					alert('납입회차!!');
					document.getElementById('cheriii'+nn).focus();
					document.getElementById("cheri"+nn).value=1;
					return false;
				}

			}

		if(memberNum){	
				

				if(val==1){

					var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum 
														 +"&insuranceCompany="+insuranceCompany
											//if(val==1){ 
														 +"&sigi="+sigi
														 +"&jeonggi="+jeonggi
														 +"&cheriii="+cheriii
													  // }
														 +"&certiNum="+certiNum
														 +"&selNum="+selNum
														 +"&eNum="+eNum
														 +"&nn="+nn
														 +"&c_preminum="+c_preminum
							
														 +"&val="+val	;



				}else{



				var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum 
														 +"&insuranceCompany="+insuranceCompany
														 +"&certiNum="+certiNum
														 +"&eNum="+eNum
														 +"&nn="+nn
														 +"&c_preminum="+c_preminum
														 +"&val="+val	;
				}
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=pushReceive;
				myAjax.send('');
			}
		}else{
			document.getElementById("cheri"+nn).value=1;
			return false;
		}
	}else{ //동부화재가 아닌경우 
		
		
		if(confirm('처리 합니다')){
			if(memberNum){	
				var toSend = "./ajax/endorseChange.php?memberNum="+memberNum
														 +"&DaeriCompanyNum="+DaeriCompanyNum
														 +"&CertiTableNum="+CertiTableNum
														 +"&insuranceCompany="+insuranceCompany
														 +"&certiNum="+certiNum
														 +"&eNum="+eNum
														 +"&nn="+nn
														 +"&c_preminum="+c_preminum
														 +"&val="+val	;
			//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=pushReceive;
				myAjax.send('');
			}
		}else{
			document.getElementById("cheri"+nn).value=1;
			return false;
		}
	}
}
function serch(val){
	
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

		var a14=document.getElementById('a14').value;
if(val){
	for(var k=0;k<9;k++){	
			document.getElementById('B0b'+k).value='';
			document.getElementById('B1b'+k).innerHTML=''
			document.getElementById('B2b'+k).innerHTML='';
			document.getElementById('B3b'+k).innerHTML='';
			document.getElementById('B4b'+k).innerHTML=''
			//document.getElementById('cNum'+k).value='';				
			//document.getElementById('iNum'+k).value='';				
			//document.getElementById('pNum'+k).value='';
			document.getElementById('B5b'+k).innerHTML=''
			document.getElementById('B6b'+k).innerHTML='';
			document.getElementById('B7b'+k).innerHTML=''
			document.getElementById('B8b'+k).value='';
			
			document.getElementById('B9b'+k).innerHTML='';
			document.getElementById('B10b'+k).innerHTML='';
			if(a14==2||a14==8){
			document.getElementById('B11b'+k).innerHTML='';				
			document.getElementById('B12b'+k).innerHTML='';
			document.getElementById('B13b'+k).innerHTML='';
			document.getElementById('B14b'+k).innerHTML='';
			document.getElementById('B15b'+k).innerHTML='';
			}
			
			document.getElementById('B16b'+k).innerHTML='';
			//pnum	
		}
	for(var k=0;k<10;k++){
		document.getElementById('C0b'+k).value='';
		document.getElementById('C1b'+k).innerHTML='';
		document.getElementById('C2b'+k).innerHTML='';
		document.getElementById('C3b'+k).innerHTML='';
		document.getElementById('C4b'+k).innerHTML='';
		document.getElementById('C5b'+k).innerHTML='';

	}
}
	myAjax=createAjax();

	var num=document.getElementById("num").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var DariMemberNum=document.getElementById("DariMemberNum").value;

	//var endorseDay=document.getElementById("endorseDay").value;

	if(num){	
		var toSend = "./ajax/claimAjaxSerch.php?num="+num
												 +"&CertiTableNum="+CertiTableNum
												 +"&DariMemberNum="+DariMemberNum
												// +"&endorseDay="+endorseDay
												 +"&page="+page;
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}
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












function memberEndorse(val){
	//alert(document.getElementById("num").value);
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('B0b'+val).value;
	var a4=document.getElementById('B4b'+val).value;
	var a2=document.getElementById('sang'+val).options[document.getElementById('sang'+val).selectedIndex].value;
	if(certiNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./MemberEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'ppss','left='+winl+',top='+wint+',resizable=yes,width=430,height=600,scrollbars=no,status=yes')
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}
	   

	
}
function changeEndorseDay(){
	var num=document.getElementById("num").value;
	
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var eNum=document.getElementById("eNum").value;
	var endorseDay=document.getElementById("endorseDay").value;
	if(eNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./php/EndorseDayChange.php?eNum='+eNum+'&CertiTableNum='+CertiTableNum+'&endorseDay='+endorseDay+'&num='+num,'ed','left='+winl+',top='+wint+',resizable=yes,width=600,height=400,scrollbars=no,status=yes')
	}

}

function dailyPreminum(){//보험회사별 일일보험료 
	
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	var a9=document.getElementById('a9').value;
	var a14=document.getElementById('a14').value;//InsuraneCompany
	var a7=document.getElementById('a7').value;//월납입일


	if(!a7){
		alert('월 납입일 부터 정하셔요!')
			return false;

	}


	//alert(a14);
	//alert('1');

	var emday=document.getElementById('endorseDay').innerHTML;
		 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
			// window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a2+'&policyNum='+a4,'cPre','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
			window.open('./NewPreminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9+'&emday='+emday,'12preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
		/*switch(eval(a14)){
			case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9+'&emday='+emday,'12preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 2 :
				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum2Dongbu.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9+'&emday='+emday,'12preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 3 :
				case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9+'&emday='+emday,'12preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;


				break;
			case 4:

				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./preminum.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&policyNum='+a9+'&emday='+emday,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
				break;
		}*/

}
function retirePrint(){//퇴직증명서 


	if(this.value.length==5){
		var nn=this.id.substr(4,5);
	}else{
		var nn=this.id.substr(4,6);
	}

	var driverNum=document.getElementById('B0b'+nn).value;
	 var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./print/retirePrint.php?driverNum='+driverNum,'2012retire','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
}

function EndorsePrint(){//보험회사별 배서프린트
	var userId=document.getElementById("userId").value;
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	var eNum=document.getElementById('eNum').value;
	var a9=document.getElementById('a9').value;
	var a14=document.getElementById('a14').value;

		switch(eval(a14)){
			case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 2 :
				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 3 :
				case 1 :
				 var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;


				break;
			case 4:

				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/endorsePrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
			case 7:

				var winl = (screen.width - 1024) / 2
				var wint = (screen.height - 768) / 2
				window.open('./print/MgEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+a14+'&userId='+userId+'&eNum='+eNum,'2012preminum','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
				break;
				break;
		}
}

function SanPrint(){//상품설명서 프린트
	var userId=document.getElementById("userId").value;
	var num=document.getElementById("num").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	var eNum=document.getElementById('eNum').value;
	var a9=document.getElementById('a9').value;
	var InsuraneCompany=document.getElementById('a14').value;

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./print/sangPrint.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+InsuraneCompany+'&userId='+userId+'&eNum='+eNum,'2012san','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
		
		
}
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
function enIn(){
		var val=document.getElementById('enNum').value;
		if(val.length==13){
		
		//alert(val.substring(5,12))
		document.getElementById('enNum').value=val.substring(5,13);
		}
}


function enInput(){

		var val=document.getElementById('enNum').value;
		if(val.length==8){

			document.getElementById('enNum').value='RQ'+document.getElementById('endorseDay').innerHTML.substring(2,4)+'-'+document.getElementById('enNum').value;
			var enNum=document.getElementById('enNum').value;//lig 배서번호
			var endorse_num=document.getElementById('eNum').value;
			var CertiTableNum=document.getElementById('CertiTableNum').value;
			var toSend = "./ajax/enNumInput.php?CertiTableNum="+CertiTableNum
				   +"&endorse_num="+endorse_num
				   +"&enNum="+enNum;
				  // +"&tableNum="+tableNum;
		myAjax=createAjax();//청약번호 저장을 하기위해
		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=changeEtagResponse;
		myAjax.send('');
		}else{

			alert('여덟자리!!')

				document.getElementById("enNum").focus();
				return false;
		}

}


function gilo(){
		var DariMemberNum=document.getElementById('DariMemberNum').value;
		var endorseDay=document.getElementById('endorseDay2').value;
		//var comment=encodeURIComponent(document.getElementById('comment2').value);
		var comment=document.getElementById('comment2').value;
		var jumin=document.getElementById('B3b0').innerHTML;
	//alert(	 DariMemberNum+'/'+endorseDay+'/'+comment);

		var send_url = "/_db/_sago.php";
	$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"sago",
						   DariMemberNum:DariMemberNum,
						   endorseDay:endorseDay,
						   comment:comment,
						   jumin:jumin
						  
						}
			}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

					
						//certi Table 조회 결과
							certiTableValue = new Array();
							
								
						
								certiTableValue.push( {	
										"message":$(this).find('message').text()
										//"DariMemberNum":$(this).find('DariMemberNum').text(),
										//"comment":$(this).find('comment').text(),
										//"jumin":$(this).find('jumin').text()

									} );
							
					
							//policy 리스트 화면에 표시하기 위해 */
						certi_max_num = certiTableValue.length;	
				/*		if(!certi_max_num){ alert('조회결과가 없습니다')}
						if(certiTableValue[0].main==3){ 
							certiTableValue[0].main=1;
							certi_max_num=0;
						}	*/
						 dp_certiList();
						//alert(certi_max_num);
		            });
						
				});
}

function dp_certiList(){


	for (var i=0;i<certi_max_num ; i++)
	{

		alert(certiTableValue[0].message);
	}
}