<?php

require_once('Board.php');
class GestionnaireDesTaches
{
	private $_utilisateur;
	private $_boards = array();
	private $_key = 0;
	
	public function __construct($utilisateur)
	{
		$this->setUtilisateur($utilisateur);
	}
	
	public function setUtilisateur($utilisateur)
	{
		if(!is_string($utilisateur)){
			trigger_error('Entrer une chaine de caractère comme nom d\'utilisateur', E_USER_WARNING);
			return;
		}
		$this->_utilisateur = $utilisateur;
	}
	
	public function getUtilisateur()
	{
		return $this->_utilisateur;
	}
	
	public function setBoards($boards)
	{
		if(!is_array($boards)){
			trigger_error('Entrer un tableau de boards', E_USER_WARNING);
			return;
		}
		$_boards = $boards;
	}
	
	public function getBoards()
	{
		return $this->_boards;
	}
	
	public function addBoard($board) 
	{
		if (isset($this->items[$key])) {
			throw new KeyHasUseException("Key $key already in use.");
		}
		else {
			$this->_boards[$key++] = $board;
		}
	}
	
	public function toXML()
	{
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> \r\n";
		$xml .= "<gestionnaireDesTaches utilisateur=" . "\"" . $this->_utilisateur . "\">\r\n";
		foreach($this->_boards as $board){
			$xml .= $board->toXML();
		}
		$xml .= "<\gestionnaireDesTaches>";
		return $xml;
	}
		
}

$gt = new GestionnaireDesTaches("Othman");
$board = new Board("Maison");
$gt->addBoard($board);
echo $gt->getUtilisateur();
echo "\n toto \n";
//
file_put_contents('C:\Users\othmane\workspace\ProjetLong\GestionnaireDesTaches\gtxml.xml', $gt->toXML());
//echo "\"" .$gt->toXML(). "\"";

?>