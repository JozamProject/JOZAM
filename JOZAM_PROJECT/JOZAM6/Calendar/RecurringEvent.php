<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
require_once ('CalendarDate.php');
require_once ('CalendarEvent.php');
require_once ('CalendarTime.php');

/**
 * A RecurringEvent is a way to generate recurring events
 * such as weekends, lunch breaks, morning and afternoon recess breaks,
 * in a time slot so they can be added to a set of calendar events.
 *
 * @since 1.0
 * @author Jaafar Bouayad <bouayad.jaafar@gmail.com>
 */
class RecurringEvent {
	/**
	 * The days of the week.
	 *
	 * @var array
	 */
	public static $WEEKDAYS = array (
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday' 
	);
	
	/**
	 * The time slot during which the generated events will occur.
	 *
	 * @var EventDate
	 */
	private $timeSlot;
	
	/**
	 * The days of the week during which the generated events will occur.
	 *
	 * @var array
	 */
	private $days;
	
	/**
	 * The time at which the recurring event will start.
	 *
	 * @var CalendarTime
	 */
	private $startTime;
	
	/**
	 * The time at which the recurring event will end.
	 *
	 * @var CalendarTime
	 */
	private $endTime;
	
	/**
	 * Initializes a new RecurringEvent.
	 *
	 * @param CalendarTime $startTime
	 *        	The time at which the recurring event will start.
	 * @param CalendarTime $endTime
	 *        	The time at which the recurring event will end.
	 * @param EventDate $timeSlot
	 *        	The time slot during which the generated events will occur.
	 * @param array $days
	 *        	The days of the week during which the generated events will occur (all days of the week by default).
	 */
	public function __construct($startTime, $endTime, $timeSlot, $days = null) {
		$this->setstartTime ( $startTime );
		$this->setendTime ( $endTime );
		$this->setTimeSlot ( $timeSlot );
		$this->setDays ( is_null ( $days ) ? new ArrayCollection ( self::$WEEKDAYS ) : new ArrayCollection ( $days ) );
	}
	
	/**
	 * Sets the time slot during which the generated events will occur.
	 *
	 * @param EventDate $timeSlot
	 *        	The time slot during which the generated events will occur.
	 *        	
	 * @return void
	 */
	public function setTimeSlot($timeSlot) {
		$this->timeSlot = $timeSlot;
	}
	
	/**
	 * Returns the time slot during which the generated events will occur.
	 *
	 * @return EventDate
	 */
	public function getTimeSlot() {
		return $this->timeSlot;
	}
	
	/**
	 * Sets the days during which the generated events will occur.
	 *
	 * @param array $days
	 *        	The days during which the generated events will occur.
	 *        	
	 * @return void
	 */
	public function setDays($days) {
		$this->days = $days;
	}
	
	/**
	 * Returns the days during which the generated events will occur.
	 *
	 * @return array
	 */
	public function getDays() {
		return $this->days;
	}
	
	/**
	 * Sets the time at which the recurring event will start.
	 *
	 * @param CalendarTime $startTime
	 *        	The time at which the recurring event will start.
	 *        	
	 * @return void
	 */
	public function setstartTime($startTime) {
		$this->startTime = $startTime;
	}
	
	/**
	 * Returns the time at which the recurring event will start.
	 *
	 * @return CalendarTime
	 */
	public function getstartTime() {
		return $this->startTime;
	}
	
	/**
	 * Sets the time at which the recurring event will end.
	 *
	 * @param CalendarTime $endTime
	 *        	The time at which the recurring event will end.
	 *        	
	 * @return void
	 */
	public function setendTime($endTime) {
		$this->endTime = $endTime;
	}
	
	/**
	 * Returns the time at which the recurring event will end.
	 *
	 * @return CalendarTime
	 */
	public function getendTime() {
		return $this->endTime;
	}
	
	/**
	 * Generates all recurring events in accordance with specified start and end time, days, and time slot.
	 *
	 * @return ArrayCollection Returns an ArrayCollection of all the CalendarEvents generated.
	 */
	public function generate() {
		$busyEventDates = new ArrayCollection ();
		
		// busy event date start hour
		$startTime = $this->getstartTime ();
		$startTime_hour = $startTime->getHour ();
		$startTime_minute = $startTime->getMinute ();
		$startTime_second = $startTime->getSecond ();
		
		// busy event date end hour
		$endTime = $this->getendTime ();
		$endTime_hour = $endTime->getHour ();
		$endTime_minute = $endTime->getMinute ();
		$endTime_second = $endTime->getSecond ();
		
		// time slot start date
		$timeSlot = $this->getTimeSlot ();
		$startDate = $timeSlot->getStartDate ();
		$startDate_year = $startDate->getYear ();
		$startDate_month = $startDate->getMonth ();
		$startDate_day = $startDate->getDay ();
		
		$j = ($startTime >= $endTime) ? 1 : 0;
		
		$floor_days = $timeSlot->duration ()->floor_days ();
		for($i = 0; $i < $floor_days; $i ++) {
			$busy_startDate = new CalendarDate ( $startDate_year, $startDate_month, $startDate_day + $i, $startTime_hour, $startTime_minute, $startTime_second );
			if ($this->getDays ()->contains ( $busy_startDate->format ( 'l' ) )) {
				$busy_endDate = new CalendarDate ( $startDate_year, $startDate_month, $startDate_day + $i + $j, $endTime_hour, $endTime_minute, $endTime_second );
				$busy_eventDate = new CalendarEvent ( $busy_startDate, $busy_endDate );
				$busyEventDates->add ( $busy_eventDate );
			}
		}
		
		return $busyEventDates;
	}
}