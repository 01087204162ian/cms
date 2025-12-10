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


function popGeneral(){
	var winl = (screen.width - 1024) / 2
		var wint = (screen.height - 768) / 2
		window.open('/kibs_admin/consulting/list_2.php?new=1','chubb_Union','left='+winl+',top='+wint+',resizable=yes,width=930,height=540,scrollbars=no,status=yes')
}