<?php
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
	$dom = new DOMDocument("1.0");
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($languages->asXML());
	file_put_contents('Languages.xml', $dom->saveXML());
}
?>
