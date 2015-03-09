<?php
require('XMLFunctions.php');

    //test if there is an action
    if (isset ( $_POST ['action'] )) {
    	$action = $_POST ['action'];
    	if($action === "archive"){
			$boards = new SimpleXMLElement ( 'input.xml', 0, true );
			$string = "";
			foreach($boards->board as $board){
				foreach($board->project as $p){
					$string .=archive($p,$board['name'],$boards);
				}
			}
			file_put_contents ( "archive.txt", $string, FILE_APPEND);
			save_xml("input.xml",$boards);
    	}
    }
    
    function archive($p,$parentName,$boards){
    	$array = array();
    	$string = "";
    	$newParentName = $parentName."->".$p['name'];
    	foreach($p->project as $sp){
    		$string .=archive($sp,$newParentName,$boards);
    	}
    	$i = 0;
    	foreach($p->task as $t){
    		if($t['archive'] == "true"){
    			$aux = $t;
				array_push($array, $t['id']);
    			$i += 1;
    			$string .= $newParentName." : ".$t['title']."\t\n";
    		}
    	}
    	for ($j = 0; $j <$i; $j++) {
    		$id = array_pop($array);
    		$t = findTask($id,$boards);
    		deleteElement($t);
		}
    	return $string;
    }
    
    
?>