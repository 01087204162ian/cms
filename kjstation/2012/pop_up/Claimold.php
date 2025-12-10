<?include '../../2012/lib/lib_auth.php';?>
<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />
	<link href="../css/sj.css" rel="stylesheet" type="text/css" />
	 <script src="../../me/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	 <script src="../js/pop.js" type="text/javascript"></script>
	<link href="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
	<script src="../../sj/calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js" type="text/javascript"></script>
	<script src="../../sj/js/smsAjax.js" type="text/javascript"></script>
	<script src="../js/create.js" type="text/javascript"></script>
	<script src="./js/basicAjax.js" type="text/javascript"></script>
	<script src="./js/claimAjax.js" type="text/javascript"></script>
	
</head>

<?include "./php/endorseCount.php";?>
<?$redirectURL='DMM_System'?>

<body id="popUp">
<form>
	<input type='hidden' id='num' value='<?=$daeriComNum?>' />
	<input type='hidden' id='CertiTableNum' value='<?=$CertiTableNum?>' />
	<input type='hidden' id='DariMemberNum' value='<?=$DariMemberNum?>' />
	<input type='hidden' id='userId' name='userId' value='<?=$userId?>'>
		<div id="claimPopUpMain">
		  
			  <div id="listPart" ><? include "./php/daeriTable.php";?>	</div>
			  <div id='smSide'><? include "./php/smsState.php";?></div>
			   
			<input type='button' id='sms' class='btn-b' value='smsList' />
			<input type='button' id='smsTotal' class='btn-b' value='smsList[전체]' />
			<div id='first'><? include "./php/smsList.php";?></div>
	
			<div id='claimList'><? include "./php/claimList.php";?></div>
			
				

			</div>	
		  
				
		</div>
			
			


</form>
</body>
</html>

-->
<!DOCTYPE html>
<html lang="en">

  <head>
	<script>
	if (location.protocol === 'http:' && location.host.indexOf('cafe24.com') === -1) {
    var sUrl = 'https://' + location.host + location.pathname + location.search;

		 window.location.replace(sUrl);
	}
	</script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>대리운전사고처리</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/portfolio-item.css" rel="stylesheet">

  </head>

  <body>

    
      <!-- /.row -->
	  <div class="row" id='contact'>

        <div class="col-md-6">
          <h4>CONTACT US</h4>
			<ul>
			 <li>서울특별시 강남구 자곡로 강남에이스 타워215호 </li>
			 <li>215,Jagok-ro 174-10 Gil  Kangnam-gu, Seoul,Korea </li>

			 <!--<li>사업자번호: 129-92-79346</li>-->
			 <li>사업자번호: 128-87-16058</li>
			 <li>상호: (주)이투엘 보험대리점</li>
			 <li>대표자 : 오 성준</li>
			 <li>정보담당자 : 장인하</li>
			  <li>Office : 070-7841-5963</li>
			 <li>Fax : 02-6455-0223</li>
			 <li>Email : sj@simg.kr</li>
            </ul>

        </div>

		 <div class="col-md-6">
		 
			<form>
			  <input type='hidden' value='1' id='subject'>
			  <div class="form-group">
				<!--<label for="formGroupExampleInput">성명</label>-->
				<input type="text" class="form-control" id="u_name" placeholder="성명">
			  </div>
			  <div class="form-group">
				<!--<label for="s_sigi">상담일</label>-->
				<input type="text"  id="s_sigi" placeholder="상담일">
			  </div>
			  <div class="form-group">
				<!--<label for="formGroupExampleInput">핸드폰</label>-->
				<input type="text" class="form-control" id="hphone" placeholder="하이푼없이숫자만">
			  </div>

			  <div class="form-group">
				<!--<label for="formGroupExampleInput">핸드폰</label>-->
                <textarea type="text" class="form-control" id="problem" placeholder="요청사항" height="200"></textarea>
			  </div>

			  <button type="button" class="btn btn-primary" id='sangdam'>상담요청</button>
			</form>
         
		</div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; 이투엘보험대리점 2018</p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="./intro/vendor/jquery/jquery.min.js"></script>
    <script src="./intro/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="/static/lib/js/intro.js"></script>
  </body>

</html>