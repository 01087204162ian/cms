

function dongbu3Month(){

		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
			//alert(myAjax.responseText);
	   	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		}else{
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		}
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);

			document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].text;

			document.getElementById('gaesu').value=myAjax.responseXML.documentElement.getElementsByTagName("last")[0].text
			for(var j=0;j<eval(myAjax.responseXML.documentElement.getElementsByTagName("last")[0].text);j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('B0b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mCompany")[j].text;//대리운전회사명
				switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("mGita")[j].text))
						{
								case 1 :
									document.getElementById('B1b'+j).innerHTML='일반';
								    document.getElementById('B1b'+j).style.color='#CCCCCC';
									break;
								case 2 :
									document.getElementById('B1b'+j).innerHTML='탁송';
									document.getElementById('B1b'+j).style.color='#F345FC';
									break;
								
						}
				var jagi=myAjax.responseXML.documentElement.getElementsByTagName("jagi")[j].text;
				Etage(j,myAjax.responseXML.documentElement.getElementsByTagName("a6")[j].text,jagi);
				document.getElementById('B4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[j].text;//cPreminum 의 num
				document.getElementById('B2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mRate")[j].text;//증권번호
			}
		}else{
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);

			document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].textContent;

			document.getElementById('gaesu').value=myAjax.responseXML.documentElement.getElementsByTagName("last")[0].textContent
			for(var j=0;j<eval(myAjax.responseXML.documentElement.getElementsByTagName("last")[0].textContent);j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('B0b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("mCompany")[j].textContent;//대리운전회사명
				switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("mGita")[j].textContent))
						{
								case 1 :
									document.getElementById('B1b'+j).innerHTML='일반';
								    document.getElementById('B1b'+j).style.color='#CCCCCC';
									break;
								case 2 :
									document.getElementById('B1b'+j).innerHTML='탁송';
									document.getElementById('B1b'+j).style.color='#F345FC';
									break;
								
						}
				var jagi=myAjax.responseXML.documentElement.getElementsByTagName("jagi")[j].textContent;
				Etage(j,myAjax.responseXML.documentElement.getElementsByTagName("a6")[j].textContent,jagi);
				document.getElementById('B4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[j].textContent;//cPreminum 의 num
				document.getElementById('B2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mRate")[j].textContent;//증권번호
			}
			
		}
			
		
	
	
		
	
	}



}

function cSucess(){

		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			
			document.getElementById('C2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].text;
			document.getElementById('gaesu3').value=myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].text;
			for(var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].text;j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('C0b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sPreminum")[j].text;//시작나이
				document.getElementById('C1b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ePreminum")[j].text;//끝나이
				document.getElementById('C4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[j].text;//cPreminum 의 num
				document.getElementById('C2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("certi")[j].text;//증권번호
			}
		}else{
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			document.getElementById('C2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("company")[0].textContent;
			document.getElementById('gaesu3').value=myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].textContent;
			for(var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("mRnum")[0].textContent;j++){ //나이구분에 따른 for문이 돌고

				document.getElementById('C0b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("sPreminum")[j].textContent;//시작나이
				document.getElementById('C1b'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ePreminum")[j].textContent;//끝나이
				document.getElementById('C4b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("mNum")[j].textContent;//cPreminum 의 num
				document.getElementById('C2b'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("certi")[j].textContent;//증권번호
			}
		}
			
		
	
	
		
	
	}



}
function cSerch(val){

	for(var _k=0;_k<8;_k++){

		document.getElementById('C0b'+_k).innerHTML='';
		document.getElementById('C1b'+_k).innerHTML='';
		//document.getElementById('B5b'+_k).innerHTML='';
		document.getElementById('C2b'+_k).value='';
		document.getElementById('C4b'+_k).value='';



	}
	var moNum=document.getElementById('B4b'+val).value;

	myAjax=createAjax();
	
		var toSend = "./ajax/pMgserch3.php?CertiTableNum="+moNum//CertiTableNum
										//+"&moNum="+moNum
										//+"&sunso="+nn;		    
	//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=cSucess;
		myAjax.send('');
}
function Etage(k,etag,jagi){
	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B5b'+k);
	 newInput2.id='etag'+k;
	 newInput2.style.width = '100px';
	 newInput2.className='selectbox';
	 newInput2.onchange=changeMoValue;
	 var opts=newInput2.options;
	//alert(etag)
	opts.length=3;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(etag)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 1 :
					  opts[i].text='모계약선택';
						break;
					case 2 :
						opts[i].text='안함';
					break;
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);

	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('B6b'+k);
	 newInput2.id='jagi'+k;
	 newInput2.style.width = '100px';
	 newInput2.className='selectbox';
	 newInput2.onchange=changeJagi;
	 var opts=newInput2.options;
	//alert(etag)
	opts.length=4;
	
	 for(var i=0;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(jagi)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
					default :
					  opts[i].text='=선택=';
						break;
				   case 1 :
					  opts[i].text='10만원';
						break;
					case 2 :
						opts[i].text='20만원';
					break;
					case 3 :
						opts[i].text='30만원';
					break;
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);
}
function CmoSucess(){
	if(myAjax.readyState == 4 && myAjax.status == 200) {
    // alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){

			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text);
			//alert(document.getElementById('gaesu').value);
			//for(var _j=0;_j<eval(document.getElementById('gaesu').value);_j++){
			for(var _j=0;_j<eval(document.getElementById('gaesu').value);_j++){
				if(_j!=eval(myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text)){
					
					document.getElementById('etag'+_j).value=2;
				}
				
			}//document.getElementById('A1a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("InsuraneCompany")[0].text;
				//document.getElementById('A2a').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("DaeriCompanyNum")[0].text;

		}else{


			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);


		}
			
			
	}else{
		//	
	}



}
function changeJagi(){

	if(this.id.length==5){
		var nn=this.id.substr(4,5);
		
	}else{
		var nn=this.id.substr(4,6);
	}
	var moNum=document.getElementById('B4b'+nn).value;

	var jagi=document.getElementById('jagi'+nn).options[document.getElementById('jagi'+nn).selectedIndex].value;//납입회차\

	if(!jagi){
		alert('자기부담금을 정하고 하셔요');
		ocument.getElementById('jagi'+nn).focus();
		return false;
	}
	var CertiTableNum=document.getElementById('CertiTableNum').value;
	//alert(moNum+'/'+jagi+'/'+CertiTableNum);
	

	if(confirm('자기부담금 변경합니다!')){

		myAjax=createAjax();
	
		var toSend = "./ajax/CjagiChange.php?jagi="+jagi//CertiTableNum
										+"&moNum="+moNum
										+"&sunso="+nn;		    
	//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=CmoSucess;
		myAjax.send('');

	}else{

		this.value=2;
		return false;
	}


}
function changeMoValue(){

	
	if(this.id.length==5){
		var nn=this.id.substr(4,5);
		
	}else{
		var nn=this.id.substr(4,6);
	}
	var moNum=document.getElementById('B4b'+nn).value;
	var CertiTableNum=document.getElementById('CertiTableNum').value;

	if(confirm('모계약으로 선택합니다!!')){

		myAjax=createAjax();
	
		var toSend = "./ajax/CmoChange.php?CertiTableNum="+CertiTableNum//CertiTableNum
										+"&moNum="+moNum
										+"&sunso="+nn;		    
	//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=CmoSucess;
		myAjax.send('');

	}else{

		this.value=2;
		return false;
	}
}
function serch(val){
	myAjax=createAjax();
	switch(val){
			case 1 :/* 다음*/
				var page=document.getElementById("page").value;
				page=eval(page)+1;
			break;
			case 2: /*이전*/
				var page=document.getElementById("page").value;
				page=eval(page)-1;
			break;
			default:/* 그냥 조회*/

				var page='';
			break;
		}

	for(var _k=0;_k<10;_k++){

		document.getElementById('B0b'+_k).innerHTML='';
		document.getElementById('B1b'+_k).innerHTML='';
		document.getElementById('B5b'+_k).innerHTML='';
		
		document.getElementById('B2b'+_k).value='';
		document.getElementById('B4b'+_k).value='';



	}
	
	var DaeriCompanyNum=document.getElementById('DaeriCompanyNum').value;
	var CertiTableNum=document.getElementById('CertiTableNum').value;
	var InsuraneCompany=document.getElementById('InsuraneCompany').value;
	var toSend = "./ajax/pMgserch2.php?CertiTableNum=" + CertiTableNum
			+"&InsuraneCompany="+InsuraneCompany
			+"&DaeriCompanyNum="+DaeriCompanyNum
			+"&page="+page;
			
		
	//alert(toSend)
	//self.document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=dongbu3Month;
	myAjax.send('');
}

addLoadEvent(serch);
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