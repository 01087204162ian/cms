
$(document).ready(function(){
	
	$('#haeji').click(function(){

		if(!$("#hphone").val()){
			$('#hphoneError').html('핸드폰번호를 입력하세요');
			$('#hphoneError').css("color","red");
            $("#hphone").focus();
           return false;
        }

		var form_data = new FormData();
		form_data.append('ClientListNum', $("#ClientListNum").val());
		form_data.append('hphone', $("#hphone").val());
		//form_data.append('memo', this.value);

		$.ajax({
			url: 'http:/index.php/cashnote/cashfc/hphoneClickSujeong', // point to server-side controller method
			dataType: 'json', // what to expect back from the server
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function (data) {

				alert('핸드폰번호 수정됨!!');


			},
			error: function (response) {
				$('#msg').html(response); // display error response from the server
			}
		});
	});

	

});


function haeji(){

	if(confirm('해지 합니다')){

		var form_data = new FormData();
		form_data.append('CertiTableNum', $("#CertiTableNum").val());
		form_data.append('proc', 'haeji');
		//form_data.append('memo', this.value);

		$.ajax({
			url: '/_db/_haeji.php', // point to server-side controller method
			dataType: 'json', // what to expect back from the server
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function (data) {

				$.each(data, function(key, val){

					alert(val+"명 해지함");
					location.reload()
				});

			},
			error: function (response) {
				$('#msg').html(response); // display error response from the server
			}
		});
	}

}


	
		