<?php
namespace Calendar;
/**
 * A CalendarDuration is a utility object that provides 
 * a way to easily switch between a date interval and the number of seconds of a duration
 * and a user-friendly display of the duration.
 *
 * @since 1.0
 * @author Jaafar Bouayad <bouayad.jaafar@gmail.com>
 */
class CalendarDuration {
	/**
	 * The date interval.
	 *
	 * @var DateInterval
	 */
	private $dateInterval;
	
	/**
	 * The duration in seconds.
	 *
	 * @var string|integer
	 */
	private $nbSeconds;
	
	/**
	 * Initializes a new CalendarDuration.
	 *
	 * If no date interval is specified,
	 * the duration has to be set manually via setNbSeconds($nbSeconds).
	 *
	 * @param DateInterval $dateInterval
	 *        	The date interval.
	 */
	public function __construct($dateInterval = null) {
		if (is_null ( $dateInterval )) {
			$this->setNbSeconds ( 0 );
		} else {
			$this->setDateInterval ( $dateInterval );
		}
	}
	
	/**
	 * Sets the duration in seconds.
	 *
	 * @param string|integer $nbSeconds
	 *        	The duration in seconds.
	 *        	
	 * @return void
	 */
	public function setNbSeconds($nbSeconds) {
		$this->nbSeconds = $nbSeconds;
		$this->dateInterval = DateInterval::createFromDateString ( $nbSeconds . ' seconds' );
	}
	
	/**
	 * Returns the duration in seconds.
	 *
	 * @return string|integer
	 */
	public function getNbSeconds() {
		return $this->nbSeconds;
	}
	
	/**
	 * Sets the date interval.
	 *
	 * @param DateInterval $dateInterval
	 *        	The date interval.
	 *        	
	 * @return void
	 */
	public function setDateInterval($dateInterval) {
		$this->dateInterval = $dateInterval;
		$this->nbSeconds = $this->dateInterval_to_nbSeconds ();
	}
	
	/**
	 * Returns the date interval.
	 *
	 * @return DateInterval
	 */
	public function getDateInterval() {
		return $this->dateInterval;
	}
	
	/**
	 * Transforms the date interval into a number of seconds.
	 */
	private function dateInterval_to_nbSeconds() {
		$reference = new DateTimeImmutable ();
		$endTime = $reference->add ( $this->dateInterval );
		return $reference->getTimestamp () - $endTime->getTimestamp ();
	}
	
	/**
	 * Returns the number of days of the duration.
	 *
	 * @return string|integer
	 */
	public function floor_days() {
		return floor ( floor ( $this->getNbSeconds () / 3600 ) / 24 );
	}
	
	/**
	 * Returns the number of hours of the duration.
	 *
	 * @return string|integer
	 */
	public function floor_hours() {
		return floor ( $this->getNbSeconds () / 3600 ) % 24;
	}
	
	/**
	 * Returns the number of minutes of the duration.
	 *
	 * @return string|integer
	 */
	public function floor_minutes() {
		return ($this->getNbSeconds () / 60) % 60;
	}
	
	/**
	 * Returns a string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		// days
		$d = $this->floor_days ();
		$ds = ($d > 1) ? 's' : '';
		$days = ($d == 0) ? '' : sprintf ( '%d day%s', $d, $ds );
		
		// hours
		$h = $this->floor_hours ();
		$hs = ($h > 1) ? 's' : '';
		$hours = ($h == 0) ? '' : sprintf ( '%d hour%s', $h, $hs );
		
		// minutes
		$i = $this->floor_minutes ();
		$is = ($i > 1) ? 's' : '';
		$minutes = ($i == 0) ? '' : sprintf ( '%d minute%s', $i, $is );
		
		$return = sprintf ( '%s %s %s', $days, $hours, $minutes );
		return $return;
	}
	
	/**
	 * Tests if the duration is strictly positive.
	 *
	 * @return boolean TRUE if the duration is strictly positive, FALSE otherwise.
	 */
	public function is_strictly_positive() {
		return $this->nbSeconds > 0;
	}
}