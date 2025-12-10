
function serch(val){
	alert('1')
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
	
	alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=companyReceive;
	myAjax.send('');
}
addLoadEvent(serch);//로딩후 데이타 바로 조회를 위해
