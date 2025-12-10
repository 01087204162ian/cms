	function dayPclear(){
		$("#sjo").children().remove();
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

addLoadEvent(DayPreminumSerch);
	function DayPreminumSerch(dNum,cNum,nowtime,content,giho,serchNum,pages){

		
		
		$("#sjo").children().remove();
		$("#mr").children().remove();
		$("#kr").children().remove();
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
										"totalpage":$(this).find('totalpage').text(),
										
									} ); //Pvalues 끝

												
								 }); //item 끝


							
		            });
						certi_max_num = certiTableValue.length;	
						
						//alert(certi_max_num);

						dp_certiList(giho,nowtime)
				
				});
}
function dp_certiList(giho,nowtime){ //giho 1: 성명,주민번호 조회하는 경우 2전체명단 조회하는 경우
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
		
	   	   
				str += "</tr>\n";
				str += "<tr>";
					str += "<td width='5%'>"+"No"+"</td>\n";
					str += "<td width='60%' colspan='2'>"+"제목"+"</td>\n";
					str += "<td width='16%'>"+"등록일"+"</td>\n";
					str += "<td width='10%'>"+"글쓴이"+"</td>\n";
					str += "<td width='9%'>"+"조회"+"</td>\n";
					
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
				}else{
				str += "<td class='center_td'>"  //공지
					 			 
					 +"</td>\n";
				}
				if(certiTableValue[i].notice_chk==1){
				str += "<td class='ctitle'>"  //제목
					 +certiTableValue[i].title
					 +"</td>\n";
				}else{
				str += "<td class='ctitle2'>"  //제목
					 +certiTableValue[i].title
					 +"</td>\n";

				}
				str += "<td class='center_td'>"  //시간
					 +certiTableValue[i].wdate
					 +"</td>\n";
				str += "<td class='center_td'>"  //글쓴이
					 +certiTableValue[i].write
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

	}
	
	

function layer_popup_close(IdName)
{

	
	$('#'+IdName).css('display','none');
}



function sjoClear(){  
	
	$("#sjo").children().remove();
}


function clickshow(num){
	$('#popup_wrap2',parent.document).css('display','block');
	parent.dNum(num,1,''); //index.php의 page_index.js
	//$('#popup_wrap2',parent.document).draggable();
	layer_popup_close('layer_option_detail');
}







function pageChange(){

	var dNum=	$("#daeriCompanyNum").val();
	var cNum=	$("#certiTableNum").val();
	var pages =this.value;
	 DayPreminumSerch(dNum,cNum,'','','','',pages);
}



function gasiWrit(gasiNum){

	$("#sjo").children().remove();
	$("#mr").children().remove();
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
		
		//alert(max_num);
		var str="";
		
		$("#sjo").children().remove();
		str += "<tr >";
		   str+="<input type='hidden' id='gasiNum' >"
		    str += "<td>"
				+"<input type='text' id='title' class='title'> "
			   +"</td>\n";
		  
		   str += "<td width='15%'>"
				+"<input type='checkbox' class='check' id='gongji'>"+"공지로지정"
			   +"</td>\n";
		  
		str += "</tr>\n";	

		str += "<br>";	

		str += "<tr >";	   
		    str += "<td colspan='2'>"
				+"<textarea cols='130' rows='16' id='content' class='content'></textarea>"
			   +"</td>\n";
		str += "</tr>\n";	


		str += "<tr>";	   
		    str += "<td colspan='2'>"
			    if(beforNum){
			str +="<input type='button' class='gtn'   value='이전글'  onFocus='this.blur()' onClick='gasiWrit("+beforNum+")'>"
				}
			str +=11"<input type='button' class='gtn'   id='a32'  onFocus='this.blur()' onClick='write_()'>"
			   // +"<img src='/2014/imgs/list_list.gif' border='0' alt='리스트' onFocus='this.blur()' onClick='DayPreminumSerch()'>"
				//+"<input type='button' class='gtn'   value='리스트'  onFocus='this.blur()' onClick='DayPreminumSerch()'>"
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
			$("#a32").val("글쓰기22");

		}

		// 댓글 부분

		//alert(gasiNum);
		if(gasiNum){

		

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
							 mr +="<img src='/2014/imgs/re.gif' border='0' alt='답글' >"
							 
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
						mr+="<input type='button' class='stn' value='수정' onClick='sujeongWrite("+values[i].rNum+','+i+")'>"
						mr+="<input type='button' class='stn' value='응답글' onClick='rreplyWrite("+values[i].rNum+','+i+")'>"
				
						   +"</td>\n";
					mr += "</tr>\n"
				mr +="</table>\n";
			  mr+="</div>\n";
			 mr+="<div id='t"+i+"'>"
			
			  mr+="</div>\n";

			}
			$("#mr").append(mr);

			for(var i=0; i<max_num; i++){

					$("#reply"+i).val(values[i].dcontent);
			}

			$("#kr").children().remove();
			var kr="";
			kr += "<tr>";
			  
				kr += "<td>"
					+"<textarea cols='130' rows='2' id='dcontent' class='content'></textarea> "
				   +"</td>\n";
			  
			   kr += "<td width='15%'>"
			       kr +="<img src='/2014/imgs/list_reply.gif' border='0' alt='답글' onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
					//kr +="<input type='button' class='gtn'   value='등록'  onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
				   +"</td>\n";
			  
			kr += "</tr>\n";
			$("#kr").append(kr);
		}
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
					+"<textarea cols='130' rows='2' id='dcontent"+gasiNum+"' class='content'></textarea> "
				   +"</td>\n";
			  
			   kr += "<td width='15%'>"
			       kr +="<img src='/2014/imgs/list_reply.gif' border='0' alt='답글' onFocus='this.blur()' onClick='daWrite2("+gasiNum+")'>"
					//kr +="<input type='button' class='gtn'   value='등록'  onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
				   +"</td>\n";
			  
			kr += "</tr>\n";
		kr +="</table>";
			$("#t"+sunbun).prepend(kr);

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



/// 답글 작성을 위해 

function  daWrite(gasiNum){

	//alert(gasiNum+$("#dcontent").val());
	if(!$("#dcontent").val()){

			alert('내용이 없습니다 ');
			$("#dcontent").focus();
			return false;

	}

	var send_url = "/2014/_db/_gasi.php";		
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
	var send_url = "/2014/_db/_gasi.php";		
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
							 mr +="<img src='/2014/imgs/re.gif' border='0' alt='답글' >"
							 
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
						mr+="<input type='button' class='stn' value='수정' onClick='sujeongWrite("+values[i].rNum+','+i+")'>"
						mr+="<input type='button' class='stn' value='응답글' onClick='rreplyWrite("+values[i].rNum+','+i+")'>"
				
						   +"</td>\n";
					mr += "</tr>\n"
				mr +="</table>\n";
			  mr+="</div>\n";
			 mr+="<div id='t"+i+"'>"
			
			  mr+="</div>\n";

			}
			$("#mr").append(mr);

			for(var i=0; i<max_num; i++){

					$("#reply"+i).val(values[i].dcontent);
			}

			$("#kr").children().remove();
			var kr="";
			kr += "<tr>";
			  
				kr += "<td>"
					+"<textarea cols='130' rows='2' id='dcontent' class='content'></textarea> "
				   +"</td>\n";
			  
			   kr += "<td width='15%'>"
			       kr +="<img src='/2014/imgs/list_reply.gif' border='0' alt='답글' onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
					//kr +="<input type='button' class='gtn'   value='등록'  onFocus='this.blur()' onClick='daWrite("+gasiNum+")'>"
				   +"</td>\n";
			  
			kr += "</tr>\n";
			$("#kr").append(kr);



}


function write_(){

	if($("#gongji").is(":checked")){
		var gongi=1;
	}else{
		var gongi=2;
	}
	//alert($("#title").val()+'/'+gongi+'/'+$("#content").val());


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
	var send_url = "/2014/_db/_gasi.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gasipan_W", 
				     gongi:gongi,
					 title:$("#title").val(),
				     content:$("#content").val(),
					 gasiNum:$("#gasiNum").val()
				    }
		}).done(function( xml ) {

			//Pvalues = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {
            	   
					message=$(this).find('message').text();
				 }); 		
			});

			alert(message);
			
			 DayPreminumSerch('','','','','','','');
		});
}