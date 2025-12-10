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

				}else{
				rows[j].style.backgroundColor="#ffffff";
				odd=false;
				}
				
			}else{
				//rows[j].style.backgroundColor="#f9f8f0";
				if(j==0){

					rows[j].style.backgroundColor="#e9f8c0";
				    odd=true;
				}else if(j==1){ 
					rows[j].style.backgroundColor="#e9f8c0";
				    odd=true;

				}else{

				rows[j].style.backgroundColor="#f9f8f0";
				odd=true;
				}

			}
		}

		//var thd=tables[i].getElementsByTagName("thead");
		//alert( i+'번'+thd.length);
	}
	
}
addLoadEvent(stripeTables);


function cordSerch(pages){
		//alert($pnum)
	//alert('t');
		$("#sjo").children().remove();
			var send_url = "./ajax/_cord.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"cord",
						   insuranceComNum:$("#insuranceComNum").val(),
						   pages:pages
						 
					
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									//alert($(this).find('s_nai').text());
									certiTableValue.push( {	
										"num":$(this).find('num').text(),
											"inscom":$(this).find('insuCompany').text(),
											"agent":$(this).find('agent').text(),
											"s_name":$(this).find('s_name').text(),
											"cord":$(this).find('cord').text(),
											"cord2":$(this).find('cord2').text(),
											"password":$(this).find('password').text(),
											"verify":$(this).find('verify').text(),
											"wdate":$(this).find('wdate').text(),
											"jijem":$(this).find('jijem').text(),
											"jijemLady":$(this).find('jijemLady').text(),
											"phone":$(this).find('phone').text(),
											"fax":$(this).find('fax').text(),
											"pages":$(this).find('pages').text(),
										    "totalpage":$(this).find('totalpage').text(),
											"gita":$(this).find('gita').text()
	
										
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
			    str += "<td class='center_td' rowspan='2'>"
					   
						+j
					 +"</td>\n";
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
						+"<option value='6'>메리츠</option>"
						+"<option value='7'>MG</option>"
						+"<option value='8'>삼성</option>"
					    +"</select>"
					    +"</td>\n";
				
				str += "<td class='center_td'>"  //대리점
						+"<input type='text' class='textareP' id='agent"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //코드
						+"<input type='text' class='textareP' id='cord"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //사용인
						+"<input type='text' class='textareP' id='s_name"
						+i+"'"
						+"></td>\n";
					
				
				str += "<td class='center_td'>"  //사용인코드
						+"<input type='text' class='textareP' id='cord2"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //비밀번호
						+"<input type='text' class='textareP' id='password"
						+i+"'"
						+" onBlur='passChange("+i+")"+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //인증번호
						+"<input type='text' class='textareP' id='verify"
						+i+"'"
						+"></td>\n";
					 

				str += "<td class='center_td'>"  //인증번호
						+certiTableValue[i].wdate
						
						+"</td>\n";
					 
				
				str += "<td rowspan='2'>"
						+"<input type='button' id='eSto' class='btn-b' value='수정'"
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
					
				
				str += "<td class='center_td'>"  //지점여직원
						+"<input type='text' class='textareP' id='jijemLady"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //지점전화번호
						+"<input type='text' class='textareP' id='phone"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //지점팩스
						+"<input type='text' class='textareP' id='fax"
						+i+"'"
						+"></td>\n";
					
				
				str += "<td class='center_td' colspan='4'>"  //사용인코드
						+"<input type='text' class='textareP' id='gita"
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
			$("#agent"+i).val(certiTableValue[i].agent);
			$("#s_name"+i).val(certiTableValue[i].s_name);
			$("#cord"+i).val(certiTableValue[i].cord);
			$("#cord2"+i).val(certiTableValue[i].cord2);

			$("#password"+i).val(certiTableValue[i].password);
			$("#verify"+i).val(certiTableValue[i].verify);

			$("#jijem"+i).val(certiTableValue[i].jijem);
			$("#jijemLady"+i).val(certiTableValue[i].jijemLady);
			$("#phone"+i).val(certiTableValue[i].phone );
			$("#fax"+i).val(certiTableValue[i].fax);

			$("#gita"+i).val(certiTableValue[i].gita);
		
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

			var send_url = "./ajax/_cord.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"cord_sujeong",
						   
						   num:$("#num"+i).val(),
						   insCom:$("#insCom"+i).val(),
						   agent:$("#agent"+i).val(),
						   s_name:$("#s_name"+i).val(),
						   cord:$("#cord"+i).val(),
						   cord2:$("#cord2"+i).val(),
						   password:$("#password"+i).val(),
						   verify:$("#verify"+i).val(),
						   jijem:$("#jijem"+i).val(),
						   jijemLady:$("#jijemLady"+i).val(),
						   phone:$("#phone"+i).val(),
						   fax:$("#fax"+i).val(),
						   gita:$("#gita"+i).val()

						 
					
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
			    str += "<td class='center_td' rowspan='2'>"
					   
						+j
					 +"</td>\n";
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
						+"<option value='6'>메리츠</option>"
						+"<option value='7'>MG</option>"
						+"<option value='8'>삼성</option>"
					    +"</select>"
					    +"</td>\n";
				
				str += "<td class='center_td'>"  //대리점
						+"<input type='text' class='textareP' id='agent"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //코드
						+"<input type='text' class='textareP' id='cord"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //사용인
						+"<input type='text' class='textareP' id='s_name"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //사용인코드
						+"<input type='text' class='textareP' id='cord2"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //비밀번호
						+"<input type='text' class='textareP' id='password"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //인증번호
						+"<input type='text' class='textareP' id='verify"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //인증번호
						
						
						+"</td>\n";
					 +"</td>\n";
				
				str += "<td rowspan='2'>"
						+"<input type='button' id='eSto' class='btn-b' value='저장'"
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
						+"<input type='text' class='textareP' id='jijemLady"
						+i+"'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //지점전화번호
						+"<input type='text' class='textareP' id='phone"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //지점팩스
						+"<input type='text' class='textareP' id='fax"
						+i+"'"
						+"></td>\n";
					
				
				str += "<td class='center_td' colspan='4'>"  //사용인코드
						+"<input type='text' class='textareP' id='gita"
						+i+"'"
						+"></td>\n";
					
			str += "</tr>\n";

		}
        str += "";
	
			$("#sjo").append(str);


}