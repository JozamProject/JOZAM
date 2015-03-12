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
ini_set ( 'xdebug.max_nesting_level', 100000 );
generate_calendar_events (); // to be removed

$action = $_POST ['action'];
if (isset ( $action )) {
	if ($action === 'InitializeCalendar') {
		initialize ();
		generate_calendar_events ();
	}
	
	if ($action === 'ModifyCalendar') {
		$calendar_url = $_POST ['calendar_url'];
		setCalendar_url ( $calendar_url );
		setConfiguration ();
		generate_calendar_events ( $calendar_url );
	}
	
	if ($action === 'RefreshRefreshRate') {
		$refresh_rate = $_POST ['refresh_rate'];
		if ($refresh_rate === '00h 00min ') {
			$refresh_rate = '00h 05min ';
		}
		setRefresh_rate ( $refresh_rate );
		setConfiguration ();
	}
	
	if ($action === 'RefreshTimeLeft') {
		$boards = new SimpleXMLElement ( 'input.xml', 0, true );
		foreach ( $boards->board as $board ) {
			foreach ( $board->projet as $p ) {
				refresh ( $p );
			}
		}
		save_xml ( 'input.xml', $boards );
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
	
	if ($action === 'SetRecurringEvent') {
		$edit = $_POST ['edit'];
		$name = $_POST ['name'];
		$id = $_POST ['id'];
		if ($edit == 'false') {
			$id = str_replace ( ' ', '_', $name );
		}
		
		$startTime = new DateTime ( $_POST ['startTime'] );
		$startTime = new CalendarTime ( $startTime->format ( 'H' ), $startTime->format ( 'i' ), $startTime->format ( 's' ) );
		
		$endTime = new DateTime ( $_POST ['endTime'] );
		$endTime = new CalendarTime ( $endTime->format ( 'H' ), $endTime->format ( 'i' ), $endTime->format ( 's' ) );
		
		$days = str_replace ( "'", '', $_POST ['days'] );
		
		$timeSlot_startDate = $_POST ['timeSlot_startDate'] . ' ' . $_POST ['timeSlot_startTime'];
		$timeSlot_startDate = DateTime::createFromFormat ( 'm/d/Y g:i A', $timeSlot_startDate );
		$timeSlot_startDate = CalendarDate::date_to_CalendarDate ( $timeSlot_startDate );
		
		$timeSlot_endDate = $_POST ['timeSlot_endDate'] . ' ' . $_POST ['timeSlot_endTime'];
		$timeSlot_endDate = DateTime::createFromFormat ( 'm/d/Y g:i A', $timeSlot_endDate );
		$timeSlot_endDate = CalendarDate::date_to_CalendarDate ( $timeSlot_endDate );
		
		$timeSlot = new CalendarEvent ( $timeSlot_startDate, $timeSlot_endDate );
		$recurringEvent = new RecurringEvent ( $name, $startTime, $endTime, $timeSlot, $days );
		$recurringEvent = new SimpleXMLElement ( $recurringEvent->__toXML ( $id ) );
		if ($edit == 'true') {
			setRecurringEvent ( $id, $recurringEvent );
		} else {
			addRecurringEvent ( $id, $recurringEvent );
		}
		setConfiguration ();
	}
	
	if ($action === 'GetRecurringEventAttributes') {
		$recurringEvent = getRecurringEvent ( $_POST ['recurringEvent_id'] );
		
		echo $recurringEvent ['name'] . '|';
		
		$days = substr ( $recurringEvent ['days'], 1, - 1 );
		echo $days . '|';
		
		$startTime = DateTime::createFromFormat ( 'H i', $recurringEvent->startTime ['hour'] . ' ' . $recurringEvent->startTime ['minute'] );
		echo $startTime->format ( 'g:i A' ) . '|';
		
		$endTime = DateTime::createFromFormat ( 'H i', $recurringEvent->endTime ['hour'] . ' ' . $recurringEvent->endTime ['minute'] );
		echo $endTime->format ( 'g:i A' ) . '|';
		
		$xml_startDate = $recurringEvent->timeSlot->startDate;
		$startDate_format = $xml_startDate ['year'] . ' ' . $xml_startDate ['month'] . ' ' . $xml_startDate ['day'] . ' ' . $xml_startDate ['hour'] . ' ' . $xml_startDate ['minute'];
		$startDate = DateTime::createFromFormat ( 'Y m d H i', $startDate_format );
		echo $startDate->format ( 'm/d/Y' ) . '|';
		echo $startDate->format ( 'g:i A' ) . '|';
		
		$xml_endDate = $recurringEvent->timeSlot->endDate;
		$endDate_format = $xml_endDate ['year'] . ' ' . $xml_endDate ['month'] . ' ' . $xml_endDate ['day'] . ' ' . $xml_endDate ['hour'] . ' ' . $xml_endDate ['minute'];
		$endDate = DateTime::createFromFormat ( 'Y m d H i', $endDate_format );
		echo $endDate->format ( 'm/d/Y' ) . '|';
		echo $endDate->format ( 'g:i A' );
	}
	
	if ($action === 'GetRefreshRate') {
		$refresh_rate = getRefresh_rate ();
		$nb_hours = intval ( substr ( $refresh_rate, 0, 2 ) );
		$nb_minutes = intval ( substr ( $refresh_rate, 4, 2 ) );
		$nb_seconds = $nb_hours * 3600 + $nb_minutes * 60;
		$nb_milliseconds = $nb_seconds * 1000;
		echo $nb_milliseconds;
	}
}
function refresh($p) {
	foreach ( $p->projet as $sp ) {
		refresh ( $sp );
	}
	foreach ( $p->tache as $t ) {
		$t ['timeLeft'] = '' . timeLeft ( $t ['deadLine'] );
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
	return './User_data/FreeTimeCalendars/';
}
function timeLeft($idTask, $deadLine) {
	global $configuration;
	global $calendar_events;
	
	$startDate = CalendarDate::date_to_CalendarDate ( new DateTime ( 'now' ) );
	$endDate = DateTime::createFromFormat ( 'm/d/Y', $deadLine );
	$endDate = CalendarDate::date_to_CalendarDate ( $endDate );
	// file_put_contents ( 'test', $endDate );
	$deadLine_exceeded = $startDate >= $endDate;
	
	if (! $deadLine_exceeded) {
		$task_calendarEvent = new CalendarEvent ( $startDate, $endDate );
		// file_put_contents ( 'test', $task_calendarEvent );
		$files = glob ( recurringEvents_path () . '*.xml' );
		$recurringEvents = new ArrayCollection ( array_map ( recurringEvent_file_to_recurringEvent, $files ) );
		
		$recurringEvents_to_be_deleted = new ArrayCollection ();
		foreach ( $recurringEvents as $re ) {
			if ($task_calendarEvent->mergeable ( $re->getTimeSlot () )) {
				$intersection = CalendarEvent::intersection ( $task_calendarEvent, $re->getTimeSlot () );
				$re->setTimeSlot ( $intersection );
				// $re->setTimeSlot ( $task_calendarEvent );
			} else {
				$recurringEvents_to_be_deleted->add ( $re );
			}
		}
		$recurringEvents->removeAllElements ( $recurringEvents_to_be_deleted );
		
		$allEvents = clone $calendar_events;
		$recurringEvents_generated = RecurringEvent::map_generate ( $recurringEvents );
		$recurringEvents_generated->flatten ();
		CalendarEvent::add ( $allEvents, $recurringEvents_generated );
		
		$freeCalendarEvents = CalendarFreeTime::retrieveFreeCalendarEvents ( $allEvents, $task_calendarEvent );
		$timeLeft = CalendarFreeTime::timeLeft ( $freeCalendarEvents );
		
		$task = $configuration->xpath ( 'task[@id="' . $idTask . '"]' )[0];
		$icsCalendar = CalendarFreeTime::createCalendar ( $freeCalendarEvents, $task ['title'] . ' free time', freeTimeCalendars_path () . 'task_' . $idTask . '.ics' );
		
		$timeLeft = $timeLeft->is_strictly_positive () ? $timeLeft : 'no time left !';
		
		// file_put_contents ( 'test', 'time left : ' . $timeLeft );
	} else {
		$timeLeft = 'no time left !';
	}
	return $timeLeft;
}
?>