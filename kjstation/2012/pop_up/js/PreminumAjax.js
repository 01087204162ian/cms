
function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);
		//alert(myAjax.responseXML.documentElement.getElementsByTagName("a").length);
		//alert(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text);
	     if(myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text!=undefined){
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].text;
					document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("name4")[0].text;
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text)
					document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStart")[0].text;
		}else{
					document.getElementById('num').value=myAjax.responseXML.documentElement.getElementsByTagName("num")[0].textContent;
					document.getElementById('checkPhone').value=myAjax.responseXML.documentElement.getElementsByTagName("name4")[0].textContent;
					alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent)
						document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStart")[0].textContent;
										
		}
		for(var j=1;j<8;j++){
			if(myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text!=undefined){
				document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].text;
			}else{
				document.getElementById('a'+j).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+j)[0].textContent;
									
			}
		}
		
		
		var length=myAjax.responseXML.documentElement.getElementsByTagName("a").length;
		document.getElementById("sumT").value=length;
		var query='';;
	if(myAjax.responseXML.documentElement.getElementsByTagName("a")[0].text!=undefined){
				for(var k=0;k<length;k++){
					var _k=k+1;
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("a")[k].text);
					for (var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("b"+k).length;j++ )
					{
						var _j=j+1;
						document.getElementById('F1b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("icom"+k)[j].text//보험회사
						document.getElementById('F3b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("nai"+k)[j].text//나이구분
				
						document.getElementById('F4b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("b"+k)[j].text//인원
						if(myAjax.responseXML.documentElement.getElementsByTagName("p"+k)[j].text!=0){
							document.getElementById('F5b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p"+k)[j].text//보험료월
						}
						if(myAjax.responseXML.documentElement.getElementsByTagName("t"+k)[j].text!=0){
							document.getElementById('F6b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t"+k)[j].text//보험료월x인원
						}
						document.getElementById('F1b'+_k + _j).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+k)[j].text//보험회사
					}
					
					//
					document.getElementById('TotInwon'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ci")[k].text;//증권별인원합계
					document.getElementById('TotInwonM'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("cim")[k].text;//증권별인원합계
					document.getElementById('TotPreminum'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ct")[k].text;//증권별 보험료 합계
					document.getElementById('TotPreminumM'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("ctm")[k].text;//증권별 보험료 합계

					query+=myAjax.responseXML.documentElement.getElementsByTagName("icom"+k)[0].text+document.getElementById('TotPreminum'+_k).innerHTML+'+';
				}

				for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].text);_k++)
				{
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].text;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].text;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].text;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].text;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].text;
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)){
						bagoJo(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].text)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}
				}
				document.getElementById("comment").innerHTML=query+'='+myAjax.responseXML.documentElement.getElementsByTagName("me")[0].text;
		}else{
			for(var k=0;k<length;k++){
			var _k=k+1;
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("a")[k].text);
			for (var j=0;j<myAjax.responseXML.documentElement.getElementsByTagName("b"+k).length;j++ )
			{
				var _j=j+1;
				document.getElementById('F1b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("icom"+k)[j].textContent//보험회사
				document.getElementById('F3b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("nai"+k)[j].textContent//나이구분
		
				document.getElementById('F4b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("b"+k)[j].textContent//인원
				if(myAjax.responseXML.documentElement.getElementsByTagName("p"+k)[j].textContent!=0){
					document.getElementById('F5b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p"+k)[j].textContent//보험료월
				}
				if(myAjax.responseXML.documentElement.getElementsByTagName("t"+k)[j].textContent!=0){
					document.getElementById('F6b'+_k + _j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t"+k)[j].textContent//보험료월x인원
				}
				document.getElementById('F1b'+_k + _j).style.color=myAjax.responseXML.documentElement.getElementsByTagName("icolor"+k)[j].textContent//보험회사
			}
			
			//
			document.getElementById('TotInwon'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ci")[k].textContent;//증권별인원합계
			document.getElementById('TotInwonM'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("cim")[k].textContent;//증권별인원합계
			document.getElementById('TotPreminum'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ct")[k].textContent;//증권별 보험료 합계
			document.getElementById('TotPreminumM'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("ctm")[k].textContent;//증권별 보험료 합계

		    query+=myAjax.responseXML.documentElement.getElementsByTagName("icom"+k)[0].textContent+document.getElementById('TotPreminum'+_k).innerHTML+'+';
	    }
		for(var _k=0;_k<eval(myAjax.responseXML.documentElement.getElementsByTagName("gaesu")[0].textContent);_k++)
				{
					document.getElementById('C0b'+_k).value=myAjax.responseXML.documentElement.getElementsByTagName("Seg"+_k)[0].textContent;
					document.getElementById('C1b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("bunbho"+_k)[0].textContent;
					document.getElementById('C2b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("dates"+_k)[0].textContent;
					document.getElementById('C3b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("Msg"+_k)[0].textContent;
					document.getElementById('C4b'+_k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("comName"+_k)[0].textContent;
					if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)){
						bagoJo(_k,myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent);
						//alert(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent))
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("get"+_k)[0].textContent)==2)

						{	
							document.getElementById('C1b'+_k).style.color='#0A8FC1';
							document.getElementById('C2b'+_k).style.color='#0A8FC1';
							document.getElementById('C3b'+_k).style.color='#0A8FC1';
							document.getElementById('C4b'+_k).style.color='#0A8FC1';
						}
					}
				}

		document.getElementById("comment").innerHTML=query+'='+myAjax.responseXML.documentElement.getElementsByTagName("me")[0].textContent;


		}
	}else{
		//	
	}
}


function serch(val){
	myAjax=createAjax();

	var num=document.getElementById("num").value;
	//var CertiTableNum=document.getElementById("CertiTableNum").value;
	//var eNum=document.getElementById("eNum").value;

	if(num){	
		//var toSend = "./ajax/preminumAjaxSerch.php?num="+num; //옛날방식 대리운전회사당 보험료 동일하다는 전제
		var toSend = "./ajax/differPserch.php?num="+num
												// +"&CertiTableNum="+CertiTableNum
												// +"&eNum="+eNum;
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=companyReceive;
		myAjax.send('');
	}
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function checkSele(k){
	
	var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('pun'+k);
	 newInput2.type='checkbox';
	 newInput2.id='pcheck'+k;
	 newInput2.onclick=bonagi;//핸드폰 번호를 보내기 위해
	 newInput2.style.width = '30px';
	 aJumin.appendChild(newInput2);

	
}

function changClear(element){

	while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
}

function ssCheckP(val){
	//alert(val)

		var total=0;
		//alert(document.getElementById('F1b'+val+'1').innerHTML+document.getElementById('TotPreminumM'+val).value)
			var com=document.getElementById('F1b'+val+'1').innerHTML+document.getElementById('TotPreminum'+val).innerHTML;
			document.getElementById("comment").innerHTML+=com+'+';

			//total+=eval(document.getElementById('TotPreminumM'+val).value)+12;
			
			//var total+=eval(document.getElementById('TotPreminumM'+val).value)
			//document.getElementById("comment").innerHTML+=com+'+'+'계'+total;

}			

function sumTotal(){
		
		for(var i=1;i<eval(document.getElementById("sumT").value)+1;i++){
				//alert(document.getElementById("ssPcheck"+i);
			if(document.getElementById("ssPcheck"+i).checked==true){

				alert(document.getElementById('TotPreminumM'+val).value)
			}
		}
   // for(var i=1;

}





















