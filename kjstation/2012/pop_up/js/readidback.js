
$(document).ready(function(){


	//alert($('#num').val());
	ReadIdSerch($('#num').val());

});

function ReadIdSerch(num){


	var form_data = new FormData();
		form_data.append('num', num);
		form_data.append('proc', 'ReadId');
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
				member_data= new Array();
				$.each(data, function(key, val){

					member_data.push({
							"num":val.num,
							"mem_id":val.mem_id,
							"name":val.name,
							
						});
				});
				
				idList();
			},
			error: function (response) {
				$('#msg').html(response); // display error response from the server
			}
		});


}

function idList(){
	alert(member_data.length);

	var str='';
    str +="읽기전용 ID리스트";
	str +="<table class='table table-bordered' width='100%'>";
	  
      str +=" <tr>";	
		str +="<th width='12%'>"+"순번"+"</th>";
		str +="<th width='22%'>"+"성명"+"</th>";
		str +="<th width='22%'>"+"아이디"+"</th>";
		str +="<th width='22%'>"+"비번"+"</th>";
		str +="<th width='22%'>"+"비고"+"</th>";
	 str +=" </tr>";
	 for(var i=0;i<member_data.length;i++){
		 str +=" <tr>";	
			str +="<td>"+member_data[i].num+"</td>";
			str +="<td>"+"성명"+"</td>";
			str +="<td>"+"아이디"+"</td>";
			str +="<td>"+"비번"+"</td>";
			str +="<td>"+"비번"+"</td>";
		 str +=" </tr>";
	 }
	str +="</table>";

	$("#idList").append(str);
}