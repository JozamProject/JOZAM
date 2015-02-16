<?php
require_once('Projet.php');
class Board {
	private $_nom;
	private $_projets = array();
	private $_graphique;
	private $_key;
	
public function __construct($nom = "my board")
	{
		$this->setNom($nom);
	}
	
	public function setNom($nom)
	{
		if(!is_string($nom)){
			trigger_error('Entrer une chaine de caractère comme nom d\'utilisateur', E_USER_WARNING);
			return;
		}
		$this->_nom = $nom;
	}
	
	public function getNom()
	{
		return $this->_nom;
	}
	
	public function setProjets($projets)
	{
		if(!is_array($projets)){
			trigger_error('Entrer un tableau de boards', E_USER_WARNING);
			return;
		}
		$_projets = $projets;
	}
	
	public function getProjets()
	{
		return $this->_projets;
	}
	
	public function addProjet($projet) 
	{
		if (isset($this->items[$this->_key])) {
			throw new KeyHasUseException("Key $this->key already in use.");
		}
		else {
			$this->_projets[$this->_key++] = $projet;
		}
	}
	
	public function toXML($nbTabs)
	{
		$xml = "";
		for ($i = 1; $i <= $nbTabs; $i++) {
			$xml .= "\t";
		}
		$xml .= "<board nom=" . "\"" . $this->_nom . "\">\r\n";
		foreach($this->_projets as $projet){
			$xml .= $projet->toXML($nbTabs+1);
		}
		for ($i = 1; $i <= $nbTabs; $i++) {
			$xml .= "\t";
		}
		$xml .= "<\board>\r\n";
		return $xml;
	}
}
?>