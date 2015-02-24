<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
require_once ('CalendarDate.php');
require_once ('EventDate.php');
require_once ('BusyEventDate.php');
class FreeTime {
	static function retrieveDate($date) {
		$date = strtotime ( $date );
		$year = date ( 'Y', $date );
		$month = date ( 'm', $date );
		$day = date ( 'd', $date );
		$hour = date ( 'H', $date );
		$minute = date ( 'i', $date );
		$second = date ( 's', $date );
		return new CalendarDate ( $year, $month, $day, $hour, $minute, $second );
	}
	static function retrieveStartDate($date) {
		$date = substr ( $date, 8, 16 );
		return FreeTime::retrieveDate ( $date );
	}
	static function retrieveEndDate($date) {
		$date = substr ( $date, 6, 16 );
		return FreeTime::retrieveDate ( $date );
	}
	static function retrieveCalendar($calendar_path) {
		return file_get_contents ( $calendar_path );
	}
	static function retrieveEventDates($calendar) {
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
	static function retrieveFreeEventDates($retrievedEventDates, $timeSlot) {
		EventDate::sort ( $retrievedEventDates );
		echo 'Sorted event dates :<br>' . $retrievedEventDates . '<br><br>';
		$freeEventDates = $timeSlot->removeAllEventDates ( $retrievedEventDates );
		return $freeEventDates;
	}
	static function createFreeCalendar($freeEventDates, $freeEventDate_name = 'Free Time', $calendarFile_path = 'FreeTime.ics') {
		$freeCalendar = EventDate::__toIcsCalendar ( $freeEventDates, $freeEventDate_name );
		file_put_contents ( $calendarFile_path, $freeCalendar );
	}
	static function timeLeft($freeEventDates) {
		$timeLeft = 0;
		foreach ( $freeEventDates as $fed ) {
			$timeLeft += $fed->duration ()->getNbSeconds ();
		}
		$return = new Duration ();
		$return->setNbSeconds ( $timeLeft );
		return $return;
	}
}

/* Tests */

// task event datex
$task_EventDate = new EventDate ( new CalendarDate ( '2015', '03', '02', '00', '00', '00' ), new CalendarDate ( '2015', '03', '09', '00', '00', '00' ) );
echo 'Task event date :<br>' . $task_EventDate . '<br><br>';

// retrieve calendar
$calendar = FreeTime::retrieveCalendar ( 'ADECal.ics' );

// retrieved dates for .ics file
$retrievedDates = FreeTime::retrieveEventDates ( $calendar );
//echo 'Calendar event dates :<br>' . $retrievedDates . '<br><br>';

// sort retrieved dates
EventDate::sort ( $retrievedDates, false );
echo 'Sorted calendar event dates : <br>' . $retrievedDates . '<br><br>';

// WEEKEND busy event dates
// busy start & end hours
$weekend_startHour = new CalendarHour ( '00', '00', '00' );
$weekend_endHour = new CalendarHour ( '00', '00', '00' );
// busy time slot
$weekend_startDate = new CalendarDate ( '2015', '03', '02' );
$weekend_endDate = new CalendarDate ( '2015', '03', '09' );
$weekend_timeSlot = new EventDate ( $weekend_startDate, $weekend_endDate );
// busy days
$weekend = array (
		'Saturday',
		'Sunday' 
);
// busy event dates creation & generation
$weekend_eventDate = new BusyEventDate ( $weekend_startHour, $weekend_endHour, $weekend_timeSlot, $weekend );
$weekendEventDates = $weekend_eventDate->generate ();
echo 'Weekend event dates :<br>' . $weekendEventDates . '<br><br>';

// SLEEP busy event dates
// busy start & end hours
$sleep_startHour = new CalendarHour ( '18', '00', '00' );
$sleep_endHour = new CalendarHour ( '08', '00', '00' );
// busy time slot
$sleep_startDate = new CalendarDate ( '2015', '03', '01' );
$sleep_endDate = new CalendarDate ( '2015', '03', '09' );
$sleep_timeSlot = new EventDate ( $sleep_startDate, $sleep_endDate );
// busy days
// $sleep = array('Saturday', 'Sunday');
// busy event dates creation & generation
$sleep_eventDate = new BusyEventDate ( $sleep_startHour, $sleep_endHour, $sleep_timeSlot );
$sleepEventDates = $sleep_eventDate->generate ();
echo 'Sleep event dates :<br>' . $sleepEventDates . '<br><br>';

// LUNCHBREAK busy event date
// busy start & end hours
$lunchBreak_startHour = new CalendarHour ( '12', '00', '00' );
$lunchBreak_endHour = new CalendarHour ( '14', '00', '00' );
// busy time slot
$lunchBreak_startDate = new CalendarDate ( '2015', '03', '01' );
$lunchBreak_endDate = new CalendarDate ( '2015', '03', '09' );
$lunchBreak_timeSlot = new EventDate ( $lunchBreak_startDate, $lunchBreak_endDate );
// busy days
// $lunchBreak = array('Saturday', 'Sunday');
// busy event dates creation & generation
$lunchBreak_eventDate = new BusyEventDate ( $lunchBreak_startHour, $lunchBreak_endHour, $lunchBreak_timeSlot );
$lunchBreakEventDates = $lunchBreak_eventDate->generate ();
echo 'Lunch break event dates :<br>' . $lunchBreakEventDates . '<br><br>';

// morning_recess busy event date
// busy start & end hours
$morning_recessBreak_startHour = new CalendarHour ( '09', '45', '00' );
$morning_recessBreak_endHour = new CalendarHour ( '10', '15', '00' );
// busy time slot
$morning_recessBreak_startDate = new CalendarDate ( '2015', '03', '01' );
$morning_recessBreak_endDate = new CalendarDate ( '2015', '03', '09' );
$morning_recessBreak_timeSlot = new EventDate ( $morning_recessBreak_startDate, $morning_recessBreak_endDate );
// busy days
// $morning_recessBreak = array('Saturday', 'Sunday');
// busy event dates creation & generation
$morning_recessBreak_eventDate = new BusyEventDate ( $morning_recessBreak_startHour, $morning_recessBreak_endHour, $morning_recessBreak_timeSlot );
$morning_recessBreakEventDates = $morning_recessBreak_eventDate->generate ();
echo 'Morning recess break event dates :<br>' . $morning_recessBreakEventDates . '<br><br>';

// afternoon_recess busy event date
// busy start & end hours
$afternoon_recessBreak_startHour = new CalendarHour ( '15', '45', '00' );
$afternoon_recessBreak_endHour = new CalendarHour ( '16', '15', '00' );
// busy time slot
$afternoon_recessBreak_startDate = new CalendarDate ( '2015', '03', '01' );
$afternoon_recessBreak_endDate = new CalendarDate ( '2015', '03', '09' );
$afternoon_recessBreak_timeSlot = new EventDate ( $afternoon_recessBreak_startDate, $afternoon_recessBreak_endDate );
// busy days
// $afternoon_recessBreak = array('Saturday', 'Sunday');
// busy event dates creation & generation
$afternoon_recessBreak_eventDate = new BusyEventDate ( $afternoon_recessBreak_startHour, $afternoon_recessBreak_endHour, $afternoon_recessBreak_timeSlot );
$afternoon_recessBreakEventDates = $afternoon_recessBreak_eventDate->generate ();
echo 'Afternoon recess break event dates :<br>' . $afternoon_recessBreakEventDates . '<br><br>';

$all_eventDates = new ArrayCollection ();
EventDate::add ( $all_eventDates, $retrievedDates );
EventDate::add ( $all_eventDates, $weekendEventDates );
EventDate::add ( $all_eventDates, $sleepEventDates );
EventDate::add ( $all_eventDates, $lunchBreakEventDates );
EventDate::add ( $all_eventDates, $morning_recessBreakEventDates );
EventDate::add ( $all_eventDates, $afternoon_recessBreakEventDates );
// EVENTS INTERSECTION /!\
//echo 'All event dates :<br>' . $all_eventDates . '<br><br>';

// free event dates
$freeEventDates = FreeTime::retrieveFreeEventDates ( $all_eventDates, $task_EventDate );
echo 'Free event dates :<br>' . $freeEventDates . '<br><br>';

// creating ics calendar
$icsCalendar = FreeTime::createFreeCalendar ( $freeEventDates );
echo 'ICS Calendar :<br>' . htmlspecialchars ( file_get_contents ( 'FreeTime.ics' ) ) . '<br><br>';

// computing time left
$timeLeft = FreeTime::timeLeft ( $freeEventDates );
echo 'Time left : ' . $timeLeft . '<br><br>';
?>