<?
echo "<gaesu>".$gaesu."</gaesu>\n";
	for($k=0;$k<10;$k++){
	
	echo "<Seg".$k.">".$Seg[$k]."</Seg".$k.">\n";//
	echo "<get".$k.">".$get[$k]."</get".$k.">\n";//
	echo "<bunbho".$k.">".$buho[$k]."</bunbho".$k.">\n";//
	echo "<dates".$k.">".$ysear[$k]."년".$month[$k]."월".$day[$k]."일".$_time[$k]."시".$_minute[$k]."분"."</dates".$k.">\n";//
	echo "<Msg".$k.">".$Msg[$k]."</Msg".$k.">\n";//
	echo "<comName".$k.">".$comName[$k]."</comName".$k.">\n";//
	}
?>