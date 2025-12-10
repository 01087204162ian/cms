<?

	switch ($_j){

				case 1 :
						$where="(nai>='26' and nai<='34')";
						$nai='26技~34技';
						$etag='老馆';
						$ewhere="and (etag='' or etag='1')";
						$monThP=$rCertiRow[preminum1];
					break;
				case 2 :
					    $where="(nai>='35' and nai<='47')";
						$nai='34技~47技';
						$etag='老馆';
						$ewhere="and (etag='' or etag='1')";
						$monThP=$rCertiRow[preminum2];
				break;
				case 3 :
						$where="(nai>='48')";
						$nai='48技捞惑';
						$etag='老馆';
						$ewhere="and (etag='' or etag='1')";
						$monThP=$rCertiRow[preminum3];
					break;
				case 4 :
						$where="(nai>='26' and nai<='34')";
						$nai='26技~34技';
						$etag='殴价';
						$ewhere="and etag='2'";
						$monThP=$rCertiRow[preminumE1];
					break;
				case 5 :
					    $where="(nai>='35' and nai<='47')";
						$nai='34技~47技';
						$etag='殴价';
						$ewhere="and etag='2'";
						$monThP=$rCertiRow[preminumE2];
				break;
				case 6 :
						$where="(nai>='48')";
						$nai='48技捞惑';
						$etag='殴价';
						$ewhere="and etag='2'";
						$monThP=$rCertiRow[preminumE3];
					break;
			}




?>