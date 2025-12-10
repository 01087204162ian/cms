$(function(){
	$("#sms").click(function(){
		$("div#first:not(:animated)").toggle("slow");
	});
});
$(function(){
	$("#Memo").click(function(){
	
		$("div#memo:not(:animated)").toggle("slow");
	});
});

/*$(function(){
	$("dd:not(:first)").css("display","none");
	$("dt:first").addClass("selected");
	$("dl dt").click(function(){
		if($("+dd",this).css("display")=="none"){
			$("dd").slideUp("slow");
			$("+dd",this).slideDown("slow");
			$("dt").removeClass("selected");
			$(this).addClass("selected");
		}
	}).mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");
	});
});*/

$(function(){
	$("#smsTotal").click(function(){
		if(document.getElementById('checkPhone')){
		var checkPhone=document.getElementById('checkPhone').value;
		}
		if(document.getElementById('a4')){
		var checkPhone=document.getElementById('a4').value;
		}

		var dnum=$('#num').val()
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./php/smsListAll.php?checkPhone='+checkPhone+'&dnum='+dnum,'smsList','left='+winl+',top='+wint+',resizable=yes,width=1024,height=800,scrollbars=yes,status=yes')
	});
});
	
$(function(){
	$("#application").click(function(){
		
		var num=document.getElementById('num').value;

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./print/dajoongApplication.php?num='+num,'application','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	});
});

$(function(){
	$("#policy").click(function(){
		
		var num=document.getElementById('num').value;

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./print/policy.php?num='+num,'policy','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	});
});

$(function(){
	$("#smsTotal2").click(function(){
		if(document.getElementById('checkPhone')){
		var checkPhone=document.getElementById('checkPhone').value;
		}
		if(document.getElementById('B3b')){
		var checkPhone=document.getElementById('B3b').value;
		}

		
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./php/smsListAll.php?checkPhone='+checkPhone,'smsList','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	});
});


$(function(){  //RentCompany.php 저장 클릭
	$("#sj").click(function(){
		
			alert($("#a1").val()+'/'+$("#a2").val()+'/'+$("#a3").val()+'/'+$("#a4").val()+'/'+$("#a5").val()+'/'+$("#a6").val()+'/'+$("#a7").val()+'/'
			
			
			+$("#a8").val()+'/'+$("#a9").val()+'/'+$("#a10").val()+'/'+$("#a11").val()+'/'+$("#a12").val()+'/'+$("#a13").val()+'/'
			
			
			+$("#a28").val()+'/'+$("#a29").val()+'/'+$("#a30").val()+'/')



	});
});