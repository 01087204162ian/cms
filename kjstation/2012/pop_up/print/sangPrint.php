<?include '../../../dbcon.php';


//include "../../LiGListPrint/endorse/php/En_hqInformation.php";


//대리업체 명을 찾기위해  
$cSql="SELECT * FROM 2012DaeriCompany WHERE num='$DaeriCompanyNum'";
$cRs=mysql_query($cSql,$connect);
$cRow=mysql_fetch_array($cRs);
$b_num=$cRow[damdanga]; //계약자
$company=$cRow[company];//대리운전회사

$Dsql="SELECT * FROM 2012DaeriMember WHERE CertiTableNum='$CertiTableNum' and EndorsePnum='$eNum'";
//echo "Dsql $Dsql <br>";
	$Drs=mysql_query($Dsql,$connect);
	$DNum=mysql_num_rows($Drs);
for($_h=0;$_h<$DNum;$_h++){

	$DRow=mysql_fetch_array($Drs);
$DriverName[$_h%16]=$DRow[Name];

$bunho[$_h%16]=$_h+1;
$B_num[$_h%16]=$b_num;
}

// echo "_h $_h ";// echo "sql $sql <Br>";
   // 한글폰트/폰트의 디렉토리를 설정  
   define('FPDF_FONTPATH','../../pdf_2/font/');  
    
   // 관련 라이브러리 파일을 호출 
   require('../../../kibs_admin/pdf_2/fpdi.php'); 
   require('../../../kibs_admin/pdf_2/korean.php'); 
    
   // pdf라는 객체를 생성하는데, A4용지, 단위는 mm, 세로(P/L) 
   $pdf = new PDF_Korean('P','mm','A4'); 
    
   // 한글폰트 설정 H2hdrM -> HD  
   $pdf->AddUHCFont('HD', 'H2hdrM');  
    
   // PDF 파일을 오픈합니다.  
   $pdf->Open(); 

  
   // 한페이지를 추가 
   $pdf->AddPage(); 
    
   // 기존 pdf파일의 경로를 설정 
   $pdf->setSourceFile('../../../kibs_admin/pdf/fpdf153/ss.pdf'); 
    
   // 한페이지를 가져옴 
   $tplIdx = $pdf->ImportPage(1); 
    
   // 현재의 pdf문서에 기존의 문서를 지정한 위치에 지정한 크기로 호출 
   $pdf->useTemplate($tplIdx,0,0,210); 
    
   // 실제 데이타 설정 

 
  //$data[wdate] =$year."년".$month."월".$day."일";

// echo "0 $DriverName[0] 1 $DriverName[1] 2 $DriverName[2] <br>";
   $data[name] = $DriverName[0];
   $data[bun]= $bunho[0];
   $data[name1] = $DriverName[1];
   $data[bun1]= $bunho[1];
   $data[name2] = $DriverName[2];
   $data[bun2]= $bunho[2];
   $data[name3] = $DriverName[3];
   $data[bun3]= $bunho[3];
   $data[name4] = $DriverName[4];
   $data[bun4]= $bunho[4];
   $data[name5] = $DriverName[5];
   $data[bun5]= $bunho[5];
   $data[name6] = $DriverName[6];
   $data[bun6]= $bunho[6];
   $data[name7] = $DriverName[7];
   $data[bun7]= $bunho[7];
   $data[name8] = $DriverName[8];
   $data[bun8]= $bunho[8];
   $data[name9] = $DriverName[9];
   $data[bun9]= $bunho[9];
   $data[name10] = $DriverName[10];
   $data[bun10]= $bunho[10];


   $data[name11] = $DriverName[11];
   $data[bun11]= $bunho[11];
   $data[name12] = $DriverName[12];
   $data[bun12]= $bunho[12];
   $data[name13] = $DriverName[13];
   $data[bun13]= $bunho[13];
   $data[name14] = $DriverName[14];
   $data[bun14]= $bunho[14];
   $data[name15] = $DriverName[15];
   $data[bun15]= $bunho[15];
   $data[name16] = $DriverName[16];
   $data[bun16]= $bunho[16];

   $data[name17] = $DriverName[17];
   $data[bun17]= $bunho[17];
   $data[name18] = $DriverName[18];
   $data[bun18]= $bunho[18];



   $data[bname1]=$B_num[0];//계약자
   $data[comp]=$company;//피보험자
  
  // $data[sigi] =$s_year."년".$s_month."월".$s_day."일";
 //  $data[sigi_2] = $s_year."년".$s_month."월".$s_day."일";
 
    
    
   // 템플릿 파일명  
   $tpl_file = "../../../kibs_admin/pdf/fpdf153/explain.frm"; 
    
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
    
   ///2page///////////////////////////////////////////////////////////////////////////////////
/*
   // 한페이지를 추가 
   $pdf->AddPage(); 
    
   // 기존 pdf파일의 경로를 설정 
   $pdf->setSourceFile('./t2.pdf'); 
    
   // 한페이지를 가져옴 
   $tplIdx = $pdf->ImportPage(1); 
    
   // 현재의 pdf문서에 기존의 문서를 지정한 위치에 지정한 크기로 호출 
   $pdf->useTemplate($tplIdx,0,0,210); 
    
   // 실제 데이타 설정 

 
  // $data[sigi] =$s_year."년".$s_month."월".$s_day."일";
 //  $data[sigi_2] = $s_year."년".$s_month."월".$s_day."일";
 
    
    
   // 템플릿 파일명  
   $tpl_file = "explain2.frm"; 
    
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


	///3page///////////////////////////////////////////////////////////////////////////////////

	// 한페이지를 추가 
   $pdf->AddPage(); 
    
   // 기존 pdf파일의 경로를 설정 
   $pdf->setSourceFile('./t3.pdf'); 
    
   // 한페이지를 가져옴 
   $tplIdx = $pdf->ImportPage(1); 
    
   // 현재의 pdf문서에 기존의 문서를 지정한 위치에 지정한 크기로 호출 
   $pdf->useTemplate($tplIdx,0,0,210); 
    
   // 실제 데이타 설정 

 
 
    
    
   // 템플릿 파일명  
   $tpl_file = "explain3.frm"; 
    
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
*/
   $pdf->Output();  
?> 