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

function jeongLi(){
		

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/jeongLi.php','idmake','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')
		//window.open('../pop_up/jeongLi.php','plist','left='+winl+',top='+wint+',resizable=yes,width=400,height=600,scrollbars=no,status=yes')
}
function jeongLi7(){
		

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/jeongLi7.php','idmake7','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')
		//window.open('../pop_up/jeongLi.php','plist','left='+winl+',top='+wint+',resizable=yes,width=400,height=600,scrollbars=no,status=yes')
}

function jeongLi8(){
		

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/_db/hcurl.php','idmake8','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')
		//window.open('../pop_up/jeongLi.php','plist','left='+winl+',top='+wint+',resizable=yes,width=400,height=600,scrollbars=no,status=yes')
}

function jeongLi3(){

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/2012/intro/php/samsung.php','idmake','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')


}

function jeongLi4(){

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/2012/intro/php/inwonExcel.php','inwonExcel','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')



}
function stripeTables(){
	//alert('1')
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
		document.forms[0].action = '../../2012/lib/proc_out.php'
		document.forms[0].submit();
	}
}
function popGeneral(){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		
		
		window.open('../pop_up/DaeriCompany.php','idmake','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')

}

function popDajoong(){

	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
		
		
		window.open('../pop_up/dajoong.php','dajoong','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')

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
	//alert('1')
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
		 bButton.onclick=company;
	
}



function allInwon(k,k2){//순서 전체인원
	 var bButton=document.getElementById('A10a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=k2;
		 bButton.onclick=inWonTotal;


}
//문자리스트 보기 
function sMs(k,k2){
	var bButton=document.getElementById('A8a'+k);
		// bButton.className='input5';
		 //aButton.value=val1;
		 bButton.style.cursor='hand';
		// bButton.style.width = '300px';
		 bButton.innerHTML=k2;
		 bButton.onclick=smsList;

}

	
function smsList(){
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==4){
		nn=nn.substr(3,4);
	}else{
		nn=nn.substr(3,5);
	}

	var ssC='A5a'+nn;
	//alert(ssC);
	var checkPhone=document.getElementById(ssC).innerHTML;
	
	//alert(num)
	if(checkPhone){
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/php/smsListAll.php?checkPhone='+checkPhone,'smsList','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	}else{

		alert('증권번호 부터 저징 후 !!')
			return false;
	}


}
//전체 인원 보기 
function inWonTotal(){
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==5){
		nn=nn.substr(4,5);
	}else{
		nn=nn.substr(4,6);
	}

	var ssC='num'+nn;
	var num=document.getElementById(ssC).value;
	
	if(num){
    var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('../pop_up/daeriList.php?DaeriCompanyNum='+num,'allDriverMember','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	}else{

		alert('증권번호 부터 저징 후 !!')
			return false;
	}
}

function company(){//대리운전회사 기본정보
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
		window.open('../pop_up/DaeriCompany.php?num='+ssang_c_num+'&Pcompany='+Pcompany,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=1080,height=540,scrollbars=yes,status=yes')
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
function gusung(val){
	//var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;//2012DaeriCompanyNum
	//var certiNum=document.getElementById('CertiTableNum').value;
	//var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
if(val==9){
	val='';}
	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/MemberList.php?InsuraneCompany='+val,'plist','left='+winl+',top='+wint+',resizable=yes,width=400,height=600,scrollbars=no,status=yes')
}	

function findAddr()
{
	var url
	url = "/csd/cs_member/member/find_address2.php?mode=def"
	f_OpenModalPop("AddressFind",url,1,456,380)
}
function f_OpenModal(strURL,arrArguVal,nHeight,nWidth,nTop,nLeft,bEdge,bCenter,bHelp,bResiz,bStatus,bScroll,bUnadorned) {
var strTop = '';
if(nTop > 0) {
	strTop = 'dialogTop:'+nTop+'px;';
}
var strLeft = '';
if(nLeft > 0) {
	strLeft = 'dialogLeft:'+nLeft+'px;';
}
var strEdge = '';
if(bEdge != '') {
	strEdge = "edge:";
	strEdge += (bEdge > 0) ? 'raised' : 'sunken';
	strEdge += ';';
}
var objArgu = new Object();
objArgu.objWin = self;
objArgu.objParam = arrArguVal;
var objWin = window.showModalDialog(strURL,objArgu,"dialogHeight:"+nHeight+"px;dialogWidth:"+nWidth+"px;"+strTop+strLeft+strEdge+"center:"+bCenter+";help:"+bHelp+";resizable:"+bResiz+";status:"+bStatus+";scroll:"+bScroll+";unadorned:"+bUnadorned+";");
return objWin;
}
function f_OpenModalPop(Name,Action,bScroll,width, height) {
		var w, h;
		w = width;
		h = height;
		var strURL = Action;
		var objArgu = new Object();
		objArgu.Width = w;
		objArgu.Height = h;
		objArgu.Action = Action;
		var objWin = f_OpenModal(strURL,objArgu,h,w,0,0,0,1,0,0,0,bScroll,0);
		return objWin;
	}

function mgJeongli(){
		

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/mgjeongLi.php','idmake','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')
		//window.open('../pop_up/jeongLi.php','plist','left='+winl+',top='+wint+',resizable=yes,width=400,height=600,scrollbars=no,status=yes')
} 


function  cord(){  // 대리점 코드 정리


		 var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/cord.php','code','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')
}

function ilban(){ //// 일반계약정리

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/ilban.php','ilban','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')

}

function gasi(){

	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/gesipan.php','gasi','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')

}

function dongbuS(){ //// 동부화재 신규

	    var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('../pop_up/dongbushin.php','dongbu','left='+winl+',top='+wint+',resizable=yes,width=930,height=570,scrollbars=yes,status=yes')

}