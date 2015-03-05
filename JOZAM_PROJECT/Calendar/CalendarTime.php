<?php
/**
 * A CalendarTime is a time format used in BusyEventDate to describe recurring events.
 *
 * @since 1.0
 * @author Jaafar Bouayad <bouayad.jaafar@gmail.com>
 */
class CalendarTime {
	/**
	 * The hour.
	 *
	 * @var string|integer
	 */
	private $hour;
	
	/**
	 * The minute.
	 *
	 * @var string|integer
	 */
	private $minute;
	
	/**
	 * The second.
	 *
	 * @var string|integer
	 */
	private $second;
	
	/**
	 * Initializes a new CalendarTime.
	 *
	 * @param string|integer $hour        	
	 * @param string|integer $minute        	
	 * @param string|integer $second        	
	 */
	function __construct($hour, $minute, $second) {
		$this->setHour ( $hour );
		$this->setMinute ( $minute );
		$this->setSecond ( $second );
	}
	
	/**
	 * Sets the hour.
	 *
	 * @param string|integer $hour        	
	 *
	 * @return void
	 */
	public function setHour($hour) {
		$this->hour = $hour;
	}
	
	/**
	 * Returns the hour.
	 *
	 * @return string|integer
	 */
	public function getHour() {
		return $this->hour;
	}
	
	/**
	 * Sets the minute.
	 *
	 * @param string|integer $minute        	
	 *
	 * @return void
	 */
	public function setMinute($minute) {
		$this->minute = $minute;
	}
	
	/**
	 * Returns the minute.
	 *
	 * @return string|integer
	 */
	public function getMinute() {
		return $this->minute;
	}
	
	/**
	 * Sets the second.
	 *
	 * @param string|integer $second        	
	 *
	 * @return void
	 */
	public function setSecond($second) {
		$this->second = $second;
	}
	
	/**
	 * Returns the second.
	 *
	 * @return string|integer
	 */
	public function getSecond() {
		return $this->second;
	}
	
	/**
	 * Returns a string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->getHour () . ':' . $this->getMinute () . ':' . $this->getSecond ();
	}
	
	/**
	 * Generates a Simple XML Element string matching this object.
	 *
	 * @param String $name
	 *        	Name of the Simple XML Element.
	 *        	
	 * @return String Simple XML Element string matching this object.
	 */
	public function __toXML($name = 'calendarTime') {
		$xml_string = '<' . $name . ' hour = "' . $this->getHour () . '" minute = "' . $this->getMinute () . '" second = "' . $this->getSecond () . '"/>
';
		
		return $xml_string;
	}
	
	/**
	 * Generates a CalendarTime matching a SimpleXMLElement.
	 *
	 * @param SimpleXMLElement $calendarTime
	 *        	A Simple XML Element.
	 *        	
	 * @return CalendarTime RecurringEvent matching the Simple XML Element.
	 */
	public static function XML_to_CalendarTime($calendarTime) {
		return new CalendarTime ( $calendarTime ['hour'], $calendarTime ['minute'], $calendarTime ['second'] );
	}
}
?>