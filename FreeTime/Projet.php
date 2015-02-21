<?php
require_once('Projet.php');
class Projet {
	private $_nom;
	private $_equipe;
	private $_dateDebut;
	private $_dateFin;
	private $_description;
	private $_commentaire;
	private $_etat;
	private $_projets = array();
	private $_taches = array();
	private $_graphique;
	private $_key1;    // clé pour les projets
	private $_key2;    // clé pour les taches
	
public function __construct($nom = "my project", $equipe = "", $dateDebut = "", $dateFin = "", $description = "", $commentaire = "", $etat = "")
	{
		$this->setNom($nom);
		$this->setEquipe($equipe);
		$this->setDateDebut($dateDebut);
		$this->setDateFin($dateFin);
		$this->setDescription($description);
		$this->setCommentaire($commentaire);
		$this->setEtat($etat);
		$_projets = array();
		$_taches = array();
		$this->_key1 = 0;
		$this->_key2 = 0;
	
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

	public function setEquipe($equipe)
	{
		if(!is_string($equipe)){
			trigger_error(' Equipe ', E_USER_WARNING);
			return;
		}
		$this->_equipe = $equipe;
	}
	
	public function getEquipe()
	{
		return $this->_equipe;
	}

	public function setDateDebut($dateDebut)
	{
		if(!is_string($dateDebut)){
			trigger_error('Entrer date de debut de projet', E_USER_WARNING);
			return;
		}
		$this->_dateDebut = $dateDebut;
	}
	
	public function getDateDebut()
	{
		return $this->_dateDebut;
	}
	
	public function setDateFin($dateFin)
	{
		if(!is_string($dateFin)){
			trigger_error('Entrer date de fin de projet', E_USER_WARNING);
			return;
		}
		$this->_datefin = $dateFin;
	}
	
	public function getDateFin()
	{
		return $this->_dateFin;
	}
	
	public function setDescription($description)
	{
		if(!is_string($description)){
			trigger_error('Entrer la description ', E_USER_WARNING);
			return;
		}
		$this->_description = $description;
	}
	
	public function getDescription()
	{
		return $this->_description;
	}
	
	public function setCommentaire($commentaire)
	{
		if(!is_string($commentaire)){
			trigger_error('Entrer un commentaire ', E_USER_WARNING);
			return;
		}
		$this->_commentaire = $commentaire;
	}
	
	public function getCommentaire()
	{
		return $this->_commentaire;
	}
	
	public function setEtat($etat)
	{
		if(!is_string($etat)){
			trigger_error('Entrer l\'etat du projet', E_USER_WARNING);
			return;
		}
		$this->_etat = $etat;
	}
	
	public function getEtat()
	{
		return $this->_etat;
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
	
	public function setTaches($taches)
	{
		if(!is_array($taches)){
			trigger_error('Entrer un tableau de taches', E_USER_WARNING);
			return;
		}
		$_taches = $taches;
	}
	
	public function getTaches()
	{
		return $this->_taches;
	}
	
	public function addProjet($projet) 
	{
		if (isset($this->_projets[$this->_key1])) {
			throw new KeyHasUseException("Key $this->_key1 already in use.");
		}
		else {
			$this->_projets[$this->_key1++] = $projet;
		}
	}
	
	public function addTache($tache)
	{
		if (isset($this->_taches[$this->_key2])) {
			throw new KeyHasUseException("Key $this->_key2 already in use.");
		}
		else {
			$this->_taches[$this->_key2++] = $tache;
		}
	}
	
	public function toXML($nbTabs)
	{
		$xml = "";
		for ($i = 1; $i <= $nbTabs; $i++) {
			$xml .= "\t";
		}
		$xml .= "<projet nom="         . "\"" . $this->_nom         . "\" "
				       ."equipe="      . "\"" . $this->_equipe      . "\" "
				       ."dateDebut="   . "\"" . $this->_dateDebut   . "\" "
				       ."dateFin="     . "\"" . $this->_dateFin     . "\" "
				       ."description=" . "\"" . $this->_description . "\" "
				       ."commentaire=" . "\"" . $this->_commentaire . "\" "
				       ."etat="        . "\"" . $this->_etat        . "\" "
				 .">\r\n";
		foreach($this->_projets as $projet){
			$xml .= $projet->toXML($nbTabs+1);
		}
		foreach($this->_taches as $tache){
			$xml .= $tache->toXML($nbTabs+1);
		}
		for ($i = 1; $i <= $nbTabs; $i++) {
			$xml .= "\t";
		}
		$xml .= "</projet>\r\n";
		return $xml;
	}
}
?>