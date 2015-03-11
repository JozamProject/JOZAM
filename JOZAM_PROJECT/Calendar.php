<?php
error_reporting ( E_ALL ^ E_NOTICE );
use Collections\ArrayCollection;
require_once ('Calendar/Collections/ArrayCollection.php');
require_once ('Calendar/CalendarFreeTime.php');
require_once ('Calendar/CalendarDate.php');
require_once ('Calendar/CalendarEvent.php');
require_once ('Calendar/RecurringEvent.php');
require_once ('Calendar/CalendarFreeTime.php');

$configuration;
$calendar_events;

initialize ();
$timeLeft = timeLeft ( '2015-03-09' );
// echo $timeLeft;

// setCalendar ( 'http://localhost/JOZAM/JOZAM_PROJECT/Calendar/Tests/ADECal.ics' );
// setCalendar ( 'https://edt.inp-toulouse.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=150&projectId=4&calType=ical&nbWeeks=20' );

// file_put_contents ( 'test', '$default_recurringEvents : ' . $default_recurringEvents );

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
	
	if ($action === 'an action') {
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
		// save_xml ( 'input.xml', $boards );
	}
	
	if ($action === 'an other action') {
		//
	}
}

//
function refresh($p/* other arguments */){
	foreach ( $p->projet as $sp ) {
		refresh ( $sp );
	}
	foreach ( $p->tache as $t ) {
		$deadLine = $t ['echeance'];
		// modify the tasks time remaining
		// $t['timeRemaining'] = timeLeft($deadLine)
	}
}
function configuration_path() {
	return 'User_data/Configuration.xml';
}
function initialize() {
	global $configuration;
	$configuration = new SimpleXMLElement ( file_get_contents ( configuration_path () ) );
	setCalendar_url ( $configuration ['calendar_url'] );
	generate_calendar_events ();
}
function setConfiguration() {
	global $configuration;
	file_put_contents ( configuration_path (), $configuration->asXML () );
}
function getCalendar_url() {
	global $configuration;
	return $configuration ['calendar_url'];
}
function setCalendar_url($calendar_url) {
	global $configuration;
	$configuration ['calendar_url'] = $calendar_url;
	setConfiguration ();
}
function getRefresh_rate() {
	global $configuration;
	return $configuration ['refresh_rate'];
}
function setRefresh_rate($refresh_rate) {
	global $configuration;
	$configuration ['refresh_rate'] = $refresh_rate;
	setConfiguration ();
}
function generate_calendar_events() {
	global $configuration;
	global $calendar_events;
	
	$calendar = CalendarFreeTime::retrieveCalendar ( $configuration ['calendar_url'] );
	$calendar_events = CalendarFreeTime::retrieveCalendarEvents ( $calendar );
}
function getRecurringEvents() {
	global $configuration;
	return $configuration->recurringEvent;
}
function recurringEvent_path($recurringEvent_name) {
	return 'RecurringEvents/' . $recurringEvent_name . '.xml';
}
function getRecurringEvent($recurringEvent_name) {
	return new SimpleXMLElement ( file_get_contents ( recurringEvent_path ( $recurringEvent_name ) ) );
}
function setRecurringEvent($recurringEvent_name, $recurringEvent) {
	file_put_contents ( recurringEvent_path ( $recurringEvent_name ), $recurringEvent->asXML () );
}
function addRecurringEvent($recurringEvent_name, $reccuringEvent) {
	global $configuration;
	setRecurringEvent ( $recurringEvent_name, $recurringEvent );
	$recurringEvent_node = $configuration->addChild ( 'recurringEvent' );
	$recurringEvent_node->addAttribute ( 'name', $recurringEvent_name );
	setConfiguration ();
}
function deleteRecurringEvent($recurringEvent_name) {
	global $configuration;
	unlink ( recurringEvent_path ( $recurringEvent_name ) );
	unset ( $configuration->xpath ( 'recurringEvent[@name="' . $recurringEvent_name . 'A12"]' )[0]->{0} );
	setConfiguration ();
}
function recurringEvents_path() {
	return './User_data/RecurringEvents/';
}
function recurringEvent_file_to_recurringEvent($file) {
	file_put_contents ( 'test', 'debug : ' . $file );
	return RecurringEvent::XML_to_RecurringEvent ( file_get_contents ( $file ) );
}
function timeLeft($deadLine) {
	global $configuration;
	global $calendar_events;
	
	// /!\ $startDate has to be changed /!\
	$startDate = CalendarDate::date_to_CalendarDate ( new DateTime ( '2015-03-02' ) );
	$endDate = CalendarDate::date_to_CalendarDate ( new DateTime ( $deadLine ) );
	
	$deadLine_exceeded = $startDate >= $endDate;
	
	if (! $deadLine_exceeded) {
		$task_calendarEvent = new CalendarEvent ( $startDate, $endDate );
		
		$files = glob ( recurringEvents_path () . '*.xml' );
		$recurringEvents = new ArrayCollection ( array_map ( recurringEvent_file_to_recurringEvent, $files ) );
		file_put_contents ( 'test', '$recurringEvents : ' . $recurringEvents );
		
		foreach ( $recurringEvents as $re ) {
			// /!\ add min event for recurring event time slot and task time slot /!\
			$re->setTimeSlot ( $task_calendarEvent );
		}
		$allEvents = clone $calendar_events;
		$recurringEvents_generated = RecurringEvent::map_generate ( $recurringEvents );
		// echo 'recurringEvents_generated :'. $recurringEvents_generated . '<br><br>';
		// file_put_contents ( 'test', '$$recurringEvents_generated : ' . $recurringEvents_generated );
		$recurringEvents_generated->flatten ();
		CalendarEvent::add ( $allEvents, $recurringEvents_generated );
		
		$freeCalendarEvents = CalendarFreeTime::retrieveFreeCalendarEvents ( $allEvents, $task_calendarEvent );
		// file_put_contents ( 'test', '$freeCalendarEvents : ' . $freeCalendarEvents );
		$timeLeft = CalendarFreeTime::timeLeft ( $freeCalendarEvents );
		$timeLeft = $timeLeft->is_strictly_positive () ? $timeLeft . '<a href="http://localhost/JOZAM/JOZAM_PROJECT/CalendarFreeTime.ics">&nbsp;See</a>' : 'no time left !';
		// /!\ ics folder creation /!\
		$icsCalendar = CalendarFreeTime::createCalendar ( $freeCalendarEvents );
	} else {
		$timeLeft = 'no time left !';
	}
	return $timeLeft;
}

?>