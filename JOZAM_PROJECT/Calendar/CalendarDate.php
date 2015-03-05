<?php
/**
 * A CalendarDate provides a set of custom functions for the DateTime class.
 *
 * @since 1.0
 * @author Jaafar Bouayad <bouayad.jaafar@gmail.com>
 */
class CalendarDate extends DateTime {
	/**
	 * Initializes a new CalendarDate.
	 *
	 * @param string|integer $year
	 *        	The year.
	 * @param string|integer $month
	 *        	The month.
	 * @param string|integer $day
	 *        	The day.
	 * @param string|integer $hour
	 *        	The hour.
	 * @param string|integer $minute
	 *        	The minute.
	 * @param string|integer $second
	 *        	The second.
	 */
	public function __construct($year = '1970', $month = '01', $day = '01', $hour = '00', $minute = '00', $second = '00') {
		parent::__construct ();
		$this->setCalendarDate ( $year, $month, $day, $hour, $minute, $second );
	}
	
	/**
	 * Sets the calendar date.
	 *
	 * @param string|integer $year
	 *        	The year.
	 * @param string|integer $month
	 *        	The month.
	 * @param string|integer $day
	 *        	The day.
	 * @param string|integer $hour
	 *        	The hour.
	 * @param string|integer $minute
	 *        	The minute.
	 * @param string|integer $second
	 *        	The second.
	 *        	
	 * @return void
	 */
	public function setCalendarDate($year, $month, $day, $hour, $minute, $second) {
		$this->setDate ( $year, $month, $day );
		$this->setTime ( $hour, $minute, $second );
	}
	
	/**
	 * Returns the year.
	 *
	 * @return string|integer
	 */
	public function getYear() {
		return $this->format ( 'Y' );
	}
	
	/**
	 * Sets the year.
	 *
	 * @param string|integer $year
	 *        	The year.
	 *        	
	 * @return void
	 */
	public function setYear($year) {
		$this->setDate ( $year, $this->getMonth (), $this->getDay () );
	}
	
	/**
	 * Returns the month.
	 *
	 * @return string|integer
	 */
	public function getMonth() {
		return $this->format ( 'm' );
	}
	
	/**
	 * Sets the month.
	 *
	 * @param string|integer $month
	 *        	The month.
	 *        	
	 * @return void
	 */
	public function setMonth($month) {
		$this->setDate ( $this->getYear (), $month, $this->getDay () );
	}
	
	/**
	 * Returns the day.
	 *
	 * @return string|integer
	 */
	public function getDay() {
		return $this->format ( 'd' );
	}
	
	/**
	 * Sets the day.
	 *
	 * @param string|integer $day
	 *        	The day.
	 *        	
	 * @return void
	 */
	public function setDay($day) {
		$this->setDate ( $this->getYear (), $this->getMonth (), $day );
	}
	
	/**
	 * Returns the hour.
	 *
	 * @return string|integer
	 */
	public function getHour() {
		return $this->format ( 'H' );
	}
	
	/**
	 * Sets the hour.
	 *
	 * @param string|integer $hour
	 *        	The hour.
	 *        	
	 * @return void
	 */
	public function setHour($hour) {
		$this->setTime ( $hour, $this->getMinute (), $this->getSecond () );
	}
	
	/**
	 * Returns the minute.
	 *
	 * @return string|integer
	 */
	public function getMinute() {
		return $this->format ( 'i' );
	}
	
	/**
	 * Sets the minute.
	 *
	 * @param string|integer $minute
	 *        	The minute.
	 *        	
	 * @return void
	 */
	public function setMinute($minute) {
		$this->setTime ( $this->getHour (), $minute, $this->getSecond () );
	}
	
	/**
	 * Returns the second.
	 *
	 * @return string|integer
	 */
	public function getSecond() {
		return $this->format ( 's' );
	}
	
	/**
	 * Sets the second.
	 *
	 * @param string|integer $second
	 *        	The second.
	 *        	
	 * @return void
	 */
	public function setSecond($second) {
		$this->setTime ( $this->getHour (), $this->getMinute (), $second () );
	}
	
	/**
	 * Returns a string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->format ( 'Y-m-d H:i:s' );
	}
	
	/**
	 * Returns an ICS date format of this object.
	 *
	 * @return string
	 */
	public function __toIcsDate() {
		$return = $this->format ( 'Ymd\THis' );
		$timeZone = new DateTimeZone ( 'UTC' );
		$dateTime = new DateTime ( $return );
		$dateTime->setTimezone ( $timeZone );
		$return = $dateTime->format ( 'Ymd\THis' ) . 'Z';
		return $return;
	}
	
	/**
	 * Generates a Simple XML Element string matching this object.
	 *
	 * @param String $name
	 *        	Name of the Simple XML Element.
	 *        	
	 * @return String Simple XML Element string matching this object.
	 */
	public function __toXML($name = 'calendarDate') {
		$xml_string = '<' . $name . ' year = "' . $this->getYear () . '" month = "' . $this->getMonth () . '" day = "' . $this->getDay () . '" hour = "' . $this->getHour () . '" minute = "' . $this->getMinute () . '" second = "' . $this->getSecond () . '"/>
';
		return $xml_string;
	}
	
	/**
	 * Generates a CalendarDate matching a SimpleXMLElement.
	 *
	 * @param SimpleXMLElement $calendarDate
	 *        	A Simple XML Element.
	 *        	
	 * @return CalendarDate CalendarDate matching the Simple XML Element.
	 */
	public static function XML_to_CalendarDate($calendarDate) {
		return new CalendarDate ( (int) $calendarDate ['year'], (int) $calendarDate ['month'], (int) $calendarDate ['day'], (int) $calendarDate ['hour'], (int) $calendarDate ['minute'], (int) $calendarDate ['second'] );
	}
}