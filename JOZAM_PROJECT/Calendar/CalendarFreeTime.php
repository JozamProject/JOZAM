<?php
use Collections\ArrayCollection;
require_once ('Collections/ArrayCollection.php');
require_once ('CalendarTime.php');
require_once ('CalendarDate.php');
require_once ('CalendarEvent.php');
require_once ('RecurringEvent.php');

/**
 * CalendarFreeTime is a utility class that provides a way to :
 * - retrieve data from an ics file
 * - retrieve free calendar events from these data
 * - create an ics file containing the free calendar events
 * - compute the time left for a set of free calendar events
 *
 * @since 1.0
 * @author Jaafar Bouayad <bouayad.jaafar@gmail.com>
 */
class CalendarFreeTime {
	/**
	 * Transforms a date string into a calendar date.
	 *
	 * @param string $date
	 *        	date string.
	 *        	
	 * @return CalendarDate
	 */
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
	
	/**
	 * Transforms an ICS format start date string into a calendar date.
	 *
	 * @param string $date
	 *        	ICS format start date string.
	 *        	
	 * @return CalendarDate
	 */
	static function retrieveStartDate($date) {
		$date = substr ( $date, 8, 16 );
		return CalendarFreeTime::retrieveDate ( $date );
	}
	
	/**
	 * Transforms an ICS format start date string into a calendar date.
	 *
	 * @param string $date
	 *        	ICS format start date string.
	 *        	
	 * @return CalendarDate
	 */
	static function retrieveEndDate($date) {
		$date = substr ( $date, 6, 16 );
		return CalendarFreeTime::retrieveDate ( $date );
	}
	
	/**
	 * Retrieves the calendar from an ICS file path.
	 *
	 * @param string $calendar_path
	 *        	ICS file path.
	 *        	
	 * @return string
	 */
	static function retrieveCalendar($calendar_path) {
		return file_get_contents ( $calendar_path );
	}
	
	/**
	 * Retrieves the calendar events from the ICS calendar.
	 *
	 * @param string $calendar
	 *        	An ICS calendar.
	 *        	
	 * @return ArrayCollection
	 */
	static function retrieveCalendarEvents($calendar) {
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
		$retrievedCalendarEvents = new ArrayCollection ();
		for($i = 0; $i < $nbDates; $i ++) {
			$retrievedCalendarEvents->add ( new CalendarEvent ( CalendarFreeTime::retrieveStartDate ( $startDates->get ( $i ) ), CalendarFreeTime::retrieveEndDate ( $endDates->get ( $i ) ) ) );
		}
		return $retrievedCalendarEvents;
	}
	
	/**
	 * Retrieves the free calendar events from the time slot.
	 *
	 * @param ArrayCollection $calendarEvents
	 *        	Calendar events.
	 *        	
	 * @param CalendarEvent $timeSlot
	 *        	A calendar event.
	 *        	
	 * @return ArrayCollection
	 */
	static function retrieveFreeCalendarEvents($calendarEvents, $timeSlot) {
		CalendarEvent::sort ( $calendarEvents );
		// debug
		// echo 'Sorted event dates :<br>' . $calendarEvents . '<br><br>';
		$freeCalendarEvents = $timeSlot->removeAllCalendarEvents ( $calendarEvents );
		return $freeCalendarEvents;
	}
	
	/**
	 * Create an ics file containing the calendar events.
	 *
	 * @param ArrayCollection $calendarEvents
	 *        	Calendar events.
	 *        	
	 * @param string $recurringEvent_name
	 *        	Name of the recurring event (is 'Free Time' by default).
	 *        	
	 * @param string $calendar_file_path
	 *        	The calendar file path to put contents in (is 'CalendarFreeTime.ics' by default).
	 *        	
	 * @return void
	 */
	static function createCalendar($calendarEvents, $recurringEvent_name = 'Free Time', $calendar_file_path = 'CalendarFreeTime.ics') {
		$calendar = CalendarEvent::__toIcsCalendar ( $calendarEvents, $recurringEvent_name );
		file_put_contents ( $calendar_file_path, $calendar );
	}
	
	/**
	 * Computes the duration of all the free calendar events.
	 *
	 * @param ArrayCollection $freeCalendarEvents
	 *        	The free calendar events.
	 *        	
	 * @return string | integer
	 */
	static function timeLeft($freeCalendarEvents) {
		$timeLeft = 0;
		foreach ( $freeCalendarEvents as $fed ) {
			$timeLeft += $fed->duration ()->getNbSeconds ();
		}
		$return = new CalendarDuration ();
		$return->setNbSeconds ( $timeLeft );
		return $return;
	}
}
?>