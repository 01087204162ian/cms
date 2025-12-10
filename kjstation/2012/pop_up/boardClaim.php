<?include '../../2012/lib/lib_auth.php';?>
<!DOCTYPE html>
<html lang="en">

  <head>
	<script>
	if (location.protocol === 'http:' && location.host.indexOf('cafe24.com') === -1) {
    var sUrl = 'https://' + location.host + location.pathname + location.search;

		 window.location.replace(sUrl);
	}
	</script>
    <meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>킥보드사고</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./css/portfolio-item.css" rel="stylesheet">
	<link href="../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../css/pop.css" rel="stylesheet" type="text/css" />


  </head>

  <body >

    
      <div id="claimL">

				<input type='hidden' id='DariMemberNum' value='<?=$DariMemberNum?>'>
				<input type='hidden' id='jumin' value='<?=$jumin?>'>
			  
				<table>

				 <tr> 
				   <td width='25%'>성명</td>
				   <td width='25%'>주민번호</td>
				   <td width='25%'>연락처</td>
				   <td width='25%'>클레임추가</td>
				 </tr>

				 <tr> 
				   <td><span id='name'></span></td>
				   <td><span id='jumin2'></span></td>
				   <td><span id='hphone'></span></td>
				   <td><button id='claminButton'>클레임추가</button></td>
				   <input type='hidden' id='Certi' value='<?=$PolicyNum?>'>
				 </tr>

				</table>
				<Br>
						 
					<table>
					 <tbody id="sj">
					
					
					</tbody>
				</table>
		       
				<Br>
					 
					<table>
					 <tbody id="kj">
					
					
					</tbody>
				</table>
		   
				
				
  
				
		    </div><!--main끝-->
			
      
		</div>

    <!-- Bootstrap core JavaScript -->
    <script src="./jquery/jquery.min.js"></script>
	
    <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script src="./js/intro.js"></script>
  </body>

</html>