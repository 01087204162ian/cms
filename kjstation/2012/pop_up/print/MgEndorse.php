<?include '../../../dbcon.php';


//include "../../LiGListPrint/endorse/php/En_hqInformation.php";

include "./endorseSerch.php";
	

	//echo " dd $cRow[company] ";
// echo "_h $_h ";// echo "sql $sql <Br>";
   // 한글폰트/폰트의 디렉토리를 설정  
   define('FPDF_FONTPATH','../../pdf_2/font/');  
    
   // 관련 라이브러리 파일을 호출 
   require('../../../kibs_admin/pdf_2/fpdi.php'); 
   require('../../../kibs_admin/pdf_2/korean.php'); 
    
   // pdf라는 객체를 생성하는데, A4용지, 단위는 mm, 세로(P/L) 
   $pdf = new PDF_Korean('L','mm','A4'); 
    
   // 한글폰트 설정 H2hdrM -> HD  
   $pdf->AddUHCFont('HD', 'H2hdrM');  
    
   // PDF 파일을 오픈합니다.  
   $pdf->Open(); 

//  for($_p=7;$_p<=7;$_p++){
   // 한페이지를 추가 
   $pdf->AddPage(); 
    
   // 기존 pdf파일의 경로를 설정 
   $pdf->setSourceFile('../MGEndorse.pdf'); 
    
   // 한페이지를 가져옴 
   $tplIdx = $pdf->ImportPage(1); 
    
   // 현재의 pdf문서에 기존의 문서를 지정한 위치에 지정한 크기로 호출 
      // 현재의 pdf문서에 기존의 문서를 지정한 위치에 지정한 크기로 호출 
   $pdf->useTemplate($tplIdx,0,0,297); //가로
  // $pdf->useTemplate($tplIdx,0,0,210); //세로일대
    
   // 실제 데이타 설정 
  $data[endorse_day]=$eRow[endorse_day];//배서기준일
   $data[company] = $cRow[company];		//대리운전회사
   $data[certi0] =$mgCerti[0];			//증권번호첫번째
   $data[certi1] =$mgCerti[1];			//증권번호두번번째
   $data[certi2] =$mgCerti[2];			//증권번호세번째

   for($_m=0;$_m<$DNum;$_m++){

	$push=$driverPush[$_m];
		//ho "$push <br>";
	switch($push){
		case 1 :  //해지

			
			$data[na1me.$_m] = $driverName[$_m];
			$data[ju1min.$_m] = $driverJumin[$_m];
			$data[na1i.$_m] = $driverNai[$_m];	
			$data[ce1rt.$_m] = $dcerrti[$_m];	


			break;
		case 2 :
			
			$data[na2me.$_m] = $driverName[$_m];
			$data[ju2min.$_m] = $driverJumin[$_m];
			$data[na2i.$_m] = $driverNai[$_m];	
			$data[ce2rt.$_m] = $dcerrti[$_m];	

			break;


		}
   }
 /*  $data[inputDay]= $row[inputDay];		//가입일
   $data[gigan] = $gigan;				//보험기간
   $data[seiral]= $c[10];				//일련번호
   $data[Name] = $row[Name];		    //계약자
   $data[address]= $a[29].$a[30];		//주소
   $data[hphone] = $row[hphone];        //연락처
   $data[Email]= $row[email];			//e-mail
   $data[pName] = $row[Name];			//피보험자
   $data[preminum]= number_format($row[preminum]); //총보험료
   $data[preminum2] =  number_format($row[preminum]); //납입하실보험료
   $data[company]= $sRow[company];        //업소명
   $data[gubun] = $gubun;				  //구분	
   $data[giDan]= $giDan;			      //산출기초단위
   $data[gi] = $row[gi];				  //산술기초수
   $data[mojemol]= $a[29].$a[30];		  //목적물 소재지
  // $data[babheng]= $row[inputDay];		  //발행일*/


   // 템플릿 파일명  
  $tpl_file = './frm/mg.frm'; 
    
   // 파일 불러오기 
   $list = file($tpl_file); 
    
   // 폼의 총 줄수 구하기 
   $count = count($list); 
    
    for($i=0;$i<$count;$i++){ 
        // 자료분리 
        $tmp = explode("|",$list[$i]); 
         
        // 자료가 없으면 다음자료로 이동 
        if(!$tmp[0]) continue; 
         
        $cur_data = $data[$tmp[0]];  // $data[cno1]; 
        if(!$cur_data) continue;  
         
        //     0   1 2  3       4       5     6 
        // |변수명|x|y|글씨체|글자크기|자간|STRPAD값| 
        // frm에서 지정된 곳에 값을 출력  
         
        // strpad값이 있을경우 일정칸을 확보하고 우측부터 글씨를 기재  
        if($tmp[6]){ 
          $cur_data = str_pad($cur_data,$tmp[6]," ",STR_PAD_LEFT);  
        } 
         
        $pdf->setFont($tmp[3],'',$tmp[4]); 
        $pdf->SetCharSpacing($tmp[5]); 
        $pdf->SetXY($tmp[1],$tmp[2]); 
        $pdf->Write(0,$cur_data);  
         
        // 하단의 사본부분 채우기  
       // $pdf->SetXY($tmp[1],$tmp[2]+138); 
       // $pdf->Write(0,$cur_data);  
          
       
      } 
    
//  }
   $pdf->Output();  
?> 