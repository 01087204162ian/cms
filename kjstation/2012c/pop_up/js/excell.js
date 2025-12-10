 // upload 형식을 설명하기 위해
 $(document).ready(function(){
			var str='';
			   str +="<tr>"
				str +="<th colspan='2'>"+"보험 가입명단을 아래 형식에 맞춰 엑셀 파일 구성하면 됩니다"+"</th>"
				
		       str += "</tr>\n";
			   str +="<tr>"
				str +="<th colspan='2'>"+" 1행부터 성명,주민번호를 입력하시면 됩니다"+"</th>"
		       str += "</tr>\n";
			  str +="<tr>"
				str +="<td >"+"A열"+"</td>"
				str +="<td >"+"B열"+"</td>"
		       str += "</tr>\n";
			     str +="<tr>"
				str +="<td >"+"성명"+"</td>"
				str +="<td >"+"주민번호는 910214-5234567 형식입니다"+"</td>"
		       str += "</tr>\n";
		

		$("#pj").append(str);


		$('#upload').on('click', function () {
					//alert($('#file').val());

					
                    var file_data = $('#upload_file').prop('files')[0];
					if(!file_data){
						alert('선택된 파일이 없습니다');
						return false;
					}
					$("#pj").children().remove();
					var  lastFileName=file_data.name.split(".");  
						
					if(!(lastFileName[1]=='xlsx' || lastFileName[1]=='xls')){
						swal2(lastFileName[1]);
						return false;
					}
					
					
					
                    var form_data = new FormData();
					form_data.append('dNum',$("#daeriCompanyNum").val());
					form_data.append('etage',$("#etage").val());
			
                    form_data.append('upload_file', file_data);
				
                    $.ajax({
						url:'/2012/pop_up/_db/kdriveExample2.php',
                        //url: 'https:/index.php/rider_2/ajaxupload/new_upload_excel', // point to server-side controller method
                        dataType: 'json', // what to expect back from the server
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function (data) {
							 

							// alert(data);
							$('#upload_file').val('');
							//excelRead(data,$('#tournum').val());
							hana_data= new Array();
							$.each(data, function(key, val){

								//alert(val.errorName);
								hana_data.push({
									"sunso":val.sunso,
									"reasion":val.reasion,
									"errorName":val.errorName,
									"errorJumin":val.errorJumin,
									"inwon":val.inwon,
								});
							});
							excelResult($('#tournum').val());
							//certi_max_num = kosa_data.length;
							//kosa_table();*/
							//alert(data);
							//$('#url').html(data);
                        }
						,beforeSend:function(){
							//(이미지 보여주기 처리)
							$("#pj").children().remove();
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
                });
 });