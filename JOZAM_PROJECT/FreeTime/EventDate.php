<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
require_once ('Duration.php');
class EventDate {
	private $startDate;
	private $endDate;
	public function __construct($startDate, $endDate) {
		$this->setEventDate ( $startDate, $endDate );
	}
	public function setEventDate($startDate, $endDate) {
		$null_duration_event_exception = ($startDate == $endDate);
		if ($null_duration_event_exception) {
			// throw new Exception ( 'ZERO DURATION EVENT EXCEPTION : event has the same start date and end date : ' . $this . '<br><br>' );
			echo '!!! Duration of date event is zero !!!<br>';
		}
		$right_order = $startDate < $endDate;
		$this->setStartDate ( $right_order ? $startDate : $endDate );
		$this->setEndDate ( $right_order ? $endDate : $startDate );
	}
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}
	public function getStartDate() {
		return $this->startDate;
	}
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}
	public function getEndDate() {
		return $this->endDate;
	}
	public function __toString() {
		return '{from ' . $this->getStartDate () . ' to ' . $this->getEndDate () . '}';
	}
	public function duration() {
		$duration = new Duration ( $this->getEndDate ()->diff ( $this->getStartDate () ) );
		return $duration;
	}
	public function duration_is_zero() {
		// echo $this->duration()->getNbSeconds().'<br> KAPPA <br>';
		// echo var_dump($this->duration()->getNbSeconds() == 0).'KAPPA';
		return $this->duration ()->getNbSeconds () == 0;
	}
	public static function startDateSort($ed0, $ed1) {
		$sd0 = $ed0->getStartDate ();
		$sd1 = $ed1->getStartDate ();
		$return = $ed0 < $ed1 ? - 1 : ($ed0 > $ed1 ? 1 : 0);
		return $return;
	}
	public static function endDateSort($ed0, $ed1) {
		$sd0 = $ed0->getEndDate ();
		$sd1 = $ed1->getEndDate ();
		$return = $ed0 < $ed1 ? - 1 : ($ed0 > $ed1 ? 1 : 0);
		return $return;
	}
	// pre-condition : event dates intersect
	public function merge($eventDate) {
		$this_startDate = $this->getStartDate ();
		$this_endDate = $this->getEndDate ();
		$eventDate_startDate = $eventDate->getStartDate ();
		$eventDate_endDate = $eventDate->getEndDate ();
		$mergeable = $this->mergeable ( $eventDate );
		if (! $mergeable) {
			echo 'NO MERGE EXCEPTION :' . $this . '<br>' . $eventDate . '<br><br>';
		} else {
			$startDate = ($this_startDate < $eventDate_startDate) ? $this_startDate : $eventDate_startDate;
			$endDate = ($this_endDate > $eventDate_endDate) ? $this_endDate : $eventDate_endDate;
			$this->setStartDate ( $startDate );
			$this->setEndDate ( $endDate );
		}
	}
	public function mergeable($eventDate) {
		$mergeable = ($this != $eventDate);
		
		// attributes
		$this_startDate = $this->getStartDate ();
		$this_endDate = $this->getEndDate ();
		$eventDate_startDate = $eventDate->getStartDate ();
		$eventDate_endDate = $eventDate->getEndDate ();
		
		// conditions
		$mergeable_this = ($this_endDate >= $eventDate_startDate) && ($this_endDate <= $eventDate_endDate);
		$mergeable_eventDate = ($eventDate_endDate >= $this_startDate) && ($eventDate_endDate <= $this_endDate);
		$mergeable &= ($mergeable_this || $mergeable_eventDate);
		
		return $mergeable;
	}
	public static function sort($eventDates, $order_by_startDates = true) {
		$order_by_startDates ? $eventDates->sort ( 'EventDate::startDateSort' ) : $eventDates->sort ( 'EventDate::endDateSort' );
		$previous_ed = $eventDates->first ();
		$to_be_removed_eventDates = new ArrayCollection ();
		foreach ( $eventDates as $ed ) {
			$mergeable = $ed->mergeable ( $previous_ed );
			if ($mergeable) {
				// debug
				//echo '$previous_ed :<br>' . $previous_ed . '<br>$ed :<br>' . $ed . '<br><br>';
				$to_be_removed_eventDates->add ( $previous_ed );
				$ed->merge ( $previous_ed );
				//debug
				//echo '$ed after merge :<br>' . $ed . '<br><br>';
			}
			$previous_ed = $ed;
		}
		// debug 
		//echo 'To be removed :<br>' . $to_be_removed_eventDates . '<br><br>';
		
		// debug
		$eventDates->removeAllElements ( $to_be_removed_eventDates );
		
		// debug
		// echo 'After removal :<br>' . $eventDates . '<br><br>';
	}
	public function is_equal($eventDate) {
		$startDate_is_equal = ($this->getStartDate () == $eventDate->getStartDate ());
		$endDate_is_equal = ($this->getEndDate () == $eventDate->getEndDate ());
		return $startDate_is_equal && $endDate_is_equal;
	}
	public function removeEventDate($eventDate) {
		// debug
		echo // true
$eventDate->getStartDate ()->getDay () == '08' ? '------------------------------------------removeEventDate <br>' . $eventDate . '<br><br>from<br><br>' . $this . '<br><br>' : '';
		
		$new_eventDates = new ArrayCollection ();
		$same_events = $this->is_equal ( $eventDate );
		
		if (! $same_events) {
			
			// first event
			$startDate_is_after_start = $eventDate->getStartDate () >= $this->getStartDate ();
			$startDate_is_before_end = $eventDate->getStartDate ()->getTimestamp () <= $this->getEndDate ()->getTimestamp ();
			$startDate_is_viable = $startDate_is_after_start && $startDate_is_before_end;
			
			// debug
			
			// echo '$startDate_is_after_start'.var_dump($startDate_is_after_start).'<br><br>';
			// echo '$startDate_is_before_end'.var_dump($startDate_is_before_end).'<br><br>';
			// echo '$startDate_is_viable'.var_dump($startDate_is_viable).'<br><br>';
			
			if ($startDate_is_viable) {
				$eventDate_0 = new EventDate ( $this->getStartDate (), $eventDate->getStartDate () );
				$duration_is_zero_0 = $eventDate_0->duration_is_zero ();
				// echo $duration_is_zero_0 ? $eventDate_0.'DURATION IS ZERO 0'.var_dump().'<br><br>' : '';
				if (! $duration_is_zero_0) {
					$new_eventDates->add ( $eventDate_0 );
				}
			}
			
			// second event
			$endDate_is_before_end = $eventDate->getEndDate () <= $this->getEndDate ();
			$endDate_is_after_start = $eventDate->getEndDate () >= $this->getStartDate ();
			$endDate_is_viable = $endDate_is_before_end && $endDate_is_after_start;
			if ($endDate_is_viable) {
				// echo 'I NEVER GO THERE 1 EVENT DATE 1<br>';
				$eventDate_1 = new EventDate ( $eventDate->getEndDate (), $this->getEndDate () );
				$duration_is_zero_1 = $eventDate_1->duration_is_zero ();
				// echo $eventDate_1->duration ()->getNbSeconds () . ' << duration event date 1<br>';
				// echo '^^^^is duration 0 ?' . var_dump ( $duration_is_zero_1 );
				if (! $duration_is_zero_1) {
					$new_eventDates->add ( $eventDate_1 );
				}
			}
			
			if ($new_eventDates->isEmpty ()) {
				$new_eventDates->add ( $this );
			}
		}
		
		// debug
		
		echo // true
$eventDate->getStartDate ()->getDay () == '08' ? 'results :<br>' . $new_eventDates . '<br><br>' : '';
		
		return $new_eventDates;
	}
	// pre-condition : event dates must be sorted
	public function removeAllEventDates($eventDates) {
		// debug
		// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\this is this : <br>' . $this . '<br><br>';
		// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\remove event dates : <br>' . $eventDates . '<br><br>';
		$splits = new ArrayCollection ();
		$splits_queue = new ArrayCollection ();
		if (! $eventDates->isEmpty ()) {
			$splits_to_be_added = $this->removeEventDate ( $eventDates->first () );
			// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\splits to be added : <br>' . $splits_to_be_added . '<br><br>';
			if ($splits_to_be_added->count () == 2) {
				// echo '------first split : <br>' . $splits_to_be_added->first () . '<br><br>';
				// echo '------second split : <br>' . $splits_to_be_added->last () . '<br><br>';
				$splits->add ( $splits_to_be_added->first () );
				$eventDates_queue = $eventDates->queue ();
				$splits_queue = $splits_to_be_added->last ()->removeAllEventDates ( $eventDates_queue );
				// echo '\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\splits queue 2 : <br>' . $splits_queue . '<br><br>';
			} else {
				if ($splits_to_be_added->count () == 1) {
					$eventDates_queue = $eventDates->queue ();
					$splits_queue = $splits_to_be_added->first ()->removeAllEventDates ( $eventDates_queue );
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
	public function __toIcsEvent($summary) {
		return 'BEGIN:VEVENT
DTSTART:' . $this->getStartDate ()->__toIcsDate () . '
DTEND:' . $this->getEndDate ()->__toIcsDate () . '
SUMMARY:' . $summary . '
END:VEVENT

';
	}
	public static function __toIcsCalendar($eventDates, $summary) {
		$return = 'BEGIN:VCALENDAR
METHOD:REQUEST

';
		foreach ( $eventDates as $ed ) {
			$return .= $ed->__toIcsEvent ( $summary );
		}
		$return .= 'END:VCALENDAR';
		return $return;
	}
	// pre-condition : $eventDates and $to_be_added_eventDates don't overlap
	static function add($eventDates, $to_be_added_eventDates) {
		$clone = clone $to_be_added_eventDates;
		$eventDates->addAll ( $clone );
	}
}