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

addLoadEvent(cordSerch)
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
				if(j==1){ 
					rows[j].style.backgroundColor="#e9f8c0";
				    odd=true;

				}else if(j==2){ 
					rows[j].style.backgroundColor="#e9f8c0";
				    odd=true;

				}else{
				rows[j].style.backgroundColor="#ffffff";
				odd=false;
				}
				
			}else{
				//rows[j].style.backgroundColor="#f9f8f0";
				if(j==0){

					rows[j].style.backgroundColor="#e9f8c0";
				    odd=true;
				}else if(j==2){ 
					rows[j].style.backgroundColor="#e9f8c0";
				    odd=false;

				}else{

				rows[j].style.backgroundColor="#f9f8f0";
				odd=false;
				}

			}
		}

		//var thd=tables[i].getElementsByTagName("thead");
		//alert( i+'번'+thd.length);
	}
	
}
addLoadEvent(stripeTables);

function cordSerch2(){ // 다시 읽기 하면 검색을 초기화 하기위해
	$("#kind").val(99);
	$("#content").val('');
	$("#insuranceComNum").val(99);
	$("#product").val(99);
	cordSerch();
}
function cordSerch(pages){
		//alert($pnum)
	//alert('t');
		$("#sjo").children().remove();

			
			var send_url = "./ajax/_dongbushin.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"cord",
						   kind:$("#kind").val(),
						   content:$("#content").val(),
						   insuranceComNum:$("#insuranceComNum").val(),
						   pages:pages,
						   product:$("#product").val()
						 
					
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									//alert($(this).find('s_nai').text());
									certiTableValue.push( {	
										    "num":$(this).find('num').text(),
											"inscom":$(this).find('insuCompany').text(),
											"product":$(this).find('product').text(),
											"sigi":$(this).find('sigi').text(),
											"cord":$(this).find('cord').text(),
											"cord2":$(this).find('cord2').text(),
											"password":$(this).find('password').text(),
											"selNum":$(this).find('selNum').text(),
											"wdate":$(this).find('wdate').text(),
											"jijem":$(this).find('jijem').text(),
											"hphone":$(this).find('hphone').text(),
											"phone":$(this).find('phone').text(),
											"fax":$(this).find('fax').text(),
											"pages":$(this).find('pages').text(),
										    "totalpage":$(this).find('totalpage').text(),
											"gita":$(this).find('gita').text(),
											"carNumber":$(this).find('carNumber').text(),
											"jumin":$(this).find('jumin').text()
	
										
									} );
									
												
								 }); //policy 끝


							
		            });
						
						certi_max_num = certiTableValue.length;	
						
						dp_certiList()
						
				});
}
function dp_certiList(pages){
		var sunbun=0;
		var str="";
	
	
		
		$("#sjo").children().remove();
			
        str += "";

		if(certi_max_num>=1){
		for(var i=0;i<certi_max_num;i++){
			var j=i+1;
			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\""
				str += "<input type='hidden'  id='num"
						+i+"'" 
						+">\n";
			    str += "<td class='center_td' rowspan='2'>" 
						+j
					 +"</td>\n";
				 str += "<td class='center_td'>"  //성명
						+"<input type='text' class='textareP' id='jijem"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td' >"  //주민/사업자번호
						+"<input type='text' class='textareP' id='jumin"
						+i+"'"
						+" onBlur='juminBlur("+i+")"+"'"
						+" onClick='juminClick("+i+")"+"'"
						+"></td>\n";	
				
				str += "<td class='center_td'>"  //핸드폰
						+"<input type='text' class='textareP' id='hphone"
						+i+"'"
						+" onBlur='hphoneBlur("+i+")"+"'"
						+" onClick='hphoneClick("+i+")"+"'"
						+"></td>\n";
				
				
				str += "<td class='center_td'>"  //진행상황
						+"<select "
						 +" id='product_"+i+"'"
						 +" onchange='insuranceShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>==선택==</option>"
						+"<option value='1'>신청</option>"
						+"<option value='2'>심사중</option>"
						+"<option value='3'>거절</option>"
						+"<option value='4'>입금대기</option>"
						+"<option value='5'>계약</option>"
						+"<option value='6'>생각중</option>"
						+"<option value='7'>자동차</option>"
						
					    +"</select>"
					    +"</td>\n";

				
				str += "<td class='center_td'>"  //설계번호
						+"<input type='text' class='textareP' id='selNum"
						+i+"'"
						+" onBlur='selbunBlur("+i+")"+"'"
					//	+" onClick='hphoneClick("+i+")"+"'"
						+"></td>\n";	
				
				str += "<td class='center_td' colspan='2'>"  //자동이체계좌번ㄴ호
						+"<input type='text' class='textareP2' id='sigi"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //신청일
						+certiTableValue[i].wdate
						+"</td>\n";	
					
				str += "<td rowspan='2'>"
						+"<input type='button' id='eSto' class='osj-b' value='수정'"
						+" onclick='passSt("+i+")' "+";return false;'"
						+">";
						+"</td>\n";
			str += "</tr>\n";

			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\">"
					 
				str += "<td class='center_td' colspan='8' >"  //주소
						+"<input type='text' class='textareP2' id='phone" 
						+i+"'"
						+"></td>\n";
				
					
			str += "</tr>\n";
			
			
		}

		str += "<tr>";
			str += "<td colspan='10'>"+"<span  id='changeCom'>"+""+"</td>\n";
		str += "</tr>\n";
        str += "";
	
			$("#sjo").append(str);

		for(var i=0;i<certi_max_num;i++){
			//alert(certiTableValue[i].agent+$("#agent"+i).val());
			$("#num"+i).val(certiTableValue[i].num);
			$("#insCom"+i).val(certiTableValue[i].inscom);
			$("#product_"+i).val(certiTableValue[i].product);
			//$("#sigi"+i).val(certiTableValue[i].sigi);
			$("#cord"+i).val(certiTableValue[i].cord);
			$("#cord2"+i).val(certiTableValue[i].cord2);

			$("#password"+i).val(certiTableValue[i].password);
			$("#selNum"+i).val(certiTableValue[i].selNum);

			$("#jijem"+i).val(certiTableValue[i].jijem);
			$("#hphone"+i).val(certiTableValue[i].hphone);
			$("#phone"+i).val(certiTableValue[i].phone );
			$("#fax"+i).val(certiTableValue[i].fax);

			$("#gita"+i).val(certiTableValue[i].gita);
			$("#gita"+i).css("color","#E4690C");
			$("#carNumber"+i).val(certiTableValue[i].carNumber);
			$("#jumin"+i).val(certiTableValue[i].jumin);
		/*	$( "#sigi" +i).datepicker({
					dateFormat: 'yy-mm-dd',
					prevText: '이전 달',
					nextText: '다음 달',
					monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
					monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
					dayNames: ['일','월','화','수','목','금','토'],
					dayNamesShort: ['일','월','화','수','목','금','토'],
					dayNamesMin: ['일','월','화','수','목','금','토'],
					showMonthAfterYear: true,
					yearSuffix: '년'
				  });*/

			$("#sigi"+i).val(certiTableValue[i].sigi);
		
		}

		}else{
			alert('조회결과가 없습니다');

		}

/*pag 구성을 위해****************************/
			$("#changeCom").children().remove();


		var newInput2=document.createElement("select");
		// var aJumin=document.getElementById('B16b'+k);
		 newInput2.id='diffC';
		 newInput2.style.width = '70px';
		 newInput2.className='selectbox';
		 newInput2.onchange=pageChange;//page 변경
		 var opts=newInput2.options;
		 opts.length=eval(certiTableValue[0].totalpage)+1;;
		for(var _i=1;_i<opts.length;_i++){	
			//alert(i+"번째"+opts[i].value);
		  if(_i==certiTableValue[0].pages){
			newInput2.selectedIndex=_i;
		  }
		  opts[_i].value=_i;
		  opts[_i].text=_i+'page';
		
		}
	
		$("#changeCom").append(newInput2);

		/*pag 구성을 위해****************************/
	}
/* 진행상황*/
function insuranceShow(i){
		if($("#num"+i).val()){

		
	    var send_url = "./ajax/_dongbushin.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"ch",						   
						   num:$("#num"+i).val(),					   
						   ch:$("#product_"+i).val()
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									certiTableValue.push( {		  
										"num":$(this).find('num').text()		
									} );						
								 }); //item 끝

		            });
						
						if($("#num"+i).val()){
							alert('진행상황정리!!');
						}	
				});

	}
}

//비밀번호 변경
function selbunBlur(i){

	if($("#num"+i).val()){

		
	    var send_url = "./ajax/_dongbushin.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"selNum",						   
						   num:$("#num"+i).val(),					   
						   selNum:$("#selNum"+i).val()
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									certiTableValue.push( {		  
										"num":$(this).find('num').text()		
									} );						
								 }); //item 끝

		            });
						
						if($("#num"+i).val()){
							alert('설계번호!!');
						}	
				});

	}

}
function pageChange(){

	var dNum=	$("#daeriCompanyNum").val();
	var cNum=	$("#certiTableNum").val();
	var pages =this.value;
	 cordSerch(pages);
}

function passSt(i){//수정 또는 저장

			var send_url = "./ajax/_dongbushin.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"cord_sujeong",
						   
						   num:$("#num"+i).val(),
						   insCom:$("#insCom"+i).val(),
						   product:$("#product_"+i).val(),
						   sigi:$("#sigi"+i).val(),
						   cord:$("#cord"+i).val(),
						   cord2:$("#cord2"+i).val(),
						   password:$("#password"+i).val(),
						   selNum:$("#selNum"+i).val(),
						   jijem:$("#jijem"+i).val(),
						   hphone:$("#hphone"+i).val(),
						   phone:$("#phone"+i).val(),
						   fax:$("#fax"+i).val(),
						   gita:$("#gita"+i).val(),
						   carNumber:$("#carNumber"+i).val(),
						   jumin:$("#jumin"+i).val()
							

						 
					
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									//alert($(this).find('s_nai').text());
									certiTableValue.push( {	
										  
										"num":$(this).find('num').text()	
										
									} );
									
												
								 }); //policy 끝

		            });
						
						if($("#num"+i).val()){

							alert('수정완료');
						}else{
							
							alert('저장완료');
							$("#eSto").val('수정');

							storeList(i);
						}
						
						//dp_certiList()
						
				});

}
function storeList(i){

	$("#num"+i).val(certiTableValue[0].num)

}

function sin_cord(){ //신규 등록
		

		
		var str="";
		$("#sjo").children().remove();
			
        
		for(var i=0;i<1;i++){
			var j=i+1;
			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\""
				str += "<input type='hidden'  id='num"
						+i+"'" 
						+">\n";
			    str += "<td class='center_td' rowspan='2'>" 
						+j
					 +"</td>\n";
				 str += "<td class='center_td'>"  //성명
						+"<input type='text' class='textareP' id='jijem"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td' >"  //주민/사업자번호
						+"<input type='text' class='textareP' id='jumin"
						+i+"'"
						+" onBlur='juminBlur("+i+")"+"'"
						+" onClick='juminClick("+i+")"+"'"
						+"></td>\n";	
				
				str += "<td class='center_td'>"  //핸드폰
						+"<input type='text' class='textareP' id='hphone"
						+i+"'"
						+" onBlur='hphoneBlur("+i+")"+"'"
						+" onClick='hphoneClick("+i+")"+"'"
						+"></td>\n";
				
				
				str += "<td class='center_td'>"  //진행상황
						+"<select "
						 +" id='product_"+i+"'"
						 +" onchange='insuranceShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>==선택==</option>"
						+"<option value='1'>신청</option>"
						+"<option value='2'>심사중</option>"
						+"<option value='3'>거절</option>"
						+"<option value='4'>입금대기</option>"
						+"<option value='5'>계약</option>"
						+"<option value='6'>생각중</option>"
						+"<option value='7'>자동차</option>"
						
					    +"</select>"
					    +"</td>\n";

				str += "<td class='center_td'>"  //신청일
						//+certiTableValue[i].wdate
						+"</td>\n";	 
				str += "<td class='center_td'>"  //설계번호
						+"<input type='text' class='textareP' id='selNum"
						+i+"'"
						+" onBlur='selbunBlur("+i+")"+"'"
					//	+" onClick='hphoneClick("+i+")"+"'"
						+"></td>\n";
				str += "<td class='center_td' colspan='2'>"  //자동이체계좌번ㄴ호
						+"<input type='text' class='textareP2' id='sigi"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //신청일
						
						+"</td>\n";	
				str += "<td rowspan='2'>"
						+"<input type='button' id='eSto' class='osj-b' value='저장'"
						+" onclick='passSt("+i+")' "+";return false;'"
						+">";
						+"</td>\n";
			str += "</tr>\n";

			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\">"
					 
				str += "<td class='center_td' colspan='8' >"  //주소
						+"<input type='text' class='textareP2' id='phone" 
						+i+"'"
						+"></td>\n";
				
					
			str += "</tr>\n";

		}
        str += "";
	
			$("#sjo").append(str);

		/*	for(var i=0;i<1;i++){
				$( "#sigi" +i).datepicker({
					dateFormat: 'yy-mm-dd',
					prevText: '이전 달',
					nextText: '다음 달',
					monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
					monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
					dayNames: ['일','월','화','수','목','금','토'],
					dayNamesShort: ['일','월','화','수','목','금','토'],
					dayNamesMin: ['일','월','화','수','목','금','토'],
					showMonthAfterYear: true,
					yearSuffix: '년'
				  });

				  $("#gita"+i).css("color","#E4690C");
			}*/


}

function control(){ //serch 조회하는 순간 보험사,종목 off

	$("#insuranceComNum").val(99);
	$("#product").val(99);
}
function juminBlur(i){
	if($("#jumin"+i).val().length>0 && $("#jumin"+i).val().length<13){

		alert('13자리!!');

		$("#jumin"+i).focus();
		return false;

	}else if($("#jumin"+i).val().length==13){
		var f=$("#jumin"+i).val().substring(0,6);
		var s=$("#jumin"+i).val().substring(6,13);
		

		$("#jumin"+i).val(f+'-'+s)
	}else{
		alert('13자리!!');

		$("#jumin"+i).focus();
		return false;

	}
	//alert($("#jumin"+i).val(f+'-'+s+'-'+t))

}

function juminClick(i){

	if($("#jumin"+i).val().length==14){
		var f=$("#jumin"+i).val().substring(0,6);
		var s=$("#jumin"+i).val().substring(7,14);
		//alert($("#jumin"+i).val());

		$("#jumin"+i).val(f+s);
	}

}
function hphoneBlur(i){
	if($("#hphone"+i).val().length>0 && $("#hphone"+i).val().length<10){

		alert('11자리!!');

		$("#hphone"+i).focus();
		return false;

	}else if($("#hphone"+i).val().length==11){
		var f=$("#hphone"+i).val().substring(0,3);
		var s=$("#hphone"+i).val().substring(3,7);
		var t=$("#hphone"+i).val().substring(7,11);

		$("#hphone"+i).val(f+'-'+s+'-'+t)
	}else{
		alert('11자리!!');

		$("#hphone"+i).focus();
		return false;

	}
	//alert($("#hphone"+i).val(f+'-'+s+'-'+t))

}

function hphoneClick(i){

	if($("#hphone"+i).val().length==13){
		var f=$("#hphone"+i).val().substring(0,3);
		var s=$("#hphone"+i).val().substring(4,8);
		var t=$("#hphone"+i).val().substring(9,13);

		$("#hphone"+i).val(f+s+t);
	}

}