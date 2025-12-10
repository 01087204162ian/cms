
<? $sql="SELECT distinct(InsuraneCompany),num FROM  2012CertiTable  WHERE  2012DaeriCompanyNum='$id' and startyDay>='$yearbefore' ";
//echo "Sql $sql <br>";
   $rs=mysql_query($sql,$connect);
   $Ccount=mysql_num_rows($rs);
?>