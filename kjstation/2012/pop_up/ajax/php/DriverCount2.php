<?

	switch ($_j){

				case 1 :
						$where="(nai>='26' and nai<='30')";
						$nai[$_j]='26技~30技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum1];
					break;
				case 2 :
					    $where="(nai>='31' and nai<='45')";
						$nai[$_j]='31技~45技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum2];
				break;
				case 3 :
						$where="(nai>='46' and nai<='50')";
						$nai[$_j]='46技~50技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum3];
					break;
				case 4 :
						$where="(nai>='51' and nai<='55')";
						$nai[$_j]='51技~55技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum4];
					break;
				case 5 :
					    $where="(nai>='56' and nai<='60')";
						$nai[$_j]='56技~60技';
						$etag[$_j]='老馆';
						//$ewhere="and (etag='' or etag='1')";
						$monThP[$_j]=$rCertiRow[preminum5];
				break;
				case 6 :
						$where="(nai>='61')";
						$nai[$_j]='61技捞惑';
						$etag[$_j]='老馆';
						//$ewhere="and etag='2'";
						$monThP[$_j]=$rCertiRow[preminum6];
					break;
			}




?>