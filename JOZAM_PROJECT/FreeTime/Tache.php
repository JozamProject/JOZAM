<?php
class Tache {
	private $_nom;
	private $_priorite;
	private $_dateDebut;
	private $_dateFin;
	private $_description;
	private $_commentaire;
	private $_etat;
	private $_graphique;
	
public function __construct($nom = "A task", $priorite = "", $dateDebut = "", $dateFin = "", $description = "", $commentaire = "", $etat = "")
	{
		$this->setNom($nom);
		$this->setPriorite($priorite);
		$this->setDateDebut($dateDebut);
		$this->setDateFin($dateFin);
		$this->setDescription($description);
		$this->setCommentaire($commentaire);
		$this->setEtat($etat);
	
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

	public function setPriorite($priorite)
	{
		if(!is_string($priorite)){
			trigger_error(' Priorite ', E_USER_WARNING);
			return;
		}
		$this->_priorite = $priorite;
	}
	
	public function getPriorite()
	{
		return $this->_priorite;
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

	
	public function toXML($nbTabs)
	{
		$xml = "";
		for ($i = 1; $i <= $nbTabs; $i++) {
			$xml .= "\t";
		}
		$xml .= "<tache nom="         . "\"" . $this->_nom         . "\" "
				       ."priorite="      . "\"" . $this->_priorite      . "\" "
				       ."dateDebut="   . "\"" . $this->_dateDebut   . "\" "
				       ."dateFin="     . "\"" . $this->_dateFin     . "\" "
				       ."description=" . "\"" . $this->_description . "\" "
				       ."commentaire=" . "\"" . $this->_commentaire . "\" "
				       ."etat="        . "\"" . $this->_etat        . "\" "
				 .">\r\n";
		for ($i = 1; $i <= $nbTabs; $i++) {
			$xml .= "\t";
		}
		$xml .= "</tache>\r\n";
		return $xml;
	}
}
?>