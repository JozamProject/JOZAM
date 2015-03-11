<?php
use Collections\ArrayCollection;
require_once ('../Collections/ArrayCollection.php');
require_once ('../CalendarDate.php');
require_once ('../CalendarEvent.php');
require_once ('../RecurringEvent.php');
require_once ('../CalendarFreeTime.php');

/**
 * This file aims to show how to use the functions of the CalendarFreeTime utility class.
 */

// task calendar event
$task_CalendarEvent = new CalendarEvent ( new CalendarDate ( '2015', '03', '02', '00', '00', '00' ), new CalendarDate ( '2015', '03', '09', '00', '00', '00' ) );
echo 'Task event date :<br>' . $task_CalendarEvent . '<br><br>';

// retrieve calendar
$calendar = CalendarFreeTime::retrieveCalendar ( 'ADECal.ics' );
// $calendar = CalendarFreeTime::retrieveCalendar ( 'https://www.google.com/calendar/ical/vfp5pk3n8udcuvsj7l5deuruas%40group.calendar.google.com/private-ca0015ea949cf5cd75f80f56118597dd/basic.ics' );
// $calendar = CalendarFreeTime::retrieveCalendar ( 'http://edt.inp-toulouse.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=150&projectId=4&calType=ical&nbWeeks=20' );

// retrieved dates for .ics file
$calendar_events = CalendarFreeTime::retrieveCalendarEvents ( $calendar );
// echo 'Calendar event dates :<br>' . $calendar_events . '<br><br>';

// sort retrieved dates
CalendarEvent::sort ( $calendar_events, false );
echo 'Sorted calendar event dates : <br>' . $calendar_events . '<br><br>';

// Recurring events time slot
$recurringEvent_startDate = new CalendarDate ( '2015', '03', '02' );
$recurringEvent_endDate = new CalendarDate ( '2015', '03', '09' );
$recurringEvent_timeSlot = new CalendarEvent ( $recurringEvent_startDate, $recurringEvent_endDate );

// WEEKEND
// start & end time
$weekend_startTime = new CalendarTime ( '00', '00', '00' );
$weekend_endTime = new CalendarTime ( '00', '00', '00' );
// days
$weekend = array (
		'Saturday',
		'Sunday' 
);
// creation & generation
$weekend_CalendarEvent = new RecurringEvent ( 'Weekend', $weekend_startTime, $weekend_endTime, $recurringEvent_timeSlot, $weekend );
$weekendCalendarEvents = $weekend_CalendarEvent->generate ();
echo 'Weekend event dates :<br>' . $weekendCalendarEvents . '<br><br>';

echo 'Weekend Calendar Event :<br>' . $weekend_CalendarEvent . '<br><br>';

// xml transformation
$weekend_xml = $weekend_CalendarEvent->__toXML ( 'weekend' );
file_put_contents ( 'RecurringEvents/Weekend.xml', $weekend_xml );
$weekend_xml = file_get_contents ( 'RecurringEvents/Weekend.xml' );

echo '$weekend_xml :<br>' . htmlspecialchars ( $weekend_xml ) . '<br><br>';

$weekend_generated_from_xml = RecurringEvent::XML_to_RecurringEvent ( $weekend_xml );
echo 'Weekend generated from xml :<br>' . $weekend_generated_from_xml . '<br><br>';

// SLEEP
// start & end time
$sleep_startTime = new CalendarTime ( '18', '00', '00' );
$sleep_endTime = new CalendarTime ( '08', '00', '00' );
;
// creation & generation
$sleep_CalendarEvent = new RecurringEvent ( 'Sleep', $sleep_startTime, $sleep_endTime, $recurringEvent_timeSlot );
$sleepCalendarEvents = $sleep_CalendarEvent->generate ();
echo 'Sleep event dates :<br>' . $sleepCalendarEvents . '<br><br>';

$sleep_xml = $sleep_CalendarEvent->__toXML ( 'sleep' );
file_put_contents ( 'RecurringEvents/Sleep.xml', $sleep_xml );

// LUNCHBREAK
// start & end time
$lunchBreak_startTime = new CalendarTime ( '12', '00', '00' );
$lunchBreak_endTime = new CalendarTime ( '14', '00', '00' );
// creation & generation
$lunchBreak_CalendarEvent = new RecurringEvent ( 'Lunch Break', $lunchBreak_startTime, $lunchBreak_endTime, $recurringEvent_timeSlot );
$lunchBreakCalendarEvents = $lunchBreak_CalendarEvent->generate ();
echo 'Lunch break event dates :<br>' . $lunchBreakCalendarEvents . '<br><br>';

$lunchBreak_xml = $lunchBreak_CalendarEvent->__toXML ( 'lunchBreak' );
file_put_contents ( 'RecurringEvents/LunchBreak.xml', $lunchBreak_xml );

// MORNING RECESS
// start & end time
$morning_recess_startTime = new CalendarTime ( '09', '45', '00' );
$morning_recess_endTime = new CalendarTime ( '10', '15', '00' );
// creation & generation
$morning_recess_CalendarEvent = new RecurringEvent ( 'Morning Recess', $morning_recess_startTime, $morning_recess_endTime, $recurringEvent_timeSlot );
$morning_recessCalendarEvents = $morning_recess_CalendarEvent->generate ();
echo 'Morning recess break event dates :<br>' . $morning_recessCalendarEvents . '<br><br>';

$morning_recess_xml = $morning_recess_CalendarEvent->__toXML ( 'morning_recess' );
file_put_contents ( 'RecurringEvents/Morning_recess.xml', $morning_recess_xml );

// AFTERNOON RECESS
// start & end time
$afternoon_recess_startTime = new CalendarTime ( '15', '45', '00' );
$afternoon_recess_endTime = new CalendarTime ( '16', '15', '00' );
// creation & generation
$afternoon_recess_CalendarEvent = new RecurringEvent ( 'Afternoon Recess', $afternoon_recess_startTime, $afternoon_recess_endTime, $recurringEvent_timeSlot );
$afternoon_recessCalendarEvents = $afternoon_recess_CalendarEvent->generate ();
echo 'Afternoon recess break event dates :<br>' . $afternoon_recessCalendarEvents . '<br><br>';

$afternoon_recess_xml = $afternoon_recess_CalendarEvent->__toXML ( 'afternoon_recess' );
file_put_contents ( 'RecurringEvents/Afternoon_recess.xml', $afternoon_recess_xml );

$all_CalendarEvents = new ArrayCollection ();
CalendarEvent::add ( $all_CalendarEvents, $calendar_events );
CalendarEvent::add ( $all_CalendarEvents, $weekendCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $sleepCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $lunchBreakCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $morning_recessCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $afternoon_recessCalendarEvents );
// echo 'All event dates :<br>' . $all_CalendarEvents . '<br><br>';

$generated = RecurringEvent::map_generate ( new ArrayCollection ( array (
		$weekend_CalendarEvent,
		$sleep_CalendarEvent,
		$lunchBreak_CalendarEvent,
		$morning_recess_CalendarEvent,
		$afternoon_recess_CalendarEvent 
) ) );

echo 'Generated :<br>' . $generated . '<br><br>';

// free event dates
$freeCalendarEvents = CalendarFreeTime::retrieveFreeCalendarEvents ( $all_CalendarEvents, $task_CalendarEvent );
echo 'Free event dates :<br>' . $freeCalendarEvents . '<br><br>';

// creating ics calendar
$icsCalendar = CalendarFreeTime::createCalendar ( $freeCalendarEvents );
echo 'ICS Calendar :<br>' . htmlspecialchars ( file_get_contents ( 'CalendarFreeTime.ics' ) ) . '<br><br>';

// computing time left
$timeLeft = CalendarFreeTime::timeLeft ( $freeCalendarEvents );
echo 'Time left : ' . $timeLeft . '<br><br>';
?>