<?php
class CalendarDate extends DateTime {
	public function __construct($year, $month, $day, $hour, $minute, $second) {
		parent::__construct ();
		$this->setCalendarDate ( $year, $month, $day, $hour, $minute, $second );
	}
	public function setCalendarDate($year, $month, $day, $hour, $minute, $second) {
		$this->setDate ( $year, $month, $day );
		$this->setTime ( $hour, $minute, $second );
	}
	public function getYear() {
		return $this->format ( 'Y' );
	}
	public function setYear($year) {
		$this->setDate ( $year, $this->getMonth (), $this->getDay () );
	}
	public function getMonth() {
		return $this->format ( 'm' );
	}
	public function setMonth($month) {
		$this->setDate ( $this->getYear (), $month, $this->getDay () );
	}
	public function getDay() {
		return $this->format ( 'd' );
	}
	public function setDay($day) {
		$this->setDate ( $this->getYear (), $this->getMonth (), $day );
	}
	public function getHour() {
		return $this->format ( 'H' );
	}
	public function setHour($hour) {
		$this->setTime ( $hour, $this->getMinute (), $this->getSecond () );
	}
	public function getMinute() {
		return $this->format ( 'i' );
	}
	public function setMinute($minute) {
		$this->setTime ( $this->getHour (), $minute, $this->getSecond () );
	}
	public function getSecond() {
		return $this->format ( 's' );
	}
	public function setSecond($second) {
		$this->setTime ( $this->getHour (), $this->getMinute (), $second () );
	}
	public function __toString() {
		return $this->format ( 'Y-m-d H:i:s' );
	}
	public function __toIcsDate() {
		return $this->format('Ymd').'T'.$this->format('His').'Z';
	}
}