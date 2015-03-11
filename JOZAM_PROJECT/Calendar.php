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
generate_calendar_events (); // to be removed
                             // refresh button & functionnality
                             
// test if there is an action
$action = $_POST ['action'];
if (isset ( $action )) {
	// implement InitializeCalendar action
	if ($action === 'InitializeCalendar') {
		generate_calendar_events ();
	}
	
	// implement ModifyCalendar action
	if ($action === 'ModifyCalendar') {
		$calendar_url = $_POST ['calendar_url'];
		setCalendar_url ( $calendar_url );
		generate_calendar_events ( $calendar_url );
	}
	
	// implement RefreshTimeLeft action
	if ($action === 'RefreshTimeLeft') {
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		$idMaxProjects = intval ( $projects ['idMax'] );
		$id = $_POST ['idProject'];
		$task_id = $id . '-' . $idMaxProjects;
		// $task = findTask ( $task_id, $boards );
		
		foreach ( $boards->board as $board ) {
			foreach ( $board->projet as $p ) {
				refresh ( $p );
			}
		}
		save_xml ( 'input.xml', $boards );
	}
	
	// implement SetConfiguration action
	if ($action === 'SetConfiguration') {
		setCalendar_url ( $_POST ['calendar_url'] );
		setRefresh_rate ( $_POST ['refresh_rate'] );
		setConfiguration ();
		generate_calendar_events ();
	}
	
	if ($action === 'removeRecurringEvent') {
		removeRecurringEvent ( $_POST ['selected_value'] );
		setConfiguration ();
	}
	
	// from default recurring events
	if ($action === 'add_to_recurringEvents') {
		$selected_value = $_POST ['selected_value'];
		$recurringEvent = new SimpleXMLElement ( file_get_contents ( 'DefaultRecurringEvents/' . $selected_value . '.xml' ) );
		addRecurringEvent ( $selected_value, $recurringEvent );
		setConfiguration ();
	}
	
	// implement SetRecurringEvent action
	if ($action === 'SetRecurringEvent') {
		$name = $_POST ['name'];
		$id = str_replace(' ', '_', $name);
		$startTime = date_to_CalendarDate(new DateTime($_POST ['startTime']));
		$endTime = date_to_CalendarDate(new DateTime($_POST ['endTime']));
		$days = array($_POST ['days']);
		$timeSlot_startDate = date_to_CalendarDate(date('',strtotime($_POST ['timeSlot_startDate'] . ' '.$_POST ['timeSlot_endDate'])));
		$timeSlot_endDate = date_to_CalendarDate(date('',strtotime($_POST ['timeSlot_endDate'] . ' '.$_POST ['timeSlot_endTime'])));
		$timeSlot = new CalendarEvent($timeSlot_startDate, $timeSlot_endDate);
		$recurringEvent = new RecurringEvent($name, $startTime, $endTime, $timeSlot, $days);
		setRecurringEvent($id, $recurringEvent->__toXML());
		setConfiguration ();
	}
}

//
function refresh($p/* other arguments */){
	foreach ( $p->projet as $sp ) {
		refresh ( $sp );
	}
	foreach ( $p->tache as $t ) {
		$t ['timeLeft'] = timeLeft ( $t ['deadLine'] );
	}
}
function configuration_path() {
	return 'User_data/Configuration.xml';
}
function initialize() {
	global $configuration;
	$configuration = new SimpleXMLElement ( file_get_contents ( configuration_path () ) );
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
}
function getRefresh_rate() {
	global $configuration;
	return $configuration ['refresh_rate'];
}
function setRefresh_rate($refresh_rate) {
	global $configuration;
	$configuration ['refresh_rate'] = $refresh_rate;
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
function recurringEvents_path() {
	return './User_data/RecurringEvents/';
}
function recurringEvent_path($recurringEvent_id) {
	return recurringEvents_path () . $recurringEvent_id . '.xml';
}
function getRecurringEvent($recurringEvent_id) {
	return new SimpleXMLElement ( file_get_contents ( recurringEvent_path ( $recurringEvent_id ) ) );
}
function setRecurringEvent($recurringEvent_id, $recurringEvent) {
	file_put_contents ( recurringEvent_path ( $recurringEvent_id ), $recurringEvent->asXML () );
}
function addRecurringEvent($recurringEvent_id, $recurringEvent) {
	global $configuration;
	setRecurringEvent ( $recurringEvent_id, $recurringEvent );
	$recurringEvent_node = $configuration->addChild ( 'recurringEvent' );
	$recurringEvent_node->addAttribute ( 'id', $recurringEvent_id );
	$recurringEvent_node->addAttribute ( 'name', $recurringEvent ['name'] );
}
function removeRecurringEvent($recurringEvent_id) {
	global $configuration;
	unlink ( recurringEvent_path ( $recurringEvent_id ) );
	unset ( $configuration->xpath ( 'recurringEvent[@id="' . $recurringEvent_id . '"]' )[0]->{0} );
}
function recurringEvent_file_to_recurringEvent($file) {
	return RecurringEvent::XML_to_RecurringEvent ( file_get_contents ( $file ) );
}
function freeTimeCalendars_path() {
	return 'User_data/FreeTimeCalendars/';
}
function timeLeft($idTask, $deadLine) {
	global $configuration;
	global $calendar_events;
	
	// /!\ $startDate has to be changed /!\
	$startDate = CalendarDate::date_to_CalendarDate ( new DateTime ( 'now' ) );
	file_put_contents ( 'test', $startDate->format ( 'Y/m/d H:i:s' ) );
	$endDate = CalendarDate::date_to_CalendarDate ( new DateTime ( $deadLine ) );
	
	$deadLine_exceeded = $startDate >= $endDate;
	
	if (! $deadLine_exceeded) {
		$task_calendarEvent = new CalendarEvent ( $startDate, $endDate );
		
		$files = glob ( recurringEvents_path () . '*.xml' );
		$recurringEvents = new ArrayCollection ( array_map ( recurringEvent_file_to_recurringEvent, $files ) );
		// file_put_contents ( 'test', '$recurringEvents : ' . $recurringEvents );
		
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
		
		$task = $configuration->xpath ( 'task[@id="' . $idTask . '"]' )[0];
		$icsCalendar = CalendarFreeTime::createCalendar ( $freeCalendarEvents, $task ['title'] . ' free time', freeTimeCalendars_path () . 'task_' . $idTask . '.ics' );
		
		$timeLeft = $timeLeft->is_strictly_positive () ? $timeLeft . '<a href="' . freeTimeCalendars_path () . 'task_' . $idTask . '.ics">&nbsp;See</a>' : 'no time left !';
	} else {
		$timeLeft = 'no time left !';
	}
	return $timeLeft;
}

?>