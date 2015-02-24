<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
require_once ('CalendarDate.php');
require_once ('EventDate.php');
require_once ('CalendarHour.php');
class BusyEventDate {
	public static $WEEKDAYS = array (
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday' 
	);
	private $timeSlot;
	private $days;
	private $startHour;
	private $endHour;
	public function __construct($startHour, $endHour, $timeSlot, $days = null) {
		$this->setStartHour ( $startHour );
		$this->setEndHour ( $endHour );
		$this->setTimeSlot ( $timeSlot );
		$this->setDays ( is_null ( $days ) ? new ArrayCollection ( self::$WEEKDAYS ) : new ArrayCollection ( $days ) );
	}
	public function setTimeSlot($timeSlot) {
		$this->timeSlot = $timeSlot;
	}
	public function getTimeSlot() {
		return $this->timeSlot;
	}
	public function setDays($days) {
		$this->days = $days;
	}
	public function getDays() {
		return $this->days;
	}
	public function setStartHour($startHour) {
		$this->startHour = $startHour;
	}
	public function getStartHour() {
		return $this->startHour;
	}
	public function setEndHour($endHour) {
		$this->endHour = $endHour;
	}
	public function getEndHour() {
		return $this->endHour;
	}
	public function generate() {
		$busyEventDates = new ArrayCollection ();
		
		// busy event date start hour
		$startHour = $this->getStartHour ();
		$startHour_hour = $startHour->getHour ();
		$startHour_minute = $startHour->getMinute ();
		$startHour_second = $startHour->getSecond ();
		
		// busy event date end hour
		$endHour = $this->getEndHour ();
		$endHour_hour = $endHour->getHour ();
		$endHour_minute = $endHour->getMinute ();
		$endHour_second = $endHour->getSecond ();
		
		// time slot start date
		$timeSlot = $this->getTimeSlot ();
		$startDate = $timeSlot->getStartDate ();
		$startDate_year = $startDate->getYear ();
		$startDate_month = $startDate->getMonth ();
		$startDate_day = $startDate->getDay ();
		
		$j = ($startHour >= $endHour) ? 1 : 0;
		
		$floor_days = $timeSlot->duration ()->floor_days ();
		for($i = 0; $i < $floor_days; $i ++) {
			$busy_startDate = new CalendarDate ( $startDate_year, $startDate_month, $startDate_day + $i, $startHour_hour, $startHour_minute, $startHour_second );
			if ($this->getDays ()->contains ( $busy_startDate->format ( 'l' ) )) {
				$busy_endDate = new CalendarDate ( $startDate_year, $startDate_month, $startDate_day + $i + $j, $endHour_hour, $endHour_minute, $endHour_second );
				$busy_eventDate = new EventDate ( $busy_startDate, $busy_endDate );
				$busyEventDates->add ( $busy_eventDate );
			}
		}
		
		return $busyEventDates;
	}
}