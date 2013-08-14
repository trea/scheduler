<?php
namespace Trea\Scheduler;

class Scheduler {
	const VERSION = 0.1;

	protected $windowStart;
	protected $windowEnd;
	protected $interval;
	protected $busy;
	protected $minutes;

	function slotsAtInterval(\DateInterval $interval) {
		$this->interval = $interval;
		$this->minutes = $interval->i;
		return $this;
	}

	function windowStarts(\DateTime $start) {
		$roundMin = $this->minutes * ceil($start->format('i') / $this->minutes);
		$this->windowStart = $start->setTime($start->format('H'), $roundMin, 0);
		return $this;
	}

	function windowEnds(\DateTime $end) {
		$this->windowEnd = $end;
		return $this;
	}


	function busy($busy = array()) {
		if (!is_array($busy)) {
			$json = json_decode($busy);
			
			if (!$json) {
				throw new Exception("Unknown input passed to Scheduler::busy()");
			}
			else {
				$this->busy = $json;
			}
		}
		else {
			$this->busy = $busy;
		}
		return $this;
	}

	function findTimes() {
		if ($this->validate()) {
			$available = [];
			foreach (new \DatePeriod($this->windowStart, $this->interval, $this->windowEnd) as $dt) {
				$available[] = $dt;
			}
			return $available;
		}
	}

	private function validate () {
		if (is_null($this->windowStart)) {
			throw new Exception("Scheduler::windowStart cannot be empty.  Set with Scheduler::windowStarts() method.");
		}

		if (is_null($this->interval)) {
			throw new Exception("Scheduler::interval cannot be empty.  Set with Scheduler::inteval() method.");
		}

		if (is_null($this->windowEnd)) {
			throw new Exception("Scheduler::windowEnd cannot be empty.  Set with Scheduler::windowEnds() method.");
		}

		return true;
	}

	function version() {
		return self::VERSION;
	}
}