<?php 
include '../dbcon.php';

		$proc 		= $_POST["proc"];

	    $num_ 		= iconv("utf-8","euc-kr",$_POST["num_"]);	// 
		
		
		//$proc="sago";


if (!function_exists('json_decode')) {

    function json_decode($content, $assoc=false) {

        require_once 'libs/JSON.php';

        if ($assoc) {

            $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);

        }

        else {

            $json = new Services_JSON;

        }

        return $json->decode($content);

    }

}



if (!function_exists('json_encode')) {

    function json_encode($content) {

        require_once 'libs/JSON.php';

        $json = new Services_JSON;

        return $json->encode($content);

    }

}

		$proc		= $_POST["proc"];

		$daeriCompanyNum 	= $_POST["dNum"];
		$etage = $_POST["etage"];
		$date = $_POST["date"];
		$fileName= $_POST["fileName"];
		

		

		if($proc == "gias_new_W") {
				
					
					//대리운전회사의 대리,탁송별 인원과 당사 인원 비교
			


			// 대리운전회사의 특정일을 찾기위해 
			

			
			require_once '../2012/pop_up/_pages/php/Excel/reader.php';
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('utf-8');		
			$fileName= '../2012/pop_up/_pages/php/uploadFiles/'.$fileName;
			$data->read($fileName);


			$m=0;
			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				$m++;
					
						  //나이 계산 ,증권번호, 중복,주민오류 검사

						$pName1=trim($data->sheets[0]['cells'][$i][1]);
						$pName=iconv("utf-8","euc-kr",$pName1);
						$jumin=trim($data->sheets[0]['cells'][$i][2]);
						
						

						
						$juminLength=strlen($jumin);
						//include "../../../2012/pop_up/_db/php/kdriveCompare.php";

						//$sql="SELECT * FROM  2012DaeriMember WHERE Jumin='$jumin' AND 2012DaeriCompanyNum='$daeriCompanyNum' AND etag='$etage' AND push='4'";

						$insert="INSERT INTO kdriveMember (Name,Jumin,etag,2012DaeriCompanyNum,endorse_day) ";
						$insert.="values ('$pName','$jumin','$etage','$daeriCompanyNum',now())";
						//echo $insert;
						$rs=mysql_query($insert,$connect);
						//$count=mysql_num_rows($rs);

						//$row=mysql_fetch_array($rs);

							//echo $row[Jumin];
						if($count==0){
							$m__=1;  //계약없음
						}elseif($count==1){
						  $m__=2;
						}else{
						  $m__=3; // 중복 게약
						}


					$data = array("bunho"=>$m,"bunho2"=>$m__,"name"=>iconv("euc-kr","utf-8",$pName),'jumin' =>$jumin, 'daeriCompanyNum'=>$daeriCompanyNum,'etage'=>etage);
						//$local=htmlspecialchars($local,ENT_NOQUOTES,"UTF-8");
						//$jisa=htmlspecialchars($jisa,ENT_NOQUOTES,"UTF-8");

						

			}
						   
							
			
			
			//@mysql_close($DB->Connect_ID);

		}
		echo json_encode($data);




//print_r($data);
//print_r($data->formatRecords);
?>
