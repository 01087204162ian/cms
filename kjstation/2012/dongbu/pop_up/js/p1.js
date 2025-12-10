

function prReceive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		alert(myAjax.responseText);
	
	
		for(var i=0;i<12;i++){
			var j=i+1;
				if(myAjax.responseXML.documentElement.getElementsByTagName("nai"+i)[0].text!=undefined){
					//alert(myAjax.responseXML.documentElement.getElementsByTagName("nai"+i)[0].text);
					//document.getElementById('sql'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("num"+i)[0].text;
					document.getElementById('nai'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("age"+i)[0].text;
					document.getElementById('year'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("year"+i)[0].text;
					document.getElementById('daeinP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("daein"+i)[0].text;
					document.getElementById('daemolP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("daemool"+i)[0].text;
					document.getElementById('jasonP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("jason"+i)[0].text;
					document.getElementById('charP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("char"+i)[0].text;
					document.getElementById('totalP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("totalP"+i)[0].text;
					document.getElementById('hP'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h1P"+i)[0].text;
					document.getElementById('h2P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h2P"+i)[0].text;
					document.getElementById('h3P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h3P"+i)[0].text;
					document.getElementById('h4P'+i).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("h4P"+i)[0].text;
					
					
				}else{
					document.getElementById('f_'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("total"+i)[0].textContent;
					document.getElementById('da_'+j).innerHTML=myAjax.responseXML.documentElement.getElementsByTagName("da"+i)[0].textContent;
					
				}
			}
	
	
	}else{
		//	
	}
}
function serch(){
	

	var sigi=document.getElementById('sigi').value;
	//var psigi=document.getElementById('psigi').value;

	var daein_3=document.getElementById("daein_3").options[document.getElementById("daein_3").selectedIndex].value;
	
	var daemool_3=document.getElementById("daemool_3").options[document.getElementById("daemool_3").selectedIndex].value;
	var jason_3=document.getElementById("jason_3").options[document.getElementById("jason_3").selectedIndex].value;
	//var sele_3=document.getElementById("sele_3").options[document.getElementById("sele_3").selectedIndex].value;
	var jagibudam_3=document.getElementById("jagibudam_3").options[document.getElementById("jagibudam_3").selectedIndex].value;
	var char_3=document.getElementById("char_3").options[document.getElementById("char_3").selectedIndex].value;
		
	//var sago_3=document.getElementById("sago_3").options[document.getElementById("sago_3").selectedIndex].value;
	
	//var law_3=document.getElementById("law_3").options[document.getElementById("law_3").selectedIndex].value;


	//var bunnab_3=document.getElementById("bunnab_3").options[document.getElementById("bunnab_3").selectedIndex].value;
	var h1=document.getElementById("h1").options[document.getElementById("h1").selectedIndex].value;
	var h2=document.getElementById("h2").options[document.getElementById("h2").selectedIndex].value;
	
	myAjax=createAjax();
	
	
		
		
		var toSend = "./ajax/dongBuPreminum.php?daein_3="+daein_3	 
			       +"&daemool_3="+daemool_3
			       +"&jason_3="+jason_3
			       //+"&sele_3="+sele_3
			       +"&jagibudam_3="+jagibudam_3
				   +"&char_3="+char_3
				   +"&h1="+h1
				   +"&h2="+h2
				   +"&sigi="+sigi;
				  // +"&sago_3="+sago_3
			       //+"&law_3="+law_3
				   

		//alert(toSend)
		//document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=prReceive;
		myAjax.send('');
	
}

//addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
function changClear(element){

	while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
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
function clear_text_3(){

	for(var i=0;i<6;i++){

					document.getElementById('nai'+i).innerHTML='';
					document.getElementById('daeinP'+i).innerHTML='';
					document.getElementById('daemolP'+i).innerHTML='';
					document.getElementById('jasonP'+i).innerHTML='';
					document.getElementById('charP'+i).innerHTML='';
					document.getElementById('totalP'+i).innerHTML='';
					document.getElementById('hP'+i).innerHTML='';
					document.getElementById('h2P'+i).innerHTML='';
					document.getElementById('h3P'+i).innerHTML='';
					document.getElementById('h4P'+i).innerHTML='';

	}
}