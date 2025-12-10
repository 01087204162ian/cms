
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

addLoadEvent(dp_certiList);
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


addLoadEvent(gasiRead);
	function gasiRead(dNum,cNum,nowtime,content,giho,serchNum,pages){

		
		
		$("#sjo").children().remove();
		
		$("#kr").children().remove();
		$("#jr").children().remove();
			var send_url = "./ajax/_gasi.php";
			
		
			
			
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"gasipan_R",
						pages:pages
						
					
					 }
				}).done(function( xml ) {

					
					
					$(xml).find('values').each(function(){

							certiTableValue = new Array();	
							$(xml).find('item').each(function() {
									
									certiTableValue.push( {	
										"title":$(this).find('title').text(), 	   
										"num":$(this).find('num').text(),
										"wdate":$(this).find('wdate').text(),
										"write":$(this).find('write').text(),
										"hit":$(this).find('hit').text(),
										"notice_chk":$(this).find('notice_chk').text(),
										
										"pages":$(this).find('pages').text(),
										"totalpage":$(this).find('totalpage').text()
										
									} ); //Pvalues 끝

												
								 }); //item 끝


							
		            });
						certi_max_num = certiTableValue.length;	
						
						//alert(certi_max_num);

						gasi_List(giho,nowtime)
				
				});
}

function gasi_List(giho,nowtime){ //giho 1: 성명,주민번호 조회하는 경우 2전체명단 조회하는 경우
	//alert(giho)

	//nowtime 10이면 _c 에서 오는 경우에 명단 Excell,월보험료 분석을 안보이게 하기 위해
		//
		
		var sunbun=0;
		var str="";
		var maxT=certi_max_num;
		//alert(maxT);{
		if(maxT>=1){
			//alert("조회 되었습니다!!");
		}else{
			//alert("조회 결과가 없습니다!!");
			}
		var $dp_type=1;
			//alert(giho);
		$("#sjo").children().remove();
		
	   	   
				
				str += "<tr >";
					str += "<td width='5%'>"+"No"+"</td>\n";
					str += "<td width='68%' colspan='2'>"+"제목"+"</td>\n";
					str += "<td width='8%'>"+"글쓴이"+"</td>\n";
					str += "<td width='14%'>"+"등록일"+"</td>\n";
					str += "<td width='5%'>"+"조회"+"</td>\n";
					
			  str += "</tr>\n";
		
		for(var i=0; i<maxT; i++)
        {
			//alert(certiTableValue[i].beforeMonth);


			var j=i+1+(certiTableValue[0].pages-1)*15;///인원수 pages  
			var p=i%2;
			if(p==1){
				p="R";
			}else{
				p="P";
			}
			str += "<tr onMouseOver=\"style.backgroundColor='#cde2fd'; style.cursor='pointer';  self.status='';\"" 
				    + "onMouseOut =\"style.backgroundColor='';  self.status='';\""
					+ "onclick='gasiWrit("+certiTableValue[i].num+");return false;'\">"
			
				str += "<td>"+j+"</td>\n";
				if(certiTableValue[i].notice_chk==1){
				str += "<td class='center_td' width='5%'>"  //공지
					 +"공지"				 
					 +"</td>\n";
				str += "<td class='ctitle' style='text-align:left'>"  //제목
					 +certiTableValue[i].title
					 +"</td>\n";
				}else{
				str += "<td class='center_td' colspan='2' style='text-align:left'>"  //공지
					 	+certiTableValue[i].title		 
					 +"</td>\n";
				}
				
				
				str += "<td class='center_td'>"  //글쓴이
					 +certiTableValue[i].write
					 +"</td>\n";
				str += "<td class='center_td'>"  //시간
					 +certiTableValue[i].wdate
					 +"</td>\n";
				str += "<td class='center_td'>"  //hit
					 +certiTableValue[i].hit
					 +"</td>\n";
		
			str += "</tr>\n";
		}
		// page 구성
	

			str += "<tr>";
				str += "<td colspan='6'>"+"<span  id='changeCom'>"+""+"</td>\n";
			  str += "</tr>\n";
		
        str += "";
        //alert();

	
			//alert(today);
			$("#sjo").append(str);

			//******************************************************/
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
					//rows[j].style.backgroundColor="#e9f8c0";
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
					//rows[j].style.backgroundColor="#e9f8c0";
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
function fill_up_sjoNum(dNum,cNum,nowtime){

		//alert(dNum+'/'+ cNum +'/' +nowtime);
		screen_height_def();
		$("#daeriCompanyNum").val(dNum);
		$("#certiTableNum").val(cNum);
		 preminum_init(nowtime);
		//alert(nowtime);

	}	
	function preminum_init(nowtime){
		$("#sjo").children().remove();
		//alert(nowtime +'' +preminum_init);
		//보험료 테이블 초기화 

		//alert($("#daeriCompanyNum").val());
		//alert('1')
	//	var cw = document.getElementById('dayPreminum_frame').contentWindow;
		//cw.dayPclear();//dayPreminum.js 변수 전달 한다
		//alert($("#daeriCompanyNum").val()+"/"+$("#certiTableNum").val());
		var send_url = "/2014/_db/daeri_Com_preminum_R.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"certi_R", 
				     daeriCompanyNum:$("#daeriCompanyNum").val(),
					 certiTableNum:$("#certiTableNum").val(),
					 nowtime:nowtime//배서인경우는 배서기준일 ,이외는 현재일자가
				    }
		}).done(function( xml ) {

			//Pvalues = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {

				//	store			= $(this).find('store').text();
            	    name32		= $(this).find('name32').text();
					//message	= $(this).find('message').text();
					message ="게시판!!!";
					company	= $(this).find('company').text();
					policyNum = $(this).find('policyNum').text();
					gita			= $(this).find('gita').text();	
					insurane    = $(this).find('insurane').text();
					insNum		= $(this).find('insNum').text();
					startyDay  =$(this).find('startyDay').text();
            	 //  	values.push( {	"ZIPCODE":ZIPCODE, 
                //	   				"address":address
                //	   				} );
	   				
				 });

			
			});
			
			
						
			dp_certiList(nowtime);
			
		});
	}

function dp_certiList(nowtime){
		
	//	screen_height_def(1);
		
		var str="";
		
		$("#sj").children().remove();
		str += "<tr >";
		   str += "<td colspan='2' style='text-align:left'>"
		        +"<img src='../images/bt_write.gif' border='0' alt='새글쓰기' onFocus='this.blur()' onClick='gasiWrite2()'>"
			
				+"</td>\n";
		 
		str += "</tr>\n";		   
		$("#sj").append(str);
		//var cw = document.getElementById('dayPreminum_frame').contentWindow;
		//cw.gasiRead($("#daeriCompanyNum").val(),$("#certiTableNum").val(),nowtime,'','2','');//gaesipanList.js 변수 전달 한다

	

	}

	
function gasiWrite(num){
	
	//alert(num)
	$('#id_wrap').css('display','block');
	//alert($("#daeriCompanyNum").val());
	var cw = document.getElementById('id_frame').contentWindow;
	cw.fill_up_sjoNum(num,'','');//popId.js //popNewId.php
}


function reload(){
	
		var cw = document.getElementById('dayPreminum_frame').contentWindow;
		cw.gasiRead($("#daeriCompanyNum").val(),$("#certiTableNum").val(),nowtime,'','2','');//pop_dayList.js 변수 전달 한다
}

function gasiWrite2(){

	//var cw = document.getElementById('dayPreminum_frame').contentWindow;
		//cw.gasiWrit();
	gasiWrit();
}

function gasiWrit(gasiNum){

	$("#sjo").children().remove();
	$("#mr").children().remove();
	$("#jr").children().remove();
	$("#kr").children().remove();
	gaesi_init(gasiNum);
}


function gaesi_init(gasiNum){
		
		// 글읽기 

		// 댓글도 읽기
		var send_url = "./ajax/gasi_R.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gasi_R" ,
				   gasiNum:gasiNum
					
				    }
		}).done(function( xml ) {

			values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {

				   beforNum=$(this).find('beforNum').text();
				   afterNum=$(this).find('afterNum').text();
            	   title=$(this).find('title').text();
				   content=$(this).find('content').text();
				   notice_chk=$(this).find('notice_chk').text();
				   damdanga=$(this).find('damdanga').text();
				
					message ="게시판 쓰기";
				
				 }); 
				 $(xml).find('thItem').each(function() {
					values.push( {	
						"dcontent":$(this).find('dcontent').text(),
						"wdate":$(this).find('wdate').text(),
						"rNum":$(this).find('rNum').text(),// 댓글의 num값
						"depth":$(this).find('depth').text(),
						"id":$(this).find('id').text()
						
					} );
				});	 

			});
				max_num = values.length;
				//alert(message);
				
			gasiW(gasiNum);
			
		});
	}

	function gasiW(gasiNum){
		
		//alert(gasiNum);
		var str="";
		
		$("#sjo").children().remove();
		str += "<tr>";
		   str+="<input type='hidden' id='gasiNum' >"
		    str += "<td  >"
				+"<input type='text' id='title' class='textareL'> "
			   +"</td>\n";
		    str += "<td  width='15%'>"
				+"<span id='dj'></span>"
			   +"</td>\n";
		   str += "<td  width='15%'>"
				if(notice_chk==1){
				 str +="<input type='checkbox' class='check2' id='gongji'>"+"공지"
				}else{
				  str +="<input type='checkbox' class='check2' id='gongji'>"+"공지로지정"
				}
			   +"</td>\n";
		  
		str += "</tr>\n";	

		

		str += "<tr >";	   
		    str += "<td colspan='3'>"
				+"<textarea  id='content' class='contentk'></textarea>"
			   +"</td>\n";
		str += "</tr>\n";	


		str += "<tr>";	   
		    str += "<td style='text-align:right' colspan='3'>"
			    if(beforNum){
			str +="<input type='button' class='gtn2'   value='이전글'  onFocus='this.blur()' onClick='gasiWrit("+beforNum+")'>"
				 +"  ";
				}
			str +="<input type='button' class='gtn2'   id='a32' value='새글쓰기'  onFocus='this.blur()' onClick='write_()'>"
			     +"  "
				+"<input type='button' class='gtn'   value='리스트'  onFocus='this.blur()' onClick='gasiRead()'>"
				 +"  "
				if(afterNum){
			str +="<input type='button' class='gtn2'   value='다음글'  onFocus='this.blur()' onClick='gasiWrit("+afterNum+")'>"
				}
			str +="</td>\n";
		str += "</tr>\n";	
		$("#sjo").append(str);

		$("#title").val(title);
		$("#content").val(content);
		$("#gasiNum").val(gasiNum);
		if(notice_chk==1){
			$("#gongji").attr("checked", true);
		}

		
		if(gasiNum){
			$("#a32").val("수정");
		}else{
			$("#a32").val("글쓰기");

		}

		//담당자 함수 
		damdangj(damdanga);
		// 댓글 부분




		
	//alert(gasiNum);
		if(gasiNum){

		

			$("#jr").children().remove();
			var mr="";
			for(var i=0; i<max_num; i++){
					var k=3*(values[i].depth-1);
					//alert(values[i].id);

				mr+="<div id='s"+i+"'>"
			 mr+="<table>"
					mr += "<tr>";
						mr += "<td width='"+k+"%'>"
						//mr += "<td width='5%'>"
							 for(var j=1;j<values[i].depth;j++){
								 
								// if(j==1){mr+="답변";}
								 mr +="<img src='/2012/images/re.gif' border='0' alt='답글' >"
							 }
							 
							 
						   +"</td>\n";
						mr += "<td>"
							+"<input type='text' id='reply"+i+"' class='textareL'> "
							
						   +"</td>\n";
						   mr += "<td width='7%'>"
		         		     +values[i].id
						   +"</td>\n";
						mr += "<td width='10%'>"
							+values[i].wdate
						   +"</td>\n";
						
						mr += "<td width='14%'>"
								mr+="<input type='button' class='stn' value='응답글' onClick='rreplyWrite("+values[i].rNum+','+i+")'>"
									+"  "
								mr+="<input type='button' class='stn' value='수정' onClick='sujeongWrite("+values[i].rNum+','+i+")'>" 
						   +"</td>\n";
					mr += "</tr>\n"
				mr +="</table>\n";
				mr +="<br />";
			 mr+="</div>\n";
			 mr+="<div id='t"+i+"'>"  //댓글에 다시 응답글 달기 위해
			
			  mr+="</div>\n";
			}

			$("#jr").append(mr);
			
			for(var i=0; i<max_num; i++){

					$("#reply"+i).val(values[i].dcontent);
			}

			$("#kr").children().remove();
			var kr="";
			kr += "<tr>";
			  
				kr += "<td>"
					+"<textarea cols='109' rows='2' id='dcontent' class='contentj'></textarea> "
				   +"</td>\n";
			  
			   kr += "<td width='10%'>"
			       kr +="<input type='button' class='gtn2'   value='댓글'  onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
					//kr +="<input type='button' class='gtn'   value='등록'  onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
				   +"</td>\n";
			  
			kr += "</tr>\n";
			$("#kr").append(kr);


			
		}
	}

	function write_(){

	if($("#gongji").is(":checked")){
		var gongi=1;
	}else{
		var gongi=2;
	}
	//alert($("#title").val()+'/'+gongi+'/'+$("#content").val());
	alert($("#damdanga").val());

	if(!$("#title").val()){

			alert('제목이 없습니다 ');
			$("#title").focus();
			return false;

	}
	if(!$("#content").val()){

			alert('내용이 없습니다 ');
			$("#content").focus();
			return false;

	}

	//alert($("#newId").val());
	var send_url = "./ajax/_gasi.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gasipan_W", 
				     gongi:gongi,
					 title:$("#title").val(),
				     content:$("#content").val(),
					 gasiNum:$("#gasiNum").val(),
					 damdanga:$("#damdanga").val()
				    }
		}).done(function( xml ) {

			//Pvalues = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {
            	   
					message=$(this).find('message').text();
				 }); 		
			});

			alert(message);
			
			 gasiRead('','','','','','','');
		});
}

/// 답글 작성을 위해 

function  daWrite(gasiNum){

	//alert(gasiNum+$("#dcontent").val());
	if(!$("#dcontent").val()){

			alert('내용이 없습니다 ');
			$("#dcontent").focus();
			return false;

	}

	var send_url = "./ajax/_gasi.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gasipan_A",  // 댓글
				     dcontent:$("#dcontent").val(),
					 gasiNum:gasiNum
				    }
		}).done(function( xml ) {

			//Pvalues = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {
            	   
					message=$(this).find('message').text();
				 }); 		
			});
			$("#dcontent").val('');
			alert(message);
			
			djeongLi(gasiNum); // 댓글 입력후 댓글을 정리하기 위해 
			
		});

}
function djeongLi(gasiNum){
	
	$("#mr").children().remove();
//	$("#kr").children().remove();
	var send_url = "./ajax/_gasi.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"respon_R",  // 댓글
					gasiNum:gasiNum
				    }
		}).done(function( xml ) {

			values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('thItem').each(function() {
            	   
					values.push( {	
						"dcontent":$(this).find('dcontent').text(),
						"wdate":$(this).find('wdate').text(),
						"rNum":$(this).find('rNum').text(),
						"depth":$(this).find('depth').text(),
						"id":$(this).find('id').text()
					} );
				 }); 		
			});

			max_num=values.length;
			
			s_jeongLi(gasiNum); // 조회 후 정리
			
		});
}
//댓글 입력 후 정리 하기 위해

function s_jeongLi(gasiNum){

			
	      $("#mr").children().remove();
			var mr="";
			for(var i=0; i<max_num; i++){
					var k=5*(values[i].depth-1);
					//alert(k);

				mr+="<div id='s"+i+"'>"
				  mr+="<table>"
					mr += "<tr>";
						mr += "<td width='"+k+"%'>"
						//mr += "<td width='5%'>"
							 for(var j=1;j<values[i].depth;j++){
								 mr+="답변";
							 }
							 mr +="<img src='/2012/images/re.gif' border='0' alt='답글' >"
							 
						   +"</td>\n";
						mr += "<td>"
							+"<input type='text' id='reply"+i+"' class='title'> "
							
						   +"</td>\n";
						mr += "<td width='10%'>"
							+values[i].wdate
						   +"</td>\n";
						 mr += "<td width='7%'>"

						     +values[i].id
					
	
						   +"</td>\n";
						mr += "<td width='5%'>"
						mr+="<input type='button' class='stn' value='수정' onClick='sujeongWrite("+values[i].rNum+','+i+")'>"+ "' "
						mr+="<input type='button' class='stn' value='응답글' onClick='rreplyWrite("+values[i].rNum+','+i+")'>"
				
						   +"</td>\n";
					mr += "</tr>\n"
				mr +="</table>\n";
			  mr+="</div>\n";
			 mr+="<div id='t"+i+"'>"
			
			  mr+="</div>\n";

			}
			$("#jr").append(mr);

			for(var i=0; i<max_num; i++){

					$("#reply"+i).val(values[i].dcontent);
			}

			$("#kr").children().remove();
			var kr="";
			kr += "<tr>";
			  
				kr += "<td>"
					+"<textarea cols='110' rows='2' id='dcontent' class='contentj'></textarea> "
				   +"</td>\n";
			  
			   kr += "<td width='15%'>"
			       kr +="<img src='/2012/images/list_reply.gif' border='0' alt='답글' onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
					//kr +="<input type='button' class='gtn'   value='등록'  onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
				   +"</td>\n";
			  
			kr += "</tr>\n";
			$("#kr").append(kr);



}

//응답글 수정 
function sujeongWrite(gasiNum,sunbun){

		//alert(gasiNum+'/'+sunbun);

		if(!$("#reply"+sunbun).val()){

			alert('내용이 없습니다 ');
			$("#reply"+sunbun).focus();
			return false;

	}

	var send_url = "./ajax/_gasi.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"reply",  // 댓글 수정
				     dcontent:$("#reply"+sunbun).val(),
					 gasiNum:gasiNum
				    }
		}).done(function( xml ) {

			//Pvalues = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {
            	   
					message=$(this).find('message').text();
				 }); 		
			});
			//$("#dcontent").val('');
			alert(message);
			
		
			
		});
}

// 응답글에 다시 답글 달기 위해 
function rreplyWrite(gasiNum,sunbun){

			var kr="";

	//	kr="<input type='button' id='cSto"+sunbun+"'value='수정'"
		//				+" onclick='certiStore("+sunbun+")"+";return false;'"
		//				+">";
		kr +="<table>";
			kr += "<tr>";
			  
				kr += "<td>"
					+"<textarea cols='110' rows='2' id='dcontent"+gasiNum+"' class='contentk'></textarea> "
				   +"</td>\n";
			  
			   kr += "<td width='15%'>"
			       kr +="<img src='/2012/images/list_reply.gif' border='0' alt='답글' onFocus='this.blur()' onClick='daWrite2("+gasiNum+")'>"
					//kr +="<input type='button' class='gtn'   value='등록'  onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
				   +"</td>\n";
			  
			kr += "</tr>\n";
		kr +="</table>";
			$("#t"+sunbun).prepend(kr);

}

//댓글의 댓글

function daWrite2(gasiNum){

	if(!$("#dcontent"+gasiNum).val()){

			alert('내용이 없습니다 ');
			$("#dcontent"+gasiNum).focus();
			return false;

	}

	var send_url = "./ajax/_gasi.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gasipan_A",  // 댓글
				     dcontent:$("#dcontent"+gasiNum).val(),
					 gasiNum:gasiNum
				    }
		}).done(function( xml ) {

			//Pvalues = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {
            	   
					message=$(this).find('message').text();
				 }); 		
			});
			$("#dcontent"+gasiNum).val();
			alert(message);
			
			djeongLi(gasiNum); // 댓글 입력후 댓글을 정리하기 위해 
			
		});

}

function pageChange(){

	var dNum=	$("#daeriCompanyNum").val();
	var cNum=	$("#certiTableNum").val();
	var pages =this.value;
	 gasi_List(dNum,cNum,'','','','',pages);
}

///담당자 지정 또는 변경 하기 위해 


function damdangj(d){

	//alert(d);
		$("#dj").children().remove();
		var send_url = "./ajax/damdanglist.php";
				//alert(s_contents);
	      $.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"damdanga"
											
					}
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

								//	alert($(this).find('sj').text());
						//certi Table 조회 결과
							certiTableValue = new Array();
							$(xml).find('item').each(function() {
								
						
							certiTableValue.push( {	"num":$(this).find('num').text(),
										
										"name":$(this).find('name').text()
									} );
							});//certi Table 조회 결과
					
							//policy 리스트 화면에 표시하기 위해 */
						certi_max_num = certiTableValue.length;	
						
						 dam_list(d);
						
		            });
						
				});
}

function dam_list(d){

		//alert(d)
	   var newInput2=document.createElement("select");
		// var aJumin=document.getElementById('B16b'+k);
		 newInput2.id='damdanga';
		 newInput2.style.width = '100px';
		 newInput2.className='kj';
		 newInput2.onchange=certiChange;/// 증권번호로 조회하기
		 //newInput2.onchange=DnumChange;/// 주간분석
		 var opts=newInput2.options;
		 opts.length=certi_max_num;

		for(var _i=0;_i<opts.length;_i++){	
		
		  
		 if(certiTableValue[_i].num==d){
				newInput2.selectedIndex=_i;
				opts[_i].value=99;
				opts[_i].text=certiTableValue[_i].name;

		  }else{

				opts[_i].value=certiTableValue[_i].num;
				opts[_i].text=certiTableValue[_i].name;
		  }
		}
	//alert($("#dj").val());
		$("#dj").append(newInput2);


}

function certiChange(){
		
		var send_url = "./ajax/damdanglist.php";
				//alert(s_contents);
	      $.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"damdangaChange",
						   num:$("#gasiNum").val(),
						   damdanga:$("#damdanga").val()				
					}
				}).done(function( xml ) {

					
					$(xml).find('values').each(function(){

								//	alert($(this).find('sj').text());
						//certi Table 조회 결과
						/*	certiTableValue = new Array();
							$(xml).find('item').each(function() {
								
						
							certiTableValue.push( {	"num":$(this).find('num').text(),
										
										"name":$(this).find('name').text()
									} );
							});//certi Table 조회 결과
					
							//policy 리스트 화면에 표시하기 위해 */
						//certi_max_num = certiTableValue.length;	
						
						// dam_list(d);
						
		            });


					alert("담당자 변경 완료 !!");
						
				});
	


	   
}