<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
    
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		<style type="text/css">
*{
	margin:0;
	padding:0;
	border:0;
}
body {
	 FONT-SIZE: 9pt;
	font-family: "Lucida Grande", Verdana, Geneva, Lucida, Arial, Helvetica, sans-serif;
	background:black;
}
#container{
	margin: 10px 0 0 10px;
	width:1000px;

}
#content{
  	background:#ffffff;
	width:140px;
   height: 300px;
	float:left;
	border:1px solid white;
	padding: 10px 1px 0px  2px;
}


#navi{
	width:300px;
	float:left;
}

#navi ul{
	height:460px;
}

#navi ul li{
	width:150px;

	float:left;
}

#navi ul li img{
	border:3px solid white;
}
#main{
	width:550px;
	float:right;
}
#main img{
	border:3px solid white;
}
		</style>
		<script type="text/javascript">
$(function(){
	$("#navi a").click(function(){
		$("#main img").attr("src",$(this).attr("href"));

		var imagefile=$(this).attr("href");
		var PhoneNumber = $('#PhoneNumber').val();
		
		var data = 'uPhoneNumer='+ PhoneNumber
						  +"&uimagefile="+imagefile;


			$.ajax({
				type:"POST",
				dataType:"xml",
				url:"./ajax/imageAjax.php",
				data:data,
				success:function (xml){
					$(xml).find('data').each(function(){

						$("#uname").text($(this).find('name').text());
						$("#uphone").text($(this).find('phone').text());
						$("#ujumin").text($(this).find('jumin').text());
						 $("#date").text($(this).find('date').text());
					    $("#time").text($(this).find('time').text());
						 
					});
				}
			});
		return false;
	});
});
		</script>
		<title>image관리</title>
	</head>






<body>
	<?
	    	$sql="SELECT * FROM 2014ValetImage WHERE PhoneNumber='$PhoneNumber'";
	        $rs=mysql_query($sql,$connect);
	       $count=mysql_num_rows($rs);

	?>
		<div id="container">
			<input type='hidden' id='PhoneNumber' value='<?=$PhoneNumber?>' />
			<div id="navi">
				<ul>
						<?  for($j=0;$j<$count;$j++){
								$row=mysql_fetch_array($rs);?>
								<li><a href="/valet/uploads/<?=$row[images]?>">
								       <img src="/valet/uploads/<?=$row[images]?>" width="120" height="90" /></a>
								</li>
						<?}?>
				</ul>
			</div>
			<div id="content">
				<ol>
				<li><span id="date">&nbsp;</span> <span id="time">&nbsp;</span></li>
				
				<li><span id="uname">&nbsp;</span></li>
				<li><span id="uphone">&nbsp;</span></li>
				<li><span id="ujumin">&nbsp;</span></li>
				
				</ol>
			</div>
			<div id="main">
				<img src="images/photo1.jpg" width="500" height="550" />
			</div>
		</div>
	</body>
</html>