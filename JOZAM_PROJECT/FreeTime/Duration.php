<?php
class Duration {
	private $dateInterval;
	private $nbSeconds;
	public function __construct($dateInterval = null) {
		if (is_null ( $dateInterval )) {
			$this->setNbSeconds ( 0 );
		} else {
			$this->setDateInterval ( $dateInterval );
		}
	}
	public function setNbSeconds($nbSeconds) {
		$this->nbSeconds = $nbSeconds;
		$this->dateInterval = DateInterval::createFromDateString ( $nbSeconds . ' seconds' );
	}
	public function getNbSeconds() {
		return $this->nbSeconds;
	}
	public function setDateInterval($dateInterval) {
		$this->dateInterval = $dateInterval;
		$this->nbSeconds = $this->dateInterval_to_nbSeconds ();
	}
	public function getDateInterval() {
		return $this->dateInterval;
	}
	private function dateInterval_to_nbSeconds() {
		$reference = new DateTimeImmutable ();
		$endTime = $reference->add ( $this->dateInterval );
		return $reference->getTimestamp () - $endTime->getTimestamp ();
	}
	public function floor_days() {
		return floor ( floor ( $this->getNbSeconds () / 3600 ) / 24 );
	}
	public function floor_hours() {
		return floor ( $this->getNbSeconds () / 3600 ) % 24;
	}
	public function floor_seconds() {
		return ($this->getNbSeconds () / 60) % 60;
	}
	public function __toString() {
		// days
		$d = $this->floor_days();
		$ds = ($d > 1) ? 's' : '';
		$days = ($d == 0) ? '' : sprintf ( '%d day%s', $d, $ds );

		// hours
		$h = $this->floor_hours();
		$hs = ($h > 1) ? 's' : '';
		$hours = ($h == 0) ? '' : sprintf ( '%d hour%s', $h, $hs );

		// minutes
		$i = $this->floor_seconds();
		$is = ($h > 1) ? 's' : '';
		$minutes = ($i == 0) ? '' : sprintf ( '%d minute%s', $i, $is );

		$return = sprintf ( '%s %s %s', $days, $hours, $minutes);
		return $return;
	}
	public function is_strictly_positive() {
		return $this->nbSeconds > 0;
	}
}