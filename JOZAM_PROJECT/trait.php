<?php
    //test if there is an action
    if (isset ( $_POST ['action'] )) {
        $action = $_POST ['action'];
        //script if the action is to create a board
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
        //script if the action is to create a project
        if ($action === "CreationProjet") {
            $id = $_POST ['idParent'];
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
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
                                $idPrec .= $idList [$i];
                                $projects = $project;
                                break;
                            }
                        }
                        $i += 1;
                    }
                    $idProjet = $id . '-' . $projects->count ();
                    $projet = $projects->addChild ( 'projet' );
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
        //script if the action is to create a task
        if ($action === "CreationTache") {
            $id = $_POST ['idProjet'];
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
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
                                $idPrec .= $idList [$i];
                                $projects = $project;
                                break;
                            }
                        }
                        $i += 1;
                    }
                    $idTache = $id . '-' . $projects->count ();
                    $tache = $projects->addChild ( 'tache' );
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
        //script if the action is to modify a project (move or resize)
        if ($action === "ModifyProject") {
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            $id = $_POST ['idProj'];
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
                                $idPrec .= $idList [$i];
                                $projects = $project;
                                break;
                            }
                        }
                        $i += 1;
                    }
                    if($_POST ['action1'] == "Resize"){
                        $projects ['data-sizex'] = 	$_POST ['NewWidth'];
                        $projects ['data-sizey'] =  $_POST ['NewHeight'];
                    }
                    elseif($_POST ['action1'] == "Move"){
                        $projects ['data-row'] = 	$_POST ['NewRow'];
                        $projects ['data-col'] =  	$_POST ['NewCol'];
                    }
                    break;
                }
            }
            $dom = new DOMDocument ( "1.0" );
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML ( $boards->asXML () );
            file_put_contents ( 'input.xml', $dom->saveXML () );
        }
        //script if the action is to toggle
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
        //script if the action is to dlete a project
        if ($action === "DeleteProject") {
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            $id = $_POST ['idProj'];
            $idList = split ( "-", $id );
            file_put_contents ( 'bbb.xml', $idList[0]." ".$idList[1]." ".$idList[2] );
            foreach ( $boards->board as $board ) {
                if ($board ['id'] == $idList [0]) {
                    $i = 1;
                    $projects = $board;
                    $idPrec = $idList [0];
                    $string = "";
                    while ( $idList [$i] != "" ) {
                        $idPrec .= "-";

                        foreach ( $projects->projet as $project ) {
                            //file_put_contents ( $project['id'].'.xml',  );
                            $string .= $idPrec. "\n";
                            if ($project ['id'] == $idPrec . $idList [$i]) {
                                $idPrec .= $idList [$i];
                                $projects = $project;
                                break;
                            }
                        }
                        $i += 1;
                    }
                    file_put_contents ( 'aaa.xml', $string );
                    $dom = dom_import_simplexml ( $projects );
                    $dom->parentNode->removeChild ( $dom );
                    break;
                }
            }
            $dom = new DOMDocument ( "1.0" );
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML ( $boards->asXML () );
            file_put_contents ( 'input.xml', $dom->saveXML () );
        }
    }

    //function simple import xml to add elements node to an existing xml file
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