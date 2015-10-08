<?php

//load function to check if valid bitcoin address
require_once "checkaddress.php";

//load database 
require_once "db.php";

$mysqli = new mysqli($dbserver, $dbuser, $dbpassword, $dbname);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//create new table template for daily stats
$tablecreate = "CREATE TABLE IF NOT EXISTS `template` ( `id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `token` VARCHAR( 24 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `address` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `totalpts` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL)";

//create new table for today's stats
$tablecreate = "CREATE TABLE `$day` LIKE `template`";

if ($mysqli->query($tablecreate) === TRUE) {
		
	echo "Table created successfully<br>---<br>";
	    
	$filename = $day.".txt";	    
	$i = 0;
       
    //parse data from [TODAY].txt line-by-line
	$handle = @fopen($filename, "r");
	if ($handle) {
    	while (($buffer = fgets($handle, 4096)) !== false) {
             
        	//skip first two lines of data file
            if($i > 1) {

        		$linedata = explode("	", $buffer);
        		$name = $linedata[0];
				$credit = $linedata[1];
				
                //usernames must be at least 26 characters
				if (strlen($name) >= 26) {
                    
				    //check if member of Foldingcoin team (226728) and using bitcoin address only username                
					$team = trim($linedata[3]);
				
					if ($team == "226728") {
					
						$isvalid = checkAddress($name);
        						
        				if($isvalid == 1) {
        			
        					$namelinedata[0] = "---";
        					$namelinedata[1] = "FLDC";
        					$namelinedata[2] = $name;
        				
        				} else {
        				
        					$namelinedata = explode("_", $buffer);
        				
        				}
					
					} else {
				
						$namelinedata = explode("_", $buffer);
					
					}
                    
                    //check for Foldingcoin user by naming convention	
					if (count($namelinedata) == 3 || $namelinedata[0] == "---") {
                        
                        //check if token indicated in username is valid
						if($namelinedata[1] == "ALL" || $namelinedata[1] == "FLDC" || $namelinedata[1] == "OCTO" || $namelinedata[1] == "MAGICFLDC" || $namelinedata[1] == "SCOTCOIN"){
					
        					$fldcname = $namelinedata[0];
        					$token = $namelinedata[1];
        				
        					$addressdata = explode("	",$namelinedata[2]);
        				
        					$address = $addressdata[0];
        				    
                            //check if bitcoin address is valid       
        					if(strlen($address) >= 26 && strlen($address) <= 35 && substr($address,0,1) == 1 ) {
        						if (ctype_alnum($address)) {
        						
        							$isvalid = checkAddress($address);
        						
        							if($isvalid == 1) {
        							
        								echo $name." - ";
        								echo $credit."<br>";
                                        
                                        //enter foldingcoin user information into database
        								$tableentry = "INSERT INTO `$dbname`.`$day` (`name`, `token`, `address`, `totalpts`) VALUES ('$fldcname','$token','$address','$credit')";

										$mysqli->query($tableentry);
								
        							}
                                    
        						}
						
        					}
        			
						}
                        
					}
                    
				}
                
        	}
		
			//LIMIT FOR TESTING ONLY
        	//if($i > 10000) {break;}
        
        	$i++;
        
    	}
    	
    	fclose($handle);
    	
	}

	$mysqli->close();
	    	    
} else {
		
	echo "Error creating table: " . $mysqli->error;
	    
}




?>