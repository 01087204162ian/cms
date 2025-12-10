function prem(id,val,sunso){
		
		myAjax=createAjax();
	//	alert(id+'/'+numberWithCommas2(val)+'/'+document.getElementById("daeriCompanyNum").value+'/'+document.getElementById("date").value+'/'+sunso);
	

	if(eval(val)>=1){
		if(confirm('보험료 입력합니다!!')){
				var val=numberWithCommas2(val);
				var date=document.getElementById("date").value;
				var DaeriCompanyNum=document.getElementById("daeriCompanyNum").value;	

				
				var toSend = "./ajax/kdriveEndorse.php?DaeriCompanyNum="+DaeriCompanyNum//
							+"&val="+val
							+"&id="+id
							+"&sunso="+sunso
							+"&date="+date;

							
			//alert(toSend);

			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=dailySucess;
			myAjax.send('');
		}else{

			 //document.getElementById("B2b"+sunso).focus();
		
			 return false;
		}
	}
}
function dailySucess(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
       //alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		
			
			document.getElementById('prem1_1').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem1_1")[0].text);
			document.getElementById('prem1_2').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem1_2")[0].text);
			document.getElementById('prem2_1').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem2_1")[0].text);
			document.getElementById('prem2_2').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem2_2")[0].text);
			document.getElementById('total_p').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("total_p")[0].text);

		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			document.getElementById('prem1_1').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem1_1")[0].textContent);
			document.getElementById('prem1_2').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem1_2")[0].textContent);
			document.getElementById('prem2_1').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem2_1")[0].textContent);
			document.getElementById('prem2_2').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("prem2_2")[0].textContent);
			document.getElementById('total_p').value=numberWithCommas(myAjax.responseXML.documentElement.getElementsByTagName("total_p")[0].textContent);


		}
			
			
	}else{
		//	
	}



}
//컴마 제거 함수
function numberWithCommas2(x) {
    var str = String(x);
    return str.replace(/[^\d]+/g, '');
}
//컴머 찍기
function numberWithCommas(x) {
	 var str = String(x);
    return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}