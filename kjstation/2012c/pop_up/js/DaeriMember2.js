
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
//alert(myAjax.responseText);

			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
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


				document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].text;
				var notJumin=myAjax.responseXML.documentElement.getElementsByTagName("name15")[0].text;
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].text);
				for(var j=0;j<eval(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].text);j++){
					document.getElementById('a1b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("certiNum"+j)[0].text;
					document.getElementById('a2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("policyNum"+j)[0].text;
					document.getElementById('a5b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("divi"+j)[0].text;
					document.getElementById('a3b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("startyDay"+j)[0].text;

					document.getElementById('a6b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("gita"+j)[0].text;
					document.getElementById('a7b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("gitaName"+j)[0].text;

					sele(j,myAjax.responseXML.documentElement.getElementsByTagName("certiNum"+j)[0].text,notJumin);//상태를 만들기 위해
				//	document.getElementById('a4b'+j).value='';

				}
				
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
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
					case 7 :
						var InsuranceCompany="삼성";
					break;
				}


				document.getElementById('company').innerHTML="["+InsuranceCompany+"]"+myAjax.responseXML.documentElement.getElementsByTagName("name1")[0].textContent;
				var notJumin=myAjax.responseXML.documentElement.getElementsByTagName("name15")[0].textContent;
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].text);
				for(var j=0;j<eval(myAjax.responseXML.documentElement.getElementsByTagName("Rnum")[0].textContent);j++){
					document.getElementById('a1b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("certiNum"+j)[0].textContent;
					document.getElementById('a2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("policyNum"+j)[0].textContent;
					document.getElementById('a3b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("startyDay"+j)[0].textContent;
					document.getElementById('a5b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("divi"+j)[0].textContent;

					document.getElementById('a6b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("gita"+j)[0].textContent;
					document.getElementById('a7b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("gitaName"+j)[0].textContent;

					sele(j,myAjax.responseXML.documentElement.getElementsByTagName("certiNum"+j)[0].textContent,notJumin);//상태를 만들기 위해
				//	document.getElementById('a4b'+j).value='';

				}						
			}
			
	}else{
		//	
	}
}
function serch(val){
	myAjax=createAjax();
	var num=document.getElementById("DaeriCompanyNum").value;
	var com=document.getElementById("InsuraneCompany").value;
	if(num){	
		var toSend = "./ajax/popAjaxSerch2.php?num="+num
				      +"&com="+com;
//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해

function sele(k,certiNum,notJumin){

	var aButton=document.getElementById('a4b'+k);
	 aButton.className='input2';
	 aButton.value=certiNum;
	 aButton.style.cursor='hand';
	 aButton.style.color='orange';
	 aButton.style.width = '40px';
	 aButton.innerHTML='신규신청'
	 if(notJumin==1){
		aButton.onclick=DaeriMemberCompany2;
	 }else{
		aButton.onclick=DaeriMemberCompany;
	 }

}

function DaeriMemberCompany(){
	//alert('1');
	var nn=this.getAttribute("id");
	if(nn.length==4){
		nn=nn.substr(3,4);
	}else{
		nn=nn.substr(3,5);
	}
	var certi=document.getElementById('a4b'+nn).value;
	var gita=document.getElementById('a6b'+nn).value
	var cNum=document.getElementById('DaeriCompanyNum').value;
	var com=document.getElementById('InsuraneCompany').value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
    window.open('../pop_up/MemberEndorse.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com+'&CertiTableNum='+certi+'&gita='+gita,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
}

function DaeriMemberCompany2(){
    ///alert('2');
	var nn=this.getAttribute("id");
	if(nn.length==4){
		nn=nn.substr(3,4);
	}else{
		nn=nn.substr(3,5);
	}
	var certi=document.getElementById('a4b'+nn).value;
	var gita=document.getElementById('a6b'+nn).value
	var cNum=document.getElementById('DaeriCompanyNum').value;
	var com=document.getElementById('InsuraneCompany').value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
    window.open('../pop_up/MemberEndorse2.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com+'&CertiTableNum='+certi+'&gita='+gita,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
}



function preminum(i){

	if(document.getElementById('a5b'+i).innerHTML=='정상' && document.getElementById('InsuraneCompany').value==2){

			//alert(document.getElementById('a4b'+i).value+'/'+document.getElementById('a5b'+i).innerHTML);
			var certi=document.getElementById('a4b'+i).value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
    window.open('../pop_up/dongbupreminum.php?CertiTableNum='+certi,'preminum','left='+winl+',top='+wint+',resizable=yes,width=730,height=650,scrollbars=no,status=yes')
	}

}