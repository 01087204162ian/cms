<ul>
<? $sql="SELECT distinct(InsuraneCompany) FROM  2012CertiTable  WHERE  2012DaeriCompanyNum='$cNum' and startyDay>='$yearbefore' ";
//echo "Sql $sql <br>";
   $rs=mysql_query($sql,$connect);
   $Ccount=mysql_num_rows($rs);

	for($_p=0;$_p<$Ccount;$_p++){

		$row=mysql_fetch_array($rs);


		//echo "$row[divi]  <Br>";
		//if($row[divi]==1){$diviName[$_p]='[직접]';}
		//echo " $yearbefore ";
		//if($row[startyDay]>=$yearbefore){

			//echo "div $diviName[$_p] <Br>";
			switch($row[InsuraneCompany]){

				
				case 1 :
					$inSom[$_p]='흥국추가';
					break;
				case 2 :
					$inSom[$_p]='동부추가';
					break;
				case 3 :
					$inSom[$_p]='KB추가';
					break;
				case 4 :
					$inSom[$_p]='현대추가';
					break;
				case 5 :
					$inSom[$_p]='롯데추가';
					break;
				case 6 :
					$inSom[$_p]='더케이추가';
					break;
				case 7:
					$inSom[$_p]='MG화재추가';
					break;
				case 8:
					$inSom[$_p]='삼성추가';
					break;


			}?>

			<li><input type="button" class="ctn-b"   value="<?=$inSom[$_p]?>"  onFocus='this.blur()'  onClick="newUnjeon(<?=$cNum?>,'<?=$row[InsuraneCompany]?>')"></li>
		<?}

	//}
		//$diviName='';
	?>



</ul>