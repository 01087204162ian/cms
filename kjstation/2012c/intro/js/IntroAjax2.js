function companyReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
	//alert(myAjax.responseText);
	   	if(myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text!=undefined){
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].text;
		}else{
			pageList();
			var sumTotal=myAjax.responseXML.documentElement.getElementsByTagName("total")[0].textContent;
		}

		if(sumTotal>0){

			for(var k=0;k<myAjax.responseXML.documentElement.getElementsByTagName("a1").length;k++){

					if(myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text!=undefined){
						//checkSele(k);bunho
						document.getElementById('bunho'+k).innerHTML=k+1;
						//detailSele(k)
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].text;
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].text;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].text;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].text;
						
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text)){

							case 1 :	
								document.getElementById('A4a'+k).innerHTML='청약';
								document.getElementById('A4a'+k).style.color='#E4690C';
								document.getElementById('A9a'+k).style.color='#E4690C';
								document.getElementById('A11a'+k).style.color='#E4690C';
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 3 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							default :

								sele(k,myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].text);//상태를 만들기 위해
								break;
						}
						//document.getElementById('A5a'+k).innerHTML=
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text)==1){//흥국화재는 탁송이 없다
							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].text);
						}
						document.getElementById('A8a'+k).innerHTML=document.getElementById('A6a'+k);
							//checkSele(k,document.getElementById('A6a'+k););
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text))
						{
								case 1 :
									document.getElementById('A8a'+k).innerHTML='흥국';
								    document.getElementById('A8a'+k).style.color='#F345FC';

									
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='DB';
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
									 document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='한화';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A8a'+k).innerHTML='더 케이';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;


						}
						document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].text;
						//certi(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text);
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].text;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].text;
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].text;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].text;
						//document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].text;
						document.getElementById('eNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("eNum")[k].text;
						document.getElementById('A22a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a22")[k].text;
						//document.getElementById('endorseDay'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].text;
						//seongSelect(myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].text,k);
						//document.getElementById("A15a"+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a15")[k].text;
					}else{
						document.getElementById('bunho'+k).innerHTML=k+1;
						//detailSele(k)
						document.getElementById('num'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a1")[k].textContent;
						document.getElementById('A2a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a2")[k].textContent;
						document.getElementById('A3a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a3")[k].textContent;
						//document.getElementById('A4a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a4")[k].textContent;
						
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent)){

							case 1 :	
								document.getElementById('A4a'+k).innerHTML='청약';
								document.getElementById('A4a'+k).style.color='#E4690C';
								document.getElementById('A9a'+k).style.color='#E4690C';
								document.getElementById('A11a'+k).style.color='#E4690C';
							break;
							case 2 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 3 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							case 8 :
								document.getElementById('A4a'+k).innerHTML='해지';
							break;
							default :

								sele(k,myAjax.responseXML.documentElement.getElementsByTagName("a5")[k].textContent);//상태를 만들기 위해
								break;
						}
						//document.getElementById('A5a'+k).innerHTML=
						if(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent)==1){//흥국화재는 탁송이 없다
							document.getElementById('A5a'+k).innerHTML='일반';
						}else{	
							Etage(k,myAjax.responseXML.documentElement.getElementsByTagName("a6")[k].textContent);
						}
						document.getElementById('A8a'+k).innerHTML=document.getElementById('A6a'+k);
							//checkSele(k,document.getElementById('A6a'+k););
							document.getElementById('iNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						switch(eval(myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent))
						{
								case 1 :
									document.getElementById('A8a'+k).innerHTML='흥국';
								    document.getElementById('A8a'+k).style.color='#F345FC';

									
									break;
								case 2 :
									document.getElementById('A8a'+k).innerHTML='DB';
									break;
								case 3 :
									document.getElementById('A8a'+k).innerHTML='KB';
									break;
								case 4 :
									document.getElementById('A8a'+k).innerHTML='현대';
									 document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 5 :
									document.getElementById('A8a'+k).innerHTML='한화';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 6 :
									document.getElementById('A8a'+k).innerHTML='더 케이';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 7 :
									document.getElementById('A8a'+k).innerHTML='MG';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;
								case 8 :
									document.getElementById('A8a'+k).innerHTML='삼성';
								    document.getElementById('A8a'+k).style.color='#E4690C';
									break;


						}
						document.getElementById('insCom'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a7")[k].textContent;
						//certi(k,myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent);
						document.getElementById('A9a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a8")[k].textContent;
						document.getElementById('A10a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].textContent;
						document.getElementById('A11a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a10")[k].textContent;
						document.getElementById('A22a'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a22")[k].textContent;
						document.getElementById('cNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a11")[k].textContent;
						//document.getElementById('pNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("a13")[k].textContent;
						document.getElementById('eNum'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("eNum")[k].textContent;
						//document.getElementById('endorseDay'+k).value=myAjax.responseXML.documentElement.getElementsByTagName("endorseDay")[k].textContent;
						//seongSelect(myAjax.responseXML.documentElement.getElementsByTagName("a14")[k].textContent,k);
						//document.getElementById("A15a"+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("a15")[k].textContent;





					}


			}

		}//sumTotal 끝

	}else{
		//	
	}
}


function serch(val){
//	alert('1')
	//alert(document.getElementById('bunho'+k).innerHTML)
	var s_contents=encodeURIComponent(document.getElementById("s_contents").value);
    var sigi=document.getElementById("sigi").value;
		var end=document.getElementById("end").value;
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
			
			for(var i=0;i<20;i++){
				document.getElementById("bunho"+i).innerHTML='';
				document.getElementById("A2a"+i).innerHTML='';
				document.getElementById("A3a"+i).innerHTML='';
				document.getElementById("A4a"+i).innerHTML='';
				document.getElementById("A5a"+i).innerHTML='';
				//document.getElementById("A6a"+i).innerHTML='';
				//document.getElementById("A7a"+i).innerHTML='';
				document.getElementById("A8a"+i).innerHTML='';
				document.getElementById("A9a"+i).innerHTML='';
				document.getElementById("A10a"+i).innerHTML='';
				document.getElementById("A11a"+i).innerHTML='';
			//	document.getElementById("A14a"+i).innerHTML='';
			//	document.getElementById("A15a"+i).innerHTML='';
				document.getElementById("insCom"+i).value='';
				document.getElementById("num"+i).value='';
				document.getElementById("cNum"+i).value='';
				document.getElementById("iNum"+i).value='';
			//	document.getElementById("pNum"+i).value='';
				document.getElementById("endorseDay"+i).value='';


			}
			var insuranceComNum=document.getElementById('insuranceComNum').options[document.getElementById('insuranceComNum').selectedIndex].value;
			var cNum=document.getElementById("cNum").value;
			var toSend = "./ajax/ajax2.php?sigi="+sigi
						+"&end="+end
						+"&cNum="+cNum
				        +"&insuranceComNum="+insuranceComNum
					   +"&page="+page;
	
	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
/*상태를 만들어주는 모쥴*/
function sele(k,k2){
	var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A4a'+k);
	 newInput2.id='sang'+k;
	 newInput2.style.width = '65px';
	 newInput2.className='selectbox';
	 newInput2.onchange=changeSangtae;
	 var opts=newInput2.options;
	//alert(opts);
	var si;
/*	if(nab==4){///실효중일 때는 pus_h를 7실효로 표시
		pus_h=7;
	}*/

	switch(eval(k2)){
		case 4 ://정상
			opts.length=5;
			si=2;
		break;
		
		case 2 ://해지
			opts.length=3;
			si=2
		break;
		case 1 ://청약
			opts.length=2;
			si=1;
		break;
		case 5 ://거절
			opts.length=6;
			si=5;
		break;
		case 6 ://취소
			opts.length=7;
			si=6;
		break;
		case 7 ://실효
			opts.length=8;
			si=7;
		break;

	}
	
	 for(var i=si;i<opts.length;i++){
		opts[i].value=i;
		if(opts[i].value==eval(k2)){
			newInput2.selectedIndex=i;
		}
	switch(eval(i)){
			case 4 :
			opts[i].text='정상';
			break;
			/*case 3 :
			opts[i].text='교체';
			break;*/

			case 2 :
			opts[i].text='해지';
			break;
			case 1 :
			opts[i].text='청약중';
			break;
			case 5 :
			opts[i].text='거절';
			break;
			case 6 :
			opts[i].text='취소';
			break;
			case 7 :
			opts[i].text='실효';
			break;
		}	
	 }
	if(document.getElementById('sang'+k)){
		aJumin.removeChild(aJumin.lastChild);
	}
	 aJumin.appendChild(newInput2);
}
function Etage(k,etag){
	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A5a'+k);
	 newInput2.id='etag'+k;
	 newInput2.style.width = '80px';
	 newInput2.className='selectbox';
	//newInput2.onchange=changeCh;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=6;
	
	 for(var i=1;i<opts.length;i++){	 
		opts[i].value=i;
		//alert(i)
		if(opts[i].value==eval(etag)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 1 :
					  opts[i].text='일반';
						break;
					case 2 :
						opts[i].text='탁송';
					break;
					case 3 :
						opts[i].text='일반/렌트';
					break;
					case 4 :
						opts[i].text='탁송/렌트';
					break;
					case 5 :
						opts[i].text='전차량탁송';
					break;
					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	 aJumin.appendChild(newInput2);


}