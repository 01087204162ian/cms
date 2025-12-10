<?
$old_name = " 20세이하 남자 ";
	switch($daein_3){  //대인초과 무한 추가 부분 2006.09.23일
		case 1: 

			$daein=daein1.$dong_bu;
			$daein_name = "책임보험초과 무한"; 
			break;

		case 2 :
			$daein=daein2.$dong_bu;
			$daein_name = "책임보험초과 무한"; 
			break;
	}
	
	$cha_3=$jagibudam_3.$char_3;

	//echo "cha_3 $cha_3 <bR>";
		switch($daemool_3)
		{
			case 1 :
				
				$daemool_name = "2천만원";
				break;
			
			case 2 :
				
				$daemool_name = "3천만원";
				break;
			
			case 3 :
				
				$daemool_name = "5천만원";
				break;
			
			case 4 :
				
				$daemool_name = "1억원";
				break;
		}
		
		switch($jason_3)
		{
		
			case 1:
			
				
				$jason_name ="1천5백만원";
				$jason_brand =" 자기신체손해";
				break;
			
			case 2:
				
				$jason_name ="3천만원";
				$jason_brand =" 자기신체손해";
				break;
			
			case 3:
				
				$jason_name ="5천만원 ";
				$jason_brand =" 자기신체손해";
				break;
			
			case 4:
				
				$jason_name ="1억원";
				$jason_brand =" 자기신체손해";
				break;
			
			case 5:
				
				$jason_name ="1억원 |  1천만원 | 1억원";
				$jason_brand =" 자동차손해";
			break;
			
			case 6:
				
				$jason_name ="1억원 | 2천만원 | 1억원";
				$jason_brand =" 자동차손해";
				break;
			
			case 7:
				
				$jason_name ="2억원 | 2천만원 | 2억원";
				$jason_brand =" 자동차손해";
				break;
			
			case 8:
				
				$jason_name ="가입안함 | 가입안함 | 가입안함";
				$jason_brand =" 자기신체손해";
				break;
			
		}

		
		switch($cha_3)
		{
			case a1:
				
				$jagibudam_name ="5만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차 손해";
				break;
			
			
			case a2:
				
				
				$jagibudam_name ="5만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case a3:
				
				$jagibudam_name ="5만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case a4:
				
				$jagibudam_name ="5만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case a5:
				$charyang=charyanga5.$dong_bu;
				$jagibudam_name ="5만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case a6:
			
				$charyang=charyanga6.$dong_bu;
				$jagibudam_name ="5만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차 손해"; 
				break;
#######################################################################################################
			case b1:
				$charyang=charyangb1.$dong_bu;
				$jagibudam_name ="10만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차 손해";
				break;
			
			
			case b2:
				
				$charyang=charyangb2.$dong_bu;
				$jagibudam_name ="10만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case b3:
				$charyang=charyangb3.$dong_bu;
				$jagibudam_name ="10만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case b4:
				$charyang=charyangb4.$dong_bu;
				$jagibudam_name ="10만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case b5:
				$charyang=charyangb5.$dong_bu;
				$jagibudam_name ="10만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case b6:
			
				$charyang=charyangb6.$dong_bu;
				$jagibudam_name ="10만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차 손해"; 
				break;
#######################################################################################################
			case c1:
				$charyang=charyangc1.$dong_bu;
				$jagibudam_name ="20만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차 손해";
				break;
			
			
			case c2:
				
				$charyang=charyangc2.$dong_bu;
				$jagibudam_name ="20만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case c3:
				$charyang=charyangc3.$dong_bu;
				$jagibudam_name ="20만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case c4:
				$charyang=charyangc4.$dong_bu;
				$jagibudam_name ="20만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case c5:
				$charyang=charyangc5.$dong_bu;
				$jagibudam_name ="20만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case c6:
			
				$charyang=charyangc6.$dong_bu;
				$jagibudam_name ="20만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차 손해"; 
				break;
	#####################################################################################################	
			case d1:
				$charyang=charyangd1.$dong_bu;
				$jagibudam_name ="30만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차 손해";
				break;
			
			
			case d2:
				
				$charyang=charyangd2.$dong_bu;
				$jagibudam_name ="30만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case d3:
				$charyang=charyangd3.$dong_bu;
				$jagibudam_name ="30만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case d4:
				$charyang=charyangd4.$dong_bu;
				$jagibudam_name ="30만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case d5:
				$charyang=charyangd5.$dong_bu;
				$jagibudam_name ="30만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case d6:
			
				$charyang=charyangd6.$dong_bu;
				$jagibudam_name ="30만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
#######################################################################################################
			case e1:
				$charyang=charyange1.$dong_bu;
				$jagibudam_name ="50만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차 손해";
				break;
			
			
			case e2:
				
				$charyang=charyange2.$dong_bu;
				$jagibudam_name ="50만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case e3:
				$charyang=charyange3.$dong_bu;
				$jagibudam_name ="50만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case e4:
				$charyang=charyange4.$dong_bu;
				$jagibudam_name ="50만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
				
			case e5:
				$charyang=charyange5.$dong_bu;
				$jagibudam_name ="50만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차 손해"; 
				break;
			
			case e6:
			
				$charyang=charyange6.$dong_bu;
				$jagibudam_name ="50만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차 손해"; 
				break;
#######################################################################################################

			case f1:
				$charyang=charyangf1.$dong_bu;
				$jagibudam_name ="5만원|5만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case f2:
				
				$charyang=charyangf2.$dong_bu;
				$jagibudam_name ="5만원|5만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case f3:
				$charyang=charyangf3.$dong_bu;
				$jagibudam_name ="5만원|5만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case f4:
				
				$charyang=charyangf4.$dong_bu;
				$jagibudam_name ="5만원|5만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
			    break;
			case f5:
				
				$charyang=charyangf5.$dong_bu;
				$jagibudam_name ="5만원|5만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case f6:
			
				$charyang=charyangf6.$dong_bu;
				$jagibudam_name ="5만원|5만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case g1:
				$charyang=charyangg1.$dong_bu;
				$jagibudam_name ="5만원|10만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case g2:
				
				$charyang=charyangg2.$dong_bu;
				$jagibudam_name ="5만원|10만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case g3:
				$charyang=charyangg3.$dong_bu;
				$jagibudam_name ="5만원|10만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case g4:
				$charyang=charyangb4.$dong_bu;
				$jagibudam_name ="5만원|10만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case g5:
				$charyang=charyangg5.$dong_bu;
				$jagibudam_name ="5만원|10만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case g6:
			
				$charyang=charyangg6.$dong_bu;
				$jagibudam_name ="5만원|10만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case h1:
				$charyang=charyangh1.$dong_bu;
				$jagibudam_name ="5만원|20만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case h2:
				
				$charyang=charyangh2.$dong_bu;
				$jagibudam_name ="5만원|20만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case h3:
				$charyang=charyangh3.$dong_bu;
				$jagibudam_name ="5만원|20만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case h4:
				$charyang=charyangh4.$dong_bu;
				$jagibudam_name ="5만원|20만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case h5:
				$charyang=charyangh5.$dong_bu;
				$jagibudam_name ="5만원|20만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case h6:
			
				$charyang=charyangh6.$dong_bu;
				$jagibudam_name ="5만원|20만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
	#####################################################################################################
			
			case i1:
				$charyang=charyangi1.$dong_bu;
				$jagibudam_name ="5만원|30만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case i2:
				
				$charyang=charyangi2.$dong_bu;
				$jagibudam_name ="5만원|30만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case i3:
				$charyang=charyangi3.$dong_bu;
				$jagibudam_name ="5만원|30만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case i4:
				$charyang=charyangi4.$dong_bu;
				$jagibudam_name ="5만원|30만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case i5:
				$charyang=charyangi5.$dong_bu;
				$jagibudam_name ="5만원|30만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case i6:
			
				$charyang=charyangi6.$dong_bu;
				$jagibudam_name ="5만원|30만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
#######################################################################################################
			case j1:
				$charyang=charyange1.$dong_bu;
				$jagibudam_name ="5만원|50만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case j2:
				
				$charyang=charyangj2.$dong_bu;
				$jagibudam_name ="5만원|50만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case j3:
				$charyang=charyangj3.$dong_bu;
				$jagibudam_name ="5만원|50만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case j4:
				$charyang=charyangj4.$dong_bu;
				$jagibudam_name ="5만원|50만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case j5:
				$charyang=charyangj5.$dong_bu;
				$jagibudam_name ="5만원|50만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case j6:
			
				$charyang=charyangj6.$dong_bu;
				$jagibudam_name ="5만원|50만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################

			case k1:
				$charyang=charyangk1.$dong_bu;
				$jagibudam_name ="10만원|5만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case k2:
				
				$charyang=charyangk2.$dong_bu;
				$jagibudam_name ="10만원|5만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case k3:
				$charyang=charyangk3.$dong_bu;
				$jagibudam_name ="10만원|5만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case k4:
				$charyang=charyangk4.$dong_bu;
				$jagibudam_name ="10만원|5만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
			
			case k5:
				$charyang=charyangk5.$dong_bu;
				$jagibudam_name ="10만원|5만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case k6:
			
				$charyang=charyangk6.$dong_bu;
				$jagibudam_name ="10만원|5만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case l1:
				$charyang=charyangl1.$dong_bu;
				$jagibudam_name ="10만원|10만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case l2:
				
				$charyang=charyangl2.$dong_bu;
				$jagibudam_name ="10만원|10만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case l3:
				$charyang=charyangl3.$dong_bu;
				$jagibudam_name ="10만원|10만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case l4:
				$charyang=charyangl4.$dong_bu;
				$jagibudam_name ="10만원|10만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case l5:
				$charyang=charyangl5.$dong_bu;
				$jagibudam_name ="10만원|10만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case l6:
			
				$charyang=charyangl6.$dong_bu;
				$jagibudam_name ="10만원|10만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case m1:
				$charyang=charyangm1.$dong_bu;
				$jagibudam_name ="10만원|20만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case m2:
				
				$charyang=charyangm2.$dong_bu;
				$jagibudam_name ="10만원|20만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case m3:
				$charyang=charyangm3.$dong_bu;
				$jagibudam_name ="10만원|20만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case m4:
				$charyang=charyangm4.$dong_bu;
				$jagibudam_name ="10만원|20만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case m5:
				$charyang=charyangm5.$dong_bu;
				$jagibudam_name ="10만원|20만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case m6:
			
				$charyang=charyangm6.$dong_bu;
				$jagibudam_name ="10만원|20만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
	#####################################################################################################	
			
			case n1:
				$charyang=charyangn1.$dong_bu;
				$jagibudam_name ="10만원|30만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case n2:
				
				$charyang=charyangn2.$dong_bu;
				$jagibudam_name ="10만원|30만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case n3:
				$charyang=charyangn3.$dong_bu;
				$jagibudam_name ="10만원|30만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case n4:
				$charyang=charyangn4.$dong_bu;
				$jagibudam_name ="10만원|30만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case n5:
				$charyang=charyangn5.$dong_bu;
				$jagibudam_name ="10만원|30만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case n6:
			
				$charyang=charyangn6.$dong_bu;
				$jagibudam_name ="10만원|30만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
#######################################################################################################
			case o1:
				$charyang=charyango1.$dong_bu;
				$jagibudam_name ="10만원|50만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case o2:
				
				$charyang=charyango2.$dong_bu;
				$jagibudam_name ="10만원|50만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case o3:
				$charyang=charyango3.$dong_bu;
				$jagibudam_name ="10만원|50만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case o4:
				$charyang=charyango4.$dong_bu;
				$jagibudam_name ="10만원|50만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case o5:
				$charyang=charyango5.$dong_bu;
				$jagibudam_name ="10만원|50만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case o6:
			
				$charyang=charyango6.$dong_bu;
				$jagibudam_name ="10만원|50만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################

			case p1:
				$charyang=charyangp1.$dong_bu;
				$jagibudam_name ="20만원|5만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case p2:
				
				$charyang=charyangp2.$dong_bu;
				$jagibudam_name ="20만원|5만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case p3:
				$charyang=charyangp3.$dong_bu;
				$jagibudam_name ="20만원|5만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case p4:
				$charyang=charyangp4.$dong_bu;
				$jagibudam_name ="20만원|5만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
			
			case p5:
				$charyang=charyangp5.$dong_bu;
				$jagibudam_name ="20만원|5만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case p6:
			
				$charyang=charyangp6.$dong_bu;
				$jagibudam_name ="20만원|5만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case q1:
				$charyang=charyangq1.$dong_bu;
				$jagibudam_name ="20만원|10만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case q2:
				
				$charyang=charyangq2.$dong_bu;
				$jagibudam_name ="20만원|10만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case q3:
				$charyang=charyangq3.$dong_bu;
				$jagibudam_name ="20만원|10만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case q4:
				$charyang=charyangq4.$dong_bu;
				$jagibudam_name ="20만원|10만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case q5:
				$charyang=charyangq5.$dong_bu;
				$jagibudam_name ="20만원|10만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case q6:
			
				$charyang=charyangq6.$dong_bu;
				$jagibudam_name ="20만원|10만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case r1:
				$charyang=charyangr1.$dong_bu;
				$jagibudam_name ="20만원|20만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case r2:
				
				$charyang=charyangr2.$dong_bu;
				$jagibudam_name ="20만원|20만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case r3:
				$charyang=charyangr3.$dong_bu;
				$jagibudam_name ="20만원|20만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case r4:
				$charyang=charyangr4.$dong_bu;
				$jagibudam_name ="20만원|20만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case r5:
				$charyang=charyangr5.$dong_bu;
				$jagibudam_name ="20만원|20만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case r6:
			
				$charyang=charyangr6.$dong_bu;
				$jagibudam_name ="20만원|20만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
	#####################################################################################################	
			
			case s1:
				$charyang=charyangs1.$dong_bu;
				$jagibudam_name ="20만원|30만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case s2:
				
				$charyang=charyangs2.$dong_bu;
				$jagibudam_name ="20만원|30만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case s3:
				$charyang=charyangs3.$dong_bu;
				$jagibudam_name ="20만원|30만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case s4:
				$charyang=charyangs4.$dong_bu;
				$jagibudam_name ="20만원|30만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case s5:
				$charyang=charyangs5.$dong_bu;
				$jagibudam_name ="20만원|30만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case s6:
			
				$charyang=charyangs6.$dong_bu;
				$jagibudam_name ="20만원|30만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
#######################################################################################################

			case t1:
				$charyang=charyangt1.$dong_bu;
				$jagibudam_name ="20만원|50만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case t2:
				
				$charyang=charyangt2.$dong_bu;
				$jagibudam_name ="20만원|50만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case t3:
				$charyang=charyangt3.$dong_bu;
				$jagibudam_name ="20만원|50만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case t4:
				$charyang=charyangt4.$dong_bu;
				$jagibudam_name ="20만원|50만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case t5:
				$charyang=charyangt5.$dong_bu;
				$jagibudam_name ="20만원|50만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case t6:
			
				$charyang=charyangt6.$dong_bu;
				$jagibudam_name ="20만원|50만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case u1:
				$charyang=charyangu1.$dong_bu;
				$jagibudam_name ="30만원|5만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case u2:
				
				$charyang=charyangu2.$dong_bu;
				$jagibudam_name ="30만원|5만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case u3:
				$charyang=charyangu3.$dong_bu;
				$jagibudam_name ="30만원|5만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case u4:
				$charyang=charyangu4.$dong_bu;
				$jagibudam_name ="30만원|5만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
			
			case u5:
				$charyang=charyangu5.$dong_bu;
				$jagibudam_name ="30만원|5만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case u6:
			
				$charyang=charyangu6.$dong_bu;
				$jagibudam_name ="30만원|5만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case v1:
				$charyang=charyangv1.$dong_bu;
				$jagibudam_name ="30만원|10만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case v2:
				
				$charyang=charyangv2.$dong_bu;
				$jagibudam_name ="30만원|10만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case v3:
				$charyang=charyangv3.$dong_bu;
				$jagibudam_name ="30만원|10만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case v4:
				$charyang=charyangv4.$dong_bu;
				$jagibudam_name ="30만원|10만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case v5:
				$charyang=charyangv5.$dong_bu;
				$jagibudam_name ="30만원|10만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case v6:
			
				$charyang=charyangv6.$dong_bu;
				$jagibudam_name ="30만원|10만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case w1:
				$charyang=charyangw1.$dong_bu;
				$jagibudam_name ="30만원|20만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case w2:
				
				$charyang=charyangw2.$dong_bu;
				$jagibudam_name ="30만원|20만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case w3:
				$charyang=charyangw3.$dong_bu;
				$jagibudam_name ="30만원|20만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case w4:
				$charyang=charyangw4.$dong_bu;
				$jagibudam_name ="30만원|20만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case w5:
				$charyang=charyangw5.$dong_bu;
				$jagibudam_name ="30만원|20만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case w6:
			
				$charyang=charyangw6.$dong_bu;
				$jagibudam_name ="30만원|20만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
	#####################################################################################################	
			
			case x1:
				$charyang=charyangx1.$dong_bu;
				$jagibudam_name ="30만원|30만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case x2:
				
				$charyang=charyangx2.$dong_bu;
				$jagibudam_name ="30만원|30만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case x3:
				$charyang=charyangx3.$dong_bu;
				$jagibudam_name ="30만원|30만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case x4:
				$charyang=charyangx4.$dong_bu;
				$jagibudam_name ="30만원|30만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case x5:
				$charyang=charyangx5.$dong_bu;
				$jagibudam_name ="30만원|30만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case x6:
			
				$charyang=charyangx6.$dong_bu;
				$jagibudam_name ="30만원|30만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
#######################################################################################################
			case y1:
				$charyang=charyangy1.$dong_bu;
				$jagibudam_name ="30만원|50만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case y2:
				
				$charyang=charyangy2.$dong_bu;
				$jagibudam_name ="30만원|50만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case t3:
				$charyang=charyangt3.$dong_bu;
				$jagibudam_name ="30만원|50만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case y4:
				$charyang=charyangy4.$dong_bu;
				$jagibudam_name ="30만원|50만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case y5:
				$charyang=charyangy5.$dong_bu;
				$jagibudam_name ="30만원|50만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case y6:
			
				$charyang=charyangy6.$dong_bu;
				$jagibudam_name ="30만원|50만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;

			
#######################################################################################################
			case z1:
				$charyang=charyangz1.$dong_bu;
				$jagibudam_name ="50만원|5만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case z2:
				
				$charyang=charyangz2.$dong_bu;
				$jagibudam_name ="50만원|5만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case z3:
				$charyang=charyangz3.$dong_bu;
				$jagibudam_name ="50만원|5만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case z4:
				$charyang=charyangz4.$dong_bu;
				$jagibudam_name ="50만원|5만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case z5:
				$charyang=charyangz5.$dong_bu;
				$jagibudam_name ="50만원|5만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case z6:
			
				$charyang=charyangz6.$dong_bu;
				$jagibudam_name ="30만원|5만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			

			case aa1:
				$charyang=charyangaa1.$dong_bu;
				$jagibudam_name ="50만원|10만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case aa2:
				
				$charyang=charyangaa2.$dong_bu;
				$jagibudam_name ="50만원|10만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case aa3:
				$charyang=charyangaa3.$dong_bu;
				$jagibudam_name ="50만원|10만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case aa4:
				$charyang=charyangaa4.$dong_bu;
				$jagibudam_name ="50만원|10만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case aa5:
				$charyang=charyangaa5.$dong_bu;
				$jagibudam_name ="50만원|10만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case aa6:
			
				$charyang=charyangaa6.$dong_bu;
				$jagibudam_name ="50만원|10만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
			case ab1:
				$charyang=charyangab1.$dong_bu;
				$jagibudam_name ="50만원|20만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case ab2:
				
				$charyang=charyangab2.$dong_bu;
				$jagibudam_name ="50만원|20만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case ab3:
				$charyang=charyangab3.$dong_bu;
				$jagibudam_name ="50만원|20만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case ab4:
				$charyang=charyangab4.$dong_bu;
				$jagibudam_name ="50만원|20만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case ab5:
				$charyang=charyangab5.$dong_bu;
				$jagibudam_name ="50만원|20만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case ab6:
			
				$charyang=charyangab6.$dong_bu;
				$jagibudam_name ="50만원|20만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
	#####################################################################################################
			
			case ac1:
				$charyang=charyangac1.$dong_bu;
				$jagibudam_name ="50만원|30만원";

				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case ac2:
				
				$charyang=charyangac2.$dong_bu;
				$jagibudam_name ="50만원|30만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case ac3:
				$charyang=charyangac3.$dong_bu;
				$jagibudam_name ="50만원|30만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case ac4:
				$charyang=charyangac4.$dong_bu;
				$jagibudam_name ="50만원|30만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case ac5:
				$charyang=charyangac5.$dong_bu;
				$jagibudam_name ="50만원|30만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case ac6:
			
				$charyang=charyangac6.$dong_bu;
				$jagibudam_name ="50만원|30만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
#######################################################################################################
			case ad1:
				$charyang=charyangad1.$dong_bu;
				$jagibudam_name ="50만원|50만원";
				$charyang_name="500 만원 " ;
				$cha_name = "차대차/단독사고";
				break;
			
			
			case ad2:
				
				$charyang=charyangad2.$dong_bu;
				$jagibudam_name ="50만원|50만원";
				$charyang_name="1 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case ad3:
				$charyang=charyangad3.$dong_bu;
				$jagibudam_name ="50만원|50만원";
				$charyang_name="2 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case ad4:
				$charyang=charyangad4.$dong_bu;
				$jagibudam_name ="50만원|50만원";
				$charyang_name="3 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
				
			case ad5:
				$charyang=charyangad5.$dong_bu;
				$jagibudam_name ="50만원|50만원";
				$charyang_name="5 천만원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
			
			case ad6:
			
				$charyang=charyangad6.$dong_bu;
				$jagibudam_name ="50만원|50만원";
				$charyang_name="1억원 " ;
				$cha_name = "차대차/단독사고"; 
				break;
#######################################################################################################
		}
	switch($char_3)
	{
	case 9999 :
	$charyang_dambo="차량담보";
	$charyang="charyang9999";
	$charyang_name="가입안함";
	$cha_name = "차량 담보";
	$jagibudam_name="가입안함";
	break;
	}
	switch($mubohum_3){
			case 1 :
				$mubohum_p=mubohum.$dong_bu;
				$mubohum_name = " 2억원 ";
				break;
			
			
		
			case 2 :
				
				$mubohum_p=mubohum;
				$mubohum_name = "가입안함 ";
				break;
		}

	switch($sago_3){
		case 1 :
			$sago="sago_1";
			$sago_name ="10만원";
		break;
		case 2 :
			$sago="sago_2";
			$sago_name ="20만원";
		break;
		default :
			$sago="sago_3";
			$sago_name ="가입안함";
		break;
	}

	switch($law_3){
		case 1 : 
			$law="law_1";
			$law_name = "벌금제외";
		break;
		case 2 :
			$law="law_2";
			$law_name = "벌금포함";
		break;
		default :
			$law="law_3";
			$law_name ="가입안함";
		break;
	}

switch($bunnab_3){
	case 1 :
		$bunnab = 1;
		$constant_3 = 0; //분납할증
		$bunnab_name = " 일시납 ";
		break;
	
	case 2 :
		

		$bunnab = 2;
		$constant_3 = 0.01;//분납할증
		$bunnab_name = " 2회납 ";
		break;
	
	/*case 7 :

		$bunnab = 7;
		$constant_3 = 0.01;//분납할증
		$bunnab_name = " 비연속 2회납 ";
		break;*/
		
	case 3 :

		$bunnab = 3;
		$constant_3 = 0.005;//분납할증
		$bunnab_name = " 3회납 ";
		break;
	
	case 4 :

		$bunnab = 4;
		$constant_3 = 0.015;//분납할증
		$bunnab_name_3 = " 4회납 ";
		break;
	
	case 5 :
	
		$bunnab = 5;
		$constant_3 = 0.01;//분납할증
		$bunnab_name = " 5회납 ";
		break;
	
	case 6 :
		
		
		$bunnab = 6;
		$constant_3 = 0.02;//분납할증
		$bunnab_name = "비연속 6회납 ";
		break;

		case 7 :
		
		
		$bunnab = 7;
		$constant_3 = 0.015;//분납할증
		$bunnab_name = "연속 6회납 ";
		break;
	
	case 10 :
		
		$bunnab = 10;
		$constant_3 = 0.02;//분납할증
		$bunnab_name = " 10회납 ";
		break;
	
	}
?>
<?// echo "차량 $charyang <Br>";?>
 <? //echo "constant_3 $constant_3 <br>"; ?>