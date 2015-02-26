<?php

//file_put_contents('bbb.xml', $_POST['action']);

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
	
    $action = $_POST['action'];
    //$board  = $_POST['board'];
    //$projet = new Projet();
	
    //$boards->board[0]->addBoard($projet);
    //file_put_contents('result_file_xml.xml', $gdt->toXML());
    //echo $action;
    //file_put_contents('result_file_create.txt', $action);
    if($action === "CreationBoard"){
    	$boards = new SimpleXMLElement('input.xml',0,true);
    	$idBoard = $boards->count();
    	$board = $boards->addChild('board');
    	$board->addAttribute('nom', 'New Board');
    	$board->addAttribute('id', $idBoard);
    	$projet = $board->addChild('projet');
    	$projet->addAttribute('nom', 'New Project');
    	$projet->addAttribute('id', $idBoard."-0");
    	$projet->addAttribute('data-row', '1');
    	$projet->addAttribute('data-col', '1');
    	$projet->addAttribute('data-sizex', '1');
    	$projet->addAttribute('data-sizey', '1');
    	 
    	$dom = new DOMDocument("1.0");
    	$dom->preserveWhiteSpace = false;
    	$dom->formatOutput = true;
    	$dom->loadXML($boards->asXML());
    	//file_put_contents('result_file_toto.txt', $action);
    
    	file_put_contents('input.xml', $dom->saveXML());
    }
    
    if($action === "CreationProjet"){
    	$idBoard  = $_POST['idBoard'];
    	
    	$boards = new SimpleXMLElement('input.xml',0,true);
    	foreach($boards->board as $board){
    		//file_put_contents('bbb.xml', $board['id']."bibi");
    		if($board['id']==$idBoard){
    			$idProjet = $idBoard.'-'.$board->count();
    			//file_put_contents('bbb.xml', $idBoard."toto");
    			$projet = $board->addChild('projet');
    			$projet->addAttribute('nom', 'New project');
    			$projet->addAttribute('id', $idProjet);
    			$projet->addAttribute('data-row', '1');
    			$projet->addAttribute('data-col', '1');
    			$projet->addAttribute('data-sizex', '1');
    			$projet->addAttribute('data-sizey', '1');
    			//file_put_contents('gtxml.xml', $boards->asXML());
    			$dom = new DOMDocument("1.0");
    			$dom->preserveWhiteSpace = false;
    			$dom->formatOutput = true;
    			$dom->loadXML($boards->asXML());
    			file_put_contents('input.xml', $dom->saveXML());
    		}	
    	}
    }
    
    if($action === "CreationTache"){
    	$id  = $_POST['idProjet'];
    	file_put_contents('aaa.xml', $id);
    	list($idBoard,$idProjet) = split("-",$id);
    	//file_put_contents('bbb.xml', $idBoard."/".$idProjet);
    	$boards = new SimpleXMLElement('input.xml',0,true);
    	foreach($boards->board as $board){
    		
    		if($board['id']==$idBoard){
    			//$chaine = "";
    			foreach($board->projet as $projet){
    				//$chaine .= $idProjet."/".$projet['id']. " ";
    				if($projet['id']==$id){
    					file_put_contents('bbb.xml', "tototo");
    					$idTache = $id.'-'.$projet->count();
    					$tache = $projet->addChild('tache');
    					$tache->addAttribute('id', idTache);
    					$tache->addAttribute('titre', $_POST['titre']);
    					$tache->addAttribute('echeance', $_POST['echeance']);
    					$tache->addChild('commentaire', $_POST['commentaire']);
    					$tache->addChild('description', $_POST['description']);
    					//$tache->addAttribute('data-sizey', '1');
    					//file_put_contents('gtxml.xml', $boards->asXML());
    					$dom = new DOMDocument("1.0");
    					$dom->preserveWhiteSpace = false;
    					$dom->formatOutput = true;
    					$dom->loadXML($boards->asXML());
    					file_put_contents('input.xml', $dom->saveXML());
    					//action : action, idProjet : idProjet, titre : titre.val() , echeance : echeance.val() , commentaire : commentaire.val() , description : description.val()
    				}
    			}
    			//file_put_contents('bbb.xml', $chaine);
    			 
    		}
    	}
    }

	/*
	//at a later point, you can convert it back to array like
	$recoveredData = file_get_contents('your_file_name.txt');
	$recoveredArray = unserialize($recoveredData);

	// you can print your array like
	print_r($recoveredArray);*/
	
}
else if(isset($_POST['clicked_lang']){
    file_put_contents('bbb.xml',$_POST['clicked_lang'] );
    $l = $_POST['clicked_lang'];
    $langs = new SimpleXMLElement('Languages.xml',0,true);
    foreach($langs->Languages as lang){
        if($lang['name']==$l)
        {
    		if($lang['chosen']=="false"){
                $lang->Language['chosen'] = 'true';
            }
        }else{
            $lang->Language['chosen'] = 'false';
        }
        $dom = new DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($langs->asXML());
        file_put_contents('Languages.xml', $dom->saveXML());
    }
}
?>