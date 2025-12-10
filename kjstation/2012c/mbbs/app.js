$(document).live("mobileinit", function() {

	/********************************************
	 * 기본 환경설정
	 ********************************************/
	$.extend($.mobile, {
		defaultPageTransition: "slide",
		defaultDialogTransition: "pop",
		loadingMessage: "페이지 로딩중입니다...",
		loadingMessageTextVisible: true,
		loadingMessageTheme: "a",
		pageLoadErrorMessage: "페이지를 불러올 수 없습니다.",
		pageLoadErrorMessageTheme: "e",
		allrowCrossDomainPages: true
	});
	
	/********************************************
	 *  로그인
	 ********************************************/
	$("#login").live("pageinit", function() {	
		/** 로그인 버튼 처리 */
		$("#login input#log").tap(function() {
			var user_name	= $("#login #uid");
			var user_pass	= $("#login #upassword");
			//var subject		= $("#write #subject");
			//var content		= $("#write #content");

			if (!user_name.val()) {
				alert("아이디를  입력하세요");
				user_name.focus();
				return false;
			}

				
			if (!user_pass.val()) {
				alert("비밀번호를 입력하세요");
				user_pass.focus();
				return false;
			}
		
			//alert(DaeriCompanyNum.val());
			$.ajax({
				url: "login_ok.php",
				type: "get",
				data : {
					"user_name": user_name.val(),
					"user_pass": user_pass.val()					
				},
				dataType: "json",
				timeout: 30000,
				success: function(json) {
					var num = json.num;
					var ex= json.ex;
					var p= json.p;
					
					if(p==1){
							$.mobile.changePage("driverList.php", {
								data : {
									"id": num,
									//"num": num
								}
							});
					}else{
						alert(ex);

					}

					
				},
				error: function(xhr, textStatus, errorThrown) {
					alert("글쓰기에 실패했습니다.\n" + textStatus + " (HTTP-" + xhr.status + " / " + errorThrown + ")");
				}
			});
		});
	});


	/********************************************
	 * 글 쓰기 페이지
	 ********************************************/
	$("#write").live("pageinit", function() {
	
		$("#write #user_jumin").click(function(){
			var user_jumin	= $("#write #user_jumin");
			
			if(user_jumin.val().length==14){
			var phone_first	=user_jumin.val().substring(0,6);
			var phone_second=user_jumin.val().substring(7,14);
			user_jumin.val(phone_first+phone_second);
			}
		});
		/** 글 저장 버튼 처리 */
		$("#write #btn_write_save").tap(function() {
			var DaeriCompanyNum	= $("#write #DaeriCompanyNum");
			var CertiTableNum	= $("#write #CertiTableNum");
			var insuranceNum	= $("#write #insuranceNum");
			var user_name	= $("#write #user_name");
			var user_jumin	= $("#write #user_jumin");
			//var subject		= $("#write #subject");
			//var content		= $("#write #content");

			if (!user_name.val()) {
				alert("이름을 입력하세요");
				user_name.focus();
				return false;
			}
			if (!check_juminno(user_jumin.val())){
				  user_jumin.focus();
				  user_jumin.val('');
				 
				return false;
			}else{
				var phone_first	=user_jumin.val().substring(0,6);
				var phone_second=user_jumin.val().substring(6,13);
				user_jumin.val(phone_first+"-"+phone_second);

			}
			var enddorse_day = $("#write #endorse_day");
				
				
		/*	if (!user_pass.val()) {
				alert("비밀번호를 입력하세요");
				user_pass.focus();
				return false;
			}
			if (!subject.val()) {
				alert("글 제목을 입력하세요");
				subject.focus();
				return false;
			}
		   if (!content.val()) {
				alert("내용을 입력하세요");
				content.focus();
				return false;
			}*/
			//alert(DaeriCompanyNum.val());
			$.ajax({
				url: "write_ok.php",
				type: "get",
				data : {
					"user_name": encodeURIComponent(user_name.val()),
					"user_jumin": user_jumin.val(),
					"enddorse_day": enddorse_day.val(),
					//"content": content.val(),
					"DaeriCompanyNum": DaeriCompanyNum.val(),
					"insuranceNum": insuranceNum.val(),
					"CertiTableNum": CertiTableNum.val()
				},
				dataType: "json",
				timeout: 30000,
				success: function(json) {
					var num = json.num;

					alert(num);
					$.mobile.changePage("driverList.php", {
						data : {
							"id": DaeriCompanyNum.val(),
							//"num": num
						}
					});
				},
				error: function(xhr, textStatus, errorThrown) {
					alert("글쓰기에 실패했습니다.\n" + textStatus + " (HTTP-" + xhr.status + " / " + errorThrown + ")");
				}
			});
		});
	});
	
	/********************************************
	 * 글 읽기 페이지
	 ********************************************/
/*	$("#list").live("pageinit", function() {
		/** 글 삭제 버튼 처리 */
	//	$("#list #delete_password #btn_delete").tap(function() {
	//		var pwd = $("#view #delete_password #password").val();
	//		checkPassword("delete", pwd);
	//	});

		/** 글 수정 버튼 처리 */
		/*($("#view #edit_password #btn_edit").tap(function() {
			var pwd = $("#view #edit_password #password").val();
			alert('1')
			checkPassword("edit", pwd);
		});*/

	//	$("#list #edit_password #btn_edit").tap(function() {
			
	//		var pwd = $("#list #edit_password #password").val();
			//var endors_day = $("#list>input #endorse_day").val();
			//alert(endorse_day)
	//		checkPassword("edit", pwd);
	//	});
	//});
	
	/********************************************
	 * 글 수정 페이지
	 ********************************************/
	$("#edit").live("pageinit", function() {
		
		/** 글 저장 버튼 처리 */
		$("#edit #btn_edit_save").tap(function() {
			var id	= $("#edit #id");
			var num	= $("#edit #num");
			var user_name	= $("#edit #user_name");
			var subject		= $("#edit #subject");
			var content		= $("#edit #content");

			if (!user_name.val()) {
				alert("이름을 입력하세요");
				user_name.focus();
				return false;
			}
			if (!subject.val()) {
				alert("글 제목을 입력하세요");
				subject.focus();
				return false;
			}
			if (!content.val()) {
				alert("내용을 입력하세요");
				content.focus();
				return false;
			}

			$.ajax({
				url: "edit_ok.php",
				type: "post",
				data : {
					"user_name": user_name.val(),
					"subject": subject.val(),
					"content": content.val(),
					"num": num.val()
				},
				dataType: "text",
				timeout: 30000,
				success: function(json) {
					$.mobile.changePage("view.php", {
						data : {
							"id": id.val(),
							"num": num.val()
						},
						reverse: true,
						changeHash : false
					});
				},
				error: function(xhr, textStatus, errorThrown) {
					alert("글수정에 실패했습니다.\n" + textStatus + " (HTTP-" + xhr.status + " / " + errorThrown + ")");
				}
			});
		});
	});
});

function checkPassword(mode,driverNum) {
	/*if (!pwd) {
		alert("비밀번호를 입력하세요");
		return false;
	}*/

	var enddorse_day = $("#list #endorse_day").val();
	
		
	$.ajax({
		url: "password_ok.php",
		type: "post",
		data : {
			"driverNum": driverNum,
			"enddorse_day": enddorse_day
		},
		dataType: "json",
		timeout: 30000,
		success: function(json) {
			if (!json.result) {
				alert("비밀번호가 맞지 않습니다.");
			} else {
				if (mode == "delete") {
					deleteArticle(num);
				} else {
					editArticle(num);
				}
			}
		},
		error: function(xhr, textStatus, errorThrown) {
			alert("비밀번호 검사에 실패했습니다.\n" + textStatus + " (HTTP-" + xhr.status + " / " + errorThrown + ")");
		}
	});
}

function deleteArticle(num) {
	$.ajax({
		url: "delete_ok.php",
		type: "post",
		data : "num=" + num,
		dataType: "json",
		timeout: 30000,
		success: function(json) {
			var id = $("#view #id").val();
			$.mobile.changePage("list.php", {
				data: {"id":id},
				reverse: true,
				changeHash : false
			});
		},
		error: function(xhr, textStatus, errorThrown) {
			alert("글 삭제에 실패했습니다.\n" + textStatus + " (HTTP-" + xhr.status + " / " + errorThrown + ")");
		}
	});
}

function editArticle(num) {
	var id = $("#view #id").val();
	$.mobile.changePage("edit.php#edit", {
		data: {"id":id, "num":num},
		type: "post",
		changeHash : false
	});
}