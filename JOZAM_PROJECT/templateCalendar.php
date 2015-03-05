<?php
require('XMLFunctions.php');

    //test if there is an action
    if (isset ( $_POST ['action'] )) {
    	// $action is sent from jozam.php 
    	$action = $_POST ['action'];
    	// the calendars url can be send too
    	$url = $_POST ['calendarURL'];
    	if($action === "an action"){   
    		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
    		foreach($boards->board as $board){
				foreach($board->projet as $p){
					compute($p/* other arguments */);
				}
			}
			// save your new xml elements in input.xml
			save_xml("input.xml",$boards);
    	}
    	
    	if($action === "an other action"){
			// 
    	}
    }
    
    // 
    function compute($p/* other arguments */){
    	foreach($p->projet as $sp){
    		compute($sp);
    	}
    	foreach($p->tache as $t){
    		$deadLine = $t['echeance'];
    		// call your functions here
    		
    		
    		
    		// modify the tasks time remaining
    		//$t['timeRemaining'] = 
    		
    		
    	}
    }
    
    
?>