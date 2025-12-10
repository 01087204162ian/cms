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
						jstr += "<td class='center_td' width='18%'>"
							
							+"C열"+"</td>\n";
							
						jstr += "<td class='center_td' width='26%'>"
							
							+"D열"+"</td>\n";
						jstr += "<td class='center_td' width='9%'>"
							
							+"E열"+"</td>\n";
						jstr += "<td class='center_td' width='9%'>"
							
							+"F열"+"</td>\n";
						jstr += "<td class='center_td' width='18%'>"
							
							+"G열"+"</td>\n";
						
						
				 jstr += "</tr>\n";
				
				 jstr += "<tr>";
			
						jstr += "<td class='center_td' >"
							+"성명"+"</td>\n";

						jstr += "<td class='center_td'>"
							
							+"주민번호"+"</td>\n";
						jstr += "<td class='center_td'>"
							
							+"핸드폰<br>(010-8720-4162)"+"</td>\n";	
						jstr += "<td class='center_td'>"
							
							+"통신사(1sk,2kt,3lg,4sk알뜰폰,5kt알뜰폰,6lg알뜰폰)"+"</td>\n";	
						jstr += "<td class='center_td'>"
							
							+"본인1 타인2"+"</td>\n";	
						jstr += "<td class='center_td'>"
							
							+"주소"+"</td>\n";	
						jstr += "<td class='center_td'>"
							
							+"동부화재인경우만(1234567)"+"</td>\n";
							
						
						
				 jstr += "</tr>\n";

				jstr += "<tr>";
			
						
						jstr += "<td class='center_td' colspan='7'>"
							
							+"2행부터 data 입력하세요"+"</td>\n";	
				 jstr += "</tr>\n";

				

			
			
			
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
	var send_url = "/2012/pop_up/_db/example.php";	
	//alert(send_url+fileName+ endorse);
		$("#fileName").val(fileName);
	$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"gias_new_W", 
				     dNum:$("#daeriCompanyNum").val(),
					 cNum:$("#certiTableNum").val(),
					 fileName:fileName,
					 endorse:endorse,
					 endorseDay:$("#sigi").val()

					// date:$("#sigi").val()
				    }
		}).done(function( xml ) {

			values = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('policy').each(function() {
					alert($(this).find('insCompany').text());
            	   	values.push( {	"name":$(this).find('name').text(),
									"nai":$(this).find('nai').text(),
									"nai2":$(this).find('nai2').text(),
									"jumin":$(this).find('jumin').text(),
									"hphone":$(this).find('hphone').text(),
									"local":$(this).find('local').text(),
									"jisa":$(this).find('jisa').text(),
									"nhp":$(this).find('nhp').text(),
									"insCompany":$(this).find('insCompany').text(),
									"policyNum":$(this).find('policyNum').text(),
									"daeriCompanyNum":$(this).find('daeriCompanyNum').text(),
									"certiTableNum":$(this).find('certiTableNum').text(),
									"date":$(this).find('date').text(),
									"duplicte":$(this).find('duplicte').text(),//중복,주민
									"Lcount":$(this).find('Lcount').text(),
									"l_button":$(this).find('l_button').text(),//// 지역지사가 불일치 이면 등록 버튼이 안나오게 하기 위해 
									 "n_duplicate":$(this).find('n_duplicate').text(), /////나이가 최저년령 미만인경우 제한
									 "n_duplicate2":$(this).find('n_duplicate2').text()//최저년령자가 있으면 버튼이 안나오게하기 위해
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
				
				+"성명11"+"</td>\n";
			jstr += "<td  width='5%'>"
				
				+"나이"+"</td>\n";
		//	jstr += "<td  width='5%'>"
				
		//		+"나이"+"</td>\n";
				
			jstr += "<td  width='15%'>"
				
				+"주민번호"+"</td>\n";
			jstr += "<td  width='8%'>"
				
				+"핸드폰번호"+"</td>\n";
			jstr += "<td  width='10%'>"
				
				+"통신사"+"</td>\n";
			jstr += "<td  width='10%'>"
				
				+"명의"+"</td>\n";
			jstr += "<td  width='8%'>"
				
				+"주소"+"</td>\n";
			
			jstr += "<td  width='5%'>"
				
				+"보험사"+"</td>\n";
			jstr += "<td  width='12%'>"
				
				+"증권번호"+"</td>\n";
			
			jstr += "<td  width='10%'>"
				
				+"사유"+"</td>\n";

			
			
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
		///alert(values[k].insCompany);
		jstr += "<tr>";
			jstr += "<input type='hidden'  id='cNum_"
					+k+"'" 
					+">\n";
			jstr += "<input type='hidden'  id='dNum_"
					+k+"'"
					+">\n";
			jstr += "<td >"
				+j+"</td>\n";
			jstr += "<td >"
				+values[k].name+"</td>\n";
			jstr += "<td >"
				+values[k].nai+"</td>\n";
		//	jstr += "<td >"
		//		+values[k].nai2+"</td>\n";
			jstr += "<td >"
				+values[k].jumin+"</td>\n";
			jstr += "<td >"
				+values[k].hphone+"</td>\n";
			jstr += "<td >"
				+values[k].local+"</td>\n"; //통신사
			jstr += "<td >"
				+values[k].nhp+"</td>\n";   //명의
			jstr += "<td >"
				+values[k].jisa+"</td>\n";  //주소
			
			jstr += "<td >"
				+values[k].insCompany+"</td>\n";
			jstr += "<td >"
				+values[k].policyNum+"</td>\n";
			
			jstr += "<td id='com"+k+"'"+">"
				+values[k].duplicte+jvalues[k]+values[k].Lcount+values[k].n_duplicate+"</td>\n";
		jstr += "</tr>\n";
		
		}//for 종료
	  jstr += "<tr>";
			jstr += "<td  colspan='6'>"
				 +"추가된 인원"+"</td>\n";
			jstr += "<td>"
				
				+j+"명"+"</td>\n";
			
			if(buttonS==1){
			jstr += "<td  colspan='5'>"
				+"오류 수정 후 청약과 처리 하세요";
				//+$("#daeriCompanyNum").val()+'/'+$("#certiTableNum").val()+'/'+$("#fileName").val()+'/'+buttonS
				+"</td>\n";			
			}else{

			  jstr += "<td  colspan='5'>"
				+"<input type='button' id='eSto"+k+" 'value='청약->처리' class='btn-b'" 
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