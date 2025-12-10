<html>
<head>
<title>회원 로그인</title>
<link rel="stylesheet" href="./newAdmin/css/ksh.css" type="text/css">
<script language="JavaScript">
<!--
 function login_check()
 {
	
  var form = document.login;
   if(!form.mem_id.value) {
    alert("ID를 입력하세요!");
    form.mem_id.focus();
    return false;
   }
   if(!form.passwd.value) {
    alert("패스워드를 입력하세요!");
    form.passwd.focus();
    return false;
   }
  }
-->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="30" topmargin="30" >
<table width="662" border="0" cellspacing="0" cellpadding="0" height="405" align='center'>
  <tr>
    <td class="lordfont3bold" height="29">
      <img src="./newAdmin/images/logo.gif" width="60" height="32">
    </td>
  </tr>
  <tr> 
    <td><img src="./newAdmin/images/guest/board_top.jpg" width="662" height="8"></td>
  </tr>
  <tr> 
    <td> 
      <table width="100%" border="0" cellpadding="5" cellspacing="1" bordercolordark="white" bordercolorlight="#000000" class="lordfont1">
        <tr> 
          <td>
<?  
    //회원 로그인이 되지 않았을 경우
	if(!$HTTP_COOKIE_VARS[small_id] || !$HTTP_COOKIE_VARS[small_name]){
?>
		<form method='post' name='login' action='./2012/lib/proc_login.php' onsubmit="JavaScript:return(login_check());">
            <table width="90%" border="0" cellspacing="1" cellpadding="0" >
              <tr> 
                <td colspan="2" height="25" align="center">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="2"> 
                  <table width="300" border="0"  cellspacing="1" bgcolor="#cccccc" align="center" >
                    <tr bgcolor="#FFFFFF" > 
                      <td height="35" align="center" colspan='2' bgcolor='#C5BDE4'><b>[ 
                        로그인 ]</b></td>
                    </tr>
					<tr bgcolor="#FFFFFF"> 
                      <td height="40" align="center"> ID&nbsp;&nbsp;</td> 
                      <td>  <input name=mem_id size=15 type=text></td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td height="40" align="center"> 비밀번호&nbsp;&nbsp;</td> 
                      <td>  <input name=passwd size=15 type=password> </td>
                    </tr>
					<tr bgcolor="#FFFFFF"> 
                      <td height="40" align="center" colspan='2'> 
					    <div align="center">
						 
						<input name=login type=image src='./newAdmin/images/member/login_butt.gif' border='0'> &nbsp;&nbsp;
						 <a href="m_register.html"><img src='./newAdmin/images/member/join_butt.gif' border='0'></a> 
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
         </form>
	<? 
	   }
	   //회원 로그인이 되었을 경우
	   else{
	 ?>
	        <table width="90%" border="0" cellspacing="1" cellpadding="0" >
              <tr> 
                <td colspan="2" height="25" align="center">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="2"> 
                  <table width="300" border="0" bgcolor="#FFFFFF" align="center" >
                    <tr bgcolor="#FFFFFF" > 
                      <td height="35" align="center" bgcolor='#C5BDE4'><b>[ 
                        로그인 ]</b></td>
                    </tr>
					<tr bgcolor="#FFFFFF"> 
                      <td height="40"> 
					    로그인되었습니다.<br>
						로그인 ID : <font color='blue'><?=$HTTP_COOKIE_VARS[small_id]?></font><br>
						로그인 성명 : <font color='blue'><?=$HTTP_COOKIE_VARS[small_name]?></font>
                      </td>
                    </tr>
                    <tr bgcolor="#FFFFFF"> 
                      <td height="40"><br>
					   지금 사용하신 로그인 부분은 <br>추후에 있는 게시판, 설문조사, 메일 관리, 가계부 등<br>
					   뒤쪽에서 다루게 될 실전 프로그램에서 <br>모두 사용되게 됩니다.
                      </td>
                    </tr>
					<tr bgcolor="#FFFFFF"> 
                      <td height="40" align="center"> 
					    <div align="center">
						 <a href="m_logout_proc.html"><img src='./newAdmin/images/member/logout_butt.gif' border='0'></a> &nbsp;&nbsp;
						 <a href="m_edit.html?mem_id=<?=$HTTP_COOKIE_VARS[small_id]?>"><img src='./newAdmin/images/member/modify_butt.gif' border='0'></a> 
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
	<? } ?>
	  
		  </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td><img src="./newAdmin/images/guest/board_down.gif" width="662" height="5"></td>
  </tr>
</table>
</body>
</html>