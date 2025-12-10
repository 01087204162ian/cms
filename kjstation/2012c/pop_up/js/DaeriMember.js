function inputJuminCheck(id,val){

	
	if(id.length==4){
		var nn=id.substr(3,4);
	}else{
		var nn=id.substr(3,5);
	}
	if(!document.getElementById('a2b'+nn).value){

		alert(eval(nn)+1+'번째 이름 입력하세요 ')
	    document.getElementById('a2b'+nn).focus();
		return false;


	}
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
				
				switch(eval(document.getElementById('InsuraneCompany').value))
				{
					case 1 :
						var InsuranceCompany="흥국";
					break;
					case 2 :
						var InsuranceCompany="동부";
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
				if(document.getElementById('InsuraneCompany').value!=2){//동부화재가 아닌 경우에만
					document.getElementById('policyNum').value=myAjax.responseXML.documentElement.getElementsByTagName("policyNum0")[0].text;
					document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+"["+myAjax.responseXML.documentElement.getElementsByTagName("policyNum0")[0].text+"]";
				}else{
					//document.getElementById('CertiTableNum').value=myAjax.responseXML.documentElement.getElementsByTagName("certiNum0")[0].text;
					document.getElementById('company').innerHTML="["+InsuranceCompany+"]";
				}
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			}else{
				switch(eval(document.getElementById('InsuraneCompany').value))
				{
					case 1 :
						var InsuranceCompany="흥국";
					break;
					case 2 :
						var InsuranceCompany="동부";
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

				document.getElementById('policyNum').value=myAjax.responseXML.documentElement.getElementsByTagName("policyNum0")[0].textContent;
				document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+"["+myAjax.responseXML.documentElement.getElementsByTagName("policyNum0")[0].textContent+"]";
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
									
			}
			
	}else{
		//	
	}
}
function serch(val){
	//aerch('1')
	myAjax=createAjax();
	var num=document.getElementById("DaeriCompanyNum").value;
	var com=document.getElementById("InsuraneCompany").value;
	var CertiTableNum=document.getElementById("CertiTableNum").value;
	if(num){	
		var toSend = "./ajax/popAjaxSerch.php?num="+num
					  +"&CertiTableNum="+CertiTableNum
				      +"&com="+com;
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
				//document.getElementById('test').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
				for(var j=0;j<15;j++){
					document.getElementById('a2b'+j).value='';
						document.getElementById('a3b'+j).value='';
						document.getElementById('a4b'+j).value='';

						document.getElementById('a6b'+j).value=-1;
						document.getElementById('a7b'+j).value=-1;
						document.getElementById('a8b'+j).value='';

				}
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
				for(var j=0;j<15;j++){
					document.getElementById('a2b'+j).value='';
					document.getElementById('a3b'+j).value='';
					document.getElementById('a4b'+j).value='';
					document.getElementById('a6b'+j).value=-1;
					document.getElementById('a7b'+j).value=-1;
					document.getElementById('a8b'+j).value='';

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

			//alert($('#a6b'+j).val());
	
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

			query+="a5b"+j; //
			query+="=";
			//query+=document.getElementById('a5b'+j).options[document.getElementById('a5b'+j).selectedIndex].value
			query+=document.getElementById('a5b'+j).value
			query+="&";	

			query+="a6b"+j; //통신사
			query+="=";
			query+=document.getElementById('a6b'+j).options[document.getElementById('a6b'+j).selectedIndex].value
			query+="&";

			query+="a7b"+j; //명의
			query+="=";
			query+=document.getElementById('a7b'+j).options[document.getElementById('a7b'+j).selectedIndex].value
			query+="&";

			query+="a8b"+j; //주소
			query+="=";
			//query+=document.getElementById('a5b'+j).options[document.getElementById('a5b'+j).selectedIndex].value
			query+=encodeURIComponent(document.getElementById('a8b'+j).value)
			query+="&";	
		}
	//if(!document.getElementById('a2b0').value || !document.getElementById('a3b0').value || 
	//	!document.getElementById('a4b0').value )

	for(var j=0;j<15;j++){  //핸드폰 입력을 확인하기 위해

		//셩명이 입력 되어 있다면
		if(document.getElementById('a2b'+j).value){
				
				if(!document.getElementById('a4b'+j).value){
						alert('핸드폰 번호를 반드시 입력하셔야 합니다');
						document.getElementById('a4b'+j).focus();
						return false;
				}
				if(document.getElementById('a6b'+j).options[document.getElementById('a6b'+j).selectedIndex].value<0){
						alert('통신사를 선택하세요');
						document.getElementById('a6b'+j).focus();
						return false;

				}
				if(document.getElementById('a7b'+j).options[document.getElementById('a7b'+j).selectedIndex].value<0){
						alert('핸드폰 명의자를  선택하세요');
						document.getElementById('a7b'+j).focus();
						return false;

				}

				if(!document.getElementById('a8b'+j).value){
						alert('주소를 입력하세요');
						document.getElementById('a8b'+j).focus();
						return false;

				}
		}

	}
	if(!document.getElementById('a2b0').value || !document.getElementById('a3b0').value )
	{

		alert('1명 이상 입력후 !!');
		return false;

	}
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
}

