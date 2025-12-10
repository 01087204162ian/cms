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

addLoadEvent(DayPreminumSerch)
	function DayPreminumSerch(){
		//alert(memberNum)
	//alert('t');
		$("#sjo").children().remove();
			var send_url = "./ajax/_dongpreminum.php";
			
			
			
			
			$.ajax({
					type: "POST",
					url:send_url,
					dataType : "xml",
					data:{ proc:"Ins_dayP_R",
						
					     certiTableNum:$("#certiTableNum").val()
						
					
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
					str += "<td>"+Pvalues[i].s_nai+"세~"+Pvalues[i].e_nai+"세"+"</td>\n";
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
					str += "<th align='right'>"+Pvalues[i].after_preminum+"</th>\n";
				}
			str += "</tr>\n";

		//for(var k=0;k<31;k++){

		//	var _m=k+1;			
			
			
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date1+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem1+"</th>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date2+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem2+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date3+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem3+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td colspan='3' align='center'>"+Pvalues[0].d_date4+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem4+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date5+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem5+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date6+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem6+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date7+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem7+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date8+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem8+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date9+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem9+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date10+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem10+"</td>\n";
				}	
			str += "</tr>\n";
			str += "<tr>";
				
				str += "<td  colspan='3' align='center'>"+Pvalues[0].d_date11+"</td>\n";
				for(var i=0; i<certi_max_num; i++)
				{
					str += "<th align='right'>"+Pvalues[i].d_prem11+"</td>\n";
				}	
			str += "</tr>\n";
			



			
			
	//	}
		
        str += "";
        //alert();
		
			
			$("#sjo").append(str);

			//Pvalues[0].thisDay 번째 색을 변경하는것을 찾아서 
		
			$("tr:nth-child("+Pvalues[0].thisDay+")").css("color","orange");
		
	

	}
	
//증권번호 입력

function cCerti(num){
	var em='';

	if($("#sigi"+num).val()==""){
			alert("증권의 시작일 부터 입력하세요");
			$("#sigi"+num).focus();
			
			$("#certi"+num).val(em);
			return false;
	}


	s_array = $("#sigi"+num).val().split("-");
								 sigi_= s_array[0];
								
	switch(eval($("#insCom"+num).val())){
				case 99 :
						alert("보험회사 부터 선택하고 하셔요!");
						$("#insCom"+num).focus();
						$("#certi"+num).val(em);
					break;
				case 1 ://흥국

				break;

				case 3 :// LiG
				   if(eval($("#certi"+num).val().length)==7){

							var c_=sigi_+"-"+$("#certi"+num).val();
							$("#certi"+num).val(c_);

				   }else if(eval($("#certi"+num).val().length)>0 || eval($("#certi"+num).val().length)>7){
						    alert("일곱자리 !!");
							$("#certi"+num).focus();
							return false;
				   }
				break;
				case 4 : //현대
					if(eval($("#certi"+num).val().length)==7){

							var c_=sigi_+"-"+$("#certi"+num).val();
							$("#certi"+num).val(c_);

				   }else if(eval($("#certi"+num).val().length)>0 || eval($("#certi"+num).val().length)>7){
						    alert("일곱자리 !!");
							$("#certi"+num).focus();
							return false;
				   }
				break;
				  case 7 : //현대

						alert("년령별 보험료 입려하면서 년령별로 보험증권입력하세요!!");

						return false;
				  break;
				default:


				 break;
	}

}
 

function layer_popup_close(IdName)
{

	$('#'+IdName).css('display','none');
}


function sjoClear(){  
	
	$("#sjo").children().remove();
}