<?php
function save_xml($file,$xml){
	$dom = new DOMDocument ( "1.0" );
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML ( $xml->asXML () );
	file_put_contents ( $file, $dom->saveXML () );
}

function deleteElement($element){
	$dom = dom_import_simplexml ($element);
	$dom->parentNode->removeChild ( $dom );
}

function addProject($board,$idProject){
	$project = $board->addChild ( 'project' );
	$project->addAttribute ( 'name', 'New Project' );
	$project->addAttribute ( 'id', $idProject );
	$project->addAttribute ( 'idMax', "0");
	$project->addAttribute ( 'data-row', '1' );
	$project->addAttribute ( 'data-col', '1' );
	$project->addAttribute ( 'data-sizex', '2' );
	$project->addAttribute ( 'data-sizey', '1' );
	$project->addAttribute ( 'color', 'LightSlateGray');
}

function addTask($project,$idTask){
	$task = $project->addChild ( 'task' );
	$task->addAttribute ( 'id', $idTask );
    $task->addAttribute ( 'title', $_POST ['title'] );
    $task->addAttribute ( 'deadLine', $_POST ['deadLine'] );
    $task->addAttribute ( 'archive', 'false');
    $task->addChild ( 'comment', $_POST ['comment'] );
    $task->addChild ( 'description', $_POST ['description'] );
}
function changeParent($idChild, $idFutureParent,$boards){
	$child = findProject($idChild,$boards);
	$parent = findProject($idFutureParent,$boards);
	changeId($child,$parent);
	simplexml_import_xml( $parent, $child->asXML () );
	$dom = dom_import_simplexml ($child);
	$dom->parentNode->removeChild ( $dom );
}

function changeId($project,$newParent){
	$id = $newParent['id']."-".$newParent['idMax'];
	$idParent = intval($newParent['idMax']);
    $newParent['idMax'] = $idParent+1;
    $project['id'] = $id;
}
// Returns the project with $id in $boards
function findProject($id,$boards){
	$projects;
	$idList = explode ( "-", $id );
	foreach ( $boards->board as $board ) {
		if ($board ['id'] == $idList [0]) {
			$i = 1;
			$projects = $board;
			$idPrec = $idList [0];
			$string = "";
			while ( $i <= (strlen($id)/2) ) {
				$idPrec .= "-";
				foreach ( $projects->project as $project ) {
					$string .= $idPrec. "\n";
					if ($project ['id'] == $idPrec . $idList [$i]) {
						$idPrec .= $idList [$i];
						$projects = $project;
						break;
					}
				}
				$i += 1;
			}
			return $projects;
		}
	}
	return $projects;
}

function findTask($id,$boards){
	$projects;
	$idList = explode ( "-", $id );
	foreach ( $boards->board as $board ) {
		if ($board ['id'] == $idList [0]) {
			$i = 1;
			$projects = $board;
			$idPrec = $idList [0];
			$string = "";
			while ( $i <= (strlen($id)/2)-1 ) {
				$idPrec .= "-";
				foreach ( $projects->project as $project ) {
					$string .= $idPrec. "\n";
					if ($project ['id'] == $idPrec . $idList [$i]) {
						$idPrec .= $idList [$i];
						$projects = $project;
						break;
					}
				}
				$i += 1;
			}
			foreach($projects->task as $task){
				if($task['id'] == $id)
					return $task;
			}
		}
	}
}

function findBoard($id,$boards){
	foreach ( $boards->board as $board ) {
		if ($board ['id'] == $id) {
			return $board;
		}
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