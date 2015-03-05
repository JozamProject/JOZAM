<?php
require('XMLFunctions.php');

if(isset($_POST['clicked_lang']))
{	
    //Script to chose the language selected and to set "true" or "false" the language chosen
	$clicked_lang = $_POST['clicked_lang'];
	$languages = new SimpleXMLElement('Languages.xml',0,true);
	$found = false;
	foreach ($languages->Language as $l){
		
		if($clicked_lang==$l['name']){
			$found = true;
			$l['chosen'] = "true";
			break;
		}
	}
	if($found){
		foreach ($languages->Language as $l){
		
			if($clicked_lang!=$l['name'] and $l['chosen']=="true"){
				$l['chosen'] = "false";
				break;
			}
		}
	}

	save_xml("languages.xml",$languages);
	
}
?>
