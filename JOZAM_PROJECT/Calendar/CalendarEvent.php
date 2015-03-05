<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
require_once ('CalendarDuration.php');

/**
 * A CalendarEvent is a calendar event with
 * a start date and an end date.
 *
 * @since 1.0
 * @author Jaafar Bouayad <bouayad.jaafar@gmail.com>
 */
class CalendarEvent {
	/**
	 * The start date.
	 *
	 * @var CalendarDate
	 */
	private $startDate;
	
	/**
	 * The end date.
	 *
	 * @var CalendarDate
	 */
	private $endDate;
	
	/**
	 * Initializes a new CalendarEvent.
	 *
	 * @param CalendarDate $startDate
	 *        	The start date.
	 * @param CalendarDate $endDate
	 *        	The end date.
	 */
	public function __construct($startDate, $endDate) {
		$null_duration_event_exception = $this->setCalendarEvent ( $startDate, $endDate );
		if ($null_duration_event_exception) {
			throw new Exception ( 'ZERO DURATION EVENT EXCEPTION : event has the same start date and end date.' );
		}
	}
	
	/**
	 * Sets the calendar event.
	 *
	 * @param CalendarDate $startDate
	 *        	The start date.
	 * @param CalendarDate $endDate
	 *        	The end date.
	 *        	
	 * @return void
	 */
	public function setCalendarEvent($startDate, $endDate) {
		$null_duration_event_exception = ($startDate == $endDate);
		$right_order = $startDate < $endDate;
		$this->setStartDate ( $right_order ? $startDate : $endDate );
		$this->setEndDate ( $right_order ? $endDate : $startDate );
		return $null_duration_event_exception;
	}
	
	/**
	 * Sets the start date.
	 *
	 * @param CalendarDate $startDate
	 *        	The start date.
	 *        	
	 * @return void
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}
	
	/**
	 * Returns the start date.
	 *
	 * @return CalendarDate
	 */
	public function getStartDate() {
		return $this->startDate;
	}
	
	/**
	 * Sets the end date.
	 *
	 * @param CalendarDate $endDate
	 *        	The end date.
	 *        	
	 * @return void
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}
	
	/**
	 * Returns the end date.
	 *
	 * @return CalendarDate
	 */
	public function getEndDate() {
		return $this->endDate;
	}
	
	/**
	 * Returns a string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		return '{from ' . $this->getStartDate () . ' to ' . $this->getEndDate () . '}';
	}
	
	/**
	 * Returns the duration of the event.
	 *
	 * @return string
	 */
	public function duration() {
		$duration = new CalendarDuration ( $this->getEndDate ()->diff ( $this->getStartDate () ) );
		return $duration;
	}
	
	/**
	 * Tests if the duration of the event is zero.
	 *
	 * @return boolean TRUE if the duration of the event is zero, FALSE otherwise.
	 */
	public function duration_is_zero() {
		return $this->duration ()->getNbSeconds () == 0;
	}
	
	/**
	 * Start date sort function.
	 *
	 * @param CalendarEvent $ce0
	 *        	A calendar event.
	 * @param CalendarEvent $ce1
	 *        	A calendar event.
	 *        	
	 * @return integer
	 */
	public static function startDateSort($ce0, $ce1) {
		$sd0 = $ce0->getStartDate ();
		$sd1 = $ce1->getStartDate ();
		$return = $ce0 < $ce1 ? - 1 : ($ce0 > $ce1 ? 1 : 0);
		return $return;
	}
	
	/**
	 * End date sort function.
	 *
	 * @param CalendarEvent $ce0
	 *        	A calendar event.
	 * @param CalendarEvent $ce1
	 *        	A calendar event.
	 *        	
	 * @return integer
	 */
	public static function endDateSort($ce0, $ce1) {
		$sd0 = $ce0->getEndDate ();
		$sd1 = $ce1->getEndDate ();
		$return = $ce0 < $ce1 ? - 1 : ($ce0 > $ce1 ? 1 : 0);
		return $return;
	}
	
	/**
	 * Merge the object with another CalendarEvent.
	 *
	 * Precondition : calendar events must be mergeable.
	 *
	 * @param CalendarEvent $calendarEvent
	 *        	A calendar event.
	 *        	
	 * @return void
	 */
	public function merge($calendarEvent) {
		$this_startDate = $this->getStartDate ();
		$this_endDate = $this->getEndDate ();
		$calendarEvent_startDate = $calendarEvent->getStartDate ();
		$calendarEvent_endDate = $calendarEvent->getEndDate ();
		$mergeable = $this->mergeable ( $calendarEvent );
		if (! $mergeable) {
			throw new Exception ( 'NO MERGE EXCEPTION : the events can\'t be merged.' );
		} else {
			$startDate = ($this_startDate < $calendarEvent_startDate) ? $this_startDate : $calendarEvent_startDate;
			$endDate = ($this_endDate > $calendarEvent_endDate) ? $this_endDate : $calendarEvent_endDate;
			$this->setStartDate ( $startDate );
			$this->setEndDate ( $endDate );
		}
	}
	
	/**
	 * Tests if the events are mergeable.
	 *
	 * @param CalendarEvent $calendarEvent
	 *        	A calendar event.
	 *        	
	 * @return boolean TRUE if mergeable, FALSE otherwise.
	 */
	public function mergeable($calendarEvent) {
		$mergeable = ($this != $calendarEvent);
		
		// attributes
		$this_startDate = $this->getStartDate ();
		$this_endDate = $this->getEndDate ();
		$calendarEvent_startDate = $calendarEvent->getStartDate ();
		$calendarEvent_endDate = $calendarEvent->getEndDate ();
		
		// conditions
		$mergeable_this = ($this_endDate >= $calendarEvent_startDate) && ($this_endDate <= $calendarEvent_endDate);
		$mergeable_eventDate = ($calendarEvent_endDate >= $this_startDate) && ($calendarEvent_endDate <= $this_endDate);
		$mergeable &= ($mergeable_this || $mergeable_eventDate);
		
		return $mergeable;
	}
	
	/**
	 * Sorts the calendar events.
	 *
	 * @param ArrayCollection $calendarEvents
	 *        	An ArrayCollection of calendar events.
	 * @param boolean $order_by_startDates
	 *        	TRUE if ordered by start dates, FALSE if ordered by end dates.
	 *        	
	 * @return void
	 */
	public static function sort($calendarEvents, $order_by_startDates = true) {
		$order_by_startDates ? $calendarEvents->sort ( 'CalendarEvent::startDateSort' ) : $calendarEvents->sort ( 'CalendarEvent::endDateSort' );
		$previous_ed = $calendarEvents->first ();
		$to_be_removed_eventDates = new ArrayCollection ();
		foreach ( $calendarEvents as $ed ) {
			$mergeable = $ed->mergeable ( $previous_ed );
			if ($mergeable) {
				// debug
				// echo '$previous_ed :<br>' . $previous_ed . '<br>$ed :<br>' . $ed . '<br><br>';
				$to_be_removed_eventDates->add ( $previous_ed );
				$ed->merge ( $previous_ed );
				// debug
				// echo '$ed after merge :<br>' . $ed . '<br><br>';
			}
			$previous_ed = $ed;
		}
		// debug
		// echo 'To be removed :<br>' . $to_be_removed_eventDates . '<br><br>';
		
		$calendarEvents->removeAllElements ( $to_be_removed_eventDates );
		
		// debug
		// echo 'After removal :<br>' . $calendarEvents . '<br><br>';
	}
	
	/**
	 * Tests if two calendar events are equal.
	 *
	 * @param CalendarEvent $calendarEvent
	 *        	A calendar event.
	 *        	
	 * @return boolean TRUE if events are equal, FALSE otherwise.
	 */
	public function is_equal($calendarEvent) {
		$startDate_is_equal = ($this->getStartDate () == $calendarEvent->getStartDate ());
		$endDate_is_equal = ($this->getEndDate () == $calendarEvent->getEndDate ());
		return $startDate_is_equal && $endDate_is_equal;
	}
	
	/**
	 * Removes the calendar event, thus generating 0, 1, or 2 calendar events.
	 *
	 * @param CalendarEvent $calendarEvent
	 *        	A calendar event.
	 *        	
	 * @return ArrayCollection
	 */
	public function removeCalendarEvent($calendarEvent) {
		// debug
		// echo '------------------------------------------removeCalendarEvent <br>' . $calendarEvent . '<br><br>from<br><br>' . $this . '<br><br>';
		$new_calendarEvents = new ArrayCollection ();
		$same_events = $this->is_equal ( $calendarEvent );
		
		if (! $same_events) {
			
			// first event
			$startDate_is_after_start = $calendarEvent->getStartDate () >= $this->getStartDate ();
			$startDate_is_before_end = $calendarEvent->getStartDate ()->getTimestamp () <= $this->getEndDate ()->getTimestamp ();
			$startDate_is_viable = $startDate_is_after_start && $startDate_is_before_end;
			
			// debug
			// echo '$startDate_is_after_start'.var_dump($startDate_is_after_start).'<br><br>';
			// echo '$startDate_is_before_end'.var_dump($startDate_is_before_end).'<br><br>';
			// echo '$startDate_is_viable'.var_dump($startDate_is_viable).'<br><br>';
			
			if ($startDate_is_viable) {
				$calendarEvent_0 = new CalendarEvent ( $this->getStartDate (), $calendarEvent->getStartDate () );
				$duration_is_zero_0 = $calendarEvent_0->duration_is_zero ();
				// echo $duration_is_zero_0 ? $calendarEvent_0.'DURATION IS ZERO 0'.var_dump().'<br><br>' : '';
				if (! $duration_is_zero_0) {
					$new_calendarEvents->add ( $calendarEvent_0 );
				}
			}
			
			// second event
			$endDate_is_before_end = $calendarEvent->getEndDate () <= $this->getEndDate ();
			$endDate_is_after_start = $calendarEvent->getEndDate () >= $this->getStartDate ();
			$endDate_is_viable = $endDate_is_before_end && $endDate_is_after_start;
			if ($endDate_is_viable) {
				// echo 'I NEVER GO THERE 1 EVENT DATE 1<br>';
				$calendarEvent_1 = new CalendarEvent ( $calendarEvent->getEndDate (), $this->getEndDate () );
				$duration_is_zero_1 = $calendarEvent_1->duration_is_zero ();
				// echo $calendarEvent_1->duration ()->getNbSeconds () . ' << duration event date 1<br>';
				// echo '^^^^is duration 0 ?' . var_dump ( $duration_is_zero_1 );
				if (! $duration_is_zero_1) {
					$new_calendarEvents->add ( $calendarEvent_1 );
				}
			}
			
			if ($new_calendarEvents->isEmpty ()) {
				$new_calendarEvents->add ( $this );
			}
		}
		
		// debug
		// echo $calendarEvent->getStartDate ()->getDay () == '08' ? 'results :<br>' . $new_calendarEvents . '<br><br>';
		
		return $new_calendarEvents;
	}
	
	/**
	 * Removes all the calendar events from the object.
	 *
	 * Precondition : calendar events must be sorted.
	 *
	 * @param ArrayCollection $calendarEvents
	 *        	ArrayCollection of calendar events.
	 *        	
	 * @return ArrayCollection
	 */
	public function removeAllCalendarEvents($calendarEvents) {
		// debug
		// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\this is this : <br>' . $this . '<br><br>';
		// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\remove event dates : <br>' . $calendarEvents . '<br><br>';
		$splits = new ArrayCollection ();
		$splits_queue = new ArrayCollection ();
		if (! $calendarEvents->isEmpty ()) {
			$splits_to_be_added = $this->removeCalendarEvent ( $calendarEvents->first () );
			// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\splits to be added : <br>' . $splits_to_be_added . '<br><br>';
			if ($splits_to_be_added->count () == 2) {
				// echo '------first split : <br>' . $splits_to_be_added->first () . '<br><br>';
				// echo '------second split : <br>' . $splits_to_be_added->last () . '<br><br>';
				$splits->add ( $splits_to_be_added->first () );
				$calendarEvents_queue = $calendarEvents->queue ();
				$splits_queue = $splits_to_be_added->last ()->removeAllCalendarEvents ( $calendarEvents_queue );
				// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\splits queue 2 : <br>' . $splits_queue . '<br><br>';
			} else {
				if ($splits_to_be_added->count () == 1) {
					$calendarEvents_queue = $calendarEvents->queue ();
					$splits_queue = $splits_to_be_added->first ()->removeAllCalendarEvents ( $calendarEvents_queue );
					// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\splits queue 1 : <br>' . $splits_queue . '<br><br>';
				} else {
					// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\splits queue 0 : <br>' . $splits_queue . '<br><br>';
				}
			}
			$splits->addAll ( $splits_queue );
		} else {
			$splits->add ( $this );
		}
		// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\splits : <br>' . $splits . '<br><br>';
		return $splits;
	}
	
	/**
	 * Returns an ICS event format of this object.
	 *
	 * @param string $summary
	 *        	Summary of the event.
	 *        	
	 * @return string
	 */
	public function __toIcsEvent($summary) {
		return 'BEGIN:VEVENT
DTSTART:' . $this->getStartDate ()->__toIcsDate () . '
DTEND:' . $this->getEndDate ()->__toIcsDate () . '
SUMMARY:' . $summary . '
END:VEVENT

';
	}
	
	/**
	 * Returns an ICS calendar string containing all the calendar events.
	 *
	 * @param ArrayCollection $calendarEvents
	 *        	ArrayCollection of calendar events.
	 * @param string $summary
	 *        	Summary of all the events.
	 *        	
	 * @return string
	 */
	public static function __toIcsCalendar($calendarEvents, $summary) {
		$return = 'BEGIN:VCALENDAR
METHOD:PUBLISH
X-WR-CALNAME:' . $summary . '

';
		foreach ( $calendarEvents as $ed ) {
			$return .= $ed->__toIcsEvent ( $summary );
		}
		$return .= 'END:VCALENDAR';
		return $return;
	}
	
	/**
	 * Adds an ArrayCollection of calendar events to another ArrayCollection of calendar events.
	 *
	 * Postcondition : unsorted, possibly overlapping calendar events.
	 *
	 * @param ArrayCollection $calendarEvents
	 *        	ArrayCollection of calendar events.
	 * @param ArrayCollection $to_be_added_calendarEvents
	 *        	ArrayCollection of calendar events to be added to $calendarEvents.
	 *        	
	 * @return string
	 */
	static function add($calendarEvents, $to_be_added_calendarEvents) {
		$clone = clone $to_be_added_calendarEvents;
		$calendarEvents->addAll ( $clone );
	}
	
	/**
	 * Generates a Simple XML Element string matching this object.
	 *
	 * @param String $name
	 *        	Name of the Simple XML Element.
	 *        	
	 * @return String Simple XML Element string matching this object.
	 */
	public function __toXML($name = 'calendarEvent') {
		$xml_string = '<' . $name . '>
';
		
		$xml_string .= $this->getStartDate ()->__toXML ( 'startDate' );
		$xml_string .= $this->getEndDate ()->__toXML ( 'endDate' );
		
		$xml_string .= '</' . $name . '>
';
		
		return $xml_string;
	}
	
	/**
	 * Generates a CalendarEvent matching a SimpleXMLElement.
	 *
	 * @param SimpleXMLElement $calendarEvent
	 *        	A Simple XML Element.
	 *        	
	 * @return CalendarEvent CalendarEvent matching the Simple XML Element.
	 */
	public static function XML_to_CalendarEvent($calendarEvent) {
		$startDate = CalendarDate::XML_to_CalendarDate ( $calendarEvent->startDate );
		$endDate = CalendarDate::XML_to_CalendarDate ( $calendarEvent->endDate );
		return new CalendarEvent ( $startDate, $endDate );
	}
}