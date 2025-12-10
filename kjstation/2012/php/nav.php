<ul>
     <li><a href="/2012/home/" id="introLink">Home</a></li>

	<li><a href="/2012/intro/" id="ssangyongLink">대리운전</a></li>
	<li><a href="/2012/dongbu/" id="dongbuLink">Dongbu</a></li>
	<li><a href="/2012/qboard/" id="qboardLink">킥보드</a></li>
<!--	<li><a href="/2012/dongbu2/" id="dongbuLink2">dongbu2</a></li>-->
	<li><a href="/2012/chubb/" id="chubbLink">Chubb</a></li>
	<li><a href="/2012/koima/" id="koimaLink">koima</a></li>
	<li><a href="/2012/dajoong/" id="dajoongLink">화재배상</a></li>
	<li><a href="/2012/hiautocare/" id="hiautoLink">hiautoCare</a></li>
   <li><a href="/2012/valet/" id="valerLink">Valet</a></li>
    <li><a href="/2012/rentCar/" id="rentcarLink">rentCar</a></li>
</ul>
<input type='hidden' name='Pcompany' id='Pcompany' value='<?=$Pcompany?>'>

<? $dsql="SELECT * FROM 2012Member order by num asc";
	//echo "dsql $dsql <br>";
   $drs=mysql_query($dsql,$connect);
   $dNum=mysql_num_rows($drs);
   ?>
