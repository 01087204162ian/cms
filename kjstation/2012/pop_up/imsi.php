<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script><!--SmsAjax-->
	<script src="../js/create.js" type="text/javascript"></script><!--ajax-->
	<script src="./js/basicAjax.js" type="text/javascript"></script><!--ajaxloading-->
	<script src="./js/popajax.js" type="text/javascript"></script><!--ajax-->
</head>
<?$redirectURL='DMM_System'?>
<form>
<body id="popUp">
<form>

		  
<style type="text/css">
 
 #layer_1{
   display:block;
   width:500px; 
   height:500px;
   background:#d7d7d7;
   border:1px dotted #FF0000;
}
#contents{height:650px; background:#00F;}
   .layerBtn_1{display:inline-block; padding:5px; height:1%; border:1px dotted #ccc;}
</style>
</head>
<body>
<a href="#layer_1" class="layerBtn_1">클릭으로 레이어팝업1 열기</a>
<div id="contents">내용이 들어가는 곳</div>
<div id="layer_1">레이어팝업 1영역<button type="button">닫기</button></div><!--페이지 맨 하단에 위치해야 함! -->


<script type="text/javascript">
 $(document).ready(function() {
 $("#layer_1").hide();
 $("body").css("position","relative"); //바디 태그를 팝업 위치의 기준으로 만들어 줌
$(".layerBtn_1").click(function(){
 $(this).attr("title","현재 열린팝업"); //버튼에 현재 팝업이 열려 있음을 title 속성으로 표시해줌
$("#layer_1").show();
 $("#layer_1").css({"position":"absolute","left":"50px","top":"50px"}); //레이어 팝업이 보이는 위치
return false;
}); 
$("#layer_1 button").click(function(){ // 위에서 적용된 이벤트를 모두 제거(초기화)
 $(".layerBtn_1").removeAttr("title");
 $("#layer_1").removeAttr("style");
 $("#layer_1").hide();
 return false;
});
});
</script>
					
				
  
				
		    </div><!--main끝-->
			
      
	


</form>
</body>
</html>