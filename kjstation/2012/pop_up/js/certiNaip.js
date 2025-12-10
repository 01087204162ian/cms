
function sucessFunction() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('A1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[0].text;
				document.getElementById('A3a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].text;
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("Insurane")[0].text);
				if(myAjax.responseXML.documentElement.getElementsByTagName("Insurane")[0].text==7){
					if(myAjax.responseXML.documentElement.getElementsByTagName("moValue")[0].text==1){
							document.getElementById('moValue').value=myAjax.responseXML.documentElement.getElementsByTagName("moValue")[0].text;
						}else{

							document.getElementById('moValue').value=9999;
						}

						if(!myAjax.responseXML.documentElement.getElementsByTagName("moRate")[0].text){
							document.getElementById('moRate').value=9999;

						}else{
							document.getElementById('moRate').value=myAjax.responseXML.documentElement.getElementsByTagName("moRate")[0].text;
						}
				}
				for(var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].text;j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('B0b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("s"+j)[0].text;//시기
				document.getElementById('B1b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("e"+j)[0].text;//종기
				document.getElementById('B2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y"+j)[0].text;//년간
				document.getElementById('B3b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y1y"+j)[0].text;//1회
				document.getElementById('B4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y2y"+j)[0].text;//2회
				document.getElementById('B5b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y9y"+j)[0].text;//9회
				document.getElementById('B10b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y10y"+j)[0].text;//9회
				document.getElementById('B6b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("m"+j)[0].text;//월보험료

				document.getElementById('C'+j+'c').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s"+j)[0].text+'부터'
				+myAjax.responseXML.documentElement.getElementsByTagName("e"+j)[0].text;


				//period 값에 따라 for 문

				var sunso=j+2;
					//var sumP=eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text);
					for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text);_k++){
						
						document.getElementById('da'+0+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+_k)[0].text;
						document.getElementById('da'+1+'y'+_k).innerHTML=eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text)-_k;
						document.getElementById('da'+sunso+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+j+"f"+_k)[0].text;
						if(myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text==_k){

								document.getElementById('da'+0+'y'+_k).style.color='#0A8FC1';
								document.getElementById('da'+1+'y'+_k).style.color='#0A8FC1';
								document.getElementById('da'+sunso+'y'+_k).style.color='#0A8FC1';
						}
					}//날자및경과일수 표시하고
			}
		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);

				document.getElementById('A1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].textContent;
				document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[0].textContent;
				document.getElementById('A3a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].textContent;
	if(myAjax.responseXML.documentElement.getElementsByTagName("Insurane")[0].textContent==7){
				if(myAjax.responseXML.documentElement.getElementsByTagName("moValue")[0].textContent==1){
					document.getElementById('moValue').value=myAjax.responseXML.documentElement.getElementsByTagName("moValue")[0].textContent;
				}else{

					document.getElementById('moValue').value=9999;
				}

				if(!myAjax.responseXML.documentElement.getElementsByTagName("moRate")[0].textContent){
					document.getElementById('moRate').value=9999;

				}else{
					document.getElementById('moRate').value=myAjax.responseXML.documentElement.getElementsByTagName("moRate")[0].text;
				}
	}
				for(var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].textContent;j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('B0b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("s"+j)[0].textContent;//시기
				document.getElementById('B1b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("e"+j)[0].textContent;//종기
				document.getElementById('B2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y"+j)[0].textContent;//년간
				document.getElementById('B3b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y1y"+j)[0].textContent;//1회
				document.getElementById('B4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y2y"+j)[0].textContent;//2회
				document.getElementById('B5b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y9y"+j)[0].textContent;//9회
				document.getElementById('B10b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("y10y"+j)[0].textContent;//9회
				document.getElementById('B6b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("m"+j)[0].textContent;//월보험료

				document.getElementById('C'+j+'c').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s"+j)[0].textContent+'부터'
				+myAjax.responseXML.documentElement.getElementsByTagName("e"+j)[0].textContent;


				//period 값에 따라 for 문

				var sunso=j+2;
					//var sumP=eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent);
					for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent);_k++){
						
						document.getElementById('da'+0+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+_k)[0].textContent;
						document.getElementById('da'+1+'y'+_k).innerHTML=eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent)-_k;
						document.getElementById('da'+sunso+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+j+"f"+_k)[0].textContent;
						if(myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].textContent==_k){

								document.getElementById('da'+0+'y'+_k).style.color='#0A8FC1';
								document.getElementById('da'+1+'y'+_k).style.color='#0A8FC1';
								document.getElementById('da'+sunso+'y'+_k).style.color='#0A8FC1';
						}
					}//날자및경과일수 표시하고
			}
		}
			
			
	}else{
		//	
	}
}

function serch(val){
	
	myAjax=createAjax();
	var num=document.getElementById("A20a").value; ////2012Certi의 num
	//alert('1')

	
			var toSend = "./ajax/certiNaiSerch.php?num="+num//CertiTableNum
						//+"&InsuraneCompany="+InsuraneCompany
						//+"&DaeriCompanyNum="+DaeriCompanyNum;
						
		
			//alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=sucessFunction;
			myAjax.send('');
			

	
}
addLoadEvent(serch);
function naiSucess(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
       //alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

				//document.getElementById('A1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				//document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[0].text;

		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);


		}
			
			
	}else{
		//	
	}



}
function naiCheck(id,val,sunso){


	//alert(id+'/'+value+'/'+sunso)

	if(!val){
		if(confirm('나이구분을 확정합니다!!')){
			var num=document.getElementById("CertiTableNum").value;
			var InsuraneCompanympany=document.getElementById("InsuraneCompany").value;
			var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
		var query='';
		
		sunso=eval(sunso)+1;
		for(j=0;j<sunso;j++){
			query+="s"+j;
			query+="=";
			query+=document.getElementById('B0b'+j).value;
			query+="&";
			query+="e"+j;
			query+="=";
			query+=document.getElementById('B1b'+j).value;
			query+="&";
			
		}
			var toSend = "./ajax/naiInput.php?CertiTableNum="+num//CertiTableNum
						+"&InsuraneCompany="+InsuraneCompanympany
						+"&DaeriCompanyNum="+DaeriCompanyNum
						+"&sunso="+sunso+"&"
					    +query;
		//seralert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=naiSucess;
		myAjax.send('');
		//document.getElementById("B1b"+sunso).focus();
		//return false;
		}
	}else{
		var daum=sunso+1;
	  document.getElementById("B0b"+daum).value=eval(val)+1;
	  document.getElementById("B1b"+daum).focus();
	}
}
function yearSucess(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
       //alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text;
			var sunso=eval(sunso)
			document.getElementById('B2b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].text;
			document.getElementById('B3b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum1")[0].text;
			document.getElementById('B4b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum2")[0].text;
			document.getElementById('B5b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum9")[0].text;
			document.getElementById('B10b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum10")[0].text;
				//document.getElementById('A1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				//document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[0].text;

		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].textContent);
			var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].textContent
			var sunso=eval(sunso)
			document.getElementById('B2b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].textContent;
			document.getElementById('B3b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum1")[0].textContent;
			document.getElementById('B4b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum2")[0].textContent;
			document.getElementById('B5b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum9")[0].textContent;
			document.getElementById('B10b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum10")[0].textContent;


		}
			
			
	}else{
		//	
	}



}
function yearCheck(id,val,sunso){
		
	if(!document.getElementById('B0b'+sunso).value && document.getElementById('B1b'+sunso).value){

		alert('년령부터 입력하셔야 합니다');

		return false;
	}

	if(val>=1){
		if(confirm('보험료 입력합니다!!')){
			document.getElementById('B3b'+sunso).value='';
			document.getElementById('B4b'+sunso).value='';
			document.getElementById('B5b'+sunso).value='';
			document.getElementById('B10b'+sunso).value='';
				var num=document.getElementById("CertiTableNum").value;
				var InsuraneCompany=document.getElementById("InsuraneCompany").value;
				var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	

				var sP=document.getElementById('B0b'+sunso).value;
				var eP=document.getElementById('B1b'+sunso).value;
				var toSend = "./ajax/yearInput.php?CertiTableNum="+num//CertiTableNum
							+"&InsuraneCompany="+InsuraneCompany
							+"&DaeriCompanyNum="+DaeriCompanyNum
							+"&preminum="+val
							+"&sunso="+sunso
							+"&sP="+sP
							+"&eP="+eP;

							
			//alert(toSend);

			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=yearSucess;
			myAjax.send('');
		}else{

			 //document.getElementById("B2b"+sunso).focus();
		
			 return false;
		}
	}
}
function monthSucess(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
      // alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

			//alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			
			var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text
			var sunso=eval(sunso)
			document.getElementById('B6b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].text;
				//나이구분을 표시하여 주고
			document.getElementById('C'+sunso+'c').innerHTML=document.getElementById('B0b'+sunso).value+'부터'
			+document.getElementById('B1b'+sunso).value;

			//일일보험료를 입력하기위해 

			//alert(myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text);

			sunso+=2;
			//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text));
			for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text);_k++){
			//	alert(_k);
				document.getElementById('da'+0+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+_k)[0].text;
				document.getElementById('da'+1+'y'+_k).innerHTML=eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text)-_k;
				document.getElementById('da'+sunso+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+_k)[0].text;
				if(myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text==_k){

						document.getElementById('da'+0+'y'+_k).style.color='#0A8FC1';
						document.getElementById('da'+1+'y'+_k).style.color='#0A8FC1';
						document.getElementById('da'+sunso+'y'+_k).style.color='#0A8FC1';
				}
			}


		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			
			var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].textContent
			var sunso=eval(sunso)
			document.getElementById('B6b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].textContent;
				//나이구분을 표시하여 주고
			document.getElementById('C'+sunso+'c').innerHTML=document.getElementById('B0b'+sunso).value+'부터'
			+document.getElementById('B1b'+sunso).value;

			//일일보험료를 입력하기위해 

			//alert(myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].textContent);

			sunso+=2;
			//var sumP=eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent);
			for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent);_k++){
				
				document.getElementById('da'+0+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+_k)[0].textContent;
				document.getElementById('da'+1+'y'+_k).innerHTML=eval(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent)-_k;
				document.getElementById('da'+sunso+'y'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+_k)[0].textContent;
				if(myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].textContent==_k){

						document.getElementById('da'+0+'y'+_k).style.color='#0A8FC1';
						document.getElementById('da'+1+'y'+_k).style.color='#0A8FC1';
						document.getElementById('da'+sunso+'y'+_k).style.color='#0A8FC1';
				}
			}

		}
			
			
	}else{
		//	
	}

}
function monthCheck(id,val,sunso){
		
	if(!document.getElementById('B0b'+sunso).value && !document.getElementById('B1b'+sunso).value){

		alert('년령부터 입력하셔야 합니다');

		return false;
	}
	
	
	if(confirm('월보험료 입력합니다!!')){
			var daum=sunso+2;
			for(var _j=0;_j<31;_j++){
				document.getElementById('da'+0+'y'+_j).innerHTML='';	
				document.getElementById('da'+1+'y'+_j).innerHTML='';
				document.getElementById('da'+daum+'y'+_j).innerHTML='';
			}
			var num=document.getElementById("CertiTableNum").value;
			var InsuraneCompany=document.getElementById("InsuraneCompany").value;
			var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	

			var sP=document.getElementById('B0b'+sunso).value;
			var eP=document.getElementById('B1b'+sunso).value;
			var toSend = "./ajax/monthInput.php?CertiTableNum="+num//CertiTableNum
						+"&InsuraneCompany="+InsuraneCompany
						+"&DaeriCompanyNum="+DaeriCompanyNum
						+"&preminum="+val
						+"&sunso="+sunso
						+"&sP="+sP
						+"&eP="+eP;			    
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=monthSucess;
		myAjax.send('');
	}else{

		 //document.getElementById("B2b"+sunso).focus();
	
		 return false;
	}

}
function moSucess(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
      // alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

				//document.getElementById('A1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				//document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[0].text;

		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);


		}
			
			
	}else{
		//	
	}



}
function moChange(certiNum,value){
	//alert(value);
	myAjax=createAjax();
	var toSend = "./ajax/moChange.php?CertiTableNum="+certiNum//CertiTableNum
										+"&moValue="+value;		    
	//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=moSucess;
		myAjax.send('');
}
function moRateSucess(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
      // alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

				//document.getElementById('A1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				//document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[0].text;

		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);


		}
			
			
	}else{
		//	
	}



}
function moRateChange(certiNum,value){
	//alert(value);
	myAjax=createAjax();
	var moValue=document.getElementById('moValue').options[document.getElementById('moValue').selectedIndex].value;
	if(moValue==1){

	var toSend = "./ajax/moRateChange.php?CertiTableNum="+certiNum//CertiTableNum
										+"&moRate="+value;		    
	//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=moRateSucess;
		myAjax.send('');
	}else{

		alert('모계약일때만 가능합니다')
		return false;

	}
}
function dongbuOpen(num,certiNum,InsuraneCompany){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./preminum2Dongbu.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+InsuraneCompany,'dp','left='+winl+',top='+wint+',resizable=yes,width=900,height=500,scrollbars=no,status=yes')

}
function mgOpen(num,certiNum,InsuraneCompany){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./mgcertiInput.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+InsuraneCompany,'mgp','left='+winl+',top='+wint+',resizable=yes,width=500,height=500,scrollbars=no,status=yes')

}

function mg2Open(num,certiNum,InsuraneCompany){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('./mgcertiJeongLi.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+InsuraneCompany,'mgp2','left='+winl+',top='+wint+',resizable=yes,width=800,height=710,scrollbars=no,status=yes')

}
