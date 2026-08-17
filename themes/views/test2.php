<?php 
    $change = $this->getVar('config');	
	var_dump($change); 

	$prev_Parameter = $change["prevParameter"]; 
	$next_Parameter = $change["nextParameter"]; 
	$values = $change["values"];

	print "<br><br>"; 

	var_dump($values); 

	print "<br><br>"; 

    foreach ($values as $key => $value) {
		$prev = $value["prev"]; 
		$next = $value["next"]; 

		print $prev." - ".$next."<br>"; 			
	}
?>