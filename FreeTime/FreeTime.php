<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');

require_once ('Tache.php');
require_once ('Projet.php');
require_once ('Board.php');
require_once ('CalendarDate.php');
require_once ('EventDate.php');
class FreeTime {
	static function retrieveStartDate($date) {
		$year = substr ( $date, 8, 4 );
		$month = substr ( $date, 12, 2 );
		$day = substr ( $date, 14, 2 );
		$hour = substr ( $date, 17, 2 );
		$minute = substr ( $date, 19, 2 );
		$second = substr ( $date, 21, 2 );
		return new CalendarDate ( $year, $month, $day, $hour, $minute, $second );
	}
	static function retrieveEndDate($date) {
		$year = substr ( $date, 6, 4 );
		$month = substr ( $date, 10, 2 );
		$day = substr ( $date, 12, 2 );
		$hour = substr ( $date, 15, 2 );
		$minute = substr ( $date, 17, 2 );
		$second = substr ( $date, 19, 2 );
		return new CalendarDate ( $year, $month, $day, $hour, $minute, $second );
	}
	static function retrieveEventDates($calendar_path) {
		// retrieve calendar
		$calendar = file_get_contents ( $calendar_path );
		
		// regular expressions
		$regExpStartDate = "'DTSTART:(.*)'";
		$regExpEndDate = "'DTEND:(.*)'";
		
		// parsing calendar to get start & end dates
		$nbDates = preg_match_all ( $regExpStartDate, $calendar, $startDateArray, PREG_PATTERN_ORDER );
		preg_match_all ( $regExpEndDate, $calendar, $endDateArray, PREG_PATTERN_ORDER );
		
		// using ArrayCollection
		$startDates = new ArrayCollection ( $startDateArray [0] );
		$endDates = new ArrayCollection ( $endDateArray [0] );
		
		// creating an ArrayCollection of retrieved dates [start, end]
		$retrievedEventDates = new ArrayCollection ();
		for($i = 0; $i < $nbDates; $i ++) {
			$retrievedEventDates->add ( new EventDate ( FreeTime::retrieveStartDate ( $startDates->get ( $i ) ), FreeTime::retrieveEndDate ( $endDates->get ( $i ) ) ) );
		}
		return $retrievedEventDates;
	}
	static function retrieveFreeEventDates($calendar_path, $task) {
		$retrievedEventDates = FreeTime::retrieveEventDates ( $calendar_path );
		// $splits = $task->getEventDate()->removeAllEventDate ( $retrievedEventDates ); // what we are supposed to do
		$freeEventDates = $task->removeAllEventDate ( $retrievedEventDates ); // until Task has an EventDate attribute
		return $freeEventDates;
	}
	static function createIcsCalendar($calendar_path, $task, $calendar_name = 'FreeTime') {
		$freeEventDates = FreeTime::retrieveFreeEventDates ( $calendar_path, $task );
		$icsCalendar = EventDate::__toIcsCalendar ( $freeEventDates, 'Free Time for Task 0' ); // replace by $task->getName()
		file_put_contents ( $calendar_name . '.ics', $icsCalendar );
	}
}

// task event date
$task_EventDate = new EventDate ( new CalendarDate ( '2015', '03', '01', '00', '00', '00' ), new CalendarDate ( '2015', '03', '14', '00', '00', '00' ) );
echo 'Task event date :<br>' . $task_EventDate . '<br><br>';

// working hours event dates
$working_hours_0 = new EventDate ( new CalendarDate ( '2015', '01', '01', '8', '00', '00' ), new CalendarDate ( '2016', '01', '01', '12', '00', '00' ) );
$working_hours_1 = new EventDate ( new CalendarDate ( '2015', '01', '01', '14', '00', '00' ), new CalendarDate ( '2016', '01', '01', '18', '00', '00' ) );
$working_hours = new ArrayCollection ( array (
		$working_hours_0,
		$working_hours_1 
) );
echo 'Working hours :<br>' . $working_hours . '<br><br>';

// retrieved dates for .ics file
$retrievedDates = FreeTime::retrieveEventDates ( 'ADECal.ics' );
echo 'Calendar Events :<br>' . $retrievedDates . '<br><br>';

// sort retrieved dates
EventDate::sort ( $retrievedDates, false );
echo 'After sorting : <br>' . $retrievedDates . '<br><br>';

// free event dates
$freeEventDates = FreeTime::retrieveFreeEventDates ( 'ADECal.ics', $task_EventDate );
echo 'Free event dates :<br>' . $freeEventDates . '<br><br>';

// creating ics calendar
$icsCalendar = FreeTime::createIcsCalendar ( 'ADECal.ics', $task_EventDate, 'FreeTime' );
echo 'ICS Calendar :<br>' . htmlspecialchars ( file_get_contents ( 'FreeTime.ics' ) ) . '<br><br>';

?>