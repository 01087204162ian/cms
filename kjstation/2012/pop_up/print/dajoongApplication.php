<?include '../../../dbcon.php';


//include "../../LiGListPrint/endorse/php/En_hqInformation.php";

include "./php/dSerch.php";
	
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

  for($_p=7;$_p<=7;$_p++){
   // 한페이지를 추가 
   $pdf->AddPage(); 
    
   // 기존 pdf파일의 경로를 설정 
   $pdf->setSourceFile('../'.$_p.'.pdf'); 
    
   // 한페이지를 가져옴 
   $tplIdx = $pdf->ImportPage(1); 
    
   // 현재의 pdf문서에 기존의 문서를 지정한 위치에 지정한 크기로 호출 
   $pdf->useTemplate($tplIdx,0,0,210); 
    
   // 실제 데이타 설정 

if($_p==1){
   $data[selnum] = $row[sulNum];		//설계번호
   $data[inputDay]= $row[inputDay];		//가입일
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
  // $data[babheng]= $row[inputDay];		  //발행일
}
if($_p==2){
   $data[selnum] = $row[sulNum];		//설계번호
   
   $data[Name] = $row[Name];	    //계약자
   $data[bankNum] = $row[bankNum];  //계좌번호
    // $data[babheng]= $row[inputDay];		  //발행일
}
 
if($_p==3){
   
   
   $data[Name] = $row[Name];	    //계약자
   $data[selnum] = $row[sulNum];		//설계번호
   $data[selnum2] = $row[sulNum];		//설계번호
    // $data[babheng]= $row[inputDay];		  //발행일
	$data[Name2] = $row[Name];	    //계약자
	$data[Name3] = $row[Name];	    //피보험자
}  

if($_p==4){
   $data[Name] = $row[Name];	    //계약자
   $data[selnum] = $row[sulNum];		//설계번호
  $data[selnum2] = $row[sulNum];		//설계번호
    // $data[babheng]= $row[inputDay];		  //발행일
	$data[Name2] = $row[Name];	    //계약자
	$data[Name3] = $row[Name];	    //피보험자
}  

if($_p==5){
   $data[Name] = $row[Name];	    //계약자
   $data[selnum] = $row[sulNum];		//설계번호
   $data[selnum2] = $row[sulNum];		//설계번호
    // $data[babheng]= $row[inputDay];		  //발행일
	$data[Name2] = $row[Name];	    //계약자
	$data[Name3] = $row[Name];	    //피보험자
} 
if($_p==6){
   $data[Name] = $row[Name];	    //계약자
   $data[selnum] = $row[sulNum];		//설계번호
   $data[selnum2] = $row[sulNum];		//설계번호
    // $data[babheng]= $row[inputDay];		  //발행일
	$data[Name2] = $row[Name];	    //계약자
	$data[Name3] = $row[Name];	    //피보험자
} 
if($_p==7){
	//$data[selnum] = $row[sulNum];		//설계번호
   //$data[inputDay]= $row[inputDay];		//가입일
  $data[sigi] = $row[sigi];				//보험기간
   $data[Jumin]= $row[Jumin];				//주민번호
   $data[Name] = $row[Name];		    //계약자
   $data[addre]= $a[29];		//주소
    $data[addre2]= $a[30];
   $data[hphone] = $row[hphone];        //연락처
   $data[Email]= $row[email];			//e-mail
  $data[Anum] = $row[sulNum];			//계약번호
  $data[preminum]= number_format($row[preminum]); //총보험료
 //  $data[preminum2] =  number_format($row[preminum]); //납입하실보험료
 //  $data[company]= $sRow[company];        //업소명
   $data[gubun] = $giDan.$gubun;				  //구분	
   $data[bankName]= $row[bankName];
   $data[bankNum]= $row[bankNum];//은행명
   $data[gi] = $row[gi];		//계좌번호
   $data[gi2] =$row[gi2];		//산술기초수
   //$data[mojemol]= $a[29].$a[30];		  //목적물 소재지
   //$data[sido] = $sRow[sido];	    //시도
   $data[seial] =$a[10];		//일련번호
   $data[kind] = $gubun;		//업종
    $data[fax]= $row[fax];		  //팩스
	$data[company] = $sRow[company];	    //상호
	$data[address] = $sRow[address].$sRow[address2];	    //주소
} 
   // 템플릿 파일명  
  $tpl_file = './frm/'.$_p.'.frm'; 
    
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
    
  }
   $pdf->Output();  
?> 