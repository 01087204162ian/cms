
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);

			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('InsuraneCompany').value=myAjax.responseXML.documentElement.getElementsByTagName("name2")[0].text;
				document.getElementById("policyNum").value=myAjax.responseXML.documentElement.getElementsByTagName("name3")[0].text;
				if(eval(document.getElementById('InsuraneCompany').value)>=1 && eval(document.getElementById('InsuraneCompany').value) <=8){
					
				   var pageD=document.getElementById("pCerti");
					pageD.innerHTML="<input type='button' value='excel' class='input' title='excel' onclick='toExcel()' />"
				}
				switch(eval(document.getElementById('InsuraneCompany').value))
				{
					case 1 :
						var InsuranceCompany="흥국";
					break;
					case 2 :
						var InsuranceCompany="DB";
					break;
					case 3 :
						var InsuranceCompany="KB";
					break;

					case 4 :
						var InsuranceCompany="현대";
					break;
					case 5 :
						var InsuranceCompany="한화";
					break;
					case 6 :
						var InsuranceCompany="더케이";
					break;
					case 7 :
						var InsuranceCompany="MG";
					break;
					case 8 :
						var InsuranceCompany="삼성";
					break;
					case 9 :
						var InsuranceCompany="메리츠";
					break;
					default:
						var InsuranceCompany="전체";	
					break;
				}										
				document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].text+document.getElementById("policyNum").value
					                                   +"<input type='button' value='가입증명서' class='input' title='excel' onclick='toCerti()' />";
				
				
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].text);
				for(var _m=0;_m<eval(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].text);_m++)
				{	
					var _n=_m+2;
						document.getElementById('A1'+_m).innerHTML=_m+1;
						document.getElementById('A2'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("me"+_n)[0].text;
						document.getElementById('A3'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sNum"+_n)[0].text;
						document.getElementById('A4'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("rNum"+_n)[0].text;
				}
				//var p=_m+1;
				document.getElementById('A2'+_m).innerHTML='계';
				document.getElementById('A3'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("pNum")[0].text;
				document.getElementById('A4'+_m).innerHTML='100%';
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('InsuraneCompany').value=myAjax.responseXML.documentElement.getElementsByTagName("name2")[0].textContent;
				document.getElementById("policyNum").value=myAjax.responseXML.documentElement.getElementsByTagName("name3")[0].textContent;
				if(eval(document.getElementById('InsuraneCompany').value)>=1 && eval(document.getElementById('InsuraneCompany').value) <=8){
					
				   var pageD=document.getElementById("pCerti");
					pageD.innerHTML="<input type='button' value='excel' class='input' title='excel' onclick='toExcel()' />"
				}
				switch(eval(document.getElementById('InsuraneCompany').value))
				{
					case 1 :
						var InsuranceCompany="흥국";
					break;
					case 2 :
						var InsuranceCompany="DB";
					break;
					case 3 :
						var InsuranceCompany="KB";
					break;

					case 4 :
						var InsuranceCompany="현대";
					break;
					case 5 :
						var InsuranceCompany="한화";
					break;
					case 6 :
						var InsuranceCompany="더케이";
					break;
					case 7 :
						var InsuranceCompany="MG";
					break;
					case 8 :
						var InsuranceCompany="삼성";
					break;
					case 9 :
						var InsuranceCompany="메리츠";
					break;
					default:
						var InsuranceCompany="전체";	
					break;
				}										
				document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].textContent+document.getElementById("policyNum").value
					+"<input type='button' value='가입증명서' class='input' title='excel' onclick='toCerti()' />";
				
				
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].textContent);
				for(var _m=0;_m<eval(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].textContent);_m++)
				{	
					var _n=_m+2;
						document.getElementById('A1'+_m).innerHTML=_m+1;
						document.getElementById('A2'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("me"+_n)[0].textContent;
						document.getElementById('A3'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sNum"+_n)[0].textContent;
						document.getElementById('A4'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("rNum"+_n)[0].textContent;
				}
				//var p=_m+1;
				document.getElementById('A2'+_m).innerHTML='계';
				document.getElementById('A3'+_m).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("pNum")[0].textContent;
				document.getElementById('A4'+_m).innerHTML='100%';				
			}
			
	}else{
		//	
	}
}
function serch(val){
	myAjax=createAjax();
	var num=document.getElementById("DaeriCompanyNum").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	////if(num){

	if(InsuraneCompany){CertiTableNum='';};
		var toSend = "./ajax/boardMemberListAjax.php?CertiTableNum="+CertiTableNum
											+"&InsuraneCompany="+InsuraneCompany
			                                 +"&DaeriCompanyNum="+num;
	
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	//}
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해

