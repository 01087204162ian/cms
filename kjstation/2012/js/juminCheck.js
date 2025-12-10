function checkSSN(ssn) {

		 var sum = 0; 

		 var month = ssn.substr(2,2);

		 var day = ssn.substr(4,2);

		  

		 if(ssn.length != 13) {

		 	return false;

		 }

		  

		 //월의 경우 13월을 넘지 않아야 한다.

		 if(month < 13 && month != 0 && day != 0) {

		 	//2월의 경우

		 	if(month == 2) {

		   		//29일을 넘지 않아야 한다.

		   		if(day > 29) return false;

		   		

		 	 } else if(month == 4 || month == 6 || month == 9 || month == 11){

		  		 // 4,6,9,11월의 경우 30일을 넘지 않아야 한다.   

			   	if(day > 30) return false;

		  	 } else {

			  	 // 그외 월이 31일을 넘지 않아야 한다.

		   		if(day > 31) return false;

		  	 }

		 	

		}else {

		  	 return false;

		 }

		  

		 for(var i = 0; i < 12; i++) {

		 	sum += Number(ssn.substr(i, 1)) * ((i % 8) + 2);

		 }

		  

		 if(ssn.substr(6,1) == 1 || ssn.substr(6,1) == 2 || ssn.substr(6,1) == 3 || ssn.substr(6,1) == 4 || ssn.substr(6,1) == 9 || ssn.substr(6,1) == 0) {

			 //내국인 주민번호 검증(1900(남/여) 2000(남/여))

		 	if(((11 - (sum % 11)) % 10) == Number(ssn.substr(12,1))) {

		   		return true;

		  	 }

		  	 return false;

		 }else if(ssn.substr(6,1) == 5 || ssn.substr(6,1) == 6 || ssn.substr(6,1) == 7 || ssn.substr(6,1) == 8) {

			 //외국인 주민번호 검증(1900(남/여) 2000(남/여))

		  	 if(Number(ssn.substr(8,1)) % 2 != 0) {

		   		return false;

		  	 }		  

		  	 if((((11 - (sum % 11)) % 10 + 2) % 10) == Number(ssn.substr(12, 1))){

			   return true;

			 }

		  	 return false;

		 }  

		  

	  return true;  //정상 주민번호


	 } 

function f_chkNoNum(string) {
		valid = "0123456789";
		for (var i=0; i< string.length; i++) {
			if (valid.indexOf(string.charAt(i)) != -1) {
				return false;
			return false;
			}
		}
		return true;

	} 
function stringcheck(thisstring)
{
		var total = thisstring.length
		for(i=0;i<total;i++)
		{

			if(44032 > thisstring.charCodeAt(i) || 55203 < thisstring.charCodeAt(i))
			{
				return false;
			}
		}
		return true;
}