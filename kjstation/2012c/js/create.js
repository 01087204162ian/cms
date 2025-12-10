function createAjax() {
	  if(typeof(ActiveXObject) == "function") {
	    return new ActiveXObject("Microsoft.XMLHTTP");  
	  }  
	  else if(typeof(XMLHttpRequest) == "object" || typeof(XMLHttpRequest) == "function") {
	    return new XMLHttpRequest();
	  }  
	  else {
	    self.alert("브라우저가 AJAX를 지원하지 않습니다.");
	    return null;
	  }
	} 
function displayLoading(element) { 
    while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
    var image = document.createElement("img");
    image.setAttribute("src","/kibs_admin/ssangyoung/loading.gif");
    image.setAttribute("alt","Loading...");
    element.appendChild(image);
}

function addLoadEvent(func){
	
	var oldonload=window.onload;
	if(typeof window.onload !='function'){
		window.onload=func;
	}else{
		window.onload=function(){
			oldonload();
			func();
		}
		
	}
}

function stripeTables(){
	
	if(!document.getElementsByTagName) return false;
	var tables=document.getElementsByTagName("table");
	//	var tables=document.getElementById("serchTable").value;
	for(var i=0;i<tables.length;i++){
		
		var odd=false;
		var rows=tables[i].getElementsByTagName("tr");
		for(var j=0;j<rows.length;j++){
			var columns=rows[j].getElementsByTagName("td");
			
		/*	for(var k=0;k<columns.length;k++){
				columns[0].onclick=function(){
					columns[0].style.cursor='hand';
				}
			}*/
		
			if(odd==true){
				
				rows[j].style.backgroundColor="#ffffff";
				odd=false;
				

			}else{
				//rows[j].style.backgroundColor="#f9f8f0";
				if(j==0){

					rows[j].style.backgroundColor="#e9f8c0";
				    odd=true;
				}else{

				rows[j].style.backgroundColor="#f9f8f0";
				odd=true;
				}

			}
		}

		//var thd=tables[i].getElementsByTagName("thead");
		//alert( i+'번'+thd.length);
	}
}
addLoadEvent(stripeTables);

function LogOut(){

	if(confirm("로그아웃 합니다!!")){
				//window.open('','bogan','width=400,height=100,top=600,left=600')
		document.forms[0].target = '_self'
		document.forms[0].action = '../../2012c/lib/proc_out.php'
		document.forms[0].submit();
	}
}
function popGeneral(){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		
		
		window.open('../pop_up/DaeriCompany.php','idmake','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')

}

//Exceel() 
//2015-09-27 처음 작성건
/*
function Exceel(cNum){ //eslbs 용

		var winl = (screen.width - 1024) / 2
	    var wint = (screen.height - 768) / 2
		//alert(cNum);
		
		window.open('../pop_up/excelmember.php?cNum='+cNum,'exmemb','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')

}*/
function Exceel(cNum){ //eslbs 용

		var winl = (screen.width - 1024) / 2
	    var wint = (screen.height - 768) / 2
		//alert(cNum);

		var start=document.getElementById("sigi2").value;
		var daum=document.getElementById("end2").value;

		//alert(sigi2+'/'+end2);
		//window.open('../pop_up/excelmember2.php?cNum='+cNum+'&end2='+daum+'&sigi2='+start,'exmemb','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')
		window.open('../pop_up/excelmember2.php?cNum='+cNum+'&end2='+daum+'&start='+start,'exmemb','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')
}
function pageList(){/*페이지 버튼 만들기*/

	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
		var page=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text;
		var Total=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		var totalPage=myAjax.responseXML.documentElement.getElementsByTagName("totalpage")[0].text;
		document.getElementById("page").value=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].text;
		var pageD=document.getElementById("sql3");
		/************************************************************************/
		/* 전체 페이지 1인 경우와 아닌경우
		/* 첫번재 페이지 인경와 마직막 페이지인 경우 그 가운데인 경우            */
		/*************************************************************************/
		if(totalPage==1){
			pageD.innerHTML=page+"/"+totalPage;
		}else{
			if(page==1){
				pageD.innerHTML=page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}else if(page==totalPage){
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage;
			}else{
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}
		}
	}else{

		var page=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].textContent;
		var Total=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		var totalPage=myAjax.responseXML.documentElement.getElementsByTagName("totalpage")[0].textContent;
		document.getElementById("page").value=myAjax.responseXML.documentElement.getElementsByTagName("page")[0].textContent;
		var pageD=document.getElementById("sql3");
		if(totalPage==1){
			pageD.innerHTML=page+"/"+totalPage;
		}else{
			if(page==1){
				pageD.innerHTML=page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}else if(page==totalPage){
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"page";
			}else{
				pageD.innerHTML="<input type='button' value='이전' class='input' title='이전' onclick='pop2()' />"+page+"/"+totalPage+"<input type='button' value='다음' class='input' title='다음' onclick='pop()' />";
			}
		}
	}
}

function pop(){
   while (document.getElementById("sql3").hasChildNodes()) {
        document.getElementById("sql3").removeChild(document.getElementById("sql3").lastChild);
    }
   var daum=1;
   serch(daum);
	
}
function pop2(){
   while (document.getElementById("sql3").hasChildNodes()) {
        document.getElementById("sql3").removeChild(document.getElementById("sql3").lastChild);
    }
   var daum=2;
   serch(daum);
	
}

function self_close(){
	opener.document.location.reload()
	self.close()
}
function checkSele(k){
	
	/*일일 버튼 */
		 var bButton=document.getElementById('bunho'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=k+1;
		// bButton.onclick=company;
	
}

function company(){//일일보험료 산출
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==6){
		nn=nn.substr(5,6);
	}else{
		nn=nn.substr(5,7);
	}

	var ssC='num'+nn;
	var ssang_c_num=document.getElementById(ssC).value;
	
	if(ssang_c_num){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/coProduce/pop_up/preminumSearchhyundai.php?hyundai_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
		window.open('../pop_up/DaeriCompany.php?num='+ssang_c_num+'&Pcompany='+Pcompany,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}

function monthPreminum(k,daeriCompnay){ //월보험료 산출을 위해

	/*일일 버튼 */
		 var bButton=document.getElementById('A2a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=daeriCompnay;
			bButton.onclick=PreminumCompany;
}

function PreminumCompany(){//일일보험료 산출
	var nn=this.getAttribute("id");
	//alert(nn);
	if(this.id.length==4){
		nn=this.id.substr(3,4);
	}else{
		nn=this.id.substr(3,5);
	}


	var ssang_c_num=document.getElementById('num'+nn).value;
	
	if(ssang_c_num){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2

		window.open('../pop_up/DaeriCompanyPreminum.php?daeriComNum='+ssang_c_num+'&Pcompany='+Pcompany,'new_Prenion','left='+winl+',top='+wint+',resizable=yes,width=1000,height=540,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}
/////////////////운전자 신규 가입
function newUnjeon(cNum,com){

	//var num=document.getElementById("DaeriCompanyNum").value;//2012DaeriCompanyNum
	//var certiNum=document.getElementById('CertiTableNum').value;
	
	//var a2=document.getElementById('iNum0').value;
	//alert(com)
	//var a4=document.getElementById('A9a0').innerHTML;
	if(cNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/MemberEndorseSS.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com,'ppss','left='+winl+',top='+wint+',resizable=yes,width=950,height=600,scrollbars=no,status=yes')
	/*	switch(eval(com)){

		  case 2 : //동부화재
			window.open('../pop_up/MemberEndorse.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
			break;
		 default:
				//alert(com);
		//window.open('./MemberEndorse.php?DaeriCompanyNum='+cNum+'&CertiTableNum='+certiNum+'&InsuraneCompany='+com+'&policyNum='+a4,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
		window.open('../pop_up/MemberEndorseSS.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
			break;
		}*/
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}

}

//킥보드 운전자 가입
function newQboard(cNum,com){

	if(cNum){
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/QboardEndorseSS.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com,'ppss','left='+winl+',top='+wint+',resizable=yes,width=950,height=600,scrollbars=no,status=yes')
	/*	switch(eval(com)){

		  case 2 : //동부화재
			window.open('../pop_up/MemberEndorse.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
			break;
		 default:
				//alert(com);
		//window.open('./MemberEndorse.php?DaeriCompanyNum='+cNum+'&CertiTableNum='+certiNum+'&InsuraneCompany='+com+'&policyNum='+a4,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
		window.open('../pop_up/MemberEndorseSS.php?DaeriCompanyNum='+cNum+'&InsuraneCompany='+com,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')
			break;
		}*/
	}else{

		alert('증권번호 저장 부터 하시고 !!')
			return false;

		}
}


function ReadId(){
	
	var num=document.getElementById("cNum").value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2

	if(num){
		
		
		window.open('../pop_up/ReadId.php?num='+num,'id','left='+winl+',top='+wint+',resizable=yes,width=800,height=400,scrollbars=no,status=yes')
	}else{
			alert('대리운전회사 부터 등록하세요!!')
				return false;
	}
}

function inwon__(){
	
	var num=document.getElementById("cNum").value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2

	if(num){
		
		
		window.open('../pop_up/inwon__.php?num='+num,'id','left='+winl+',top='+wint+',resizable=yes,width=400,height=400,scrollbars=no,status=yes')
	}else{
			alert('대리운전회사 부터 등록하세요!!')
				return false;
	}
}



function ExcellDown(){

	/*var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		//window.open('../pop_up/MemberEndorse.php?DaeriCompanyNum='+num+'&CertiTableNum='+certiNum+'&InsuraneCompany='+iNum+'&policyNum='+a9,'ppss','left='+winl+',top='+wint+',resizable=yes,width=640,height=600,scrollbars=no,status=yes')	
	window.open('/_db/oneyear.php?yymm=','yymm','left='+winl+',top='+wint+',resizable=yes,width=400,height=300,scrollbars=no,status=yes')*/

	var bogosuForm = document.createElement("form");
    bogosuForm.name = "bogosuForm";
    // bogosuForm.target="bogosuForm";
    bogosuForm.method="POST";
    bogosuForm.action="/_db/ExcellList.php";

    var s_sigiInput = document.createElement("input");
    s_sigiInput.type="text";
    s_sigiInput.name="cNum"
    s_sigiInput.value =$('#cNum').val() ;
    bogosuForm.appendChild(s_sigiInput);

/*    var s_sigi2Input = document.createElement("input");
    s_sigi2Input.type="text";
    s_sigi2Input.name="end";
    s_sigi2Input.value = $("#currentDay").val();
    bogosuForm.appendChild(s_sigi2Input);

 /*  var accountInput = document.createElement("input");
    accountInput.type="text";
    accountInput.name="account";
    // accountInput.value = account;
    bogosuForm.appendChild(accountInput);*/

    document.body.appendChild(bogosuForm);

    bogosuForm.submit();
	document.body.removeChild(bogosuForm);
}