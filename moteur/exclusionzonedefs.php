<?php

	
	$Exclusions = array();
	
	if ($fullRacesObj->races->idraces == 20141009)
	{
		// VOR 2014 Leg2 Madagascar
		$p1= array(-25.976217, 32.98825); // 25� 58.573'S 32� 59.295'E
		$p2= array(-25.590117, 45.143317); // 25� 35.407'S 45� 08.599'E
		$p3= array(-20.51015,57.396433 ); // 20� 30.609'S 57� 23.786'E
		
		echo "\n\t Setting exclusion zone for VOR Leg 2\n";
		$Exclusions = array ( array($p1,$p2), array($p2,$p3) );
	}

?>