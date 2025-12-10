function inputJuminCheck(id,val){

	if(val.length>1){

		 if (!check_juminno(val)){
				  document.getElementById(id).value='';
				  document.getElementById(id).focus();
				return false;
		}else{

			var phone_first	=val.substring(0,6)
			var phone_second=val.substring(6,14)
				document.getElementById(id).value=phone_first+"-"+phone_second;
		}
	}
	
}

function inputNameCheck(id,val){

	if (!f_chkNoNum(val)){
			alert("이름에 숫자는 포함될 수 없습니다.");
			 document.getElementById(id).focus();
			return false;
		}
	if(!stringcheck(val))
		{
			alert('올바른 이름을 입력해 주시기 바랍니다.');
			 document.getElementById(id).focus();
			return false;
		}	
}

function stringcheck(thisstring)
{
		var total = thisstring.length
		for(i=0;i<total;i++)
		{

			if(44032 > thisstring.charCodeAt(i) || 55203 < thisstring.charCodeAt(i))
			{
				return false;
			}
		}
		return true;
}
function f_chkNoNum(string) {
		valid = "0123456789";
		for (var i=0; i< string.length; i++) {
			if (valid.indexOf(string.charAt(i)) != -1) {
				return false;
			return false;
			}
		}
		return true;

	} 
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);

			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('InsuraneCompany').value=myAjax.responseXML.documentElement.getElementsByTagName("name2")[0].text;
				document.getElementById("policyNum").value=myAjax.responseXML.documentElement.getElementsByTagName("name3")[0].text;
			
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
				}

															
				document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+"["+document.getElementById("policyNum").value+"]"+myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].text;
				//운전자 추가 하기 위해 2016-03-13

				document.getElementById('a2b0').value=myAjax.responseXML.documentElement.getElementsByTagName("mName")[0].text;
				document.getElementById('a3b0').value=myAjax.responseXML.documentElement.getElementsByTagName("mJumin")[0].text;
				document.getElementById('a4b0').value=myAjax.responseXML.documentElement.getElementsByTagName("mHphone")[0].text;
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('InsuraneCompany').value=myAjax.responseXML.documentElement.getElementsByTagName("name2")[0].textContent;
				document.getElementById("policyNum").value=myAjax.responseXML.documentElement.getElementsByTagName("name3")[0].textContent;
			
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
				}

															
				document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+"["+document.getElementById("policyNum").value+"]"+myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].textContent;					


				//운전자 추가 하기 위해 2016-03-13

				document.getElementById('a2b0').value=myAjax.responseXML.documentElement.getElementsByTagName("mName")[0].textContent;
				document.getElementById('a3b0').value=myAjax.responseXML.documentElement.getElementsByTagName("mJumin")[0].textContent;
				document.getElementById('a4b0').value=myAjax.responseXML.documentElement.getElementsByTagName("mHphone")[0].textContent;
			}
			
	}else{
		//	
	}
}
function serch(val){
	myAjax=createAjax();
	var num=document.getElementById("DaeriCompanyNum").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;

	//var mNum=document.getElementById("mNum").value;
	//alert(document.getElementById("mNum").value)
	if(num){	

		
		var toSend = "./ajax/certiSerch.php?CertiTableNum="+CertiTableNum
			                                 +"&DaeriCompanyNum="+num;
											// +"&mNum="+mNum;
	
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function daeriMemberReceive()
{
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);

			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('test').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
				for(var j=0;j<15;j++){
					document.getElementById('a2b'+j).value='';
						document.getElementById('a3b'+j).value='';
						document.getElementById('a4b'+j).value='';
						document.getElementById('a6b'+j).value='';


				}
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				document.getElementById('test').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
				for(var j=0;j<15;j++){
					document.getElementById('a2b'+j).value='';
						document.getElementById('a3b'+j).value='';
						document.getElementById('a4b'+j).value='';
						document.getElementById('a6b'+j).value='';


				}				
			}
			
	}else{
		//	
	}


}
function store(val)
{
	//alert(val)
	
	myAjax=createAjax();
	var query='';
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	var insuranceNum=document.getElementById('InsuraneCompany').value;

	
	for(var j=0;j<15;j++){
			query+="a2b"+j;
			query+="=";
			query+=encodeURIComponent(document.getElementById('a2b'+j).value);
			query+="&";
			
			query+="a3b"+j;
			query+="=";
			query+=document.getElementById('a3b'+j).value;
			query+="&";
			query+="a4b"+j;
			query+="=";
			query+=document.getElementById('a4b'+j).value;
			query+="&";
			query+="a5b"+j;
			query+="=";
			query+=document.getElementById('a5b'+j).options[document.getElementById('a5b'+j).selectedIndex].value;
			query+="&";	
		/*	query+="a6b"+j;
			query+="=";
			query+=document.getElementById('a6b'+j).value;
			query+="&";	*/
		}
//한명이라도 입력이 되어야 하지 
if(document.getElementById('a2b0').value){
		if(val==1){//신규 입력일때 
			var toSend = "./ajax/insertMember.php?DaeriCompanyNum="+DaeriCompanyNum
						   +"&CertiTableNum="+CertiTableNum
						   +"&insuranceNum="+insuranceNum+"&"
							+query;

		}else{//배서로 신규 입력할때 배서기준일이 필요하군
			var userId=document.getElementById('userId').value;
			var endorseDay=document.getElementById('endorseDay').value;
			var policyNum=document.getElementById('policyNum').value;
			var toSend = "./ajax/insertMemberEndorse.php?DaeriCompanyNum="+DaeriCompanyNum
						   +"&CertiTableNum="+CertiTableNum
							+"&endorseDay="+endorseDay
							+"&policyNum="+policyNum
							+"&userId="+userId
						   +"&insuranceNum="+insuranceNum+"&"
							+query;

		}
			//alert(toSend);

			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=daeriMemberReceive;
			myAjax.send('');
 }else{

		alert('한명이라도 입력 후 1번 부터~~');
		return false;
 }
}

$(function(){
	$("#Excelup").click(function(){

		//alert('1')
		
		var CertiNum=$("#CertiTableNum").val();
		var Dnum=$("#DaeriCompanyNum").val();

		//alert(CertiNum+'/'+Dnum);
		var winl = (screen.width - 1024) / 2;
		var wint = (screen.height - 768) / 2;
		
		//window.open('./sjExceltotal.php?num='+num,'excelTo','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');

		window.open('./ExcelUpload.php?CertiNum='+CertiNum+'&Dnum='+Dnum,'excelUp','left='+winl+',top='+wint+',resizable=yes,width=900,height=270, scrollbars=yes,status=yes');

	});

});


function newInsert(){
	
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;//2012DaeriCompanyNum
	var certiNum=document.getElementById('CertiTableNum').value;
	//var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./newInseret.php?DaeriCompanyNum='+DaeriCompanyNum+'&CertiTableNum='+certiNum,'rlist','left='+winl+',top='+wint+',resizable=yes,width=400,height=600,scrollbars=no,status=yes')
	
}