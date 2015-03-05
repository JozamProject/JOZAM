<?php
require('XMLFunctions.php');

if(isset($_POST['idProj']))
{	
    //Script to chose the language selected and to set "true" or "false" the language chosen
	$idProject = $_POST['idProj'];
	$boards = new SimpleXMLElement('input.xml',0,true);
	$project = findProject($idProject,$boards);
	$nColor = nextColor($project['color']);
	$project['color'] = $nColor;
	
    save_xml("input.xml",$boards);
	echo (string)$nColor;
	
}

function nextColor($color){
	$colors = new SimpleXMLElement('colors.xml',0,true);
	foreach($colors->color as $c){
		if($c['value'] == (string)$color){
			$colorId = ($c['id']+1)%($colors->count());	
		}		
	}
	foreach($colors->color as $c1){
		if($c1['id'] == $colorId){
			return $c1['value'];
		}
	}
}

?>