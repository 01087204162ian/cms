//hpHone(k,hphone,shp,nhp,address);//순서,핸드폰,통신사,명의자,주소
function hpHone(k,hphone,shp,nhp,address,injeong){//핸드폰번호

	 var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A29a'+k);
	 newInput2.type='text';
	 if(k%2==0){
		 newInput2.className='handphoneInput0';
	 }else{
		newInput2.className='handphoneInput';
	 }
	 newInput2.id='heck'+k;
	
	 newInput2.value=hphone;
     newInput2.onblur=HphoneInput;
	 newInput2.onclick=clickHphone;//
	 //newInput2.style.width = '80px';
	 
	 aJumin.appendChild(newInput2);

	 //통신사

	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A30a'+k);
	 newInput2.id='shp'+k;
	 //newInput2.style.width = '50px';
	 newInput2.className='selectbox';
	newInput2.onchange=changeSk;
	 var opts=newInput2.options;
	//alert(k2)
	opts.length=7;
	/*if(eval(etag)==1){
			newInput2.style.color='#cccccc';	
		}else{
			newInput2.style.color='#E4690C';		
		}*/
	 for(var i=0;i<opts.length;i++){	
		 if(i==0){ 
			 opts[i].value=-1;
		 }else{
		   opts[i].value=i;
	     }
		//alert(i)
		if(opts[i].value==eval(shp)){	
			newInput2.selectedIndex=i;
		}
		//alert(i)
			
				switch(i){
				   case 0 :
					  opts[i].text='통신사';
					//newInput2.style.color='#cccccc'
						break;
					case 1 :
						opts[i].text='SK';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 2 :
						opts[i].text='KT';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 3 :
						opts[i].text='LG';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 4 :
						opts[i].text='SK알뜰폰';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 5 :
						opts[i].text='KT알뜰폰';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 6 :
						opts[i].text='LG알뜰폰';
					    // newInput2.style.color='#FA8FC1'
					break;

					
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	// aJumin.appendChild(newInput2);



	 //명의자

	 var newInput2=document.createElement("select");
	 var aJumin=document.getElementById('A31a'+k);
	 newInput2.id='nhp'+k;
	 //newInput2.style.width = '50px';
	 newInput2.className='selectbox';
	 newInput2.onchange=changeNhp;
	 var opts=newInput2.options;
	//alert(injeong)
	if(eval(injeong)==1){ //업체인 경우
		opts.length=4;
	}else{
		opts.length=4;
	}

	//alert(opts.length)
	if(eval(nhp)==2){
			newInput2.style.color='#E4690C';	
	}
	if(eval(nhp)==3){
			newInput2.style.color='#E4690C';	
	}
	 for(var i=0;i<opts.length;i++){	
		 if(i==0){ 
			 opts[i].value=-1;
		 }else{
		   opts[i].value=i;
	     }
		//alert(i)
		if(opts[i].value==eval(nhp)){	
			newInput2.selectedIndex=i;
		}
		
			
				switch(i){
				   case 0 :
					  opts[i].text='명의';
					//newInput2.style.color='#cccccc'
						break;
					case 1 :
						opts[i].text='본인';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 2 :
						opts[i].text='타인';
					    // newInput2.style.color='#FA8FC1'
					break;
					case 3 :
						opts[i].text='인증';
					    // newInput2.style.color='#FA8FC1'
					break;
				
				}
			
	
	 }
	//if(document.getElementById('sang'+k)){
	//	aJumin.removeChild(aJumin.lastChild);
	//}
	//newInput2.onchange=changeDong;
	// aJumin.appendChild(newInput2);

	//주소
	var newInput2=document.createElement("input");
	 var aJumin=document.getElementById('A32a'+k);
	 newInput2.type='text';
	 if(k%2==0){
		newInput2.className='addressInput0';
	 }else{
		newInput2.className='addressInput';
	 }
	 newInput2.id='address'+k;
	
	 newInput2.value=address;
     newInput2.onblur=addressInput;//주소변경
	// newInput2.onclick=clickHphone;//
	// newInput2.style.width = '200px';
	 
	 aJumin.appendChild(newInput2);
}
function hphoneReceive(){

	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
	    	if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
			
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
		}else{
			alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
		
		}

	}
}
//주소변경
function addressInput(){

	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==7){
		bunho=nn.substr(7,8);
	}else{
		bunho=nn.substr(7,9);
	}

	//alert(bunho);
	
	var memberNum=$('#num'+bunho).val();
	var address=$('#address'+bunho).val(); //통신사//통신사 1sk 2kt 3lig 4sk알뜰폰	5kt알뜰폰 6lig알뜰폰

	if(address){
		var send_url = "/2012c/intro/ajax/_jeongLi.php";
		$.ajax({
				type: "GET",
				url:send_url,
				dataType : "xml",
				data:{ proc:"_tongAddress",memberNum:memberNum,address:address }
			}).done(function( xml ) {

				
				$(xml).find('values').each(function(){
					alert($('#A2a'+bunho).html()+'님의'+$(this).find('message').text());
							
				});
					
			});
	}

}
//명의자 변경
function changeNhp(){
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==4){
		bunho=nn.substr(3,4);
	}else{
		bunho=nn.substr(3,6);
	}

	//alert(bunho);
	
	var memberNum=$('#num'+bunho).val();
	var nhp=$('#nhp'+bunho).val(); //본인1 타인2 3인증

	if(nhp<0){

			alert('명의자는 본인 또는 타인을  선택하세요');
			$('#nhp'+bunho).focus();
			return false;
		}
				var send_url = "/2012c/intro/ajax/_jeongLi.php";
			
		//alert(send_url+d_ate)

			
			$.ajax({
					type: "GET",
					url:send_url,
					dataType : "xml",
					data:{ proc:"_tongName",memberNum:memberNum,nhp:nhp }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){
						alert($('#A2a'+bunho).html()+'님의'+$(this).find('message').text());
								
		            });
						
				});

}
//통신사 변경
function changeSk(){
	var nn=this.getAttribute("id");
	//alert(nn);
	if(nn.length==4){
		bunho=nn.substr(3,4);
	}else{
		bunho=nn.substr(3,6);
	}

	//alert(bunho);
	
	var memberNum=$('#num'+bunho).val();
	var shp=$('#shp'+bunho).val(); //통신사//통신사 1sk 2kt 3lig 4sk알뜰폰	5kt알뜰폰 6lig알뜰폰
		if(shp<0){

			alert('통신사를 선택하세요');
			$('#shp'+bunho).focus();
			return false;
		}
				var send_url = "/2012c/intro/ajax/_jeongLi.php";
			
		//alert(send_url+d_ate)

			
			$.ajax({
					type: "GET",
					url:send_url,
					dataType : "xml",
					data:{ proc:"_tongSin",memberNum:memberNum,shp:shp }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){
						alert($('#A2a'+bunho).html()+'님의'+$(this).find('message').text());
								//	alert($(this).find('sj').text());
						//certi Table 조회 결과
						/*	certiTableValue = new Array();
							$(xml).find('item').each(function() {
								
								alert(
								
							});//certi Table 조회 결과
					
							//policy 리스트 화면에 표시하기 위해 */
						
						// dp_certiList();
						
		            });
						
				});
}

function HphoneInput(){
	var nn=this.getAttribute("id");



	if(nn.length==5){
		bunho=nn.substr(4,5);
	}else{
		bunho=nn.substr(4,6);
	}

	var memberNum=document.getElementById('num'+bunho).value;
	var val=this.value;
	if(val.length==11){
			var first=val.substr(0,3);
			var second=val.substr(3,4);
			var third=val.substr(7,4);

			//alert(second);
			this.value=first+'-'+second+'-'+third;
		
			var toSend = "/2012c/intro/ajax/hphone.php?hphone="+this.value
						//+"&insuranceComNum="+insuranceComNum
						+"&memberNum="+memberNum;
					 

	//alert(toSend)

	myAjax.open("get",toSend,true);
	myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
	myAjax.onreadystatechange=hphoneReceive;
	myAjax.send('');
	}


			

}
function clickHphone(){

	var nn=this.getAttribute("id");

	if(nn.length==5){
		bunho=nn.substr(4,5);
	}else{
		bunho=nn.substr(4,6);
	}
	var val=this.value;
	if(val.length==13){
			var first=val.substr(0,3);
			var second=val.substr(4,4);
			var third=val.substr(9,4);

			//alert(second);
			this.value=first+second+third;

	}
}