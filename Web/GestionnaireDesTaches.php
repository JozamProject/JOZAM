<?php

require_once('Board.php');
require_once('Projet.php');
require_once('Tache.php');
class GestionnaireDesTaches
{
	private $_utilisateur;
	private $_boards;
	private $_key ;
	
	public function __construct($utilisateur = "Utilisateur")
	{
		$this->setUtilisateur($utilisateur);
		$_key = 0;
		$_boards = array();
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
		if (isset($this->_boards[$this->_key])) {
			throw new KeyHasUseException("Key $this->_key already in use.");
		}
		else {
			$this->_boards[$this->_key] = $board;
			$this->_key++;
		}
	}
	
	public function toXML()
	{
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> \r\n";
		$xml .= "<gestionnaireDesTaches utilisateur=" . "\"" . $this->_utilisateur . "\">\r\n";
		foreach($this->_boards as $board){
			$xml .= $board->toXML(1);
		}
		$xml .= "<\gestionnaireDesTaches>";
		return $xml;
	}
		
}

$gt = new GestionnaireDesTaches("Othman");
$board1 = new Board("Maison");
$board2 = new Board("Boulot");
$projet11 = new Projet("projet11");
$projet12 = new Projet("projet12");
$projet111 = new Projet("projet111");
$projet21 = new Projet("projet21");
$tache11 = new tache("tache11");
$tache111 = new tache("tache111");
$projet11->addTache($tache11);
$projet111->addTache($tache111);
$projet11->addProjet($projet111);
$board1->addProjet($projet11);
$board1->addProjet($projet12);
$board2->addProjet($projet21);


$gt->addBoard($board1);
$gt->addBoard($board2);


echo $gt->getUtilisateur();
echo "\n toto \n";
//
file_put_contents('C:\Users\othmane\git\JOZAM\web_GestionnaireTaches\gtxml.xml', $gt->toXML());
//echo "\"" .$gt->toXML(). "\"";
echo "\nFin"

?>