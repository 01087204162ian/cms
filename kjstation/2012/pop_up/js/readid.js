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
	//alert('1');
		$("#sjo").children().remove();
			var send_url = "/2012/pop_up/ajax/_readid.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"cord",
						   daeriCompanyNum:$("#daeriCompanyNum").val(),
						  // insuranceComNum:$("#insuranceComNum").val(),
						   pages:pages
						 
					
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									
									
									certiTableValue.push( {	
										"num":$(this).find('num').text(),
											//"inscom":$(this).find('insuCompany').text(),
											"agent":$(this).find('agent').text(),
											"user":$(this).find('user').text(),
											//"s_name":$(this).find('s_name').text(),
											"cord":$(this).find('cord').text(),
											//"cord2":$(this).find('cord2').text(),
											//"password":$(this).find('password').text(),
											//"verify":$(this).find('verify').text(),
											//"wdate":$(this).find('wdate').text(),
											//"jijem":$(this).find('jijem').text(),
											//"jijemLady":$(this).find('jijemLady').text(),
											//"phone":$(this).find('phone').text(),
											//"fax":$(this).find('fax').text(),
											"pages":$(this).find('pages').text(),
										    "totalpage":$(this).find('totalpage').text(),
											//"gita":$(this).find('gita').text(),
											"permit":$(this).find('permit').text()
	
										
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
	
		//	alert(certi_max_num);
		
		$("#sjo").children().remove();
			
        str += "";
		for(var i=0;i<certi_max_num;i++){
			var j=i+1;
			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" + 
        			   "onMouseOut =\"style.backgroundColor='';  self.status='';\""
				str += "<input type='text'  id='num"
						+i+"'" 
						+">\n";
			    str += "<td class='center_td' >"
					   
						+j
					 +"</td>\n";
				str += "<td class='center_td'>"  //성명
						+"<input type='text' class='textareP' id='user"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //사용인
						+"<input type='text' class='textareP' id='agent"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //연락처
						+"<input type='text' class='textareP' id='cord"
						+i+"'"
						+"onBlur='con_phone1_check("+i+")'"+ "onClick='con_phone1_check_2("+i+")'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //비밀번호
						+"<input type='text' class='textareP' id='pass"
						+i+"'"
						+"onBlur='passChange("+i+")'"
						+"></td>\n";
					
				str += "<td class='center_td'>"
					    +"<select "
						 +" id='permit"+i+"'"
						 +" onchange='permitShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>==선택==</option>"
						+"<option value='1'>허용</option>"
						+"<option value='2'>차단</option>"
						
					    +"</select>"
					    +"</td>\n";
				str += "<td>"
						+"<input type='button' id='eSto' class='btn-b' value='수정'"
						+" onclick='passSt("+i+")' "+";return false;'"
						+">";
						+"</td>\n";
			str += "</tr>\n";


		}

		str += "<tr>";
					
					str += "<td colspan='7'>"+"<span  id='changeCom'>"+""+"</td>\n";
			  str += "</tr>\n";
        str += "";
	
			$("#sjo").append(str);

		for(var i=0;i<certi_max_num;i++){
			//alert(certiTableValue[i].permit);
			//alert(certiTableValue[i].agent+$("#agent"+i).val());
			$("#num"+i).val(certiTableValue[i].num);
			$("#insCom"+i).val(certiTableValue[i].inscom);
			$("#agent"+i).val(certiTableValue[i].agent);
			$("#user"+i).val(certiTableValue[i].user);
			//$("#s_name"+i).val(certiTableValue[i].s_name);
			$("#cord"+i).val(certiTableValue[i].cord);
			//$("#cord2"+i).val(certiTableValue[i].cord2);

			//$("#password"+i).val(certiTableValue[i].password);
			//$("#verify"+i).val(certiTableValue[i].verify);

			//$("#jijem"+i).val(certiTableValue[i].jijem);
			//$("#jijemLady"+i).val(certiTableValue[i].jijemLady);
			//$("#phone"+i).val(certiTableValue[i].phone );
			//$("#fax"+i).val(certiTableValue[i].fax);

			$("#permit"+i).val(certiTableValue[i].permit);
		
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

		//alert($("#password"+i).val());
	    var send_url = "/2012/pop_up/ajax/_readid.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"pass_sujeong",						   
						   num:$("#num"+i).val(),					   
						   password:$("#pass"+i).val()
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						//certiTableValue = new Array();
							$(xml).find('item').each(function() {
									alert('비밀번호 변경완료!!');	
									$("#pass"+i).val('');
								 }); //item 끝

		            });
					
							
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
	if(!$("#user"+i).val()){
		alert('성명을 입력하세요');
		$("#user"+i).focus();
		return false;
	}
	if(!$("#agent"+i).val()){
		alert('아이디 입력하세요');
		$("#agent"+i).focus();
		return false;
	}
	if(!$("#cord"+i).val()){
		alert('핸드폰번호 입력하세요');
		$("#cord"+i).focus();
		return false;
	}
	if(!$("#num"+i).val()){
		if(!$("#pass"+i).val()){
			alert('비밀번호를 입력하세요');
			$("#pass"+i).focus();
			return false;
		}
	}
	if($("#check"+i).val()==1){
		alert('사용할 수 없는 아이디입니다.');
		$("#cord"+i).focus();
		return false;
	}
	if($("#permit"+i).val()==99){
		alert('차단 또는 허용을 선택하세요.');
		$("#permit"+i).focus();
		return false;
	}
	
			var send_url = "/2012/pop_up/ajax/_readid.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"cord_sujeong",
						   daeriCompanyNum:$("#daeriCompanyNum").val(),
						   num:$("#num"+i).val(),
						   user:$("#user"+i).val(),
						   mem_id:$("#agent"+i).val(),
						   hphone:$("#cord"+i).val(),
						   password:$("#pass"+i).val(),
						   permit:$("#permit"+i).val(),
						   
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									alert($(this).find('message').text());
									$('#num'+i).val($(this).find('CostomerNum').text());
									if($(this).find('CostomerNum').text()){
										$("#pass"+i).val('');
										$("#eSto").val('수정');
										$('#idcheck'+i).html("읽기 전용 아이디가 등록되었습니다");
									}
									
												
								 }); //policy 끝

		            });
						
					cordSerch();
						
				});

}
function storeList(i){

	$("#num"+i).val(certiTableValue[0].num)

}

function sin_cord(){ //신규 등록
		

		//alert('1');
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
				 
				str += "<td class='center_td'>"  //비밀번호
						+"<input type='text' class='textareP' id='user"
						+i+"'"
						+"></td>\n";
				str += "<td class='center_td'>"  //id
						+"<input type='text' class='textareP' id='agent"
						+i+"'"
						+"onblur='idcheck("+i+")'"
						+"onClick='idcheck2("+i+")'"
						+"></td>\n";
					
				str += "<td class='center_td'>"  //연락처
						+"<input type='text' class='textareP' id='cord"
						+i+"'"
						+"onBlur='con_phone1_check("+i+")'"+ "onClick='con_phone1_check_2("+i+")'"
						+"></td>\n";
					 
				str += "<td class='center_td'>"  //비밀번호
						+"<input type='text' class='textareP' id='pass"
						+i+"'"
						+"></td>\n";
					
				str += "<td class='center_td'>"
					    +"<select "
						 +" id='permit"+i+"'"
						 +" onchange='permitShow("+i+")"+"'"
						 +">"
					    +"<option value='99'>==선택==</option>"
						+"<option value='1'>허용</option>"
						+"<option value='2'>차단</option>"
						
					    +"</select>"
					    +"</td>\n";
				
				str += "<td rowspan='2'>"
						+"<input type='button' id='eSto' class='btn-b' value='저장'"
						+" onclick='passSt("+i+")' "+";return false;'"
						+">";
						+"</td>\n";
			
			str += "</tr>\n";	
			str += "<tr>";
				 str += "<td class='center_td' colspan='7'>"  //아이디 조회 결과
						str +="<span id='idcheck"+i+"'></span>"
						str +="<input id='check"+i+"' type='hidden' >"
						+"</td>\n";
			str += "</tr>\n";

		}
        str += "";
	
			$("#sjo").append(str);
			$('#idcheck'+0).html('아이디는 3자리 이상으로 만들어요!');

}
function permitShow(i){

	if($("#num"+i).val()){

		
	    var send_url = "/2012/pop_up/ajax/_readid.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"permit_sujeong",						   
						   num:$("#num"+i).val(),					   
						   permit:$("#permit"+i).val()
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						//certiTableValue = new Array();
							$(xml).find('item').each(function() {
									//certiTableValue.push( {		  
									//	"num":$(this).find('num').text()		
									//} );						
								 }); //item 끝

		            });
						
						if($("#num"+i).val()){
							alert('변경완료!!');
						}	
				});

	}
}
function idcheck2(i){

	$("#agent"+i).val('');
	$('#check').val('');
	$('#idcheck').html('');
}
function idcheck(i){
	if($("#agent"+i).val().length>3){
	var send_url = "/2012/pop_up/ajax/_readid.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"id_serch", 
						   num:$("#num"+i).val(),  
						   mem_id:$("#agent"+i).val(),
						  
					 }
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

						certiTableValue = new Array();
							$(xml).find('item').each(function() {
									$('#check'+i).val($(this).find('check').text());//check 1이면 사용불가	2이면 사용가능
									$('#idcheck'+i).html($(this).find('total').text());
									if($(this).find('check').text()==1){

										$("#agent"+i).val('');
									}
												
								 }); //policy 끝

		            });
						
					/*	if($("#num"+i).val()){

							alert('수정완료');
						}else{
							
							alert('저장완료');
							$("#eSto").val('수정');

							storeList(i);
						}
					*/	
						//dp_certiList()
						
				});
	}else{



	}


}
function con_phone1_check(i){

	var val=$('#cord'+i).val();
	if(eval(val.length)==9){
			var phone_first	=val.substring(0,2)
			var phone_second =val.substring(2,5)
			var phone_third	 =val.substring(5,9)
		$('#cord'+i).val(phone_first+"-"+phone_second+"-"+phone_third);
	}else if(eval(val.length)==10){
    		var phone_first		=val.substring(0,3)
			var phone_second   =val.substring(3,6)
			var phone_third	 	=val.substring(6,10)

		$('#cord'+i).val(phone_first+"-"+phone_second+"-"+phone_third);

	}if(eval(val.length)==11){

			var phone_first		=val.substring(0,3)
			var phone_second   =val.substring(3,7)
			var phone_third	 	=val.substring(7,11)			
		$('#cord'+i).val(phone_first+"-"+phone_second+"-"+phone_third);

	}else if(eval(val.length)>0 && eval(val.length)<9){
			alert('번호 !!')
			
			$('#cord'+i).focus();
			$('#cord'+i).val('');
			return false;

	}
}


function con_phone1_check_2(i){
		
	  var val=$('#cord'+i).val();
	  if(val.length=='11'){
			
			var phone_first	=val.substring(0,2)
			var phone_second=val.substring(3,6)
			var phone_third	 =val.substring(7,12)

			 $('#cord'+i).val(phone_first+phone_second+phone_third);
			
	 }else if(val.length=='12'){
			
			var phone_first		=val.substring(0,3)
			var phone_second		=val.substring(4,7)
			var phone_third	 	=val.substring(8,13)

			 $('#cord'+i).val(phone_first+phone_second+phone_third);

	 }else if(val.length=='13'){
			

			var phone_first		=val.substring(0,3)
			var phone_second	=val.substring(4,8)
			var phone_third	 	=val.substring(9,13)

			 $('#cord'+i).val(phone_first+phone_second+phone_third);
		
	  }

	 
	}