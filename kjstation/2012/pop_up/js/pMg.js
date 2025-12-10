

function dongbu3Month(){

		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

			document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].text;
			for(var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].text;j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('B0b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sPreminum")[j].text;//시작나이
				document.getElementById('B1b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ePreminum")[j].text;//끝나이
				document.getElementById('B4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[j].text;//cPreminum 의 num
				document.getElementById('B2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("certi")[j].text;//증권번호
			}
		}else{
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].text;
			for(var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].textContent;j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('B0b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sPreminum")[j].textContent;//시작나이
				document.getElementById('B1b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ePreminum")[j].textContent;//끝나이
				document.getElementById('B4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[j].textContent;//cPreminum 의 num
				document.getElementById('B2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("certi")[j].textContent;//증권번호
			}
		}
			
		
	
	
		
	
	}



}

function pserch(){
	myAjax=createAjax();
	var DaeriCompanyNum=document.getElementById('DaeriCompanyNum').value;
	var CertiTableNum=document.getElementById('CertiTableNum').value;
	var InsuraneCompany=document.getElementById('InsuraneCompany').value;
	var toSend = "./ajax/pMgserch.php?CertiTableNum=" + CertiTableNum
			+"&InsuraneCompany="+InsuraneCompany
			+"&DaeriCompanyNum="+DaeriCompanyNum;
			
		
	//alert(toSend)
	//self.document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=dongbu3Month;
	myAjax.send('');
}

addLoadEvent(pserch);
function mCertiSucess(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
    // alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			
			
		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("preminum")[0].textContent);
			
			

		}
			
			
	}else{
		//	
	}



}
function certiCheck(id,val,sunso){
	
	var mnum=document.getElementById('B4b'+sunso).value;
	if(mnum>=1 && val){
		if(confirm('증권번호 입력합니다!!')){

				var toSend = "./ajax/MgCertiInput.php?mnum="+mnum
													+"&certi="+val;//CertiTableNum
							

							
			//alert(toSend);

			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=mCertiSucess;
			myAjax.send('');
		}else{

			 //document.getElementById("B2b"+sunso).focus();
		
			 return false;
		}
	}
}