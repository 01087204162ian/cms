<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 
	<style type="text/css">
	   #gallery{
				border: 10px solid #1DOC16;
				height: 300px;
				width: 450px;
				margin-left: auto;
				margin-right :auto;
				overflow: visible;
				background : #272229;
				-webkit-box-shadow: #272229 10px 10px 20px;
				-moz-box-shadow: #272229 10px 10px 20px;
				box-shadow: #2722229 10px 10px 10px 20px;

	    }

		gallery ul{
			margin-left: -30px;
		}

		#gallery ul li{
	
			list-style: none;
			display:inline-table;
			padding:10px;
		}

		#gallery ul li .pic{
			-webkit-transition : all 0.6s ease-in-out;
			opacity:0;
			visibility:hidden;
			position:absolute;
			margin-top:10px;
			margin-left:-20px;
			border:1px solid black;
			-webkit-box-shadow: #272229 2px 2px 10px;
				-moz-box-shadow: #272229 2px 2px 10px;
			box-shadow: #2722229 2px 2px 10px 10px;
		}
		#gallery ul li .caption{
			/*-webkit-transition : all 0.6s ease-in-out;*/
			opacity:0;
			visibility:hidden;
		}
		#gallery ul li .small:hover{
				cursor:pointer;
		}

		#gallery ul li:hover .pic {
				width:302px;
				height:240px;
				opacity:1;
				visibility:visible;
				float:right;
				

		}
		

		

	</style>

	<script type="text/javascript">
		

	</script>
</head>
<?

	$sql="SELECT * FROM 2014ValetImage WHERE PhoneNumber='$PhoneNumber'";

	
	$rs=mysql_query($sql,$connect);


	$count=mysql_num_rows($rs);
	
	//echo $count;

	//echo "$row[images]";

?>
<?//include "./php/endorseCount.php";?>
<?$redirectURL='DMM_System'?>

<body id="popUp">
<form>
	<input type='hidden' id='num' value='<?=$pImageNum?>' />
	<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
	<input type='hidden' id='eNum' value='<?=$eNum?>' />
	<input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
			  <div id="textg">
				<span id="texti"></span>
			  </div>
			
			  <div id="gallery">
			    <ul>
				 <?  for($j=0;$j<$count;$j++){
					$row=mysql_fetch_array($rs);?>
				<li><img src="/valet/uploads/<?=$row[images]?>" width="120" height="90" class="small">
				<img src="/valet/uploads/<?=$row[images]?>"  class="pic">	 
			    </li>
		       <?}?>
			   </ul>
				
				
		</div>
			
			


</form>
</body>
</html>