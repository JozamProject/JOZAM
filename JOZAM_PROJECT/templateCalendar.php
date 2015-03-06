<?php
require_once ('Calendar/CalendarFreeTime.php');
require_once ('Calendar/CalendarDate.php');
require_once ('Calendar/CalendarEvent.php');
require_once ('Calendar/RecurringEvent.php');
require_once ('Calendar/CalendarFreeTime.php');

$calendar;
$retrievedDates;

setCalendar('http://localhost/JOZAM/JOZAM_PROJECT/Calendar/Tests/ADECal.ics');

// test if there is an action
if (isset ( $_POST ['action'] )) {
	// $action is sent from jozam.php
	$action = $_POST ['action'];
	// the calendars url can be sent too
	// $url = $_POST ['calendarURL'];
	
	// $calendar = CalendarFreeTime::retrieveCalendar ( $url );
	// $retrievedDates = CalendarFreeTime::retrieveCalendarEvents ( $calendar );
	
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
	$calendar = CalendarFreeTime::retrieveCalendar ( $calendar_url);
	$retrievedDates = CalendarFreeTime::retrieveCalendarEvents ($calendar );
}

function timeLeft($deadline) {
	file_put_contents ( 'test2', '$retrievedDates : ' . $retrievedDates );
	
	$startDate = CalendarDate::date_to_CalendarDate ( new DateTime ( '2015-03-02' ) );
	// file_put_contents ( 'test2', 'Start Date : ' . $startDate );
	
	// file_put_contents ( 'test2', '$deadline : ' . $deadline );
	
	$endDate = CalendarDate::date_to_CalendarDate ( new DateTime ( $deadline ) );
	// file_put_contents ( 'test2', 'End Date : ' . $endDate );
	
	$task_CalendarEvent = new CalendarEvent ( $startDate, $endDate );
	$freeCalendarEvents = CalendarFreeTime::retrieveFreeCalendarEvents ( $retrievedDates, $task_CalendarEvent );
	file_put_contents ( 'test', '$freeCalendarEvents: ' . $freeCalendarEvents );
	
	$timeLeft = CalendarFreeTime::timeLeft ( $freeCalendarEvents );
	file_put_contents ( 'test', 'Time Left : ' . $timeLeft );
	return $timeLeft;
}

?>