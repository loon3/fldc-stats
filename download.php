<?php

$filename = $day.".txt";

if (!file_exists($filename)) {
    
	//download folding data from stanford
	$url  = "http://fah-web.stanford.edu/daily_user_summary.txt.bz2";
	$name = basename($url);
	file_put_contents("$name", file_get_contents($url));

	//$name = "daily_user_summary.txt.bz2";
	$bz = bzopen($name, "r") or die("Couldn't open $name for reading");

	$data="";

	$i = 0;

	//read folding data from file
    	do {
	
		$line=bzread($bz, 8092);
    
    		if($line!==false) { 
    		
    			$data[$i] = $line;
    	
    			$i++;
    		
    		}
    	
    	}
	while($line);
    
	bzclose($bz);

    	//write data as [TODAY].txt
	file_put_contents("$filename", $data);

	$data = null;

	echo $day.".txt download complete!<br>---<br>";

} else {
	
	echo "file already exists<br>--<br>";
	
}



?>
