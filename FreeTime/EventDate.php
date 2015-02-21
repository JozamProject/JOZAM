<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
class EventDate {
	private $startDate;
	private $endDate;
	public function __construct($startDate, $endDate) {
		$this->setEventDate ( $startDate, $endDate );
	}
	public function setEventDate($startDate, $endDate) {
		$this->setStartDate ( $startDate );
		$this->setEndDate ( $endDate );
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
		return $this->getEndDate ()->diff ( $this->getStartDate () );
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
	public static function sort($eventDates, $order_by_startDates = true) {
		$order_by_startDates ? $eventDates->sort ( 'EventDate::startDateSort' ) : $eventDates->sort ( 'EventDate::startDateSort' );
	}
	public function removeEventDate($eventDate) {
		// debug
		// echo 'removeEventDate <br>' . $eventDate . '<br><br>from<br><br>' . $this . '<br><br>';

		$new_eventDates = new ArrayCollection ();
		
		// first event
		$startDate_is_viable = $eventDate->getStartDate () > $this->getStartDate () and $eventDate->getStartDate () < $this->getEndDate ();
		if ($startDate_is_viable) {
			$eventDate_0 = new EventDate ( $this->getStartDate (), $eventDate->getStartDate () );
			$new_eventDates->add ( $eventDate_0 );
		}
		
		// second event
		$endDate_is_viable = $eventDate->getEndDate () < $this->getEndDate () and $eventDate->getEndDate () > $this->getStartDate ();
		if ($endDate_is_viable) {
			$eventDate_1 = new EventDate ( $eventDate->getEndDate (), $this->getEndDate () );
			$new_eventDates->add ( $eventDate_1 );
		}
		
		// debug
		// echo 'results :<br>' . $new_eventDates . '<br><br>';
		
		return $new_eventDates;
	}
	public function removeAllEventDate($eventDates) {
		EventDate::sort ( $eventDates );
		$splits = new ArrayCollection ();
		$splits_to_be_added = new ArrayCollection ();
		if (! $eventDates->isEmpty ()) {
			$splits_to_be_added = $this->removeEventDate ( $eventDates->first () );
			if ($splits_to_be_added->count () == 2) {
				$splits->add ( $splits_to_be_added->first () );
				$queueSplits = $splits_to_be_added->last ()->removeAllEventDate ( $eventDates->queue () );
				$splits->addAll ( $queueSplits );
			} else {
				$splits->addAll ( $splits_to_be_added );
			}
		} else {
			$splits->add ( $this );
		}
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
		foreach($eventDates as $ed) {
			$return .= $ed->__toIcsEvent($summary);
		}
		$return .= 'END:VCALENDAR';
		return $return;
	}
}