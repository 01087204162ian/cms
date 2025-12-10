<?php 
header('Access-Control-Allow-Origin: *');
		//session_start();
		//include '_DB_Class.php';
		include '../../../dbcon.php';
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
		echo "<values>\n";

		$proc		= $_POST["proc"];
		$c_name = $_POST["c_name"];
		$sigi = $_POST["sigi"];
		$juimin1   = $_POST["jumin"];
		$hphone  = $_POST["hphone"];
		$a5= $_POST["a5"];   // 용도  1가정용,2배달용 3.퀵서비스용 4.렌트용
		$a6= $_POST["a6"];    //배기량  1소형A 50cc 이하 2,소형B 50cc~125cc이하 3중형125cc~250cc이하 4 대형 250cc 초과
		$a7= $_POST["a7"];    //가입경력 1 .1년미만 2.1년이상 3.2년이상 4.3년이상
		$a8= $_POST["a8"];   //
		$a9= $_POST["a9"];   ////할인할증 1. 11z(신규) 2.12Z(1년이상 무사고 갱신) 3.13Z(2년이상 무사고 갱신)
		//4.14Z(3년이상 무사고 갱신) // 5.15Z(4년이상 무사고 갱신) 6.16Z(1년이상 무사고 갱신)


		
		$table = "2014DaeriCompany";
		
		//$DB = new DB_Class();
		if($proc == "daeri_C") {
		
	
				//$sql="SELECT * FROM 2014DaeriCompany  WHERE num='$daeriNum'";
			//	$rs=mysql_query($sql,$connect);
			//	$row=mysql_fetch_array($rs);
				echo("\t<item>\n");
				    echo("\t\t<c_name>".$c_name."</c_name>\n");
					echo("\t\t<sigi>".$sigi."</sigi>\n");
					echo("\t\t<jumin1>".$jumin1."</jumin1>\n");
					echo("\t\t<hphone>".$hphone."</hphone>\n");
					echo("\t\t<a5>".$a5."</a5>\n");
					echo("\t\t<a6>".$a6."</a6>\n");
					echo("\t\t<a7>".$a7."</a7>\n");
					echo("\t\t<a8>".$a8."</a8>\n");
					echo("\t\t<a9>".$a9."</a9>\n");
				echo("\t</item>\n");

		}
		echo "</values>";
?>