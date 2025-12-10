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
			var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"preminum",
						   daerimemberNum:$("#daerimemberNum").val(),
						   sigi:$("#sigi").val()
						 
					
					 }
				}).done(function( xml ) {

					
					Pvalues = new Array();
					Dvalues = new Array();
					$(xml).find('values').each(function(){

					
							$(xml).find('policy').each(function() {
									//alert($(this).find('s_nai').text());
									
									s_nai			= $(this).find('s_nai').text();
									e_nai			= $(this).find('e_nai').text();
									yPreminum		= $(this).find('yPreminum').text();
									mPreminum		= $(this).find('mPreminum').text();
									
											d_prem0= $(this).find('d_prem0').text();d_date0 =$(this).find('d_date0').text();
											d_prem1 = $(this).find('d_prem1').text();d_date1 =$(this).find('d_date1').text();
											d_prem2 = $(this).find('d_prem2').text();d_date2 =$(this).find('d_date2').text();
											d_prem3 = $(this).find('d_prem3').text();d_date3 =$(this).find('d_date3').text();
											d_prem4 = $(this).find('d_prem4').text();d_date4 =$(this).find('d_date4').text();
											d_prem5 = $(this).find('d_prem5').text();d_date5 =$(this).find('d_date5').text();
											d_prem6 = $(this).find('d_prem6').text();d_date6 =$(this).find('d_date6').text();
											d_prem7 = $(this).find('d_prem7').text();d_date7 =$(this).find('d_date7').text();
											d_prem8 = $(this).find('d_prem8').text();d_date8 =$(this).find('d_date8').text();
											d_prem9 = $(this).find('d_prem9').text();d_date9 =$(this).find('d_date9').text();
											d_prem10 = $(this).find('d_prem10').text();d_date10 =$(this).find('d_date10').text();


											d_prem11 = $(this).find('d_prem11').text(); d_date11 =$(this).find('d_date11').text();
											d_prem12 = $(this).find('d_prem12').text(); d_date12 =$(this).find('d_date12').text();
											d_prem13 = $(this).find('d_prem13').text(); d_date13 =$(this).find('d_date13').text();
											d_prem14 = $(this).find('d_prem14').text(); d_date14 =$(this).find('d_date14').text();
											d_prem15 = $(this).find('d_prem15').text(); d_date15 =$(this).find('d_date15').text();
											d_prem16 = $(this).find('d_prem16').text(); d_date16 =$(this).find('d_date16').text();
											d_prem17 = $(this).find('d_prem17').text(); d_date17 =$(this).find('d_date17').text();
											d_prem18 = $(this).find('d_prem18').text(); d_date18 =$(this).find('d_date18').text();
											d_prem19 = $(this).find('d_prem19').text(); d_date19 =$(this).find('d_date19').text();
											d_prem20 = $(this).find('d_prem20').text(); d_date20 =$(this).find('d_date20').text();

											d_prem21 = $(this).find('d_prem21').text(); d_date21 =$(this).find('d_date22').text();
											d_prem22 = $(this).find('d_prem22').text(); d_date22 =$(this).find('d_date22').text();
											d_prem23 = $(this).find('d_prem23').text(); d_date23 =$(this).find('d_date23').text();
											d_prem24 = $(this).find('d_prem24').text(); d_date24 =$(this).find('d_date24').text();
											d_prem25 = $(this).find('d_prem25').text(); d_date25 =$(this).find('d_date25').text();
											d_prem26 = $(this).find('d_prem26').text(); d_date26 =$(this).find('d_date26').text();
											d_prem27 = $(this).find('d_prem27').text(); d_date27 =$(this).find('d_date27').text();
											d_prem28 = $(this).find('d_prem28').text(); d_date28 =$(this).find('d_date28').text();
											d_prem29 = $(this).find('d_prem29').text(); d_date29 =$(this).find('d_date29').text();
											d_prem30 = $(this).find('d_prem30').text(); d_date30 =$(this).find('d_date30').text();
											d_prem31 = $(this).find('d_prem31').text(); d_date31 =$(this).find('d_date31').text();
									Pvalues.push( {	
													"startyDay":$(this).find('startyDay').text(),
													"before_gijun":$(this).find('before_gijun').text(),
													"after_gijun":$(this).find('after_gijun').text(),
													"endorseDay":$(this).find('endorseDay').text(),
													"s_nai":s_nai,
													"e_nai":e_nai,
													"yPreminum":yPreminum,
													"dailyPreminum":$(this).find('dailyPreminum').text(),
													"before_preminum":$(this).find('before_preminum').text(),
													"after_preminum":$(this).find('after_preminum').text(),
														
													"thisDay":$(this).find('thisDay').text(),
													 "d_date0":d_date0, "d_date1":d_date1, "d_date2":d_date2, "d_date3":d_date3, "d_date4":d_date4, "d_date5":d_date5,
													  "d_date6":d_date6, "d_date7":d_date7, "d_date8":d_date8, "d_date9":d_date9, "d_date10":d_date10, 

													  "d_date11":d_date11, "d_date12":d_date12, "d_date13":d_date13, "d_date14":d_date14, "d_date15":d_date15,
													  "d_date16":d_date16, "d_date17":d_date17, "d_date18":d_date18, "d_date19":d_date19, "d_date20":d_date20, 
													  

													 "d_date21":d_date21, "d_date22":d_date22, "d_date23":d_date23, "d_date24":d_date24, "d_date25":d_date25,
													  "d_date26":d_date26, "d_date27":d_date27, "d_date28":d_date28, "d_date29":d_date29, "d_date30":d_date30, "d_date31":d_date31, 

													 "d_prem0":d_prem0, "d_prem1":d_prem1, "d_prem2":d_prem2,"d_prem3":d_prem3, "d_prem4":d_prem4, "d_prem5":d_prem5,
										             "d_prem6":d_prem6,"d_prem7":d_prem7,"d_prem8":d_prem8,"d_prem9":d_prem9,"d_prem10":d_prem10,

													"d_prem11":d_prem11, "d_prem12":d_prem12, "d_prem13":d_prem13, "d_prem14":d_prem14, "d_prem15":d_prem15,
										             "d_prem16":d_prem16,"d_prem17":d_prem17,"d_prem18":d_prem18,"d_prem19":d_prem19,"d_prem20":d_prem20,

													"d_prem21":d_prem21, "d_prem22":d_prem22, "d_prem23":d_prem23, "d_prem24":d_prem24, "d_prem25":d_prem25,
										             "d_prem26":d_prem26,"d_prem27":d_prem27,"d_prem28":d_prem28,"d_prem29":d_prem29,"d_prem30":d_prem30



												
												} ); //Pvalues 끝

												
								 }); //policy 끝


							/*	$(xml).find('dPre').each(function() {

											Period = $(this).find('Period').text();

											
											d_date = $(this).find('d_date').text();
											//alert(d_date);
											
											Dvalues.push( {	"Period":Period,
												
											} ); //Dvalues 끝
								}); //dpre끝*/
		            });
						certi_max_num = Pvalues.length;	
						
						//alert(certi_max_num);
						dp_certiList()
						//
						
					//alert(certi_max_num);
					
						//alert(daeriNum);
							//certiTable 조회 하기 위해
						
						//	var cw = document.getElementById('certiTable_list_frame').contentWindow;
						//	cw.certiTableSerch(daeriNum);//deriCompany.js
				});
}
function dp_certiList(){
		var sunbun=0;
		var str="";
		//alert('1')
		//var tmp_photo;
		//alert(Pvalues[0].thisDay);
		//alert(certi_max_num);
		var $dp_type=1;
		$("#sjo").children().remove();
				
			str += "<tr>";
				str += "<td width='20%'>"+"증권의보험기간"+"</td>\n";
				str += "<td width='10%'>"+"경과기간"+"</td>\n";
				str += "<td width='10%'>"+"미경과기간"+"</td>\n";
			
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<td>"+Pvalues[i].s_nai+"세 ~"+Pvalues[i].e_nai+"세"+"</td>\n";
				}
			str += "</tr>\n";
			str += "<tr>";
			str += "<td>"+Pvalues[0].startyDay+"</td>\n";
			str += "<td>"+Pvalues[0].before_gijun+"</td>\n";
			str += "<td>"+Pvalues[0].after_gijun+"</td>\n";
		

				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].yPreminum+"</th>\n";
				}
			str += "</tr>\n";
			str += "<tr>";
			
				str += "<td colspan='3'>"+"1일보험료"+"</td>\n";//before_preminum
				
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].dailyPreminum+"</th>\n";
				}
			str += "</tr>\n";
			
			str += "</tr>\n";
			str += "<tr>";
			
				str += "<td>"+"기준일 부터 만기까지"+"</td>\n";//before_preminum
				str += "<td colspan='2'>"+Pvalues[0].endorseDay+"</td>\n";
				
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='total_prem"+i+"' class='textareP'>"+"</th>\n";
				}
			str += "</tr>\n";

				
			
			
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date1+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem1"+i+"' class='textareP' onBlur='putP1("+i+")'>"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date2+"</td>\n";

				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem2"+i+"' class='textareP' onBlur='putP2("+i+")' >"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date3+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem3"+i+"' class='textareP' onBlur='putP3("+i+")' >"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date4+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem4"+i+"' class='textareP' onBlur='putP4("+i+")' >"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date5+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem5"+i+"' class='textareP' onBlur='putP5("+i+")'>"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date6+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem6"+i+"' class='textareP' onBlur='putP6("+i+")'>"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date7+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem7"+i+"' class='textareP' onBlur='putP7("+i+")'>"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date8+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem8"+i+"' class='textareP' onBlur='putP8("+i+")' >"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date9+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem9"+i+"' class='textareP' onBlur='putP9("+i+")' >"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date10+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem10"+i+"' class='textareP' onBlur='putP10("+i+")' >"+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date11+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+"<input type='text' id='d_prem11"+i+"' class='textareP' onBlur='putP11("+i+")' >"+"</th>\n";
				}	
			str += "</tr>\n";
			



			
			
	//	}
		
        str += "";
        //alert();
		
			
			$("#sjo").append(str);

			//Pvalues[0].thisDay 번째 색을 변경하는것을 찾아서 
		
			$("tr:nth-child("+Pvalues[0].thisDay+")").css("color","orange");
		
			for(var i=0; i<certi_max_num; i++)
			{

				$("#total_prem"+i).val(Pvalues[i].after_preminum)
				$("#d_prem1"+i).val(Pvalues[i].d_prem1);
				$("#d_prem2"+i).val(Pvalues[i].d_prem2);
				$("#d_prem3"+i).val(Pvalues[i].d_prem3);
				$("#d_prem4"+i).val(Pvalues[i].d_prem4);
				$("#d_prem5"+i).val(Pvalues[i].d_prem5);
				$("#d_prem6"+i).val(Pvalues[i].d_prem6);
				$("#d_prem7"+i).val(Pvalues[i].d_prem7);
				$("#d_prem8"+i).val(Pvalues[i].d_prem8);
				$("#d_prem9"+i).val(Pvalues[i].d_prem9);
				$("#d_prem10"+i).val(Pvalues[i].d_prem10);
				$("#d_prem11"+i).val(Pvalues[i].d_prem11);
					
			}	

}



function putP1(i){
	if(Pvalues[0].d_date1){

		var nabang=1;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem1'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem1'+i).val($(this).find('preiminum').text());
							$('#d_prem1'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}

function putP2(i){
	if(Pvalues[0].d_date2){

		var nabang=2;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem2'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem2'+i).val($(this).find('preiminum').text());
							$('#d_prem2'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}



function putP3(i){
	if(Pvalues[0].d_date3){

		var nabang=3;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem3'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem3'+i).val($(this).find('preiminum').text());
							$('#d_prem3'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}

function putP4(i){
	if(Pvalues[0].d_date4){

		var nabang=4;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem4'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem4'+i).val($(this).find('preiminum').text());
							$('#d_prem4'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}

function putP5(i){
	if(Pvalues[0].d_date5){

		var nabang=5;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem5'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem5'+i).val($(this).find('preiminum').text());
							$('#d_prem5'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}
function putP6(i){
	if(Pvalues[0].d_date6){

		var nabang=6;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem6'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem6'+i).val($(this).find('preiminum').text());
							$('#d_prem6'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}

function putP7(i){
	if(Pvalues[0].d_date7){

		var nabang=7;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem7'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem7'+i).val($(this).find('preiminum').text());
							$('#d_prem7'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}

function putP8(i){
	if(Pvalues[0].d_date8){

		var nabang=8;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem8'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem8'+i).val($(this).find('preiminum').text());
							$('#d_prem8'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}

function putP9(i){
	if(Pvalues[0].d_date9){

		var nabang=9;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem9'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem9'+i).val($(this).find('preiminum').text());
							$('#d_prem9'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}

function putP10(i){
	if(Pvalues[0].d_date10){

		var nabang=10;
		var send_url = "./ajax/_dongpreminum.php";
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"premUpdate",
						   nabang:nabang,
						   preiminum:$('#d_prem10'+i).val(),
						   snai:Pvalues[i].s_nai,
						   enai:Pvalues[i].e_nai,
						   date:Pvalues[0].endorseDay
					 }
				}).done(function( xml ) {

					$(xml).find('values').each(function(){
							$('#d_prem10'+i).val($(this).find('preiminum').text());
							$('#d_prem10'+i).css("color","red");
							$('#total_prem'+i).val($(this).find('totalPreminum').text());
							$('#total_prem'+i).css("color","red");
							alert('수정완료!!');
					});
						

				});

	}
}