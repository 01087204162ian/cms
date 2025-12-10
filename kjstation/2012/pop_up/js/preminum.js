function yearPreminum() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
			//self.document.getElementById("kor_str").value = myAjax.responseText;

		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].text;

			if(myAjax.responseXML.documentElement.getElementsByTagName("gi")[0].text==1){
				document.getElementById('B0b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("yearP")[0].text;
				document.getElementById('B1b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year1P")[0].text;
				document.getElementById('B2b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year2P")[0].text;
				document.getElementById('B3b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year3P")[0].text;
			}else{
				document.getElementById('C0b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("yearP")[0].text;
				document.getElementById('C1b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year1P")[0].text;
				document.getElementById('C2b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year2P")[0].text;
				document.getElementById('C3b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year3P")[0].text;
			}
			
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			var sunso=myAjax.responseXML.documentElement.getElementsByTagName("sunso")[0].textContent;

			if(myAjax.responseXML.documentElement.getElementsByTagName("gi")[0].text==1){
				document.getElementById('B0b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("yearP")[0].textContent;
				document.getElementById('B1b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year1P")[0].textContent;
				document.getElementById('B2b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year2P")[0].textContent;
				document.getElementById('B3b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year3P")[0].textContent;
			}else{
				document.getElementById('C0b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("yearP")[0].textContent;
				document.getElementById('C1b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year1P")[0].textContent;
				document.getElementById('C2b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year2P")[0].textContent;
				document.getElementById('C3b'+sunso).value=myAjax.responseXML.documentElement.getElementsByTagName("year3P")[0].textContent;
			}
			}
	
		
	}else{
		//	
	}
}
function preminumCheck(id,val,sunso,gi)
{
	//alert(val)
		//alert(gi);
	/*
	document.getElementById('B1b'+sunso).value=Math.round(Math.round((val*0.2)/10))*10;
	document.getElementById('B2b'+sunso).value=Math.round(Math.round((val*0.1)/10))*10;
	document.getElementById('B3b'+sunso).value=Math.round(Math.round((val*0.05)/10))*10;
	*/
	myAjax=createAjax();
	var yearP=val;
	
	var num=document.getElementById("CertiTableNum").value;
	if(yearP){	
		var toSend = "./ajax/preminumAjax.php?num="+num
					+"&yearP="+yearP
					+"&gi="+gi  //gi가 1이면 일반, 2이면 탁송
					+"&sunso="+sunso;
	
		//alert(toSend);
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=yearPreminum;
		myAjax.send('');
	}else{
		alert('보험료 입력부터 !!');
		document.getElementById('B0b'+sunso).focus();
		return false;

	}

}
//hyunpreminumSerch
function hyunpreminumSerch() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
		 if(myAjax.responseXML.documentElement.getElementsByTagName("eco")[0].text!=undefined){
			if(myAjax.responseXML.documentElement.getElementsByTagName("eco")[0].text==1){// 저장된것이 없을 때

		        alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].text;

			}else{//저장된 것이 있을  때
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text)
				var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text;

			var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text;
			for(var _m=0;_m<4;_m++)
			{	
				document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+_m)[0].text;
				document.getElementById('B1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbme"+_m)[0].text;
				document.getElementById('B2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncme"+_m)[0].text;
				document.getElementById('B3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndme"+_m)[0].text;
				document.getElementById('B4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neme"+_m)[0].text;
				//document.getElementById('B5b'+_m).val4ue=myAjax.responseXML.documentElement.getElementsByTagName("nfme"+_m)[0].text;

		/*		document.getElementById('C0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("naEme"+_m)[0].text;
				document.getElementById('C1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbEme"+_m)[0].text;
				document.getElementById('C2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncEme"+_m)[0].text;
				document.getElementById('C3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndEme"+_m)[0].text;
				//document.getElementById('C4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].text;*/
			//	document.getElementById('C5b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].text;
			}
			document.getElementById('in1won').value=myAjax.responseXML.documentElement.getElementsByTagName("in1won")[0].text;
			document.getElementById('in2won').value=myAjax.responseXML.documentElement.getElementsByTagName("in2won")[0].text;
			document.getElementById('in3won').value=myAjax.responseXML.documentElement.getElementsByTagName("in3won")[0].text;
			document.getElementById('in4won').value=myAjax.responseXML.documentElement.getElementsByTagName("in4won")[0].text;


			
			document.getElementById('p26p').value=myAjax.responseXML.documentElement.getElementsByTagName("P26P")[0].text;
			document.getElementById('p35p').value=myAjax.responseXML.documentElement.getElementsByTagName("P35P")[0].text;
			document.getElementById('p48p').value=myAjax.responseXML.documentElement.getElementsByTagName("P48P")[0].text;
			document.getElementById('p55p').value=myAjax.responseXML.documentElement.getElementsByTagName("P55P")[0].text;

		/*	document.getElementById('p26Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
			document.getElementById('p34Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
			document.getElementById('p48Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;*/

				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].text;

					if(smsTotal>0){
						for(var i=0;i<smsTotal;i++){
								
								if(myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text!=undefined){
									document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text;
									document.getElementById('cday'+i).innerHTML=smsTotal-i;
									document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+i)[0].text;
									document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("g"+i)[0].text;
									document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h"+i)[0].text;
									document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("i"+i)[0].text;
									//document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("j"+i)[0].text;
									

									if(i==thisDay){
										document.getElementById('day'+i).style.color='#0A8FC1';
										document.getElementById('cday'+i).style.color='#0A8FC1';
										document.getElementById('pday'+i).style.color='#0A8FC1';
										document.getElementById('qday'+i).style.color='#0A8FC1';
										document.getElementById('rday'+i).style.color='#0A8FC1';
										document.getElementById('sday'+i).style.color='#0A8FC1';
									//	document.getElementById('tday'+i).style.color='#0A8FC1';
									
									}
								}

							}
					}



			}//저장된 것이 있을  때
		}else{
			if(myAjax.responseXML.documentElement.getElementsByTagName("eco")[0].textContent==1){// 저장된것이 없을 때

		        alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent)
			}else{//저장된 것이 있을  때

				var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent;

				var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].textContent;
				for(var _m=0;_m<5;_m++)
				{	
					document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+_m)[0].textContent;
					document.getElementById('B1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbme"+_m)[0].textContent;
					document.getElementById('B2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncme"+_m)[0].textContent;
					document.getElementById('B3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndme"+_m)[0].textContent;
					document.getElementById('B4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neme"+_m)[0].textContent;
					//document.getElementById('B5b'+_m).val4ue=myAjax.responseXML.documentElement.getElementsByTagName("nfme"+_m)[0].textContent;

			/*		document.getElementById('C0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("naEme"+_m)[0].textContent;
					document.getElementById('C1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbEme"+_m)[0].textContent;
					document.getElementById('C2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncEme"+_m)[0].textContent;
					document.getElementById('C3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndEme"+_m)[0].textContent;
					//document.getElementById('C4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].textContent;*/
				//	document.getElementById('C5b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].textContent;
				}
				document.getElementById('in1won').value=myAjax.responseXML.documentElement.getElementsByTagName("in1won")[0].textContent;
				document.getElementById('in2won').value=myAjax.responseXML.documentElement.getElementsByTagName("in2won")[0].textContent;
				document.getElementById('in3won').value=myAjax.responseXML.documentElement.getElementsByTagName("in3won")[0].textContent;
				document.getElementById('in4won').value=myAjax.responseXML.documentElement.getElementsByTagName("in4won")[0].textContent;


				document.getElementById('p25p').value=myAjax.responseXML.documentElement.getElementsByTagName("P25P")[0].textContent;
				document.getElementById('p26p').value=myAjax.responseXML.documentElement.getElementsByTagName("P26P")[0].textContent;
				document.getElementById('p31p').value=myAjax.responseXML.documentElement.getElementsByTagName("P31P")[0].textContent;
				document.getElementById('p45p').value=myAjax.responseXML.documentElement.getElementsByTagName("P45P")[0].textContent;
				document.getElementById('p50p').value=myAjax.responseXML.documentElement.getElementsByTagName("P50P")[0].textContent;

			/*	document.getElementById('p26Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				document.getElementById('p34Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				document.getElementById('p48Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;*/


				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].textContent;

					if(smsTotal>0){
						for(var i=0;i<smsTotal;i++){
								
								if(myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent!=undefined){
									document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent;
									document.getElementById('cday'+i).innerHTML=smsTotal-i;
									document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+i)[0].textContent;
									document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("g"+i)[0].textContent;
									document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h"+i)[0].textContent;
									document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("i"+i)[0].textContent;
									document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("j"+i)[0].textContent;
									

									if(i==thisDay){
										document.getElementById('day'+i).style.color='#0A8FC1';
										document.getElementById('cday'+i).style.color='#0A8FC1';
										document.getElementById('pday'+i).style.color='#0A8FC1';
										document.getElementById('qday'+i).style.color='#0A8FC1';
										document.getElementById('rday'+i).style.color='#0A8FC1';
										document.getElementById('sday'+i).style.color='#0A8FC1';
										document.getElementById('tday'+i).style.color='#0A8FC1';
									
									}
								}

							}
					}


			}//저장된 것이 있을  때
			

		}
		
	}else{
		//	
	}
}
function LigPreminumSerch() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);

     	if(myAjax.responseXML.documentElement.getElementsByTagName("eco")[0].text!=undefined){
			if(myAjax.responseXML.documentElement.getElementsByTagName("eco")[0].text==1){// 저장된것이 없을 때

		        alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].text;
			}else{//저장된 것이 있을  때
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text)
				var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text;

				var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text;
				for(var _m=0;_m<5;_m++)
				{	
					document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+_m)[0].text;
					document.getElementById('B1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbme"+_m)[0].text;
					document.getElementById('B2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncme"+_m)[0].text;
					document.getElementById('B3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndme"+_m)[0].text;
					document.getElementById('B4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neme"+_m)[0].text;
					//document.getElementById('B5b'+_m).val4ue=myAjax.responseXML.documentElement.getElementsByTagName("nfme"+_m)[0].text;

			/*		document.getElementById('C0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("naEme"+_m)[0].text;
					document.getElementById('C1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbEme"+_m)[0].text;
					document.getElementById('C2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncEme"+_m)[0].text;
					document.getElementById('C3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndEme"+_m)[0].text;
					//document.getElementById('C4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].text;*/
				//	document.getElementById('C5b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].text;
				}
				document.getElementById('in1won').value=myAjax.responseXML.documentElement.getElementsByTagName("in1won")[0].text;
				document.getElementById('in2won').value=myAjax.responseXML.documentElement.getElementsByTagName("in2won")[0].text;
				document.getElementById('in3won').value=myAjax.responseXML.documentElement.getElementsByTagName("in3won")[0].text;
				document.getElementById('in4won').value=myAjax.responseXML.documentElement.getElementsByTagName("in4won")[0].text;


				document.getElementById('p25p').value=myAjax.responseXML.documentElement.getElementsByTagName("P25P")[0].text;
				document.getElementById('p26p').value=myAjax.responseXML.documentElement.getElementsByTagName("P26P")[0].text;
				document.getElementById('p31p').value=myAjax.responseXML.documentElement.getElementsByTagName("P31P")[0].text;
				document.getElementById('p45p').value=myAjax.responseXML.documentElement.getElementsByTagName("P45P")[0].text;
				document.getElementById('p50p').value=myAjax.responseXML.documentElement.getElementsByTagName("P50P")[0].text;

			/*	document.getElementById('p26Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				document.getElementById('p34Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				document.getElementById('p48Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;*/


				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].text;

					if(smsTotal>0){
						for(var i=0;i<smsTotal;i++){
								
								if(myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text!=undefined){
									document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text;
									document.getElementById('cday'+i).innerHTML=smsTotal-i;
									document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+i)[0].text;
									document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("g"+i)[0].text;
									document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h"+i)[0].text;
									document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("i"+i)[0].text;
									document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("j"+i)[0].text;
									

									if(i==thisDay){
										document.getElementById('day'+i).style.color='#0A8FC1';
										document.getElementById('cday'+i).style.color='#0A8FC1';
										document.getElementById('pday'+i).style.color='#0A8FC1';
										document.getElementById('qday'+i).style.color='#0A8FC1';
										document.getElementById('rday'+i).style.color='#0A8FC1';
										document.getElementById('sday'+i).style.color='#0A8FC1';
										document.getElementById('tday'+i).style.color='#0A8FC1';
									
									}
								}

							}
					}



			}//저장된 것이 있을  때
		}else{
			if(myAjax.responseXML.documentElement.getElementsByTagName("eco")[0].textContent==1){// 저장된것이 없을 때

		        alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent)
			}else{//저장된 것이 있을  때

				var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent;

				var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].textContent;
				for(var _m=0;_m<5;_m++)
				{	
					document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+_m)[0].textContent;
					document.getElementById('B1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbme"+_m)[0].textContent;
					document.getElementById('B2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncme"+_m)[0].textContent;
					document.getElementById('B3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndme"+_m)[0].textContent;
					document.getElementById('B4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neme"+_m)[0].textContent;
					//document.getElementById('B5b'+_m).val4ue=myAjax.responseXML.documentElement.getElementsByTagName("nfme"+_m)[0].textContent;

			/*		document.getElementById('C0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("naEme"+_m)[0].textContent;
					document.getElementById('C1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbEme"+_m)[0].textContent;
					document.getElementById('C2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncEme"+_m)[0].textContent;
					document.getElementById('C3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndEme"+_m)[0].textContent;
					//document.getElementById('C4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].textContent;*/
				//	document.getElementById('C5b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].textContent;
				}
				document.getElementById('in1won').value=myAjax.responseXML.documentElement.getElementsByTagName("in1won")[0].textContent;
				document.getElementById('in2won').value=myAjax.responseXML.documentElement.getElementsByTagName("in2won")[0].textContent;
				document.getElementById('in3won').value=myAjax.responseXML.documentElement.getElementsByTagName("in3won")[0].textContent;
				document.getElementById('in4won').value=myAjax.responseXML.documentElement.getElementsByTagName("in4won")[0].textContent;


				document.getElementById('p25p').value=myAjax.responseXML.documentElement.getElementsByTagName("P25P")[0].textContent;
				document.getElementById('p26p').value=myAjax.responseXML.documentElement.getElementsByTagName("P26P")[0].textContent;
				document.getElementById('p31p').value=myAjax.responseXML.documentElement.getElementsByTagName("P31P")[0].textContent;
				document.getElementById('p45p').value=myAjax.responseXML.documentElement.getElementsByTagName("P45P")[0].textContent;
				document.getElementById('p50p').value=myAjax.responseXML.documentElement.getElementsByTagName("P50P")[0].textContent;

			/*	document.getElementById('p26Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				document.getElementById('p34Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				document.getElementById('p48Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;*/


				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].textContent;

					if(smsTotal>0){
						for(var i=0;i<smsTotal;i++){
								
								if(myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent!=undefined){
									document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent;
									document.getElementById('cday'+i).innerHTML=smsTotal-i;
									document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+i)[0].textContent;
									document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("g"+i)[0].textContent;
									document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h"+i)[0].textContent;
									document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("i"+i)[0].textContent;
									document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("j"+i)[0].textContent;
									

									if(i==thisDay){
										document.getElementById('day'+i).style.color='#0A8FC1';
										document.getElementById('cday'+i).style.color='#0A8FC1';
										document.getElementById('pday'+i).style.color='#0A8FC1';
										document.getElementById('qday'+i).style.color='#0A8FC1';
										document.getElementById('rday'+i).style.color='#0A8FC1';
										document.getElementById('sday'+i).style.color='#0A8FC1';
										document.getElementById('tday'+i).style.color='#0A8FC1';
									
									}
								}

							}
					}


			}//저장된 것이 있을  때
			

		}
	
		


		
	

		
	}else{
		//	
	}
}
function preminumSerch() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
			//alert(myAjax.responseText);
		
		if(myAjax.responseXML.documentElement.getElementsByTagName("eco")[0].text==1){// 저장된것이 없을 때

		        alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].text;
		}else{//저장된 것이 있을  때
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text)
				
				var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text;

				var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text;
				for(var _m=0;_m<3;_m++)
				{	
					document.getElementById('B0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("name"+_m)[0].text;
					document.getElementById('B1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbme"+_m)[0].text;
					document.getElementById('B2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncme"+_m)[0].text;
					document.getElementById('B3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndme"+_m)[0].text;
					document.getElementById('B4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neme"+_m)[0].text;
					//document.getElementById('B5b'+_m).val4ue=myAjax.responseXML.documentElement.getElementsByTagName("nfme"+_m)[0].text;

				/*	document.getElementById('C0b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("naEme"+_m)[0].text;
					document.getElementById('C1b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("nbEme"+_m)[0].text;
					document.getElementById('C2b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ncEme"+_m)[0].text;
					document.getElementById('C3b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("ndEme"+_m)[0].text;
					//document.getElementById('C4b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].text;
					document.getElementById('C5b'+_m).value=myAjax.responseXML.documentElement.getElementsByTagName("neEme"+_m)[0].text;*/
				}
				document.getElementById('in1won').value=myAjax.responseXML.documentElement.getElementsByTagName("in1won")[0].text;
				//document.getElementById('in2won').value=myAjax.responseXML.documentElement.getElementsByTagName("in2won")[0].text;
				document.getElementById('in3won').value=myAjax.responseXML.documentElement.getElementsByTagName("in3won")[0].text;
				document.getElementById('in4won').value=myAjax.responseXML.documentElement.getElementsByTagName("in4won")[0].text;


				document.getElementById('p26p').value=myAjax.responseXML.documentElement.getElementsByTagName("P26P")[0].text;
				document.getElementById('p34p').value=myAjax.responseXML.documentElement.getElementsByTagName("P34P")[0].text;
				document.getElementById('p48p').value=myAjax.responseXML.documentElement.getElementsByTagName("P48P")[0].text;

				//document.getElementById('p26Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				//document.getElementById('p34Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;
				//document.getElementById('p48Ep').value=myAjax.responseXML.documentElement.getElementsByTagName("PE26P")[0].text;


				document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("FirstStartDay")[0].text;


				if(smsTotal>0){
					for(var i=0;i<smsTotal;i++){
							
							if(myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text!=undefined){
								document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text;
								document.getElementById('cday'+i).innerHTML=smsTotal-i;
								document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+i)[0].text;
								//document.getElementById('p_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("g"+i)[0].text;
								document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h"+i)[0].text;
								//document.getElementById('q_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("i"+i)[0].text;
								document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("j"+i)[0].text;
								//document.getElementById('r_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("k"+i)[0].text;

								if(i==thisDay){
									document.getElementById('day'+i).style.color='#0A8FC1';
									document.getElementById('cday'+i).style.color='#0A8FC1';
									document.getElementById('pday'+i).style.color='#0A8FC1';
									//document.getElementById('p_day'+i).style.color='#0A8FC1';
									document.getElementById('qday'+i).style.color='#0A8FC1';
									//document.getElementById('q_day'+i).style.color='#0A8FC1';
									document.getElementById('rday'+i).style.color='#0A8FC1';
									//document.getElementById('r_day'+i).style.color='#0A8FC1';
								}
							}else{
								document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent;
								document.getElementById('cday'+i).innerHTML=i;
								document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("f"+i)[0].textContent;
								//document.getElementById('p_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("g"+i)[0].textContent;
								document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h"+i)[0].textContent;
								//document.getElementById('q_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("i"+i)[0].textContent;
								document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("j"+i)[0].textContent;
								//document.getElementById('r_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("k"+i)[0].textContent;
								if(i==thisDay){
									document.getElementById('day'+i).style.color='#0A8FC1';
									document.getElementById('cday'+i).style.color='#0A8FC1';
									document.getElementById('pday'+i).style.color='#0A8FC1';
									//document.getElementById('p_day'+i).style.color='#0A8FC1';
									document.getElementById('qday'+i).style.color='#0A8FC1';
									//document.getElementById('q_day'+i).style.color='#0A8FC1';
									document.getElementById('rday'+i).style.color='#0A8FC1';
									//document.getElementById('r_day'+i).style.color='#0A8FC1';
								}
							}

							
						}
				
			}else{
				//alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			
			}
	  }//eco rhksfus	
	}
	/*
		if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!=undefined){
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}*/
		
	}else{
		//	
	}
}
function serch(val){
	
	myAjax=createAjax();
	var num=document.getElementById("CertiTableNum").value;
	//alert('1')
	var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
	var emday=document.getElementById("emday").value;	
	//alert(InsuraneCompany);
	switch(eval(InsuraneCompany)){
		case 1 :
			var toSend = "./ajax/preminumSerch.php?num="+num
						+"&emday="+emday
						+"&DaeriCompanyNum="+DaeriCompanyNum;
		
			//alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=preminumSerch;
			myAjax.send('');
			break;
			case 3 :
			var toSend = "./ajax/ligpreminumSerch.php?num="+num
						+"&emday="+emday
						+"&DaeriCompanyNum="+DaeriCompanyNum;
		
			//alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=LigPreminumSerch;
			myAjax.send('');
			break;
			case 4 :
			var toSend = "./ajax/hyunpreminumSerch.php?num="+num
						+"&emday="+emday
						+"&DaeriCompanyNum="+DaeriCompanyNum;
						
		
			//alert(toSend);
			myAjax.open("get",toSend,true);
			myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
			myAjax.onreadystatechange=hyunpreminumSerch;
			myAjax.send('');
			break;

	}
}
addLoadEvent(serch);
function LigsamePcheck(insuranCe){//흥국화재 또는현대 화재 보험료 동일하게 입력하게 


	myAjax=createAjax();
	var num=document.getElementById("CertiTableNum").value;
	//var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
	if(document.getElementById("sameCheck").checked==true){	
		
	
		
				var toSend = "./ajax/sameP.php?num="+num
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+insuranCe;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=LigPreminumSerch;
				myAjax.send('');

			
		
	}

}//textContent

function HyunsamePcheck(insuranCe){//흥국화재 또는현대 화재 보험료 동일하게 입력하게 


	myAjax=createAjax();
	var num=document.getElementById("CertiTableNum").value;
	//var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
	if(document.getElementById("sameCheck").checked==true){	
		
	
		
				var toSend = "./ajax/sameP.php?num="+num
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+insuranCe;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=hyunpreminumSerch;
				myAjax.send('');

			
		
	}

}
function samePcheck(insuranCe){//흥국화재 또는현대 화재 보험료 동일하게 입력하게 


	myAjax=createAjax();
	var num=document.getElementById("CertiTableNum").value;
	//var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
	if(document.getElementById("sameCheck").checked==true){	
		
	
		
				var toSend = "./ajax/sameP.php?num="+num
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+insuranCe;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=preminumSerch;
				myAjax.send('');

			
		
	}

}


function gijunDay(){
	myAjax=createAjax();
	for(var i=0;i<32;i++){
	document.getElementById('day'+i).innerHTML='';
	document.getElementById('cday'+i).innerHTML='';
	document.getElementById('pday'+i).innerHTML='';
	//document.getElementById('p_day'+i).innerHTML='';
	document.getElementById('qday'+i).innerHTML='';
	//document.getElementById('q_day'+i).innerHTML='';
	document.getElementById('rday'+i).innerHTML='';
	//document.getElementById('r_day'+i).innerHTML=''
	}
	var num=document.getElementById("CertiTableNum").value;
	var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
	var startDay=document.getElementById("sigi").value;	
	var p26p=document.getElementById("p26p").value;
	var p34p=document.getElementById("p34p").value;
	var p48p=document.getElementById("p48p").value;

	//var p26Ep=document.getElementById("p26Ep").value;
	//var p34Ep=document.getElementById("p34Ep").value;
	//var p48Ep=document.getElementById("p48Ep").value;
	switch(eval(InsuraneCompany)){
			case 1 :
				var toSend = "./ajax/moths1Pinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p26p="+p26p
							+"&p34p="+p34p
							+"&p48p="+p48p;
							//+"&p26Ep="+p26Ep
							//+"&p34Ep="+p34Ep
							//+"&p48Ep="+p48Ep;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=preminumSerch;
				myAjax.send('');

			break;
			case 3 :
				var toSend = "./ajax/moths1Pinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p26p="+p26p
							+"&p34p="+p34p
							+"&p48p="+p48p
							+"&p26Ep="+p26Ep
							+"&p34Ep="+p34Ep
							+"&p48Ep="+p48Ep;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=preminumSerch;
				myAjax.send('');

			break;
			case 4 :
				var toSend = "./ajax/moths1Pinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p26p="+p26p
							+"&p34p="+p34p
							+"&p48p="+p48p
							+"&p26Ep="+p26Ep
							+"&p34Ep="+p34Ep
							+"&p48Ep="+p48Ep;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=preminumSerch;
				myAjax.send('');

			break;
		}
	

}

function LiggijunDay(){
	myAjax=createAjax();
	for(var i=0;i<32;i++){
	document.getElementById('day'+i).innerHTML='';
	document.getElementById('cday'+i).innerHTML='';
	document.getElementById('pday'+i).innerHTML='';
	document.getElementById('qday'+i).innerHTML='';
	document.getElementById('rday'+i).innerHTML='';
	document.getElementById('sday'+i).innerHTML='';
	document.getElementById('tday'+i).innerHTML='';
	
	}
	var num=document.getElementById("CertiTableNum").value;
	var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
	var startDay=document.getElementById("sigi").value;	
	var p25p=document.getElementById("p25p").value;
	var p26p=document.getElementById("p26p").value;
	var p31p=document.getElementById("p31p").value;
	var p45p=document.getElementById("p45p").value;
	var p50p=document.getElementById("p50p").value;

	
	switch(eval(InsuraneCompany)){
			case 1 :
				var toSend = "./ajax/moths1Pinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p26p="+p26p
							+"&p34p="+p34p
							+"&p48p="+p48p
							+"&p26Ep="+p26Ep
							+"&p34Ep="+p34Ep
							+"&p48Ep="+p48Ep;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=preminumSerch;
				myAjax.send('');

			break;
			case 3 :
				var toSend = "./ajax/mothsLigPinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p25p="+p25p
							+"&p26p="+p26p
							+"&p31p="+p31p
							+"&p45p="+p45p
							+"&p50p="+p50p;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=LigPreminumSerch;
				myAjax.send('');

			break;
			case 4 :
				var toSend = "./ajax/moths1Pinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p26p="+p26p
							+"&p34p="+p34p
							+"&p48p="+p48p
							+"&p26Ep="+p26Ep
							+"&p34Ep="+p34Ep
							+"&p48Ep="+p48Ep;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=preminumSerch;
				myAjax.send('');

			break;
		}
	

}


function hyungijunDay(){
	myAjax=createAjax();
	for(var i=0;i<32;i++){
	document.getElementById('day'+i).innerHTML='';
	document.getElementById('cday'+i).innerHTML='';
	document.getElementById('pday'+i).innerHTML='';
	document.getElementById('qday'+i).innerHTML='';
	document.getElementById('rday'+i).innerHTML='';
	document.getElementById('sday'+i).innerHTML='';
	//document.getElementById('tday'+i).innerHTML='';
	
	}
	var num=document.getElementById("CertiTableNum").value;
	var InsuraneCompany=document.getElementById("InsuraneCompany").value;
	var DaeriCompanyNum=document.getElementById("DaeriCompanyNum").value;	
	var startDay=document.getElementById("sigi").value;	
	var p26p=document.getElementById("p26p").value;
	var p35p=document.getElementById("p35p").value;
	var p48p=document.getElementById("p48p").value;
	var p55p=document.getElementById("p55p").value;


	
	switch(eval(InsuraneCompany)){
			case 1 :
				var toSend = "./ajax/moths1Pinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p26p="+p26p
							+"&p35p="+p35p
							+"&p48p="+p48p
							+"&p26Ep="+p26Ep
							+"&p34Ep="+p34Ep
							+"&p48Ep="+p48Ep;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=preminumSerch;
				myAjax.send('');

			break;
			case 3 :
				var toSend = "./ajax/mothsLigPinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p25p="+p25p
							+"&p26p="+p26p
							+"&p31p="+p31p
							+"&p45p="+p45p
							+"&p50p="+p50p;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=LigPreminumSerch;
				myAjax.send('');

			break;
			case 4 :
				var toSend = "./ajax/mothshyunPinput.php?num="+num
							+"&startDay="+startDay
							+"&DaeriCompanyNum="+DaeriCompanyNum
						   +"&InsuraneCompany="+InsuraneCompany
							+"&p26p="+p26p
							+"&p35p="+p35p
							+"&p48p="+p48p
							+"&p55p="+p55p;
				//alert(toSend);
				myAjax.open("get",toSend,true);
				myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
				myAjax.onreadystatechange=hyunpreminumSerch;
				myAjax.send('');

			break;
		}
	

}


