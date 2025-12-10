/* 2015-07-11 대리운전회사에서 운전자 추가 할 수 없게 */
	function controloun(){

		
			//	$("#sjo").children().remove();
				var send_url = "/2012/pop_up/ajax/controloun.php";
			//	var d_ate=$("#d_ate").val();
			//}
		//alert(send_url+d_ate)
			   var CertiTableNum=$('#CertiTableNum').val();
				var control=$('#control').val();
			 $.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"controlOun",CertiTableNum:CertiTableNum,control:control }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						
							certiTableValue = new Array();
							$(xml).find('item').each(function() {
								
						
								certiTableValue.push( {	"divi":$(this).find('divi').text()
										

									} );
							});//certi Table 조회 결과
					
							
						maxT = certiTableValue.length;	
						 dp_certiList();
						
		            });
						
				});
}

function dp_certiList(){
	 for(var i=0; i<maxT; i++)
        {

			//alert(certiTableValue[0].divi);
			alert('처리함')

		}

}

function Receive(){

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
function companyReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);		
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				
				document.getElementById('B0b').value=myAjax.responseXML.documentElement.getElementsByTagName("iComNum")[0].text;
				document.getElementById('B1b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].text;
				document.getElementById('B2b').value=myAjax.responseXML.documentElement.getElementsByTagName("Sname")[0].text;
				document.getElementById('B3b').value=myAjax.responseXML.documentElement.getElementsByTagName("Sjumin")[0].text;
			    document.getElementById('B4b').value=myAjax.responseXML.documentElement.getElementsByTagName("iCom")[0].text;
				document.getElementById('B5b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("pNum")[0].text;
				if(myAjax.responseXML.documentElement.getElementsByTagName("pNum")[0].text){

				  ShinButton(myAjax.responseXML.documentElement.getElementsByTagName("B6b")[0].text);
				}
				document.getElementById('C1b').value=myAjax.responseXML.documentElement.getElementsByTagName("sDay")[0].text;
				document.getElementById('C3b').value=myAjax.responseXML.documentElement.getElementsByTagName("vbank")[0].text;
				document.getElementById('C4b').value=myAjax.responseXML.documentElement.getElementsByTagName("v_number")[0].text;


				document.getElementById('D1b').value=myAjax.responseXML.documentElement.getElementsByTagName("D1")[0].text;
				document.getElementById('D2b').value=myAjax.responseXML.documentElement.getElementsByTagName("D2")[0].text;
				document.getElementById('D3b').value=myAjax.responseXML.documentElement.getElementsByTagName("D3")[0].text;
				document.getElementById('D4b').value=myAjax.responseXML.documentElement.getElementsByTagName("D4")[0].text;

				document.getElementById('control').value=myAjax.responseXML.documentElement.getElementsByTagName("control")[0].text;
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('B0b').value=myAjax.responseXML.documentElement.getElementsByTagName("iComNum")[0].textContent;
				document.getElementById('B1b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].textContent;
				document.getElementById('B2b').value=myAjax.responseXML.documentElement.getElementsByTagName("Sname")[0].textContent;
				document.getElementById('B3b').value=myAjax.responseXML.documentElement.getElementsByTagName("Sjumin")[0].textContent;
			    document.getElementById('B4b').value=myAjax.responseXML.documentElement.getElementsByTagName("iCom")[0].textContent;
				document.getElementById('B5b').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("pNum")[0].textContent;
				if(myAjax.responseXML.documentElement.getElementsByTagName("pNum")[0].textContent){

				  ShinButton(myAjax.responseXML.documentElement.getElementsByTagName("B6b")[0].textContent);
				}
				document.getElementById('C1b').value=myAjax.responseXML.documentElement.getElementsByTagName("sDay")[0].textContent;
				document.getElementById('C3b').value=myAjax.responseXML.documentElement.getElementsByTagName("vbank")[0].textContent;
				document.getElementById('C4b').value=myAjax.responseXML.documentElement.getElementsByTagName("v_number")[0].textContent;


				document.getElementById('D1b').value=myAjax.responseXML.documentElement.getElementsByTagName("D1")[0].textContent;
				document.getElementById('D2b').value=myAjax.responseXML.documentElement.getElementsByTagName("D2")[0].textContent;
				document.getElementById('D3b').value=myAjax.responseXML.documentElement.getElementsByTagName("D3")[0].textContent;
				document.getElementById('D4b').value=myAjax.responseXML.documentElement.getElementsByTagName("D4")[0].textContent;

				document.getElementById('control').value=myAjax.responseXML.documentElement.getElementsByTagName("control")[0].textContent;	
			}
			
	}else{
		//	
	}


}
function ShinButton(B6b){
	
	 var bButton=document.getElementById('sjButton');
		 bButton.className='input6';
		 //aButton.value=val1;
		// bButton.id='ch'+k;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=B6b;
		 bButton.onclick=vStore;

}
function serch(val){
	myAjax=createAjax();
	var DaeriCompanyNum=document.getElementById('DaeriCompanyNum').value;
	var CertiTableNum=document.getElementById('CertiTableNum').value;
	var toSend = "./ajax/vankSerch.php?DaeriCompanyNum="+DaeriCompanyNum
												 +"&CertiTableNum="+CertiTableNum;
		//alert(toSend)			 
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');

	
}

	addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해


function vStore(){
	myAjax=createAjax();
	var DaeriCompanyNum=document.getElementById('DaeriCompanyNum').value;
	var CertiTableNum=document.getElementById('CertiTableNum').value;
	
	var B2b=encodeURIComponent(document.getElementById('B2b').value);//계약자

	var B0b=document.getElementById('B0b').value;	//보험회사
	if(!B2b){
		alert('계약자성명');
			document.getElementById('B2b').focus();
			return false;
	}
	var B3b=document.getElementById('B3b').value;	//주민번호
	if(!B3b){
		alert('주민번호');
			document.getElementById('B3b').focus();
			return false;
	}
	var B5b=document.getElementById('B5b').innerHTML;	//증권번호
	if(!B3b){
		alert('증권번호가 없으면 ');
			document.getElementById('B5b').focus();
			return false;
	}
	
	var C3b=encodeURIComponent(document.getElementById('C3b').value);//가상은행
	var C4b=document.getElementById('C4b').value;//가상계좌
	
	var D1b=encodeURIComponent(document.getElementById('D1b').value);//카드1
	var D2b=encodeURIComponent(document.getElementById('D2b').value);//카드2
	var D3b=encodeURIComponent(document.getElementById('D3b').value);//카드3
	var D4b=encodeURIComponent(document.getElementById('D4b').value);//카드4
	
      var toSend = "./ajax/vankStore.php?DaeriCompanyNum="+DaeriCompanyNum
							 +"&CertiTableNum="+CertiTableNum
							 +"&B0b="+B0b
							 +"&B2b="+B2b
							 +"&B3b="+B3b
							 +"&B5b="+B5b
						     +"&C3b="+C3b
							 +"&C4b="+C4b
							 +"&D1b="+D1b
							 +"&D2b="+D2b
							 +"&D3b="+D3b
							 +"&D4b="+D4b
					   
		//alert(toSend);

		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=Receive;
		myAjax.send('');
}
function jumiN_check(id,val){
	
	if(eval(val.length)==13){
			var phone_first	=val.substring(0,6)
			var phone_second =val.substring(6,13)
			document.getElementById(id).value=phone_first+"-"+phone_second;
			var jumin=phone_first+"-"+phone_second
	}
}
function jumiN_check_2(id,val){
	
	    if(val.length=='14'){
			
			var phone_first	=val.substring(0,6)
			var phone_second=val.substring(7,14)
			

			document.getElementById(id).value=phone_first+phone_second;
	 }
}