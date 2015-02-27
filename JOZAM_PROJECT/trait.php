<?php
if (isset ( $_POST ['action'] )) {
	$action = $_POST ['action'];
	if ($action === "CreationBoard") {
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		$idBoard = $boards->count ();
		$board = $boards->addChild ( 'board' );
		$board->addAttribute ( 'nom', 'New Board' );
		$board->addAttribute ( 'id', $idBoard );
		$projet = $board->addChild ( 'projet' );
		$projet->addAttribute ( 'nom', 'New Project' );
		$projet->addAttribute ( 'id', $idBoard . "-0" );
		$projet->addAttribute ( 'data-row', '1' );
		$projet->addAttribute ( 'data-col', '1' );
		$projet->addAttribute ( 'data-sizex', '1' );
		$projet->addAttribute ( 'data-sizey', '1' );
		$dom = new DOMDocument ( "1.0" );
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML ( $boards->asXML () );
		file_put_contents ( 'input.xml', $dom->saveXML () );
	}
	
	if ($action === "CreationProjet") {
		$idBoard = $_POST ['idBoard'];
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		foreach ( $boards->board as $board ) {
			if ($board ['id'] == $idBoard) {
				$idProjet = $idBoard . '-' . $board->count ();
				$projet = $board->addChild ( 'projet' );
				$projet->addAttribute ( 'nom', 'New project' );
				$projet->addAttribute ( 'id', $idProjet );
				$projet->addAttribute ( 'data-row', '1' );
				$projet->addAttribute ( 'data-col', '1' );
				$projet->addAttribute ( 'data-sizex', '1' );
				$projet->addAttribute ( 'data-sizey', '1' );
				$dom = new DOMDocument ( "1.0" );
				$dom->preserveWhiteSpace = false;
				$dom->formatOutput = true;
				$dom->loadXML ( $boards->asXML () );
				file_put_contents ( 'input.xml', $dom->saveXML () );
			}
		}
	}
	
	if ($action === "CreationTache") {
		$id = $_POST ['idProjet'];
		file_put_contents ( 'aaa.xml', $id );
		list ( $idBoard, $idProjet ) = split ( "-", $id );
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		foreach ( $boards->board as $board ) {
			if ($board ['id'] == $idBoard) {
				foreach ( $board->projet as $projet ) {
					// $chaine .= $idProjet."/".$projet['id']. " ";
					if ($projet ['id'] == $id) {
						file_put_contents ( 'bbb.xml', "tototo" );
						$idTache = $id . '-' . $projet->count ();
						$tache = $projet->addChild ( 'tache' );
						$tache->addAttribute ( 'id', idTache );
						$tache->addAttribute ( 'titre', $_POST ['titre'] );
						$tache->addAttribute ( 'echeance', $_POST ['echeance'] );
						$tache->addChild ( 'commentaire', $_POST ['commentaire'] );
						$tache->addChild ( 'description', $_POST ['description'] );
						$dom = new DOMDocument ( "1.0" );
						$dom->preserveWhiteSpace = false;
						$dom->formatOutput = true;
						$dom->loadXML ( $boards->asXML () );
						file_put_contents ( 'input.xml', $dom->saveXML () );
					}
				}
			}
		}
	}
	
	if ($action === "ResizeProject") {
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		$id = $_POST ['idProj'];
		$height = $_POST ['NewHeight'];
		$width = $_POST ['NewWidth'];
		$idList = split ( "-", $id );
		foreach ( $boards->board as $board ) {
			if ($board ['id'] == $idList [0]) {
				$i = 1;
				$projects = $board;
				$idPrec = $idList [0];
				while ( $idList [$i] != "" ) {
					$idPrec .= "-";
					foreach ( $projects->projet as $project ) {
						if ($project ['id'] == $idPrec . $idList [$i]) {
							$idPrec .= $idList [i];
							$projects = $project;
							break;
						}
					}
					$i += 1;
				}
				$projects ['data-sizex'] = $width;
				$projects ['data-sizey'] = $height;
				file_put_contents ( 'bbb.xml', "x : " . $projects ['data-sizex'] . " ,y : " . $projects ['data-sizey'] );
				
				break;
			}
		}
		$dom = new DOMDocument ( "1.0" );
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML ( $boards->asXML () );
		file_put_contents ( 'input.xml', $dom->saveXML () );
	}
	
	if ($action === "Toggle") {
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		$b = $boards->board [0];
		simplexml_import_xml ( $boards, $b->asXML () );
		$dom = dom_import_simplexml ( $boards->board [0] );
		$dom->parentNode->removeChild ( $dom );
		$dom = new DOMDocument ( "1.0" );
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML ( $boards->asXML () );
		file_put_contents ( 'input.xml', $dom->saveXML () );
	}
}

function simplexml_import_xml(SimpleXMLElement $parent, $xml, $before = false) {
	$xml = ( string ) $xml;
	// check if there is something to add
	if ($nodata = ! strlen ( $xml ) or $parent [0] == NULL) {
		return $nodata;
	}
	
	// add the XML
	$node = dom_import_simplexml ( $parent );
	$fragment = $node->ownerDocument->createDocumentFragment ();
	$fragment->appendXML ( $xml );
	
	if ($before) {
		return ( bool ) $node->parentNode->insertBefore ( $fragment, $node );
	}
	
	return ( bool ) $node->appendChild ( $fragment );
}
?>