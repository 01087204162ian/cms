
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

addLoadEvent(dp_certiList);
function fill_up_sjoNum(dNum,cNum,nowtime){

		//alert(dNum+'/'+ cNum +'/' +nowtime);
		screen_height_def();
		$("#daeriCompanyNum").val(dNum);
		$("#certiTableNum").val(cNum);
		 preminum_init(nowtime);
		//alert(nowtime);

	}	
	function preminum_init(nowtime){
		$("#sjo").children().remove();
		//alert(nowtime +'' +preminum_init);
		//보험료 테이블 초기화 

		//alert($("#daeriCompanyNum").val());
		//alert('1')
	//	var cw = document.getElementById('dayPreminum_frame').contentWindow;
		//cw.dayPclear();//dayPreminum.js 변수 전달 한다
		//alert($("#daeriCompanyNum").val()+"/"+$("#certiTableNum").val());
		var send_url = "/2014/_db/daeri_Com_preminum_R.php";		
		//alert(send_url);
		$.ajax({
			type: "POST",
			url:send_url,
			dataType : "xml",
			data:{ proc:"certi_R", 
				     daeriCompanyNum:$("#daeriCompanyNum").val(),
					 certiTableNum:$("#certiTableNum").val(),
					 nowtime:nowtime//배서인경우는 배서기준일 ,이외는 현재일자가
				    }
		}).done(function( xml ) {

			//Pvalues = new Array();
			$(xml).find('values').each(function(){
				$(xml).find('item').each(function() {

				//	store			= $(this).find('store').text();
            	    name32		= $(this).find('name32').text();
					//message	= $(this).find('message').text();
					message ="게시판!!!";
					company	= $(this).find('company').text();
					policyNum = $(this).find('policyNum').text();
					gita			= $(this).find('gita').text();	
					insurane    = $(this).find('insurane').text();
					insNum		= $(this).find('insNum').text();
					startyDay  =$(this).find('startyDay').text();
            	 //  	values.push( {	"ZIPCODE":ZIPCODE, 
                //	   				"address":address
                //	   				} );
	   				
				 });

			
			});
			
			
						
			dp_certiList(nowtime);
			
		});
	}

function dp_certiList(nowtime){
		
	//	screen_height_def(1);
		
		var str="";
		
		$("#sjo").children().remove();
		str += "<tr >";
		   str += "<td>"
		        +"<img src='../images/bt_write.gif' border='0' alt='새글쓰기' onFocus='this.blur()' onClick='gasiWrite2()'>"
			
				+"</td>\n";
		 
		str += "</tr>\n";		   
		$("#sjo").append(str);
		var cw = document.getElementById('dayPreminum_frame').contentWindow;
		cw.DayPreminumSerch($("#daeriCompanyNum").val(),$("#certiTableNum").val(),nowtime,'','2','');//gaesipanList.js 변수 전달 한다

	

	}

	
function gasiWrite(num){
	
	//alert(num)
	$('#id_wrap').css('display','block');
	//alert($("#daeriCompanyNum").val());
	var cw = document.getElementById('id_frame').contentWindow;
	cw.fill_up_sjoNum(num,'','');//popId.js //popNewId.php
}


function reload(){
	
		var cw = document.getElementById('dayPreminum_frame').contentWindow;
		cw.DayPreminumSerch($("#daeriCompanyNum").val(),$("#certiTableNum").val(),nowtime,'','2','');//pop_dayList.js 변수 전달 한다
}

function gasiWrite2(){

	var cw = document.getElementById('dayPreminum_frame').contentWindow;
		cw.gasiWrit();

}




