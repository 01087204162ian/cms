function fill_up_sjoNum(dNum,cNum,sunbun){
		//alert(dNum+'//'+cNum+'///'+sunbun);
		
		screen_height_def();
		$("#daeriCompanyNum").val(dNum);
		$("#certiTableNum").val(cNum);
		 preminum_init(sunbun);
		//alert(nowtime);

	}	
	function preminum_init(sunbun){
		$("#sjo").children().remove();
		//alert(nowtime +'' +preminum_init);
		//보험료 테이블 초기화 
		var nowtime='';
		//alert($("#daeriCompanyNum").val());
		//alert('1')
		var cw = document.getElementById('dayPreminum_frame').contentWindow;
		cw.dayPclear();//dayPreminum.js 변수 전달 한다
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
					message ="인원 구성비율";
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
			
			//var maxLength=Pvalues.length;
			
			//if(!maxLength){
			/*	switch (eval(insNum))
					{
						case 1 :
							maxLength='6';
							break;
						case 2 :
							maxLength='6';
							break;
						case 3 : //Lig
							maxLength='6';
							break;
						case 4 : //현대
							maxLength='6';
							break;
						case 5 :
							maxLength='6';
							break;
						case 6 :
							maxLength='6';
							break;
						case 7 :
							maxLength='6';
							break;
						case 8 :
							maxLength='6';
							break;
					
					}
			//}*/


				alert(message);
				//$("#InCom").html("["+insurane+"]"+company+"["+policyNum+"]["+policyNum+"]");
		
			//max_num = values.length;
				
						
			dp_certiList(sunbun);
			
		});
	}

function dp_certiList(sunbun){
		
		//alert(sunbun+'/')
		
		var str="";
		
		$("#sjo").children().remove();
		str += "<tr >";
		   str += "<td width='20%'>"
				+company
				+"</td>\n";
		   str += "<td width='20%'>"
				+insurane
				+"</td>\n";
		   str += "<td width='20%'>"
				+policyNum
				+"</td>\n";
		   str += "<td width='20%'>"
				+gita
				+"</td>\n";
		   str += "<td width='20%' >"
			   +startyDay
				+"</td>\n";
		str += "</tr>\n";		   
		$("#sjo").append(str);
		var cw = document.getElementById('dayPreminum_frame').contentWindow;
		cw.DayPreminumSerch($("#daeriCompanyNum").val(),$("#certiTableNum").val(),sunbun,'','2','');//pop_dayList.js 변수 전달 한다

	

	}

	










