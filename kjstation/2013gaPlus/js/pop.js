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
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/2012/pop_up/php/smsListAll.php?checkPhone='+checkPhone,'smsList','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	});
});
	
$(function(){
	$("#application").click(function(){
		
		var num=document.getElementById('num').value;

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/2012/pop_up/print/dajoongApplication.php?num='+num,'application','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	});
});

$(function(){
	$("#policy").click(function(){
		
		var num=document.getElementById('num').value;

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/2012/pop_up/print/policy.php?num='+num,'policy','left='+winl+',top='+wint+',resizable=yes,width=960,height=800,scrollbars=yes,status=yes')
	});
});