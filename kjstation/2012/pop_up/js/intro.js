
$(document).ready(function(){
	dpTime();
	juminSerch(); //대리기사 조회 성명,주민번호,
	claimList();
	
	$('#claminButton').click(function(){
		$("#kj").children().remove();
		
		claimDetail($('#Certi').val());
	});

});

function claimDetail(certi){
	
	$("#kj").children().remove();
		str='';
		
	    str += "<tr>";
				
				str += "<td  width='25%'>"+"사고번호"+"</td>\n";
				str += "<td  width='25%'>"
					 +"<input type='text' class='textareC' id='sagoNumber'>"
					
					 +"</td>\n";
				str += "<td  width='25%'>"+"증권번호"+"</td>\n";
				str += "<td  width='25%'>"
					 +"<input type='text' class='textareC' id='policy'>"
					 +"<input type='hidden'  id='sagoTableNum'>";//사고 확인 후 sagoTableNum이 있으면 update
					 +"</td>\n";
				
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td >"+"사고일"+"</td>\n";
				str += "<td>"
					 +"<input type='text' class='textareL' id='sagotime'>"
					 +"</td>\n";
				str += "<td>"+"사고시간"+"</td>\n";
				str += "<td >"
					 +"<input type='text' class='textareL' id='sago_time2'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"사고장소"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='jangso'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"사고경위<br>"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='reason'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"보상종류"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<select id='sort_' class='textareL'>"
						+"<option value='-1'>선택</option>"
						+"<option value='1'>차</option>"
						+"<option value='2'>차,물</option>"
						+"<option value='3'>차,물,인</option>"
						+"<option value='4'>차,인</option>"
						+"<option value='5'>물,인</option>"
					 +"</select>"
					 +"</td>\n";
    	str += "</tr>\n";
	/*	str += "<tr>";
				str += "<td  >"+"진단명"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='sort'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"은행명"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='bank'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td>"+"계좌번호"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='banknumber'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"예금주"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='bankname'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"핸드폰"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='hphone2'>"
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"E-mail"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='email2'>"
					 +"</td>\n";
    	str += "</tr>\n";*/
        str += "<tr>";
				str += "<td  >"+"지급 금액"+"</td>\n";
				str += "<td>"
					 +"<input type='text' class='textareR' id='money' placeholder='확정금액'>"
					 +"</td>\n";
				str += "<td>"
					 +"<input type='text' class='textareR' id='money1' placeholder='추산금액'>"
					 +"</td>\n";
				str += "<td>"
					 +"<input type='text' class='textareR' id='money2' placeholder='계'>"
					 +"</td>\n";
    	str += "</tr>\n";
    
		str += "<tr>";
        	str += "<td colspan='4' >"
				+ "<input type='button' class='btn btn-success' id='eStore' value='사고 입력'"
				+">"
				
			    +"</td>\n";
    	    
		/*	str += "<td  >"
				+ "<input type='button' id='eStore' value='    Print    '"
				+" onClick='ePrint()'"
				+">"
			    +"</td>\n";
			str += "<td  >"
				+ "<input type='button' id='eStore' value='    Print    '"
				+" onClick='ePrint2()'"
				+">"
				+"</td>\n";*/
    	    str += "</tr>\n";
	/*	str += "<tr>";
				str += "<td  >"+"보완사유"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<textarea  id='office'   onBlur='officet()' rows='2' cols='15' style='border: 1px none #0FB9C4; overflow-y:auto; overflow-x:hidden;width:100%;word-break:break-all;background-color:#ffffff;padding:5 5 5 5;' />"
					 
					 +"</td>\n";
    	str += "</tr>\n";
		str += "<tr>";
				str += "<td  >"+"설계번호"+"</td>\n";
				str += "<td  colspan='3'>"
					 +"<input type='text' class='textareL' id='seNum'   onBlur='seNum_()' onClick='selNum2()'>"
					 +"</td>\n";
    	str += "</tr>\n";
		*/
        //alert();
		
			//
			  
			
			$("#kj").append(str);

			$( "#sagotime" ).datepicker({
				format:"yyyy-mm-dd",
				calendarWeeks:true,
				todayHighlight:true,
				autoclose:true,
				language:"kr"
			  });

			if(certi){
				//values[0].policy=certi;//클레임 신규 
				//alert(certi);
				$("#policy").val(certi);
				$("#sagoTableNum").val('');
				$("sagoNumber").val('');
				$("#sagotime").val('');
				$("#sago_time2").val('');
				$("#jangso").val('');
				$("#reason").val('');

				$("#money").val('');
				$("#money1").val('');
				$("#money2").val('');
			}else{

				//alert(values[0].sagoNumber);
				$("#sagoTableNum").val(values[0].sagoTableNum);
				$("#policy").val(values[0].policy);
				$("#sagoNumber").val(values[0].sagoNumber);
				$("#sagotime").val(values[0].sagotime);
				$("#sago_time2").val(values[0].sago_time2);
				$("#jangso").val(values[0].jangso);
				$("#reason").val(values[0].reason);
				$("#sort_").val(values[0].sort_);
				$("#money").val(numberWithCommas(values[0].money));
				$("#money1").val(numberWithCommas(values[0].money1));
				$("#money2").val(numberWithCommas(values[0].money2));
			}
			
			

		$("#money").blur(function(){
			if(!this.value){
				this.value='0';
			}else{
				
				moneyTotal();
				this.value=numberWithCommas(this.value);
			}
		});

		$("#money").click(function(){
			if(!this.value){
				this.value='0';
			}else{
				//inwonTotal();
				this.value=numberWithCommas2(this.value);
			}
		});
		

		
		$("#money1").blur(function(){
			if(!this.value){
				this.value='0';
			}else{
				moneyTotal();
				this.value=numberWithCommas(this.value);
			}
		});

		$("#money1").click(function(){
			if(!this.value){
				this.value='0';
			}else{
				
				this.value=numberWithCommas2(this.value);
			}
		});

		$("#money2").blur(function(){
			if(!this.value){
				this.value='0';
			}else{
				//inwonTotal();
				this.value=numberWithCommas(this.value);
			}
		});

		$("#money2").click(function(){
			if(!this.value){
				this.value='0';
			}else{
				//inwonTotal();
				this.value=numberWithCommas2(this.value);
			}
		});


		function moneyTotal(){
			if(!$("#money1").val()){

				$("#money1").val('0');
			}

			if(!$("#money").val()){

				$("#money").val('0');
			}
			var w_total=parseInt(numberWithCommas2($("#money").val()))
			   +parseInt(numberWithCommas2($("#money1").val()))
			   

			$('#money2').val(numberWithCommas(w_total));
		}
		$('#eStore').click(function(){
			if(!$("#sagoNumber").val()){
				alert('사고번호를 입력하세요');
				$("#sagoNumber").focus();
				return false;
			}
			var form_data = new FormData();
			        form_data.append('sagoTableNum',$("#sagoTableNum").val());// 
					 form_data.append('DariMemberNum',$("#DariMemberNum").val());// 
					form_data.append('jumin',$("#jumin").val());//
					form_data.append('sagoNumber',$("#sagoNumber").val());
					form_data.append('policy',$("#policy").val());
					form_data.append('sagotime',$("#sagotime").val());
					form_data.append('sagotime2',$("#sago_time2").val());
					//alert($("#jangso").val());
					form_data.append('jangso',$("#jangso").val());
					form_data.append('reason',$("#reason").val());
					form_data.append('sort_',$("#sort_").val());
					form_data.append('money',numberWithCommas2($("#money").val()));
					form_data.append('money1',numberWithCommas2($("#money1").val()));
					form_data.append('money2',numberWithCommas2($("#money2").val()));
                    $.ajax({
                        url: 'https:/app/2019/boardSago.php', // point to server-side controller method
                        dataType: 'json', // what to expect back from the server
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (data) {
							
							$.each(data, function(key,val){
								alert(val.message);

								$("#sagoTableNum").val(val.last_id);
								$("#kj").children().remove();
								claimList();
							});
							
                        },
                        error: function (response) {
                            $('#msg').html(response); // display error response from the server
                        }
                    });
		});

}

function dpTime()
	{
		

	
		$('#s_sigi').datepicker({
            uiLibrary: 'bootstrap4'
        });
		
	}
function juminSerch(){

	var form_data = new FormData();
					form_data.append('jumin',$("#jumin").val());
					
                    $.ajax({
                        url: 'https:/app/2019/BoardJuminSerch.php', // point to server-side controller method
                        dataType: 'json', // what to expect back from the server
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (data) {
							
							$.each(data, function(key,val){
								$('#name').html(val.Name);
								$('#jumin2').html(val.Jumin);
								$('#hphone').html(val.phone);
								//$('#Certi').val(val.Certi);
								
							});	
							
                        },
                        error: function (response) {
                            $('#msg').html(response); // display error response from the server
                        }
                    });
	//alert($('#jumin').val());
}

function claimList(){

	var form_data = new FormData();
			form_data.append('jumin',$("#jumin").val());
			
			$.ajax({
				url: 'https:/app/2019/boardClaimList.php', // point to server-side controller method
				dataType: 'json', // what to expect back from the server
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				success: function (data) {
					values = new Array();
					$.each(data, function(key,val){
						values.push( {	
										"sagoTableNum":val.num,
										"sagoNumber":val.sagoNumber, 
										"policy":val.policy,
										"sagotime":val.sagotime,
										"sagotime2":val.sagotime2,
										"jangso":val.jangso, 
										"reason":val.reason,
										"sort_":val.sort_,
										"money":val.money,
										"money1":val.money1,
										"money2":val.money2,
										"signal":val.signal
									} );
						
					});	
					
					sagoList();
				},
				error: function (response) {
					$('#msg').html(response); // display error response from the server
				}
			});

}

function sagoList(){

	max_num = values.length;
	$("#sj").children().remove();
	//alert(max_num);
	var k='';
	str='';
		str += "<tr>";
				str += "<td  width='5%'>"+"순번"+"</td>\n";
				str += "<td  width='20%'>"+"사고일시" +"</td>\n";
				str += "<td  width='20%'>"+"사고번호" +"</td>\n";
				str += "<td  width='20%'>"+"증권번호"+"</td>\n";
				str += "<td  width='20%'>"+"사고금액"+"</td>\n";
				str += "<td class='center_td'>"+"비고"+"</td>\n";
    	str += "</tr>\n";
	for(var j=0;j<max_num;j++){

		
		k=j+1;
		
		if(values[j].signal==1){
			str += "<tr>";
					str += "<td>"+k+"</td>\n";
					str += "<td>"+values[j].sagotime+"</td>\n";
					str += "<td>"+values[j].sagoNumber+"</td>\n";
					str += "<td>"+values[j].policy+"</td>\n";
					str += "<th >"+numberWithCommas(values[j].money2)+"</th>\n";
					str += "<td>"+"<input type='button' class='btn ' onclick='gusong("+values[j].sagoTableNum+")' value='사고 확인'"+">"+"</td>\n";
			str += "</tr>\n";
		}else{
			str += "<tr>";
					str += "<td colspan='4'>"+"계"+"</td>\n";
				
					str += "<th >"+numberWithCommas(values[j].money2)+"</th>\n";
					str += "<td>"+"</td>\n";
			str += "</tr>\n";

		}
		//alert(values[j].sagoNumber+'/'+values[j].policy+'/'+values[j].money2+'/');

	}


	$("#sj").append(str);
}

function gusong(sagoTableNum){

	//클레임 Detail 

	var form_data = new FormData();
			form_data.append('sagoTableNum',sagoTableNum);
			
			$.ajax({
				url: 'https:/app/2019/boardClaimDetail.php', // point to server-side controller method
				dataType: 'json', // what to expect back from the server
				cache: false,
				contentType: false,
				processData: false,
				data: form_data,
				type: 'post',
				success: function (data) {
					values = new Array();
					$.each(data, function(key,val){
						values.push( {	
										"sagoTableNum":val.num,
										"sagoNumber":val.sagoNumber, 
										"policy":val.policy,
										"sagotime":val.sagotime,
										"sagotime2":val.sagotime2,
										"jangso":val.jangso, 
										"reason":val.reason,
										"sort_":val.sort_,
										"money":val.money,
										"money1":val.money1,
										"money2":val.money2
										
									} );
						
					});	
					
					claimDetail();
				},
				error: function (response) {
					$('#msg').html(response); // display error response from the server
				}
			});


	
}
//컴마 제거 함수
function numberWithCommas2(x) {
    var str = String(x);
    return str.replace(/[^\d]+/g, '');
}
//컴머 찍기
function numberWithCommas(x) {
	 var str = String(x);
    return str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}