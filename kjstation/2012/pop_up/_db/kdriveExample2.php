<?header("Content-Type:application/json");
include "../../../dbcon.php";
if($_FILES['upload_file']['size'] > 0) {

		$file_tmp_name = $_FILES['upload_file']['tmp_name'];
		$save_filename = $_SERVER['DOCUMENT_ROOT'] . "/uploads3/" . $_FILES['upload_file']['name'];

		$file_upload	= move_uploaded_file($file_tmp_name, $save_filename);


		$fileName=$_FILES['upload_file']['name'];
}





 
$filename = '../../../uploads3/'.$fileName;

require_once "../../../PHPExcel/Classes/PHPExcel.php";
$objPHPExcel = new PHPExcel();

require_once "../../../PHPExcel/Classes/PHPExcel/IOFactory.php";

 




// PHPExcel은 메모리를 사용하므로 메모리 최대치를 늘려준다.

// 이부분은 엑셀파일이 클때는 적절히 더욱 늘려줘야 제대로 읽어올수 있다.
ini_set('memory_limit', '1024M');

 $objReader = PHPExcel_IOFactory::createReaderForFile($filename);

    // 읽기전용으로 설정

 $objReader->setReadDataOnly(true);
 
 $objExcel = $objReader->load($filename);
    // 첫번째 시트를 선택

$objExcel->setActiveSheetIndex(0);


 

    // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.

    

 

    // 엑셀파일을 읽는다


 

    $objWorksheet = $objExcel->getActiveSheet();

    $rowIterator = $objWorksheet->getRowIterator();

 

    foreach ($rowIterator as $row) {

               $cellIterator = $row->getCellIterator();

               $cellIterator->setIterateOnlyExistingCells(false);

    }

 

    $maxRow = $objWorksheet->getHighestRow();

 

     echo $maxRow . "<br>";

 
/*
    for ($i = 0 ; $i <= $maxRow ; $i++) {


               $a = $objWorksheet->getCell('A' . $i)->getValue(); // A열

               $b = $objWorksheet->getCell('B' . $i)->getValue(); // B열 
 
               

 

         echo $a . " / " . $b. " / " . " <br>\n";

    
           /*  $b  = addslashes($b);
             $c  = addslashes($c);
             $d  = addslashes($d);
             $e  = addslashes($e);
             $f  = addslashes($f);
             $g  = addslashes($g);
*/
 

          //$query = "insert into 삽입할 테이블 (continent,country_code,country,city_code,city,use_yn) values ('$b','$c','$d','$e','$f','$g')";
         // mysql_query($query) or die("Insert Error !");


     /* }*/
 
   //echo $maxRow-1 . " Data inserting finished !";

 


 

//echo json_encode($sheetData);


?>