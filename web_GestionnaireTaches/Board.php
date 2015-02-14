<?php
class Board {
	private $_nom;
	private $_projets;
	private $_graphique;
	private $_key;
	
public function __construct($nom)
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
		if (isset($this->items[$key])) {
			throw new KeyHasUseException("Key $key already in use.");
		}
		else {
			$this->_projets[$key++] = $projet;
		}
	}
	
	public function toXML()
	{
		$xml = "";
		$xml .= "<board nom=" . "\"" . $this->_nom . "\">\r\n";
		/*foreach($this->_projets as $projet){
			$xml += $projet->toXML();
		}*/
		$xml .= "<\board>\r\n";
		return $xml;
	}
}