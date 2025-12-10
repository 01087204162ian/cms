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
	$("#insuranceComNum").val(99);
	cordSerch();
}
function cordSerch(pages){
		//alert($pnum)
	//alert('t');
		$("#sjo").children().remove();

			
			var send_url = "./ajax/_ilban.php";
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
											"verify":$(this).find('verify').text(),
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
		for(var i=0;i<certi_max_num;i++){
			var j=i+1;
			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\""
				str += "<input type='hidden'  id='num"
						+i+"'" 
						+">\n";
			    str += "<td class='center_td' rowspan='3'>"
					   
						+j
					 +"</td>\n";
				str += "<td class='center_td' colspan='2'>"  //주민/사업자번호
						+"<input type='text' class='textareP' id='jumin"
						+i+"'"
						+"></td>\n";	
				 str += "<td class='center_td'>"
					    +"<select "
						 +" id='insCom"+i+"'"
						 +" onchange='insuranceShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>==선택==</option>"
						+"<option value='1'>흥국</option>"
						+"<option value='2'>동부</option>"
						+"<option value='3'>KB</option>"
						+"<option value='4'>현대</option>"
						+"<option value='5'>한화</option>"
						+"<option value='6'>더케이</option>"
						+"<option value='7'>MG</option>"
						+"<option value='8'>삼성</option>"
						+"<option value='21'>CHUBB</option>"
					    +"</select>"
					    +"</td>\n";
				
				str += "<td class='center_td'>"  //대리점
						+"<select "
						 +" id='product_"+i+"'"
						 +" onchange='insuranceShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>==선택==</option>"
						+"<option value='1'>혼유</option>"
						+"<option value='2'>복합운송</option>"
						+"<option value='3'>화재</option>"
						+"<option value='4'>근재</option>"
						+"<option value='5'>생산물</option>"
						+"<option value='6'>체육시설</option>"
						+"<option value='7'>자동차</option>"
						
					    +"</select>"
					    +"</td>\n";

					
				str += "<td class='center_td'>"  //증권번호
						+"<input type='text' class='textareP' id='cord"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //시기
						+"<input type='text' class='textareP' id='sigi"
						+i+"'"
						+"></td>\n";
					
				
				str += "<td class='center_td'>"  //보험료
						+"<input type='text' style='text-align:right' class='textareP' id='cord2" 
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //비밀번호
						+"<input type='text' class='textareP' id='password"
						+i+"'"
						//+" onBlur='passChange("+i+")"+"'"
						+"></td>\n";
				 
				
					 

				
					 
				
				str += "<td rowspan='3'>"
						+"<input type='button' id='eSto' class='osj-b' value='수정'"
						+" onclick='passSt("+i+")' "+";return false;'"
						+">";
						+"</td>\n";
			str += "</tr>\n";

			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\">"
				
				 str += "<td class='center_td'>"  //지점장
						+"<input type='text' class='textareP' id='jijem"
						+i+"'"
						+"></td>\n";
					
				
				str += "<td class='center_td'>"  //핸드폰
						+"<input type='text' class='textareP' id='hphone"
						+i+"'"
						+" onBlur='hphoneBlur("+i+")"+"'"
						+" onClick='hphoneClick("+i+")"+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //지점전화번호
						+"<input type='text' class='textareP' id='phone"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //피보험자
						+"<input type='text' class='textareP' id='verify"
						+i+"'"
						+"></td>\n";	
				
				str += "<td class='center_td'>"  //지점팩스
						+"<input type='text' class='textareP' id='fax"
						+i+"'"
						+"></td>\n";
					
				
				
				str += "<td class='center_td' >"  //특이사항
						+"<input type='text' class='textareP' id='gita_"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //update
						+certiTableValue[i].wdate
						
						+"</td>\n";
				
				
					
			str += "</tr>\n";
			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\">"
			     str += "<td class='center_td' colspan='8'>"  //특이사항
						+"<input type='text' style='text-align:left' class='textareP' id='gita"
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
			$("#verify"+i).val(certiTableValue[i].verify);

			$("#jijem"+i).val(certiTableValue[i].jijem);
			$("#hphone"+i).val(certiTableValue[i].hphone);
			$("#phone"+i).val(certiTableValue[i].phone );
			$("#fax"+i).val(certiTableValue[i].fax);

			$("#gita"+i).val(certiTableValue[i].gita);
			$("#gita"+i).css("color","#E4690C");
			$("#carNumber"+i).val(certiTableValue[i].carNumber);
			$("#jumin"+i).val(certiTableValue[i].jumin);
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

			$("#sigi"+i).val(certiTableValue[i].sigi);
		
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

	function insuranceShow(i){

	}

//비밀번호 변경
function passChange(i){

	if($("#num"+i).val()){

		
	    var send_url = "./ajax/_cord.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"pass_sujeong",						   
						   num:$("#num"+i).val(),					   
						   password:$("#password"+i).val()
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
							alert('비밀번호 변경완료!!');
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

			var send_url = "./ajax/_ilban.php";
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
						   verify:$("#verify"+i).val(),
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
			str += "<tr>";
				str += "<input type='hidden'  id='num"
						+i+"'" 
						+">\n";
			    str += "<td class='center_td' rowspan='3'>"
					   
						+j
					 +"</td>\n";
				str += "<td class='center_td' colspan='2'>"  //주민/사업자번호
						+"<input type='text' class='textareP' id='jumin"
						+i+"'"
						+"></td>\n";	
				 str += "<td class='center_td'>"
					    +"<select "
						 +" id='insCom"+i+"'"
						 +" onchange='insuranceShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>=선택=</option>"
						+"<option value='1'>흥국</option>"
						+"<option value='2'>동부</option>"
						+"<option value='3'>KB</option>"
						+"<option value='4'>현대</option>"
						+"<option value='5'>한화</option>"
						+"<option value='6'>더케이</option>"
						+"<option value='7'>MG</option>"
						+"<option value='8'>삼성</option>"
						+"<option value='21'>CHUBB</option>"
					    +"</select>"
					    +"</td>\n";
				
				str += "<td class='center_td'>"  //상품
						+"<select "
						 +" id='product_"+i+"'"
						 +" onchange='insuranceShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>==선택==</option>"
						+"<option value='99'>==선택==</option>"
						+"<option value='1'>혼유</option>"
						+"<option value='2'>복합운송</option>"
						+"<option value='3'>화재</option>"
						+"<option value='4'>근재</option>"
						+"<option value='5'>생산물</option>"
						+"<option value='6'>체육시설</option>"
						+"<option value='7'>자동차</option>"
						
					    +"</select>"
					    +"</td>\n";
					
				str += "<td class='center_td'>"  //증권번호
						+"<input type='text' class='textareP' id='cord"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //시기
						+"<input type='text' class='textareP' id='sigi"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //보험료
						+"<input type='text' class='textareP' id='cord2"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //비밀번호
						+"<input type='text' class='textareP' id='password"
						+i+"'"
						+"></td>\n";

				str += "<td rowspan='3'>"
						+"<input type='button' id='eSto' class='osj-b' value='저장'"
						+" onclick='passSt("+i+")' "+";return false;'"
						+">";
						+"</td>\n";
			str += "</tr>\n";

			str += "<tr>";
				
				 str += "<td class='center_td'>"  //지점장
						+"<input type='text' class='textareP' id='jijem"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //지점여직원
						+"<input type='text' class='textareP' id='hphone"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //지점전화번호
						+"<input type='text' class='textareP' id='phone"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //피보험자
						+"<input type='text' class='textareP' id='verify"
						+i+"'"
						+"></td>\n";	
				
				str += "<td class='center_td'>"  //차량번호
						+"<input type='text' class='textareP' id='carNumber"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //지점팩스
						+"<input type='text' class='textareP' id='fax"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td' >"  //특이사항
						+"<input type='text' class='textareP' id='gita_"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //인증번호
						
						
						+"</td>\n";
				
					
			str += "</tr>\n";

			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\">"

				str += "<td class='center_td' colspan='8'>"  //특이사항
						+"<input type='text' style='text-align:left' class='textareP' id='gita"
						+i+"'"
						+"></td>\n";
					
			str += "</tr>\n";

		}
        str += "";
	
			$("#sjo").append(str);

			for(var i=0;i<1;i++){
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
			}


}

function control(){ //serch 조회하는 순간 보험사,종목 off

	$("#insuranceComNum").val(99);
	$("#insuranceComNum").val(99);
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