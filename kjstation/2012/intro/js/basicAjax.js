function displayLoading(element) { 
    while (element.hasChildNodes()) {
        element.removeChild(element.lastChild);
    }
    var image = document.createElement("img");
    image.setAttribute("src","/kibs_admin/ssangyoung/loading.gif");
    image.setAttribute("alt","Loading...");
    element.appendChild(image);
}

function addLoadEvent(func){
	var oldonload=window.onload;
	if(typeof window.onload !='function'){
		window.onload=func;
	}else{
		window.onload=function(){
			oldonload();
			func();
		}
		
	}
}

function stripeTables(){
	if(!document.getElementsByTagName) return false;
	var tables=document.getElementsByTagName("table");
	for(var i=0;i<tables.length;i++){
		
		var odd=false;
		var rows=tables[i].getElementsByTagName("tr");
		for(var j=1;j<rows.length;j++){
			var columns=rows[j].getElementsByTagName("td");
			
		/*	for(var k=0;k<columns.length;k++){
				columns[0].onclick=function(){
					columns[0].style.cursor='hand';
				}
			}*/
			if(odd==true){
				rows[j].style.backgroundColor="#f9f8f0";
				odd=false;

			}else{
				rows[j].style.backgroundColor="#ffffff";
				odd=true;

			}
		}
	}
}
addLoadEvent(stripeTables);

function comSearch(val){

	var ssC='ssang_c'+val;
	var ssang_c_num=document.getElementById(ssC).value;
	var winl = (screen.width - 1024) / 2
	var wint = (screen.height - 768) / 2
	window.open('/kibs_admin/consulting/new_ssang.php?num='+ssang_c_num,'ssang','left='+winl+',top='+wint+',resizable=yes,width=950,height=650,scrollbars=yes,status=yes')
}

function endSearch(val){
	var ssC='ssang_c'+val;
	var pun='pun'+val;
	var ssang_c_num=document.getElementById(ssC).value;
	var pum=document.getElementById(pun).innerHTML;//?????
	//alert(document.getElementById(pun).value);
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('/kibs_admin/newLiGdaeri_2/hyun_jae_driver_endorse_detail.php?num='+u_id+'&endorse_num='+u_id2,'Union_ca_2','left='+winl+',top='+wint+',resizable=yes,width=700,height=570,scrollbars=yes,status=yes')

		window.open('/newAdmin/ssangyoung/pop_up/driver_endorse_detail.php?num='+ssang_c_num+'&endorse_num='+pum+'&newgijun='+1,'Union_ca_2','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=yes,status=yes');
}
function comDetail(val){//계약현황 찾기
	var ssC='ssang_c'+val;
	var ssang_c_num=document.getElementById(ssC).value;

	if(ssang_c_num){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open("/newAdmin/ssangyoung/ssang_c_hyun_jae_driver_list.php?item=202-1"+"&search="+3+"&ssang_c_num="+ssang_c_num,'new_3','left='+winl+',top='+wint+',resizable=yes,width=990,height=600,scrollbars=yes,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}

function ssang_preminum(val){//일일보험료 산출
	var ssC='ssang_c'+val;
	var ssang_c_num=document.getElementById(ssC).value;
	if(ssang_c_num){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/kibs_admin/coProduce/pop_up/preminumSearch.php?ssang_c_num='+ssang_c_num,'new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
	}else{
		alert('조회 후 !!');
		return false;
	}
}
function pop(){
   while (document.getElementById("sql3").hasChildNodes()) {
        document.getElementById("sql3").removeChild(document.getElementById("sql3").lastChild);
    }
   var daum=1;
   serch(daum);
	
}
function pop2(){
   while (document.getElementById("sql3").hasChildNodes()) {
        document.getElementById("sql3").removeChild(document.getElementById("sql3").lastChild);
    }
   var daum=2;
   serch(daum);
	
}
//동부화재 계약 조회 idex1.html에서 사용
function popup_drive(){
	var nn=this.getAttribute("id");
	//alert(nn)
	if(nn.length==8){
		ab=nn.substr(7,8);
	}else{
		ab=nn.substr(7,9);
	}
	//alert(ab)
	var u_id=document.getElementById("driver_num"+ab).value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('./pop_up/drive.html?num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
		window.open('/newAdmin/dongbu2/pop_up/driverSerch.php?driver_num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
}

function popupDrive2(){
	var nn=this.getAttribute("id");
	//alert(nn)
	if(nn.length==6){
		ab=nn.substr(5,6);
	}else{
		ab=nn.substr(5,7);
	}
	//alert(ab)
	var u_id=document.getElementById("driver_num"+ab).value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('./pop_up/drive.html?num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
		window.open('/newAdmin/dongbu2/pop_up/pdriverSerch.php?driver_num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
}
	
function popup_drive_2(){//기사조회에어 사용
	var nn=this.getAttribute("id");
	
	if(nn.length==7){
		ab=nn.substr(6,7);
	}else{
		ab=nn.substr(6,8);
	}
	
	var u_id=document.getElementById("driver_num"+ab).value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		//window.open('./pop_up/drive.html?num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
		window.open('./pop_up/drive2.html?num='+u_id,'dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=550,scrollbars=yes,status=yes')
	
}
function certiIssue2(u_id){
	var nn=this.getAttribute("id");
	if(nn.length==4){
		ab=nn.substr(3,4);
	}else{
		ab=nn.substr(3,5);
	}
	var u_id=document.getElementById("driver_num"+ab).value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/kibs_admin/pdf/fpdf153/drive_retire3.php?num='+u_id,'RetireCerti','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=no,status=no')
}
function retire_record(){
	var nn=this.getAttribute("id");
	if(nn.length==4){
		ab=nn.substr(3,4);
	}else{
		ab=nn.substr(3,5);
	}
	var u_id=document.getElementById("driver_num"+ab).value;

		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/kibs_admin/pdf/fpdf153/drive_retire.php?num='+u_id,'Union_ca_2','left='+winl+',top='+wint+',resizable=yes,width=900,height=570,scrollbars=no,status=no')
}
function popGeneral(){
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('./pop_up/drive.html?new=1','dongbu_Union','left='+winl+',top='+wint+',resizable=yes,width=960,height=540,scrollbars=no,status=yes')
}

function dSerch(){
	var nn=this.getAttribute("id");
	if(nn.length==5){
		ab=nn.substr(4,5);
	}else{
		ab=nn.substr(4,6);
	}

	var u_id=document.getElementById("driver_num"+ab).value;
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/newAdmin/dongbu2/pop_up/driver_num.php?driver_num='+u_id,'Union','left='+winl+',top='+wint+',resizable=yes,width=950,height=550,scrollbars=yes,status=yes')
	
}
function admin_open4(){
		var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/kibs_admin/consulting/drive.php?new=1','new_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
}