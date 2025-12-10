<?

	switch ($_j){

				case 1 :

						$where="(nai>='26' and nai<='34')";
						$nai[$_j]='26技~34技';
						$etag[$_j]='老馆';
					//	$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum1];
					break;
				case 2 :
					    $where="(nai>='35' and nai<='47')";
						$nai[$_j]='35技~47技';
						$etag[$_j]='老馆';
					//	$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum2];
				break;
				case 3 :
						$where="(nai>='48' and nai<='54')";
						$nai[$_j]='48技~54技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum3];
					break;
				case 4 :
						$where="(nai>='55')";
						$nai[$_j]='55技捞惑';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum4];
					break;
				case 5 :
						//$where='
						$nai[$_j]='';
						$etag[$_j]='';
						//$ewhere="and (etag='' or etag='1')";
						//$monThP[$_j]=$rCertiRow[preminum4];
				break;
				case 6 :
					//$where='
						$nai[$_j]='';
						$etag[$_j]='';
						//$ewhere="and (etag='' or etag='1')";
						//$monThP[$_j]=$rCertiRow[preminum4];
					break;
			}




?>