<?php
require_once('GestionnaireDesTaches.php');
require_once('Board.php');
require_once('Projet.php');
require_once('Tache.php');

$boards = new SimpleXMLElement('gtxml.xml',0,true);
//$action = $_POST['action'];
//$gdt = new GestionnaireDesTaches($boards['utilisateur']->__toString());

/*foreach ($boards->board as $board) {
	//$board = new Board($board['nom']->__toString());
	//$gdt->addBoard($board);
}
$projet = $boards->board[0]->addChild('projet','projet1');
file_put_contents('result_file_xml.xml', $boards->asXML());*/

if(isset($_POST['action']))
{
	
    //$action = $_POST['action'];
    $board  = $_POST['board'];
    //$projet = new Projet();
	
    //$boards->board[0]->addBoard($projet);
    //file_put_contents('result_file_xml.xml', $gdt->toXML());
    //echo $action;
    //file_put_contents('result_file_create.txt', $action);
    if(strcmp($_POST['action'], "CreationProjet")){
    	file_put_contents('result_file_create.txt', $action);
    	 
    	$projet = $boards->board[0]->addChild('projet','New');
    	//file_put_contents('gtxml.xml', $boards->asXML());
    	$dom = new DOMDocument("1.0");
    	$dom->preserveWhiteSpace = false;
    	$dom->formatOutput = true;
    	$dom->loadXML($boards->asXML());
    	//file_put_contents('result_file_toto.txt', $action);
    	 
    	file_put_contents('bbb.xml', $boards->asXML());
    	file_put_contents('gtxml.xml', $dom->saveXML());
    	//echo $dom->saveXML();
    	//$board = 
    	//$board = $_POST['board'];
    	//$serializedData = serialize($action); //where '$array' is your array
		//$serializedJsonData = json_encode($action); //where '$array' is your array
		//file_put_contents('result_file.txt', $serializedData);
		//file_put_contents('result_file_Json.txt', $serializedData);
		//file_put_contents('result_file_board.txt', $board);
    }

	/*
	//at a later point, you can convert it back to array like
	$recoveredData = file_get_contents('your_file_name.txt');
	$recoveredArray = unserialize($recoveredData);

	// you can print your array like
	print_r($recoveredArray);*/
	
}else{
file_put_contents('C:\Users\Ayoub\Desktop\gridster_sample\gridster_test\result_failed.txt', "failed to recover data");
}
?>