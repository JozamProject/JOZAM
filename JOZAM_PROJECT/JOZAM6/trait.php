<?php
require('XMLFunctions.php');

    //test if there is an action
    if (isset ( $_POST ['action'] )) {
        $action = $_POST ['action'];
        //script if the action is to create a board
        if ($action === "CreateBoard") {
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            //copy($boards['idMax'],$idBoard);
            $idBoard = intval($boards['idMax']);
            $boards['idMax'] = $idBoard+1;
            $board = $boards->addChild ( 'board' );
            $board->addAttribute ( 'name', 'New Board' );
            $board->addAttribute ( 'id', $idBoard );
            $board->addAttribute ( 'idMax', "1");
            file_put_contents ( "bbb.xml", $boards->asXML () );
            addProject($board,$idBoard . "-0");
            file_put_contents ( "aaa.xml", $boards->asXML () );
            save_xml("input.xml",$boards);
        }
        //script if the action is to create a project
        // Creates a project inside each Board/project wich id is include in idString
        if ($action === "CreateProjects") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$projectsIds = json_decode(stripslashes($_POST['idString']));
        	foreach($projectsIds as $idProjects){
        		createProject($idProjects,$boards);
        	}
        	save_xml("input.xml",$boards);
        }
        // Creates a project inside the project/Board with the id idParent 
        if ($action === "CreateProject") {
            $id = $_POST ['idParent'];
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            createProject($id,$boards);
            save_xml("input.xml",$boards);
        }
        //script if the action is to create a task
        if ($action === "CreateTask") {
            $id = $_POST ['idProject'];
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            $projects = findProject($id,$boards);
            $idMaxProjects = intval($projects['idMax']);
            $idTache = $id . '-' . $idMaxProjects ;
            $projects['idMax'] = $idMaxProjects+1;
            addTask($projects,$idTache);
            save_xml("input.xml",$boards);
        }
        //script if the action is to modify a project (move or resize)
        if ($action === "ModifyProject") {
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            $id = $_POST ['idProj'];
            $projects = findProject($id,$boards);
            if($_POST ['action1'] == "Resize"){
            	$projects ['data-sizex'] = 	$_POST ['NewWidth'];
            	$projects ['data-sizey'] =  $_POST ['NewHeight'];
            }
            elseif($_POST ['action1'] == "Move"){
            	$projects ['data-row'] = 	$_POST ['NewRow'];
            	$projects ['data-col'] =  	$_POST ['NewCol'];
            }
            save_xml("input.xml",$boards);
        }
        //script if the action is to toggle
        if ($action === "Toggle") {
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            $b = $boards->board [0];
            simplexml_import_xml ( $boards, $b->asXML () );
            $dom = dom_import_simplexml ( $boards->board [0] );
            $dom->parentNode->removeChild ( $dom );
            save_xml("input.xml",$boards);
        }
        //script if the action is to dlete a project
        if ($action === "DeleteProject") {
            $boards = new SimpleXMLElement ( 'input.xml', 0, true );
            $id = $_POST ['idProj'];
            $projects = findProject($id,$boards);
            $dom = dom_import_simplexml ( $projects );
            $dom->parentNode->removeChild ( $dom );
            save_xml("input.xml",$boards);
        }
        
        if ($action === "DeleteBoard") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$id = $_POST ['idBoard'];
        	$board = findBoard($id,$boards);
        	$dom = dom_import_simplexml ( $board );
        	$dom->parentNode->removeChild ( $dom );
        	save_xml("input.xml",$boards);
        }
        
        if($action === "PasteProjects") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$idNewParent = $_POST ['id'];
        	$cut 		 = $_POST ['cut'];
       		$projectsIds = json_decode(stripslashes($_POST['idString']));
       		if($cut == "true"){
       			foreach($projectsIds as $idProject){
       				changeParent($idProject,$idNewParent,$boards);
       			}
       		}
       		else 
       			foreach($projectsIds as $idProject){
       				addChild($idProject,$idNewParent,$boards);
       			}
       		save_xml("input.xml",$boards);
        }
        
        if($action === "GetTaskAttributes") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$idTask = $_POST ['idTask'];
        	$t = findTask($idTask,$boards);
        	echo $t['title']."|";
        	echo $t->description."|";
        	echo $t->comment."|";
        	echo $t['deadLine']."|";
        	echo $t['archive']."";
        }

        if($action === "ModifyTask") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$idTask = $_POST ['idTask'];
        	$t = findTask($idTask,$boards);
        	$t['title'] = $_POST ['title'];
        	$t->description = $_POST ['description'];
        	$t->comment = $_POST ['comment'];
        	$t['deadLine'] = $_POST ['deadLine'];
            echo $t['archive'];
        	$t['archive'] = $_POST ['archivemodif'];
        	save_xml("input.xml",$boards);
        }
        
        if($action === "DeleteTask") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$idTask = $_POST ['idTask'];
        	$t = findTask($idTask,$boards);
        	deleteElement($t);
        	save_xml("input.xml",$boards);
        }
        
        if($action === "ModifyBoardName") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$b = findBoard($_POST ['id'],$boards);
        	$b['name'] = $_POST ['newName'];
        	save_xml("input.xml",$boards);
        }
        
        if($action === "ModifyProjectName") {
        	$boards = new SimpleXMLElement ( 'input.xml', 0, true );
        	$p = findProject($_POST ['id'],$boards);
        	$p['name'] = $_POST ['newName'];
        	save_xml("input.xml",$boards);
        }
        
    }
?>