<?php
class CalendarHour {
	private $hour;
	private $minute;
	private $second;
	function __construct($hour, $minute, $second) {
		$this->setHour ( $hour );
		$this->setMinute ( $minute );
		$this->setSecond ( $second );
	}
	public function setHour($hour) {
		$this->hour = $hour;
	}
	public function getHour() {
		return $this->hour;
	}
	public function setMinute($minute) {
		$this->minute = $minute;
	}
	public function getMinute() {
		return $this->minute;
	}
	public function setSecond($second) {
		$this->second = $second;
	}
	public function getSecond() {
		return $this->second;
	}
}
?>