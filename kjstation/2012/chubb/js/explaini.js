

function displayLoading(element) { 
    while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
    var image = document.createElement("img");
    image.setAttribute("src","/kibs_admin/ssangyoung/loading.gif");
    image.setAttribute("alt","Loading...");
    element.appendChild(image);
}

function receiveResponse() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			alert(myAjax.responseText);
			//self.document.getElementById("dd").innerHTML = myAjax.responseText;
	
		
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!="undefined"){

			document.getElementById("cal_num").value=myAjax.responseXML.documentElement.getElementsByTagName("explainNum")[0].text;
			document.getElementById("stbutton").value='수정';
			document.getElementById("print2").innerHTML="<input type='button' value='프린트' class='input' title='프린트' onclick='cancel_print()' />"
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			document.getElementById("cal_num").value=myAjax.responseXML.documentElement.getElementsByTagName("explainNum")[0].textContent;
			document.getElementById("stbutton").value='수정';
			document.getElementById("cal_num").value=myAjax.responseXML.documentElement.getElementsByTagName("explainNum")[0].text;
		}
	
		
		
	}else{
		//	
	}
}
function cancel_store(){


	myAjax=createAjax();
	var oun1=document.getElementById("policy").value;
	var oun2=document.getElementById("invoice").value;
	var oun3=document.getElementById("from").value;
	var oun4=document.getElementById("destnation").value;
	var oun5=document.getElementById("num").value;
	var oun6=document.getElementById("endorse_day").value;
	var oun7=document.getElementById("cal_num").value;

	


	if(!document.getElementById("policy").value){
		alert('예상 가입금액 !');
		document.getElementById("policy").focus();
		return false;
	}
	if(!document.getElementById("invoice").value){
		alert('보험료(율) !');
		document.getElementById("invoice").focus();
		return false;
	}
	if(!document.getElementById("from").value){
		alert('출발지!');
		document.getElementById("from").focus();
		return false;
	}
	if(!document.getElementById("destnation").value){
		alert('도착지 !');
		document.getElementById("destnation").focus();
		return false;
	}
	
	

	//displayLoading(self.document.getElementById("imgkor"));

	var toSend = "../../ajax/ajaxExplain.php?oun1=" + oun1
			+"&oun2="+oun2
		    +"&oun3="+oun3
		    +"&oun4="+oun4
		    +"&oun5="+oun5
		    +"&oun6="+oun6
			+"&oun7="+oun7;
		
	//self.document.getElementById("url").innerHTML = toSend;
	//alert(toSend);
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=receiveResponse;
	myAjax.send();
	
		
	//		fobj.target = '_self'
	//		fobj.action="./noclaim_store.php";
	//		fobj.submit();

	
}




function cancel_print(){
	
	if(confirm('상품설명서 프린트 합니다!')){

		window.open('','noclaim','width=800,height=600,top=150,left=200')
				document.form1.target ='noclaim'
				document.form1.action = './explain_print.php?num='+document.getElementById("cal_num").value
				document.form1.submit();
		
	}

}
/*
function receiveResponse_2() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//	self.document.getElementById("kor_str").value = myAjax.responseText;
		
		if(myAjax.responseXML.documentElement.getElementsByTagName("cname")[0].text!="undefined"){
			self.document.getElementById("endors").value=myAjax.responseXML.documentElement.getElementsByTagName("cname")[0].text;
		}else{
			self.document.getElementById("endors").value=myAjax.responseXML.documentElement.getElementsByTagName("cname")[0].textContent;
		}
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!="undefined"){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		}
	if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!="undefined"){
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}
		
		
		
	}else{
		//	
	}
}


function preminum(hyundai_c_num){
	
	myAjax=createAjax();
	var yearPrem=document.getElementById("yearPrem").value;
	if(!yearPrem){
		alert('년간보험료를 입력 후 !');
		document.form1.yearPrem.focus();
		return false;
	}

	displayLoading(self.document.getElementById("imgkor"));

	var toSend = "./ajax/newHyundaiPreminum.php?yearPrem=" + yearPrem
			+"&hyundai_c_num="+hyundai_c_num;
		
	//self.document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=receiveResponse;
	myAjax.send();
	
}

function endorse(hyundai_c_num){

	myAjax=createAjax();
	
	displayLoading(self.document.getElementById("imgkor"));

	if(confirm('해지 후 환급 할 때!')){
		var toSend = "./ajax/endorseAjax.php?hyundai_c_num="+hyundai_c_num;
		
	//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=receiveResponse_2;
		myAjax.send();

		return false;

	}
}
function memoReceive() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//self.document.getElementById("kor_str").value = myAjax.responseText;

		var mem=myAjax.responseXML.documentElement.getElementsByTagName("mem");
		var wdate=myAjax.responseXML.documentElement.getElementsByTagName("wdate");
		var memkind=myAjax.responseXML.documentElement.getElementsByTagName("memkind");
		for(var k=0;k<mem.length;k++){
			
			var mem_2='mem_'+k;
			document.getElementById(mem_2).innerHTML=mem[k].text;
			var wdate_2='wdate_'+k;
			document.getElementById(wdate_2).innerHTML=wdate[k].text;
			var memokind_2='memokind_'+k;

			switch(eval(memkind[k].text)){
				case 1 :
					var mekinName='일반';
					break;
				case 2 :
					var mekinName='결재';
					break;
			}
			document.getElementById(memokind_2).innerHTML=mekinName;

		}
		document.getElementById("memo").value='';
		document.getElementById("memoNum").value='';
		
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!="undefined"){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		}

		
	if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!="undefined"){
		   document.getElementById("memoButton").value=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			document.getElementById("memoButton").value=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}
		
		
		
	}else{
		//	
	}
}

function memoStore(){
	myAjax=createAjax();

	var memoNum=document.getElementById("memoNum").value
	if(memoNum){ //메모 수정
		var memo_2=encodeURIComponent(document.getElementById("memo").value);
		if(!memo_2){
			alert('메모 내용이 없습니다 !!')
				self.document.getElementById("memo").focus();
			return false;
		}
		var c_number=document.getElementById("c_number").value;
		var memokind=document.getElementById("memokind").value;
		var useruid=document.getElementById("useruid").value;

		if(confirm('메모 수정 합니다!')){

			var toSend = "./ajax/memoSujeongAjax.php?memoNum="+memoNum
					   +"&memokind="+memokind
					   +"&memo_2="+memo_2
					   +"&useruid="+useruid
				       +"&c_number="+c_number;
			
		//self.document.getElementById("url").innerHTML = toSend;
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=memoReceive;
			myAjax.send();

			return false;


		}else{

			self.document.getElementById("memo").focus();
			return false;
		}

	}else{//신규 메모 입력
		var memo_2=encodeURIComponent(document.getElementById("memo").value);
		if(!memo_2){
			alert('메모 내용이 없습니다 !!')
				self.document.getElementById("memo").focus();
			return false;
		}
		var c_number=document.getElementById("c_number").value;
		var memokind=document.getElementById("memokind").value;
		var useruid=document.getElementById("useruid").value;

		if(confirm('메모 입력 합니다!')){

			var toSend = "./ajax/memoAjax.php?c_number="+c_number
					   +"&memokind="+memokind
					   +"&memo_2="+memo_2
					   +"&useruid="+useruid;
			
		//self.document.getElementById("url").innerHTML = toSend;
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=memoReceive;
			myAjax.send();

			return false;


		}else{

			self.document.getElementById("memo").focus();
			return false;
		}
		
	}

}

function memoSearch() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	   //self.document.getElementById("kor_str").value = myAjax.responseText;
		if(myAjax.responseXML.documentElement.getElementsByTagName("mem")[0].text!="undefined"){
			self.document.getElementById("memo").value=myAjax.responseXML.documentElement.getElementsByTagName("mem")[0].text;
		}else{
			self.document.getElementById("memo").value=myAjax.responseXML.documentElement.getElementsByTagName("mem")[0].textContent;
		}

		if(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text!="undefined"){
			self.document.getElementById("memoNum").value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
		}else{
			self.document.getElementById("memoNum").value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
		}
		if(myAjax.responseXML.documentElement.getElementsByTagName("memkind")[0].text!="undefined"){
			self.document.getElementById("memokind").value=myAjax.responseXML.documentElement.getElementsByTagName("memkind")[0].text;
		}else{
			self.document.getElementById("memokind").value=myAjax.responseXML.documentElement.getElementsByTagName("memkind")[0].textContent;
		}
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!="undefined"){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		}
			
		if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!="undefined"){
		   document.getElementById("memoButton").value=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
			//self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			document.getElementById("memoButton").value=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			//self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}
		
	}else{
		//	
	}
}

//메모 수정
function memoSujeongAjax(val){
	myAjax=createAjax();
	var toSend = "./ajax/memoSearchAjax.php?memoNum="+val
	//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=memoSearch;
		myAjax.send();

		return false;
}

function memoAll(){//메모 전체보기
	
      var c_number=document.getElementById("c_number").value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./ajax/memoAll.php?c_number='+c_number,'memoall','left='+winl+',top='+wint+',resizable=yes,width=650,height=400,scrollbars=yes,status=no')
}

function newMemo(){
	var newmemo_2=document.getElementById("newmemo");
	if(newmemo_2.checked==true){
		document.getElementById("memo").value='';
		document.getElementById("memoNum").value='';
		self.document.getElementById("memo").focus();
			return false;
	}else{
		document.getElementById("memo").value='';
		document.getElementById("memoNum").value='';
		self.document.getElementById("memo").focus();
			return false;
	}
}*/