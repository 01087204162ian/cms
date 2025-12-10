function createAjax() {
	  if(typeof(ActiveXObject) == "function") {
	    return new ActiveXObject("Microsoft.XMLHTTP");  
	  }  
	  else if(typeof(XMLHttpRequest) == "object" || typeof(XMLHttpRequest) == "function") {
	    return new XMLHttpRequest();
	  }  
	  else {
	    self.alert("브라우저가 AJAX를 지원하지 않습니다.");
	    return null;
	  }
	} 
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
	//	var tables=document.getElementById("serchTable").value;
	
	for(var i=0;i<tables.length;i++){
		
		var odd=false;
		var rows=tables[i].getElementsByTagName("tr");
		//alert(rows.length);
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
				rows[j].style.backgroundColor="#f9f8f0";
				//rows[j].style.backgroundColor="#ffffff";
				odd=true;

			}
		}
	}
}
addLoadEvent(stripeTables);


