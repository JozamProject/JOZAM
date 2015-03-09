<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
require_once ('CalendarDate.php');
require_once ('CalendarEvent.php');
require_once ('RecurringEvent.php');
require_once ('CalendarFreeTime.php');

/**
 * This file aims to show how to use the functions of the CalendarFreeTime utility class.
 */

// task calendar event
$task_CalendarEvent = new CalendarEvent ( new CalendarDate ( '2015', '03', '02', '00', '00', '00' ), new CalendarDate ( '2015', '03', '09', '00', '00', '00' ) );
echo 'Task event date :<br>' . $task_CalendarEvent . '<br><br>';

// retrieve calendar
$calendar = CalendarFreeTime::retrieveCalendar ( 'ADECal.ics' );
//$calendar = CalendarFreeTime::retrieveCalendar ( 'https://www.google.com/calendar/ical/vfp5pk3n8udcuvsj7l5deuruas%40group.calendar.google.com/private-ca0015ea949cf5cd75f80f56118597dd/basic.ics' );
//$calendar = CalendarFreeTime::retrieveCalendar ( 'http://edt.inp-toulouse.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=150&projectId=4&calType=ical&nbWeeks=20' );

// retrieved dates for .ics file
$retrievedDates = CalendarFreeTime::retrieveCalendarEvents ( $calendar );
// echo 'Calendar event dates :<br>' . $retrievedDates . '<br><br>';

// sort retrieved dates
CalendarEvent::sort ( $retrievedDates, false );
echo 'Sorted calendar event dates : <br>' . $retrievedDates . '<br><br>';

// WEEKEND busy event dates
// busy start & end hours
$weekend_startHour = new CalendarTime ( '00', '00', '00' );
$weekend_endHour = new CalendarTime ( '00', '00', '00' );
// busy time slot
$weekend_startDate = new CalendarDate ( '2015', '03', '02' );
$weekend_endDate = new CalendarDate ( '2015', '03', '09' );
$weekend_timeSlot = new CalendarEvent ( $weekend_startDate, $weekend_endDate );
// busy days
$weekend = array (
		'Saturday',
		'Sunday' 
);
// busy event dates creation & generation
$weekend_CalendarEvent = new RecurringEvent ( $weekend_startHour, $weekend_endHour, $weekend_timeSlot, $weekend );
$weekendCalendarEvents = $weekend_CalendarEvent->generate ();
echo 'Weekend event dates :<br>' . $weekendCalendarEvents . '<br><br>';

// SLEEP busy event dates
// busy start & end hours
$sleep_startHour = new CalendarTime ( '18', '00', '00' );
$sleep_endHour = new CalendarTime ( '08', '00', '00' );
// busy time slot
$sleep_startDate = new CalendarDate ( '2015', '03', '01' );
$sleep_endDate = new CalendarDate ( '2015', '03', '09' );
$sleep_timeSlot = new CalendarEvent ( $sleep_startDate, $sleep_endDate );
// busy days
// $sleep = array('Saturday', 'Sunday');
// busy event dates creation & generation
$sleep_CalendarEvent = new RecurringEvent ( $sleep_startHour, $sleep_endHour, $sleep_timeSlot );
$sleepCalendarEvents = $sleep_CalendarEvent->generate ();
echo 'Sleep event dates :<br>' . $sleepCalendarEvents . '<br><br>';

// LUNCHBREAK busy event date
// busy start & end hours
$lunchBreak_startHour = new CalendarTime ( '12', '00', '00' );
$lunchBreak_endHour = new CalendarTime ( '14', '00', '00' );
// busy time slot
$lunchBreak_startDate = new CalendarDate ( '2015', '03', '01' );
$lunchBreak_endDate = new CalendarDate ( '2015', '03', '09' );
$lunchBreak_timeSlot = new CalendarEvent ( $lunchBreak_startDate, $lunchBreak_endDate );
// busy days
// $lunchBreak = array('Saturday', 'Sunday');
// busy event dates creation & generation
$lunchBreak_CalendarEvent = new RecurringEvent ( $lunchBreak_startHour, $lunchBreak_endHour, $lunchBreak_timeSlot );
$lunchBreakCalendarEvents = $lunchBreak_CalendarEvent->generate ();
echo 'Lunch break event dates :<br>' . $lunchBreakCalendarEvents . '<br><br>';

// morning_recess busy event date
// busy start & end hours
$morning_recessBreak_startHour = new CalendarTime ( '09', '45', '00' );
$morning_recessBreak_endHour = new CalendarTime ( '10', '15', '00' );
// busy time slot
$morning_recessBreak_startDate = new CalendarDate ( '2015', '03', '01' );
$morning_recessBreak_endDate = new CalendarDate ( '2015', '03', '09' );
$morning_recessBreak_timeSlot = new CalendarEvent ( $morning_recessBreak_startDate, $morning_recessBreak_endDate );
// busy days
// $morning_recessBreak = array('Saturday', 'Sunday');
// busy event dates creation & generation
$morning_recessBreak_CalendarEvent = new RecurringEvent ( $morning_recessBreak_startHour, $morning_recessBreak_endHour, $morning_recessBreak_timeSlot );
$morning_recessBreakCalendarEvents = $morning_recessBreak_CalendarEvent->generate ();
echo 'Morning recess break event dates :<br>' . $morning_recessBreakCalendarEvents . '<br><br>';

// afternoon_recess busy event date
// busy start & end hours
$afternoon_recessBreak_startHour = new CalendarTime ( '15', '45', '00' );
$afternoon_recessBreak_endHour = new CalendarTime ( '16', '15', '00' );
// busy time slot
$afternoon_recessBreak_startDate = new CalendarDate ( '2015', '03', '01' );
$afternoon_recessBreak_endDate = new CalendarDate ( '2015', '03', '09' );
$afternoon_recessBreak_timeSlot = new CalendarEvent ( $afternoon_recessBreak_startDate, $afternoon_recessBreak_endDate );
// busy days
// $afternoon_recessBreak = array('Saturday', 'Sunday');
// busy event dates creation & generation
$afternoon_recessBreak_CalendarEvent = new RecurringEvent ( $afternoon_recessBreak_startHour, $afternoon_recessBreak_endHour, $afternoon_recessBreak_timeSlot );
$afternoon_recessBreakCalendarEvents = $afternoon_recessBreak_CalendarEvent->generate ();
echo 'Afternoon recess break event dates :<br>' . $afternoon_recessBreakCalendarEvents . '<br><br>';

$all_CalendarEvents = new ArrayCollection ();
CalendarEvent::add ( $all_CalendarEvents, $retrievedDates );
CalendarEvent::add ( $all_CalendarEvents, $weekendCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $sleepCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $lunchBreakCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $morning_recessBreakCalendarEvents );
CalendarEvent::add ( $all_CalendarEvents, $afternoon_recessBreakCalendarEvents );
// EVENTS INTERSECTION /!\
// echo 'All event dates :<br>' . $all_CalendarEvents . '<br><br>';

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