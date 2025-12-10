	function dayPclear(){
		$("#sjo").children().remove();
		$("#sj").children().remove();
	}
addLoadEvent(DayPreminumSerch);
	function DayPreminumSerch(dNum,cNum,nowtime,content,giho,serchNum){
	//alert('1')
		
			$("#sjo").children().remove();
			$("#sj").children().remove();
		//	$("#daeriCompanyNum").val(Dnum);
		//	$("#certiTableNum").val(CertiNum);
			
			var str="";
			

			str += "<tr>";
				//str += "<td width='25%'>"+"<img id='loading' src='/2014/ajax/loading.gif' style='display:none;'>"+"</td>\n";
				//str += "<td>"+"파일명 "+"</td>\n";
				//str += "<td class='center_td'>"  //증권시작일
				//	+"<input type='text' class='textareP' id='sigi'"
					//+"value='"+certiTableValue[i].certiTableNum
				//	+'"
				//	+" onBlur='cSigi()'"
				//	+" onClick='dSigi()'"
				//	+"></td>\n";
			
				str += "<td width='60%'>"+"<input id='fileToUpload' type='file' size='45' name='fileToUpload' class='fileinput'>"+"</td>\n";
				str += "<td width='10%'>"+"배서기준일"+"</td>\n";
				str += "<td width='15%'>"+"<input type='text' id='sigi' class='textareP'>"+"</td>\n";
			    str += "<td width='15%'>"+"<button class='btn-b' id='buttonUpload' onclick='return ajaxFileUpload(2);'>Upload</button>"+"</td>\n";
			str += "</tr>\n";
			
			$("#sjo").append(str);

		/*	$( "#sigi").datepicker({
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
				var dt = new Date();

				// Display the month, day, and year. getMonth() returns a 0-based number.
				var month = dt.getMonth()+1;
				var day = dt.getDate();
				var year = dt.getFullYear();
				
				if(day<10){day="0"+day};
				if(month<10){month="0"+month};
				var today =year+'-'+month+'-'+day;
				$("#sigi").val(today);
			var jstr="";
				
				  jstr += "<tr>";
			
						jstr += "<td class='center_td' width='10%'>"
							+"A열"+"</td>\n";

						jstr += "<td class='center_td' width='10%'>"
							
							+"B열"+"</td>\n";
						
						
						
				 jstr += "</tr>\n";
				
				 jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"성명"+"</td>\n";

						jstr += "<td class='center_td'>"
							
							+"주민번호"+"</td>\n";
						
							
						
						
				 jstr += "</tr>\n";

				jstr += "<tr>";
			
						
						jstr += "<td class='center_td' colspan='2'>"
							
							+"2행부터 data 입력하세요"+"</td>\n";	
				 jstr += "</tr>\n";

				/* 데이타 로딩*/
			
				
				  /* 데이타 로딩*/
				
				
			
			
			$("#sj").append(jstr);
		
			//dp_certiList();
			
	}

function ajaxFileUpload(endorse) // endorse 2 배서 추가 이므로 시작이을 배서일기준으로 하기 위해
	{
		//alert(endorse)
		$("#sj").children().remove();
		/*$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});*/

		$.ajaxFileUpload
		(
			{
				url:'/2012/pop_up/_pages/php/doajaxfileupload.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							//alert(data.msg);
							fileUploadAfter(data.msg,endorse);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}

function fileUploadAfter(fileName,endorse){ //ajax 파일을 up로드 파일을 일고 읽은것을 일단 임시로 저장합니다
		//alert($("#daeriCompanyNum").val()+'/'+$("#certiTableNum").val()+'/'+$("#sigi").val()+'/'+fileName);
	//var send_url = "/2014/_pages/php/example.php";	
	var send_url = "/2012/pop_up/_db/kdriveExample.php";	

	$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gias_new_W", 
				     dNum:$("#daeriCompanyNum").val(),
					 etage:$("#etage").val(),
					 fileName:fileName,
					 endorse:endorse,
					 endorseDay:$("#sigi").val()

					// date:$("#sigi").val()
				}
			,success:function( xml ) {

				values = new Array();
				$(xml).find('values').each(function(){
					$(xml).find('policy').each(function() {
						//alert($(this).find('insCompany').text());
						values.push( {	"name":$(this).find('name').text(),
										"bunho2":$(this).find('bunho2').text(),
										"nai2":$(this).find('nai2').text(),
										"jumin":$(this).find('jumin').text(),
										
										} );
						
					 });

			
				});
				
				var maxLength=values.length;
				//alert(maxLength);
				dp_certiList();
				
			}
		   ,beforeSend:function(){
							//(이미지 보여주기 처리)	
				$('.wrap-loading').removeClass('display-none');
			}
			,complete:function(){
				//(이미지 감추기 처리)
				$('.wrap-loading').addClass('display-none');
		 
			},
			error: function (response) {
				$('#msg').html(response); // display error response from the server
			}
		});
	}

function dp_certiList(){
	alert("비교결과! !!!");
	jvalues = new Array();
	$("#sj").children().remove();
	var jstr="";
	  jstr += "<tr>";
			
			jstr += "<td  width='5%'>"
				+"No"+"</td>\n";

			jstr += "<td  width='8%'>"
				
				+"성명"+"</td>\n";
		
				
			jstr += "<td  width='15%'>"
				
			     +"주민번호"+"</td>\n";
			jstr += "<td  width='10%'>"		
				+"비고"+"</td>\n";
		jstr += "</tr>\n";



		var buttonS=''
		var m=0;
		for(var k=0;k<values.length;k++){
			m=k+1;
			if(values[k].bunho2==1){

				jstr += "<tr>";
					
					jstr += "<td >"
						+m+"</td>\n";
					jstr += "<td >"
						+values[k].name+"</td>\n";
					
					jstr += "<td >"
						+values[k].jumin+"</td>\n";
					
					
					jstr += "<td id='com"+k+"'"+">"
						+"계약없음"+"</td>\n";
				jstr += "</tr>\n";

				buttonS++;
			}else if(values[k].bunho2==3){

				jstr += "<tr>";
					
					jstr += "<td >"
						+j+"</td>\n";
					jstr += "<td >"
						+values[k].name+"</td>\n";
					
					jstr += "<td >"
						+values[k].jumin+"</td>\n";
					
					
					jstr += "<td id='com"+k+"'"+">"
						+"중복"+"</td>\n";
				jstr += "</tr>\n";
				buttonS++
			}
			

		

			
		
		///alert(values[k].insCompany);
		
		
		}//for 종료
			

			var inwon_=m-buttonS;
		
	  jstr += "<tr>";
			jstr += "<td  colspan='2'>"
				 +"비교인원"+"</td>\n";
			jstr += "<td>"
				
				+m+"명"+"</td>\n";
			
		if(buttonS){
			jstr += "<td  colspan='5'>"
				+"중 상기"+buttonS+"명 상이합니다.";
				//+$("#daeriCompanyNum").val()+'/'+$("#certiTableNum").val()+'/'+$("#fileName").val()+'/'+buttonS
				+"</td>\n";			
			}else{

			  jstr += "<td  colspan='5'>"
				+"일치합니다" 
				//+" onclick='fileStore(2)"+";return false;'"
				
				+"</td>\n";
			}
			
		jstr += "</tr>\n";
	

	$("#sj").append(jstr);
		for(var k=0;k<values.length;k++){
	
		if((jvalues[k]=='주민오류') || (values[k].duplicte=='중복') || (values[k].Lcount=='불일치') ||(values[k].n_duplicate=='나이제한') ){
				var m=k+2;
				$("tr:nth-child("+m+")").css("color","orange");

					
		}
	}
	//for(var k=0;k<values.length;k++){
}

function fileStore(endorse){
	
	//alert($("#daeriCompanyNum").val()+'/'+$("#certiTableNum").val()+'/'+$("#fileName").val());

	//var send_url = "/2014/_pages/php/example.php";
	var send_url = "/2012/pop_up/_db/example.php";	
		//alert(send_url);
	var sj=2; //신규 저장 정상 2는 조해  신청
	//alert(sj);
	$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gias_new_W", 
				     dNum:$("#daeriCompanyNum").val(),
					 cNum:$("#certiTableNum").val(),
					 fileName:$("#fileName").val(),
					 sj:sj,
					 endorse:endorse,
					 endorseDay:$("#sigi").val()
				    }
		}).done(function( xml ) {

			values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('policy').each(function() {
					
            	   	values.push( {	"name":$(this).find('name').text(),
									"nai":$(this).find('nai').text(),
									"jumin":$(this).find('jumin').text(),
									"hphone":$(this).find('hphone').text(),
									"insCompany":$(this).find('insCompany').text(),
									"policyNum":$(this).find('policyNum').text(),
									"daeriCompanyNum":$(this).find('daeriCompanyNum').text(),
									"certiTableNum":$(this).find('certiTableNum').text(),
									"date":$(this).find('date').text(),
									"duplicte":$(this).find('duplicte').text()//중복,주민
									} );
	   				
				 });

		
			});
			
			var maxLength=values.length;
			
			//alert(maxLemgth);
			cp_certiList();
			
		});
}

function cp_certiList(){
	alert('청약과동시에 처리  완료 되었습니다!!');
	$("#sj").children().remove();


}