<?php
error_reporting ( E_ALL ^ E_NOTICE );
use Collections\ArrayCollection;
require_once ('Calendar/Collections/ArrayCollection.php');
require_once ('Calendar/CalendarFreeTime.php');
require_once ('Calendar/CalendarDate.php');
require_once ('Calendar/CalendarEvent.php');
require_once ('Calendar/RecurringEvent.php');
require_once ('Calendar/CalendarFreeTime.php');

$calendar;
$calendar_events;
$recurringEvents;

setCalendar ( 'http://localhost/JOZAM/JOZAM_PROJECT/Calendar/Tests/ADECal.ics' );
// setCalendar ( 'https://edt.inp-toulouse.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=150&projectId=4&calType=ical&nbWeeks=20' );

$AFTERNOON_RECESSBREAK = RecurringEvent::XML_to_RecurringEvent ( file_get_contents ( 'RecurringEvents/Afternoon_recessBreak.xml' ) );
$LUNCHBREAK = RecurringEvent::XML_to_RecurringEvent ( file_get_contents ( 'RecurringEvents/LunchBreak.xml' ) );
$MORNING_RECESSBREAK = RecurringEvent::XML_to_RecurringEvent ( file_get_contents ( 'RecurringEvents/Morning_recessBreak.xml' ) );
$SLEEP = RecurringEvent::XML_to_RecurringEvent ( file_get_contents ( 'RecurringEvents/Sleep.xml' ) );
$WEEKEND = RecurringEvent::XML_to_RecurringEvent ( file_get_contents ( 'RecurringEvents/Weekend.xml' ) );

$default_recurringEvents = new ArrayCollection ( array (
		$AFTERNOON_RECESSBREAK,
		$LUNCHBREAK,
		$MORNING_RECESSBREAK,
		$SLEEP,
		$WEEKEND 
) );

// file_put_contents ( 'test', '$default_recurringEvents : ' . $default_recurringEvents );

setRecurringEvents ( $default_recurringEvents );

// Configuration popup

// action modify calendar url

// refresh button & functionnality

// test if there is an action
if (isset ( $_POST ['action'] )) {
	// $action is sent from jozam.php
	$action = $_POST ['action'];
	// the calendars url can be sent too
	// $url = $_POST ['calendarURL'];
	
	// $calendar = CalendarFreeTime::retrieveCalendar ( $url );
	// $calendar_events = CalendarFreeTime::retrieveCalendarEvents ( $calendar );
	
	if ($action === "an action") {
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		$idMaxProjects = intval ( $projects ['idMax'] );
		$id = $_POST ['idProject'];
		$task_id = $id . '-' . $idMaxProjects;
		// $task = findTask ( $task_id, $boards );
		
		foreach ( $boards->board as $board ) {
			foreach ( $board->projet as $p ) {
				compute ( $p/* other arguments */);
			}
		}
		
		// save your new xml elements in input.xml
		// save_xml ( "input.xml", $boards );
	}
	
	if ($action === "an other action") {
		//
	}
}

//
function compute($p/* other arguments */){
	foreach ( $p->projet as $sp ) {
		compute ( $sp );
	}
	foreach ( $p->tache as $t ) {
		$deadLine = $t ['echeance'];
		// call your functions here
		
		// modify the tasks time remaining
		// $t['timeRemaining'] =
	}
}
function setCalendar($calendar_url) {
	global $calendar;
	global $calendar_events;
	
	$calendar = CalendarFreeTime::retrieveCalendar ( $calendar_url );
	$calendar_events = CalendarFreeTime::retrieveCalendarEvents ( $calendar );
}
function setRecurringEvents($recurringEvents_to_be_set) {
	global $recurringEvents;
	$recurringEvents = clone $recurringEvents_to_be_set;
}
function timeLeft($deadline) {
	global $calendar_events;
	global $recurringEvents;
	
	// /!\ $startDate has to be changed /!\
	$startDate = CalendarDate::date_to_CalendarDate ( new DateTime ( '2015-03-02' ) );
	$endDate = CalendarDate::date_to_CalendarDate ( new DateTime ( $deadline ) );
	$task_calendarEvent = new CalendarEvent ( $startDate, $endDate );
	
	foreach ( $recurringEvents as $re ) {
		// /!\ add min event for recurring event time slot and task time slot /!\
		$re->setTimeSlot ( $task_calendarEvent );
	}
	
	$allEvents = clone $calendar_events;
	$recurringEvents_generated = RecurringEvent::map_generate ( $recurringEvents );
	// file_put_contents ( 'test', '$$recurringEvents_generated : ' . $recurringEvents_generated );
	$recurringEvents_generated->flatten ();
	CalendarEvent::add ( $allEvents, $recurringEvents_generated );
	
	$freeCalendarEvents = CalendarFreeTime::retrieveFreeCalendarEvents ( $allEvents, $task_calendarEvent );
	file_put_contents ( 'test', '$freeCalendarEvents : ' . $freeCalendarEvents );
	$timeLeft = CalendarFreeTime::timeLeft ( $freeCalendarEvents );
	// /!\ ics folder creation /!\
	$icsCalendar = CalendarFreeTime::createCalendar ( $freeCalendarEvents );
	return $timeLeft . '<a href="http://localhost/JOZAM/JOZAM_PROJECT/CalendarFreeTime.ics">&nbsp;See</a>';
}

?>