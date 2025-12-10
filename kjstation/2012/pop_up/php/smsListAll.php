
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title><?=$titleName?></title>
<?include '../../../dbcon.php';?>
<!--	<link href="../../css/sj.css" rel="stylesheet" type="text/css" />-->
	<link href="../../css/popsj.css" rel="stylesheet" type="text/css" />
	<link href="../../css/pop.css" rel="stylesheet" type="text/css" />
	<script src="../../js/create.js" type="text/javascript"></script><!--ajax-->
<?list($hphone1,$hphone2,$hphone3)=explode("-",$checkPhone);

//echo "dnum $dnum ";
/*	$sm_sql="SELECT * FROM SMSData WHERE Rphone1='$hphone1' ";
	$sm_sql.="and Rphone2='$hphone2' and Rphone3='$hphone3'  ";
	$sm_sql.="order by SeqNo desc "; */


	$sm_sql=" SELECT * FROM SMSData WHERE 2012DaeriCompanyNum ='$dnum' and dagun='1' order by SeqNo desc";
//echo "sm_sql $sm_sql <br>";
	
	$sm_rs=mysql_query($sm_sql,$connect);
	
	$sm_total=mysql_num_rows($sm_rs);

	//echo "sm $sm_total <br>";
?>

<?// echo "sm  $sm_total <br>";?>
<script>
function Receive() {
	if(myAjax.readyState == 4 && myAjax.status == 200) {
		//alert(myAjax.responseText);
			
			if(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text!=undefined){
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].text);
			}else{
				alert(myAjax.responseXML.documentElement.getElementsByTagName("message")[0].textContent);
			
				}
		

		}
	}
function changeSangtae(val,val2){
	//alert(val) ;
	//alert(val2)
	myAjax=createAjax();
	
		
		//displayLoading(self.document.getElementById("imgkor"));

		var toSend = "/sj/intro/ajax/canCelUpdate.php?cNum="+val
				   +"&get="+val2;
		//alert(toSend)
		//self.document.getElementById("url").innerHTML = toSend;
		myAjax.open("get",toSend,true);
		myAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
		myAjax.onreadystatechange=Receive;
		myAjax.send('');

}


</script>
</head>
<body id="popUp">
<form>
<?if($sm_total){?>
<div id="SjPopUpMain">
<table >
	<tr>
		<td width="5%">번호</td>
		<td width="20%">발송일</td>
		<td width="60%">메세지</td>
		<td width="">회사</td>
		<td width="">결과</td>
		</tr>
	<?for($_g_=0;$_g_<$sm_total;$_g_++){
		$_h=$sm_total-$_g_ ;
		$sm_row=mysql_fetch_array($sm_rs);
		$LastTime	=$sm_row['LastTime'];
		$Msg		=$sm_row['Msg'];

		$ysear			=substr($LastTime,0,4);
		$month			=substr($LastTime,4,2);
		$day			=substr($LastTime,6,2);
		$_time			=substr($LastTime,8,2);
		$_minute		=substr($LastTime,10,2);
		$com	  = $sm_row[company];

		//echo "com $com <Br>";
		switch($com){
			case 1 :

				$comName='흥국';
			break;
			case 2 :

				$comName='DB';
			break;

			case 3 :

				$comName='KB';
			break;

			case 4 :

				$comName='현대';
			break;

			case 5 :

				$comName='롯데';
			break;


			case 9 :

				$comName='메리츠';
			break;

			case 10 :

				$comName='보험료';
			break;
		}

		$ssang_c_num	  = $sm_row[ssang_c_num];
		$endorse_num	  = $sm_row[endorse_num];
		$get	  = $sm_row[get];
		$canCelNum= $sm_row[SeqNo];
		if($com){
			switch($get){
				case 2 :
					$bgColor="#fffarf";
				break;
				default :
					$bgColor="#ffffff";
				break;
			}
		}else{
			$bgColor="#ffffff";
		}

	?>
		<tr bgcolor="<?=$bgColor?>" height="20" align="center">
			<td><?=$_h?></td>
			<td><?=$ysear?>년<?=$month?>월<?=$day?>일<?=$_time?>시<?=$_minute?>분</td>
			<td><?=$Msg?></td>
			<td><?if($com){?>
			<?=$comName?>
			<?}?>
			</td>
			<td>
			<?if($com){?>
				<select id='pro' name='pro'  class='input' onchange='changeSangtae(<?=$canCelNum?>,this.value)'>
					<option value='1' <?if($get==1){echo "selected";}?>>받음</option>
					<option value='1' <?if($get==2){echo "selected";}?>>미수</option>
			   </select>
			
			<?}?>
			</td>
		</tr>


	<?
		$comName='';
		$get='';
	}?>
</table>

	<?}?>
</div>
</body>

</html>