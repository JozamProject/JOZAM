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
	 * Name of the recurring event.
	 *
	 * @var string
	 */
	private $name;
	
	/**
	 * The time slot during which the generated events will occur.
	 *
	 * @var CalendarEvent
	 */
	private $timeSlot;
	
	/**
	 * The days of the week during which the generated events will occur.
	 *
	 * @var ArrayCollection
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
	 * @param CalendarEvent $timeSlot
	 *        	The time slot during which the generated events will occur.
	 * @param array $days
	 *        	The days of the week during which the generated events will occur (all days of the week by default).
	 */
	public function __construct($name, $startTime, $endTime, $timeSlot, $days = null) {
		$this->setName ( $name );
		$this->setStartTime ( $startTime );
		$this->setEndTime ( $endTime );
		$this->setTimeSlot ( $timeSlot );
		$this->setDays ( (is_null ( $days ) || empty ( $days )) ? new ArrayCollection ( self::$WEEKDAYS ) : new ArrayCollection ( $days ) );
	}
	
	/**
	 * Sets the name of the recurring event.
	 *
	 * @param string $name
	 *        	Name of the recurring event.
	 *        	
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Returns the name of the recurring event.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the time slot during which the generated events will occur.
	 *
	 * @param CalendarEvent $timeSlot
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
	 * @return CalendarEvent
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
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}
	
	/**
	 * Returns the time at which the recurring event will start.
	 *
	 * @return CalendarTime
	 */
	public function getStartTime() {
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
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}
	
	/**
	 * Returns the time at which the recurring event will end.
	 *
	 * @return CalendarTime
	 */
	public function getEndTime() {
		return $this->endTime;
	}
	
	/**
	 * Generates all recurring events in accordance with specified start and end time, days, and time slot.
	 *
	 * @return ArrayCollection Returns an ArrayCollection of all the CalendarEvents generated.
	 */
	public function generate() {
		$busyEvents = new ArrayCollection ();
		
		// start time
		$startTime = $this->getStartTime ();
		$startTime_hour = $startTime->getHour ();
		$startTime_minute = $startTime->getMinute ();
		$startTime_second = $startTime->getSecond ();
		
		// end time
		$endTime = $this->getEndTime ();
		$endTime_hour = $endTime->getHour ();
		$endTime_minute = $endTime->getMinute ();
		$endTime_second = $endTime->getSecond ();
		
		// time slot start date
		$timeSlot = $this->getTimeSlot ();
		$startDate = $timeSlot->getStartDate ();
		$startDate_year = $startDate->getYear ();
		$startDate_month = $startDate->getMonth ();
		$startDate_day = $startDate->getDay ();
		
		// days
		$days = $this->getDays ();
		
		$j = ($startTime->is_greater_or_equal($endTime)) ? 1 : 0;
		
		//debug
		//echo 'j = ' . $j . ' $startTime : '. $startTime. ' $endTime : '. $endTime. '<br><br>';
		
		$floor_days = $timeSlot->duration ()->floor_days ();
		
		for($i = - 1; $i < $floor_days; $i ++) {
			$current_startDate = new CalendarDate ( $startDate_year, $startDate_month, $startDate_day + $i, $startTime_hour, $startTime_minute, $startTime_second );
			// $current_startDate_next_day = clone $current_startDate;
			// $current_startDate_next_day->modify('tomorrow');
			$add_condition = $days->contains ( $current_startDate->format ( 'l' ) );
			// || (($j == 1) && $days->contains ( $current_startDate_next_day->format ( 'l' ) ))
			
			if ($add_condition) {
				$current_endDate = new CalendarDate ( $startDate_year, $startDate_month, $startDate_day + $i + $j, $endTime_hour, $endTime_minute, $endTime_second );
				$current_event = new CalendarEvent ( $current_startDate, $current_endDate );
				$busyEvents->add ( $current_event );
			}
		}
		
		return $busyEvents;
	}
	
	/**
	 * Maps generate function to an array collection of recurring events.
	 *
	 * @param ArrayCollection $recurringEvents
	 *        	Recurring events.
	 *        	
	 * @return ArrayCollection ArrayCollection of generated events from all recurring events.
	 */
	public static function map_generate($recurringEvents) {
		//debug
		//echo 'map_generate recurring events :<br>' . $recurringEvents . '<br><br>';
		$generate_function = function ($recurringEvent) {
			return $recurringEvent->generate ();
		};
		$recurringEvents_generated = new ArrayCollection ( array_map ( $generate_function, $recurringEvents->toArray () ) );
		return $recurringEvents_generated;
	}
	
	/**
	 * Returns a string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		$return = $this->getName () . ' : every ';
		foreach ( $this->getDays () as $d ) {
			$return .= $d . ', ';
		}
		$return .= '<br>from ' . $this->getStartTime () . ' to ' . $this->getEndTime ();
		$return .= '<br>during this time slot : ' . $this->getTimeSlot ();
		
		return $return;
	}
	
	/**
	 * Generates a Simple XML Element string matching this object.
	 *
	 * @param String $name
	 *        	Name of the Simple XML Element.
	 *        	
	 * @return String Simple XML Element string matching this object.
	 */
	public function __toXML($name = 'recurringEvent') {
		$xml_string = '<' . $name . ' name="' . $this->getName () . '" days = "' . str_replace ( '<br>', '', $this->getDays () ) . '">
';
		
		$xml_string .= $this->getStartTime ()->__toXML ( 'startTime' );
		$xml_string .= $this->getEndTime ()->__toXML ( 'endTime' );
		$xml_string .= $this->getTimeSlot ()->__toXML ( 'timeSlot' );
		
		$xml_string .= '</' . $name . '>
';
		
		return $xml_string;
	}
	
	/**
	 * Generates a RecurringEvent matching a SimpleXMLElement.
	 *
	 * @param String $recurringEvent
	 *        	String of the xml file.
	 *        	
	 * @return RecurringEvent RecurringEvent matching the Simple XML Element.
	 */
	public static function XML_to_RecurringEvent($recurringEvent) {
		$recurringEvent = new SimpleXMLElement ( $recurringEvent );
		$name = $recurringEvent ['name'];
		$startTime = CalendarTime::XML_to_CalendarTime ( $recurringEvent->startTime );
		$endTime = CalendarTime::XML_to_CalendarTime ( $recurringEvent->endTime );
		$timeSlot = CalendarEvent::XML_to_CalendarEvent ( $recurringEvent->timeSlot );
		$days = explode ( ', ', substr ( $recurringEvent ['days'], 1, - 1 ) );
		return new RecurringEvent ( $name, $startTime, $endTime, $timeSlot, $days );
	}
}