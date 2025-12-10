<?include '../../2012/lib/lib_auth.php';

////////////////////////////////////////////////////

//ssang_c table 값을 읽어 2012DaeriCompany 저장  ///
//그 증권번호를 2012CertiTable 저장후 
// 그 증권에 해당하는 대리기사를 찾아서 (ssang_drive)
// 2012DaeriMember 에 저장한다                 ///
///////////////////////////////////////////////////

//echo "대리운전회사 ssang_c,dongbu2_c  ,hyundai_c  , ,lig_c  table 값을 2012DaeriCompany 값을 넣기 위해  <Br>";


for($_k=1;$_k<5;$_k++){
	echo "_k $_k <br>";

	//if($_k!=3){continue;};
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
		$sSql="SELECT * FROM $dbName ";

		//echo "sSql $_k $sSql <br>";
		$rs=mysql_query($sSql,$connect);

		$sNum=mysql_num_rows($rs);

		//echo "sNum $sNum <br>";

		include "./comCotent.php";
		//echo "count $count ";
}
?>