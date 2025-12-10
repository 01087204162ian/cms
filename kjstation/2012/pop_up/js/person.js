function smsReceive() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
			//self.document.getElementById("kor_str").value = myAjax.responseText;

		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		
			}
	/*
		if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!=undefined){
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}*/
		
	}else{
		//	
	}
}

function seleSS(val,DaericompanyNum,memberNum){
	//alert(val)
	myAjax=createAjax();
		var mName=document.getElementById('pName'+val).value
		
		if(confirm(mName+'님을 관리자로 지정합니다!!')){

		
		var toSend = "./ajax/SSpresonalAjax.php?DaericompanyNum="+DaericompanyNum
				   +"&memberNum="+memberNum;
				   
		//alert(toSend);
		
		//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=smsReceive;
		myAjax.send('');
		}else{

			return false;
		}
}

function SSbankChange(DaericompanyNum,pBankNum,memberNum){

	myAjax=createAjax();
	if(confirm('관리계좌로 정합니다!!')){
		var toSend = "./ajax/bankAjax.php?DaericompanyNum="+DaericompanyNum
				   +"&pBankNum="+pBankNum
				   +"&memberNum="+memberNum;
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=smsReceive;
		myAjax.send('');
		
	}

}