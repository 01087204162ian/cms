<?

	switch ($_j){

				case 1 :
						$where="(nai<'26' )";
						$nai[$_j]='26技捞窍';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum1];
					break;
				case 2 :
					    $where="(nai>='26' and nai<='30')";
						$nai[$_j]='26技~30技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum2];
				break;
				case 3 :
						$where="(nai>='31' and nai<='44')";
						$nai[$_j]='31技~44技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum3];
					break;
				case 4 :
						$where="(nai>='45' and nai<='49')";
						$nai[$_j]='45技~49技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum4];
					break;
				case 5 :
					    $where="(nai>='50')";
						$nai[$_j]='50技 捞惑';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum5];
				break;
				case 6 :
					   $where="";
						$nai[$_j]='';
						$etag[$_j]='';
						$ewhere="";
						//$monThP[$_j]=$rCertiRow[preminum5];
					break;
			}




?>