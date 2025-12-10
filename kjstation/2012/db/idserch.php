<?include '../../2012/lib/lib_auth.php';

///
/*/
$sql="SELECT * FROM 2012Costomer  ";
$RS=mysql_query($sql,$connect);


$rNum=mysql_num_rows($RS);

for($j=0;$j<$rNum;$j++){
	$row=mysql_fetch_array($RS);

	echo "$j || $row[mem_id] ||$row[name] || $row[jumin] <br>";
}

*/

for($_k=1;$_k<5;$_k++){


	switch($_k){
		case 1 :
				$dbName="ssang_c";
				$dbDrive="ssang_drive";
				$ssNum="ssang_c_num";
				$produce="ssang_produce";
				$pmember="new_p_member";
			break;
		case 2 :
				$dbName="dongbu2_c";
				$dbDrive="dongbu2_drive";
				$ssNum="ssang_c_num";
				$produce="dongbu2_produce";
				$pmember="new_p_member2";
			break;
		case 3 :
				$dbName="lig_c";
				$dbDrive="lig_drive";
				$ssNum="lig_c_num";

			break;
		case 4 :
				$dbName="hyundai_c";
				$dbDrive="hyundai_drive";
				$ssNum="hyundai_c_num";
				$produce="hyundai_produce";
				$pmember="new_p_Hyundaei";
			break;



	}

		$pMsql="SELECT * FROM $pmember ";
			
				//echo "ppSql  $ppSql<br>";
				$ppRs=mysql_query($pMsql,$connect);
				$ppNum=mysql_num_rows($ppRs);

				for($j=0;$j<$ppNum;$j++){

					$pRow=mysql_fetch_array($ppRs);

					 


					
					$mem_id[$_j]=$pRow[mem_id];
					$npame[$_j]=$pRow[name];


					echo "_k $_k $pRow[num] || $mem_id[$_j] $npame[$_j]<br>";


				}


}
?>