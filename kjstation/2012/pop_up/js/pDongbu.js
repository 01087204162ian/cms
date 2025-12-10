function prReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	
	
		for(var i=0;i<12;i++){
			var j=i+1;
			//alert(i);
				if(myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].text!=undefined){
					if(i<6){
						document.getElementById('totalP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].text;
						document.getElementById('h12P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h1P"+i)[0].text;
					//	document.getElementById('znai'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inwon"+i)[0].text;

					}else{
						k=i-6;
						document.getElementById('etotalP'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].text;
						document.getElementById('e12P'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h1P"+i)[0].text;
					//	document.getElementById('eznai'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inwon"+i)[0].text;
					}
					
					//document.getElementById('h2P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h2P"+i)[0].text;
					//document.getElementById('h3P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h3P"+i)[0].text;
					
					
				}else{
					document.getElementById('f_'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("total"+i)[0].textContent;
					document.getElementById('da_'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("da"+i)[0].textContent;
					
				}
			}
			if(myAjax.responseXML.documentElement.getElementsByTagName("inTotal")[0].text!=undefined){
				document.getElementById('znaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inTotal")[0].text;
				//document.getElementById('eznaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("einTotal")[0].text;
				document.getElementById('inComTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inPrem")[0].text;
				document.getElementById('einComTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("einPrem")[0].text;
				//document.getElementById('ks_dong').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ks_dong")[0].text;
			}else{
				document.getElementById('znaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inTotal")[0].textContent;
				//document.getElementById('eznaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("einTotal")[0].textContent;
				document.getElementById('inComTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inPrem")[0].textContent;
				document.getElementById('einComTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("einPrem")[0].textContent;
				//document.getElementById('ks_dong').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("ks_dong")[0].textContent;

			}
			

	
	
	}else{
		//	
	}
}
function Dongbuserch(){	//동부보험료 산출
	//alert('1')
	var daein_3=document.getElementById("daein_3").options[document.getElementById("daein_3").selectedIndex].value;	
	var daemool_3=document.getElementById("daemool_3").options[document.getElementById("daemool_3").selectedIndex].value;
	var jason_3=document.getElementById("jason_3").options[document.getElementById("jason_3").selectedIndex].value;
	//var sele_3=document.getElementById("sele_3").options[document.getElementById("sele_3").selectedIndex].value;
	var jagibudam_3=document.getElementById("jagibudam_3").options[document.getElementById("jagibudam_3").selectedIndex].value;
	var char_3=document.getElementById("char_3").options[document.getElementById("char_3").selectedIndex].value;	
	//var sago_3=document.getElementById("sago_3").options[document.getElementById("sago_3").selectedIndex].value;
	//var law_3=document.getElementById("law_3").options[document.getElementById("law_3").selectedIndex].value;
	//var bunnab_3=document.getElementById("bunnab_3").options[document.getElementById("bunnab_3").selectedIndex].value;
	var dongbu2_c_num=document.getElementById('CertiTableNum').value;
	var psigi=document.getElementById("psigi").value;
	
	myAjax=createAjax();	
		var toSend = "./ajax/dongBuPreminum.php?daein_3="+daein_3 
			       +"&daemool_3="+daemool_3
			       +"&jason_3="+jason_3
			       //+"&sele_3="+sele_3
			       +"&jagibudam_3="+jagibudam_3
				   +"&char_3="+char_3
				    +"&dongbu2_c_num="+dongbu2_c_num
					+"&psigi="+psigi;
				  // +"&sago_3="+sago_3
			       //+"&law_3="+law_3
				   

		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=prReceive;
		myAjax.send('');
	
}
function dongbu3Month(){

		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("sigi")[0].text;
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			document.getElementById('sigi').value=myAjax.responseXML.documentElement.getElementsByTagName("sigi")[0].textContent;
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		}
			if(myAjax.responseXML.documentElement.getElementsByTagName("daein")[0].text!=undefined){
					document.getElementById('daein_3').value=myAjax.responseXML.documentElement.getElementsByTagName("daein")[0].text;
					document.getElementById('daemool_3').value=myAjax.responseXML.documentElement.getElementsByTagName("daemool")[0].text;
					document.getElementById('jason_3').value=myAjax.responseXML.documentElement.getElementsByTagName("jason")[0].text;
					document.getElementById('char_3').value=myAjax.responseXML.documentElement.getElementsByTagName("char")[0].text;
					document.getElementById('jagibudam_3').value=myAjax.responseXML.documentElement.getElementsByTagName("jagibudam")[0].text;
					document.getElementById('psigi').value=myAjax.responseXML.documentElement.getElementsByTagName("psigi")[0].text;
					var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text;
			}else{

					document.getElementById('daein_3').value=myAjax.responseXML.documentElement.getElementsByTagName("daein")[0].textContent;
					document.getElementById('daemool_3').value=myAjax.responseXML.documentElement.getElementsByTagName("daemool")[0].textContent;
					document.getElementById('jason_3').value=myAjax.responseXML.documentElement.getElementsByTagName("jason")[0].textContent;
					document.getElementById('char_3').value=myAjax.responseXML.documentElement.getElementsByTagName("char")[0].textContent;
					document.getElementById('jagibudam_3').value=myAjax.responseXML.documentElement.getElementsByTagName("jagibudam")[0].textContent;
					document.getElementById('psigi').value=myAjax.responseXML.documentElement.getElementsByTagName("psigi")[0].textContent;
					var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].textContent;
			}
		for(var i=0;i<12;i++){
			var j=i+1;
			//alert(i);
				if(myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].text!=undefined){
					if(i<6){
						document.getElementById('totalP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].text;
						document.getElementById('h12P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h1P"+i)[0].text;
						document.getElementById('znai'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inwon"+i)[0].text;

					}else{
						k=i-6;
						document.getElementById('etotalP'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].text;
						document.getElementById('e12P'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h1P"+i)[0].text;
						//document.getElementById('eznai'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inwon"+i)[0].text;
					}
					
					
					
				}else{
					if(i<6){
						document.getElementById('totalP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].textContent;
						document.getElementById('h12P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h1P"+i)[0].textContent;
						document.getElementById('znai'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inwon"+i)[0].textContent;

					}else{
						k=i-6;
						document.getElementById('etotalP'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].textContent;
						document.getElementById('e12P'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h1P"+i)[0].textContent;
						//document.getElementById('eznai'+k).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inwon"+i)[0].textContent;
					}
					
					
				}
			}
			if(myAjax.responseXML.documentElement.getElementsByTagName("inTotal")[0].text!=undefined){
				document.getElementById('znaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inTotal")[0].text;
				//document.getElementById('eznaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("einTotal")[0].text;
				document.getElementById('inComTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inPrem")[0].text;
				document.getElementById('einComTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("einPrem")[0].text;
			}else{
				document.getElementById('znaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("inTotal")[0].textContent;
				//document.getElementById('eznaiTotal').innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("einTotal")[0].textContent;

			}
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("qPrem")[0].text);
		if(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text!=undefined){
			var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text;
			for(var i=0;i<6;i++){
			
			document.getElementById('giPrem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("giPrem"+i)[0].text;
			//document.getElementById('gi2Prem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("gi2Prem"+i)[0].text;
			}
				
		}else{
			
			var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent;
			for(var i=0;i<6;i++){
			document.getElementById('giPrem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("giPrem"+i)[0].textContent;
			//document.getElementById('gi2Prem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("gi2Prem"+i)[0].textContent;
		
			}
		}

		for(var i=0;i<smsTotal;i++){
			
				if(myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text!=undefined){
					document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text;
					document.getElementById('cday'+i).innerHTML=smsTotal-i;
					document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p"+i)[0].text;
					document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q"+i)[0].text;
					document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r"+i)[0].text;
					document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s"+i)[0].text;
					document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t"+i)[0].text;
					document.getElementById('uday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u"+i)[0].text;
					/*document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text;
					document.getElementById('cday'+i).innerHTML=smsTotal-i;
					document.getElementById('p_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p2p"+i)[0].text;
					document.getElementById('q_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q2q"+i)[0].text;
					document.getElementById('r_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r2r"+i)[0].text;
					document.getElementById('s_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s2s"+i)[0].text;
					document.getElementById('t_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t2t"+i)[0].text;
					document.getElementById('u_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u2u"+i)[0].text;*/
					if(i==thisDay){
							document.getElementById('day'+i).style.color='#0A8FC1';
							document.getElementById('cday'+i).style.color='#0A8FC1';
							document.getElementById('pday'+i).style.color='#0A8FC1';
							document.getElementById('qday'+i).style.color='#0A8FC1';
							document.getElementById('rday'+i).style.color='#0A8FC1';
							document.getElementById('sday'+i).style.color='#0A8FC1';
							document.getElementById('tday'+i).style.color='#0A8FC1';
							document.getElementById('uday'+i).style.color='#0A8FC1';
							/*document.getElementById('day'+i).style.color='#0A8FC1';
							document.getElementById('cday'+i).style.color='#0A8FC1';
							document.getElementById('p_day'+i).style.color='#0A8FC1';
							document.getElementById('q_day'+i).style.color='#0A8FC1';
							document.getElementById('r_day'+i).style.color='#0A8FC1';
							document.getElementById('s_day'+i).style.color='#0A8FC1';
							document.getElementById('t_day'+i).style.color='#0A8FC1';
							document.getElementById('u_day'+i).style.color='#0A8FC1';*/
						
					}
					
				}else{
					document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent;
					document.getElementById('cday'+i).innerHTML=smsTotal-i;
					document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p"+i)[0].textContent;
					document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q"+i)[0].textContent;
					document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r"+i)[0].textContent;
					document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s"+i)[0].textContent;
					document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t"+i)[0].textContent;
					document.getElementById('uday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u"+i)[0].textContent;
					/*document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent;
					document.getElementById('cday'+i).innerHTML=smsTotali;
					document.getElementById('p_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p2p"+i)[0].textContent;
					document.getElementById('q_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q2q"+i)[0].textContent;
					document.getElementById('r_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r2r"+i)[0].textContent;
					document.getElementById('s_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s2s"+i)[0].textContent;
					document.getElementById('t_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t2t"+i)[0].textContent;
					document.getElementById('u_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u2u"+i)[0].textContent;*/
					if(i==thisDay){
							document.getElementById('day'+i).style.color='#0A8FC1';
							document.getElementById('cday'+i).style.color='#0A8FC1';
							document.getElementById('pday'+i).style.color='#0A8FC1';
							document.getElementById('qday'+i).style.color='#0A8FC1';
							document.getElementById('rday'+i).style.color='#0A8FC1';
							document.getElementById('sday'+i).style.color='#0A8FC1';
							document.getElementById('tday'+i).style.color='#0A8FC1';
							document.getElementById('uday'+i).style.color='#0A8FC1';
							/*document.getElementById('day'+i).style.color='#0A8FC1';
							document.getElementById('cday'+i).style.color='#0A8FC1';
							document.getElementById('p_day'+i).style.color='#0A8FC1';
							document.getElementById('q_day'+i).style.color='#0A8FC1';
							document.getElementById('r_day'+i).style.color='#0A8FC1';
							document.getElementById('s_day'+i).style.color='#0A8FC1';
							document.getElementById('t_day'+i).style.color='#0A8FC1';
							document.getElementById('u_day'+i).style.color='#0A8FC1';*/
						
					}
				}
			}
	
		
	
		
/*	if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!=undefined){
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}
		
		
		
	}else{
		//	*/
	}



}

function pserch(){
	myAjax=createAjax();
	var DaeriCompanyNum=document.getElementById('DaeriCompanyNum').value;
	var CertiTableNum=document.getElementById('CertiTableNum').value;
	var toSend = "./ajax/dongbuAllSerch.php?CertiTableNum=" + CertiTableNum
			+"&DaeriCompanyNum="+DaeriCompanyNum;
			
		
	//alert(toSend)
	//self.document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=dongbu3Month;
	myAjax.send('');
}

addLoadEvent(pserch);

function dongbu2Month() {
		if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
			//alert(myAjax.responseXML.documentElement.getElementsByTagName("qPrem")[0].text);
		if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		}
		if(myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text!=undefined){
			var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].text;
			var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].text;
			for(var i=0;i<6;i++){
			
			document.getElementById('giPrem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("giPrem"+i)[0].text;
			//document.getElementById('gi2Prem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("gi2Prem"+i)[0].text;
			}
				
		}else{
			
			var smsTotal=myAjax.responseXML.documentElement.getElementsByTagName("aPeriod")[0].textContent;
			var thisDay=myAjax.responseXML.documentElement.getElementsByTagName("thisDay")[0].textContent;
			for(var i=0;i<6;i++){
			document.getElementById('giPrem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("giPrem"+i)[0].textContent;
			//document.getElementById('gi2Prem'+i).value=myAjax.responseXML.documentElement.getElementsByTagName("gi2Prem"+i)[0].textContent;
		
			}
		}
		
		for(var i=0;i<smsTotal;i++){
				if(myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text!=undefined){
					document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text;
					document.getElementById('cday'+i).innerHTML=smsTotal-i;
					document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p"+i)[0].text;
					document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q"+i)[0].text;
					document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r"+i)[0].text;
					document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s"+i)[0].text;
					document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t"+i)[0].text;
					document.getElementById('uday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u"+i)[0].text;
					document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].text;
				/*	document.getElementById('cday'+i).innerHTML=smsTotal-i;
					document.getElementById('p_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p2p"+i)[0].text;
					document.getElementById('q_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q2q"+i)[0].text;
					document.getElementById('r_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r2r"+i)[0].text;
					document.getElementById('s_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s2s"+i)[0].text;
					document.getElementById('t_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t2t"+i)[0].text;
					document.getElementById('u_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u2u"+i)[0].text;*/
					if(i==thisDay){
					document.getElementById('day'+i).style.color='#0A8FC1';
					document.getElementById('cday'+i).style.color='#0A8FC1';
					document.getElementById('pday'+i).style.color='#0A8FC1';
					document.getElementById('qday'+i).style.color='#0A8FC1';
					document.getElementById('rday'+i).style.color='#0A8FC1';
					document.getElementById('sday'+i).style.color='#0A8FC1';
					document.getElementById('tday'+i).style.color='#0A8FC1';
					document.getElementById('uday'+i).style.color='#0A8FC1';
					document.getElementById('day'+i).style.color='#0A8FC1';
				/*	document.getElementById('cday'+i).style.color='#0A8FC1';
					document.getElementById('p_day'+i).style.color='#0A8FC1';
					document.getElementById('q_day'+i).style.color='#0A8FC1';
					document.getElementById('r_day'+i).style.color='#0A8FC1';
					document.getElementById('s_day'+i).style.color='#0A8FC1';
					document.getElementById('t_day'+i).style.color='#0A8FC1';
					document.getElementById('u_day'+i).style.color='#0A8FC1';*/
						
					}
					
				}else{
					document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent;
					document.getElementById('cday'+i).innerHTML=smsTotal-i;
					document.getElementById('pday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p"+i)[0].textContent;
					document.getElementById('qday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q"+i)[0].textContent;
					document.getElementById('rday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r"+i)[0].textContent;
					document.getElementById('sday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s"+i)[0].textContent;
					document.getElementById('tday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t"+i)[0].textContent;
					document.getElementById('uday'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u"+i)[0].textContent;
					document.getElementById('day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("d"+i)[0].textContent;
				/*	document.getElementById('cday'+i).innerHTML=smsTotal-i;
					document.getElementById('p_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("p2p"+i)[0].textContent;
					document.getElementById('q_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("q2q"+i)[0].textContent;
					document.getElementById('r_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("r2r"+i)[0].textContent;
					document.getElementById('s_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("s2s"+i)[0].textContent;
					document.getElementById('t_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("t2t"+i)[0].textContent;
					document.getElementById('u_day'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("u2u"+i)[0].textContent;*/
					
				}
			}
	
		
	
		
/*	if(myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text!=undefined){
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].text;
		}else{
			self.document.getElementById("imgkor").innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("care")[0].textContent;
			}
		
		
		
	}else{
		//	*/
	}
}
function gijunDay(CertiTableNum){
	//alert(CertiTableNum);
	myAjax=createAjax();
	var sigi=document.getElementById("sigi").value;
	var query='';
		for(j=0;j<6;j++){
				query+="b"+j;
				query+="=";
				query+=document.getElementById('giPrem'+j).value;
				query+="&";
				//query+="a2a"+j;
				//query+="=";
				//query+=document.getElementById('gi2Prem'+j).value;
				//query+="&";
		}

		//var giPrem=document.getElementById("giPrem"+i).value;
			
	
		//var Pcompany=document.getElementById('Pcompany').value;
	var toSend = "./ajax/donbu2MonthPrem.php?CertiTableNum=" + CertiTableNum
			+"&sigi="+ sigi+"&"
			//+"&Pcompany="+ Pcompany+"&"
			//+"&dongbu2_c_num="+ dongbu2_c_num
		    +query;
			
		
	//alert(toSend)
	//self.document.getElementById("url").innerHTML = toSend;
	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=dongbu2Month;
	myAjax.send('');
	
}

function clear_text_3(){

	for(var i=0;i<6;i++){

					//document.getElementById('nai'+i).innerHTML='';
					//document.getElementById('daeinP'+i).innerHTML='';
					//document.getElementById('daemolP'+i).innerHTML='';
					//document.getElementById('jasonP'+i).innerHTML='';
					//document.getElementById('charP'+i).innerHTML='';
					document.getElementById('totalP'+i).innerHTML='';
					document.getElementById('h12P'+i).innerHTML='';
					document.getElementById('etotalP'+i).innerHTML='';
					document.getElementById('e12P'+i).innerHTML='';
					//document.getElementById('h4P'+i).innerHTML='';

	}
}