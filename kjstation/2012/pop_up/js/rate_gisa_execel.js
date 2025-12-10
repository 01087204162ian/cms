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
				str += "<td width='10%'>"+"증권번호"+"</td>\n";
				str += "<td width='15%'>"+"<input type='text' id='policy' class='textareP'>"+"</td>\n";
			    str += "<td width='15%'>"+"<button class='btn-b' id='buttonUpload' onclick='return ajaxFileUpload();'>Upload</button>"+"</td>\n";
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
				//$("#sigi").val(today);
				
				$("#policy").val($("#certiTableNum").val());
			var jstr="";
				  jstr += "<tr>";
			
						
						jstr += "<td class='center_td' colspan='6'>"
							
							+"개인별 손해율을 일괄적으로 입력하는 화면입니다. 겡신하면서  1년에 한번 사용하겠죠 "+"</td>\n";	
				   jstr += "</tr>\n";

			      jstr += "<tr>";
			
						jstr += "<td class='center_td' width='5%'>"
							+"순번"+"</td>\n";

						jstr += "<td class='center_td' width='38%'>"
							
							+"사고건수"+"</td>\n";
						jstr += "<td class='center_td' width='7%'>"
							
							+"적용율"+"</td>\n";
							jstr += "<td class='center_td' width='5%'>"
							+"순번"+"</td>\n";

						jstr += "<td class='center_td' width=38%'>"
							
							+"사고건수"+"</td>\n";
						jstr += "<td class='center_td' width='7%'>"
							
							+"적용율"+"</td>\n";
						
				 jstr += "</tr>\n";
				 jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"1"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"기준"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1"+"</td>\n";
						jstr += "<td class='center_td' >"
							+"8"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 2건 1년간 사고건수 0건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.242"+"</td>\n";
						
				 jstr += "</tr>\n";
				  jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"2"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"무사고"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"0.9"+"</td>\n";

						jstr += "<td class='center_td' >"
							+"9"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 2건 1년간 사고건수 1건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.253"+"</td>\n";
						
				 jstr += "</tr>\n";

				  jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"3"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 0건 1년간 사고건수 0건 무사고  1년 이상"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"0.925"+"</td>\n";

						jstr += "<td class='center_td' >"
							+"9"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 2건 1년간 사고건수 2건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.314"+"</td>\n";
						
				 jstr += "</tr>\n";
				
				 jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"4"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 0건 1년간 사고건수 0건 무사고  1년 이상"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"0.898"+"</td>\n";

						jstr += "<td class='center_td' >"
							+"11"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 3건이상 1년간 사고건수 0건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.428"+"</td>\n";
						
				 jstr += "</tr>\n";

				 jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"5"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 0건 1년간 사고건수 0건 무사고  2년 이상"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"0.889"+"</td>\n";

						jstr += "<td class='center_td' >"
							+"12"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 3건이상 1년간 사고건수 1건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.435"+"</td>\n";
						
				 jstr += "</tr>\n";
				
				jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"6"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 1건 1년간 사고건수 1건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.074"+"</td>\n";

						jstr += "<td class='center_td' >"
							+"13"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 3건이상 1년간 사고건수 2건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.447"+"</td>\n";
						
				 jstr += "</tr>\n";

				 jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"7"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 1건 1년간 사고건수 1건"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.085"+"</td>\n";

						jstr += "<td class='center_td' >"
							+"14"+"</td>\n";

						jstr += "<td class='center_td' >"
							
							+"3년간 사고건수 3건이상 1년간 사고건수 3건이상"+"</td>\n";
						jstr += "<td class='center_td' >"
							
							+"1.459"+"</td>\n";
						
				 jstr += "</tr>\n";
				jstr += "<tr>";
						jstr += "<td class='center_td' colspan='6'>"
							
							+"2행부터 data 입력하세요"+"</td>\n";	
				 jstr += "</tr>\n";
				  jstr += "<tr>";
			
						jstr += "<td class='center_td' width='10%'  colspan='2'>"
							+"A열"+"</td>\n";

						jstr += "<td class='center_td' width='10%' colspan='2'>"
							
							+"B열"+"</td>\n";
						jstr += "<td class='center_td' width='18%' colspan='2'>"
							
							+"C열"+"</td>\n";
						
							
						
						
						
				 jstr += "</tr>\n";
				
				 jstr += "<tr>";
			
						jstr += "<td class='center_td' colspan='2'>"
							+"성명"+"</td>\n";

						jstr += "<td class='center_td' colspan='2'>"
							
							+"주민번호<br>(660327-1234567)"+"</td>\n";
						jstr += "<td class='center_td' colspan='2'>"
							
							+"개인별 요율<br>(1~12까지)"+"</td>\n";	
						
							
						
						
				 jstr += "</tr>\n";

				

				

			
			
			
			$("#sj").append(jstr);
		
			//dp_certiList();
			
	}

function ajaxFileUpload() // 증권번ㄴ호 
	{

		var policy=$("#policy").val();
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
							fileUploadAfter(data.msg,policy);
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

function fileUploadAfter(fileName,policy){ //ajax 파일을 up로드 파일을 일고 읽은것을 일단 임시로 저장합니다
		//alert($("#daeriCompanyNum").val()+'/'+$("#certiTableNum").val()+'/'+$("#sigi").val()+'/'+fileName);
	//var send_url = "/2014/_pages/php/example.php";	
	var send_url = "/2012/pop_up/_db/rateExcellUp.php";	
	//alert(send_url+fileName+ endorse);
		$("#fileName").val(fileName);
	$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"rateExcell", 
				    
					 fileName:fileName,
					 policy:policy
				    }
		}).done(function( xml ) {

			values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('policy').each(function() {
					
            	   	values.push( {	"name":$(this).find('name').text(),
									
									"jumin":$(this).find('jumin').text(),
									"rate":$(this).find('rate').text(),
									
									"policyNum":$(this).find('policyNum').text()
									
									} );
	   				
				 });

		
			});
			
			var maxLength=values.length;
			//alert(maxLength);
			dp_certiList();
			
		});
	}

function dp_certiList(){
	alert("upload aa!! !!!");
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
			jstr += "<td  width='8%'>"
				
				+"rate"+"</td>\n";

			jstr += "<td  width='8%'>"
				
				+"증권번호"+"</td>\n";
			
		jstr += "</tr>\n";


		var buttonS=''
		for(var k=0;k<values.length;k++){
			
			var s_array = values[k].jumin.split("-");
				var juminCheck='';
				var colorCheck='';
				
				if(!checkSSN(s_array[0]+s_array[1])){
				   jvalues[k]='주민오류';
					buttonS=1;
				}else{
					jvalues[k]='';
				}
			// 동일중권ㅇ에 중복이면
			if(values[k].duplicte=='중복'){
				buttonS=1;

			 }
			

			 //지역 또는 지사가 불일치 이면 등록 버튼이 안나오게 

			 if(values[k].l_button==1){
				buttonS=1;

			 }

			  //최저 년령자가 있으면

			 if(values[k].n_duplicate2==1){
				buttonS=1;

			 }
		var j=k+1;

		jstr += "<tr>";
		
			jstr += "<td >"
				+j+"</td>\n";
			jstr += "<td >"
				+values[k].name+"</td>\n";
		
		//	jstr += "<td >"
		//		+values[k].nai2+"</td>\n";
			jstr += "<td >"
				+values[k].jumin+"</td>\n";
			jstr += "<td >"
				+values[k].rate+"</td>\n";
			
			jstr += "<td >"
				+values[k].policyNum+"</td>\n";
			
			
		jstr += "</tr>\n";
		
		}//for 종료
	  jstr += "<tr>";
			jstr += "<td >"
				 +"추가된 인원"+"</td>\n";
			jstr += "<td>"
				
				+j+"명"+"</td>\n";
			
			if(buttonS==1){
			jstr += "<td >"
				+"오류 수정 후 개인별 요율 입력 하세요";
				//+$("#daeriCompanyNum").val()+'/'+$("#certiTableNum").val()+'/'+$("#fileName").val()+'/'+buttonS
				+"</td>\n";			
			}else{

			  jstr += "<td  >"
				+"<input type='button' id='eSto"+k+" 'value='개인별 요율 입력' class='btn-b'" 
				+" onclick='fileStore(2)"+";return false;'"
				+">"
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
	var send_url = "/2012/pop_up/_db/rateExcellUp.php";	
		//alert(send_url);
	//var sj=2; //신규 저장 정상 2는 조해  신청
	//alert(sj);
	var policy=$("#policy").val();
	$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"rateExcell_W", 
				    
					 fileName:$("#fileName").val(),
					 
					 policy:policy
					
				    }
		}).done(function( xml ) {

			values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('policy').each(function() {
					
            	   	/*values.push( {	"name":$(this).find('name').text(),
									"nai":$(this).find('nai').text(),
									"jumin":$(this).find('jumin').text(),
									"hphone":$(this).find('hphone').text(),
									"insCompany":$(this).find('insCompany').text(),
									"policyNum":$(this).find('policyNum').text(),
									"daeriCompanyNum":$(this).find('daeriCompanyNum').text(),
									"certiTableNum":$(this).find('certiTableNum').text(),
									"date":$(this).find('date').text(),
									"duplicte":$(this).find('duplicte').text()//중복,주민
									} );*/
	   				
				 });

		
			});
			
			//var maxLength=values.length;
			
			//alert(maxLemgth);
			cp_certiList();
			
		});
}

function cp_certiList(){
	alert('입력 완료 !!');
	$("#sj").children().remove();


}