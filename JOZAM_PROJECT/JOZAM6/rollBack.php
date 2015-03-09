<?php
require('XMLFunctions.php');

    //test if there is an action
    if (isset ( $_POST ['action'] )) {
    	$action = $_POST ['action'];
    	if($action === "synchronize"){
    		copy("input.xml", "backUp.xml");
    	}
    	
    	if($action === "rollBack"){
    		copy("backUp.xml", "input.xml");
    	}
    }
    
    
?>