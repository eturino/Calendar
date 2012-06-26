<?php

class EtuDev_Calendar_Control {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var bool
	 */
	protected $defaultOpen = true;

	/**
	 * @var DateTime|string
	 */
	protected $minDate;

	/**
	 * @var DateTime|string
	 */
	protected $maxDate;

	/**
	 * @var int
	 */
	protected $minHours;

	/**
	 * @var string
	 */
	protected $datePickerId;

	/**
	 * @var string
	 */
	protected $timePickerId;


	/**
	 * @var array of EtuDev_Calendar_Date
	 */
	protected $datesStatus = array();

	/**
	 * @var array of EtuDev_Calendar_Hour
	 */
	protected $hoursStatus = array();

	/**
	 * @var array of EtuDev_Calendar_Hour
	 */
	protected $defaultHours = array();

	/**
	 * @var DateTimeZone
	 */
	protected $timeZone;

	/**
	 * @return DateTimeZone
	 * @uses $timeZone
	 * @uses EtuDev_Util_Date::getDateTimeZone()
	 */
	public function getTimeZone() {
		if (!$this->timeZone) {
			return EtuDev_Util_Date::getDateTimeZone();
		}

		return $this->timeZone;
	}

	/**
	 * @param DateTimeZone|string $t
	 *
	 * @return EtuDev_Calendar_Control
	 * @uses $timeZone
	 */
	public function setTimeZone($t) {
		if (!$t) {
			$this->timeZone = null;
			return $this;
		}

		if ($t instanceof DateTimeZone) {
			$this->timeZone = $t;
			return $this;
		}

		//TODO montar timezone a partir de string

		return $this;
	}

	/**
	 * @return string the $id
	 * @uses $id
	 * @uses EtuDev_Util_String::getRandomString()
	 */
	public function getId() {
		if (!$this->id) {
			$this->id = EtuDev_Util_String::getRandomString(10);
		}
		return $this->id;
	}

	/**
	 * @return bool the $defaultOpen
	 * @uses $defaultOpen
	 */
	public function getDefaultOpen() {
		return $this->defaultOpen;
	}

	/**
	 * @return DateTime the $minDate
	 * @uses $minDate
	 */
	public function getMinDate() {
		return $this->minDate;
	}

	/**
	 * @return DateTime the $maxDate
	 * @uses $maxDate
	 */
	public function getMaxDate() {
		return $this->maxDate;
	}

	/**
	 * @return int
	 * @uses $minHours
	 */
	public function getMinHours() {
		return $this->minHours;
	}

	/**
	 * @return string the $datePickerId
	 * @uses $datePickerId
	 */
	public function getDatePickerId() {
		return $this->datePickerId;
	}

	/**
	 * @return string the $timePickerId
	 * @uses $timePickerId
	 */
	public function getTimePickerId() {
		return $this->timePickerId;
	}

	/**
	 * @return array the $hoursStatus
	 * @uses $hoursStatus
	 */
	public function getHoursStatus() {
		return $this->hoursStatus;
	}

	/**
	 * @return array the $defaultHours
	 * @uses $defaultHours
	 */
	public function getDefaultHoursStatus() {
		return $this->defaultHours;
	}

	/**
	 * get all CalendarHour used in all CalendarDate and adds them
	 * @uses getDatesStatus()
	 * @uses EtuDev_Calendar_Date::getHourIds()
	 * @uses EtuDev_Calendar_Hour::getCalendarHour()
	 * @uses addHourStatus()
	 * @return EtuDev_Calendar_Control
	 */
	public function addHourStatusOfDateStatus() {
		$ids = array();
		$dss = $this->getDatesStatus();
		foreach ($dss as $ds) {
			/* @var $ds EtuDev_Calendar_Date */
			;
			$ids = $ids + $ds->getHourIds();
		}
		$ids = array_unique($ids);
		foreach ($ids as $id) {
			$this->addHourStatus($id, EtuDev_Calendar_Hour::getCalendarHour($id));
		}

		return $this;
	}

	/**
	 * @param string $id
	 *
	 * @return EtuDev_Calendar_Hour
	 * @uses $hoursStatus
	 */
	public function getHourStatus($id) {
		return array_key_exists($id, $this->hoursStatus) ? $this->hoursStatus[$id] : null;
	}

	/**
	 * @return array the $datesStatus
	 * @uses $datesStatus
	 */
	public function getDatesStatus() {
		return $this->datesStatus;
	}

	/**
	 * @param string $id
	 *
	 * @uses $id
	 * @return EtuDev_Calendar_Control
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @param bool $defaultOpen
	 *
	 * @uses $defaultOpen
	 * @return EtuDev_Calendar_Control
	 */
	public function setDefaultOpen($defaultOpen) {
		$this->defaultOpen = ($defaultOpen == true);
		return $this;
	}

	/**
	 * @param DateTime|string $minDate
	 *
	 * @uses $minDate
	 * @return EtuDev_Calendar_Control
	 */
	public function setMinDate($minDate) {
		if (is_string($minDate) && EtuDev_Util_Date::isDate($minDate)) {
			$minDate = EtuDev_Util_Date::getDateSection($minDate);
		}

		$this->minDate = $minDate;
		return $this;
	}

	/**
	 * @param DateTime|string $maxDate
	 *
	 * @uses $maxDate
	 * @return EtuDev_Calendar_Control
	 */
	public function setMaxDate($maxDate) {
		if (is_string($maxDate) && EtuDev_Util_Date::isDate($maxDate)) {
			$maxDate = EtuDev_Util_Date::getDateSection($maxDate);
		}

		$this->maxDate = $maxDate;
		return $this;
	}

	/**
	 * @param int $minHours
	 *
	 * @uses $minHours
	 * @return EtuDev_Calendar_Control
	 */
	public function setMinHours($minHours) {
		$this->minHours = $minHours;
		return $this;
	}

	/**
	 * @param string $datePickerId
	 *
	 * @uses $datePickerId
	 * @return EtuDev_Calendar_Control
	 */
	public function setDatePickerId($datePickerId) {
		$this->datePickerId = $datePickerId;
		return $this;
	}

	/**
	 * @param string $timePickerId
	 *
	 * @uses $timePickerId
	 * @return EtuDev_Calendar_Control
	 */
	public function setTimePickerId($timePickerId) {
		$this->timePickerId = $timePickerId;
		return $this;
	}

	/**
	 * @param array $datesStatus
	 *
	 * @uses $datesStatus
	 * @return EtuDev_Calendar_Control
	 */
	public function setDatesStatus($datesStatus) {
		$this->datesStatus = $datesStatus;
		return $this;
	}

	/**
	 * @param array $hoursStatus
	 *
	 * @uses $hoursStatus
	 * @return EtuDev_Calendar_Control
	 */
	public function setHoursStatus($hoursStatus) {
		$this->hoursStatus = $hoursStatus;
		return $this;
	}

	/**
	 * @param string $key date string
	 *
	 * @return EtuDev_Calendar_Date
	 */
	public function getDateStatus($key) {
		return $this->datesStatus[$key];
	}

	/**
	 * @param $ds
	 *
	 * @return EtuDev_Calendar_Control
	 * @throws Exception
	 */
	public function addDateStatus($ds) {
		if ($ds) {
			if ($ds instanceof EtuDev_Calendar_Date) {
				$v = $ds;
			} else {
				$v = new EtuDev_Calendar_Date($ds);
			}

			$key = $v->getDateStringKey();
			if ($key) {
				return $this->setDateStatus($key, $ds);
			}
		}

		throw new Exception('invalid DateStatus');
	}

	/**
	 *
	 * @param string              $key date string
	 * @param EtuDev_Calendar_Date $ds
	 *
	 * @return EtuDev_Calendar_Control
	 * @uses EtuDev_Calendar_Date::_construct()
	 */
	public function setDateStatus($key, $ds) {
		$v = null;
		if ($ds) {
			if ($ds instanceof EtuDev_Calendar_Date) {
				$v = $ds;
			} else {
				$v = new EtuDev_Calendar_Date($ds);
			}

			//si no hay info de la fecha, se la establecemos usando la KEY que debe ser la fecha
			if (!$v->getDate()) {
				$v->setDate($key);
			}
		}
		$this->datesStatus[$key] = $v;
		return $this;
	}

	/**
	 * alias setHourStatus()
	 *
	 * @param string              $id id of the CalendarHour
	 * @param EtuDev_Calendar_Hour $hs
	 *
	 * @return EtuDev_Calendar_Control
	 *
	 * @uses setHourStatus()
	 */
	public function addHourStatus($id, $hs) {
		return $this->setHourStatus($id, $hs);
	}

	/**
	 * adds multiple hours (foreach the given array using addHourStatus())
	 *
	 * @param array $a
	 *
	 * @uses addHourStatus()
	 * @return EtuDev_Calendar_Control
	 */
	public function addHours($a) {
		/** @var $a EtuDev_Calendar_Hour */
		if ($a instanceof EtuDev_Calendar_Hour) {
			$this->addHourStatus($a->getId(), $a);
			return $this;
		}

		/** @var $a array */
		if (is_array($a)) {
			/** @var $x EtuDev_Calendar_Hour */
			foreach ($a as $x) {
				$this->addHourStatus($x->getId(), $x);
			}
		}

		return $this;
	}

	/**
	 *
	 * @param string              $id id of the CalendarHour
	 * @param EtuDev_Calendar_Hour $hs
	 *
	 * @return EtuDev_Calendar_Control
	 * @uses EtuDev_Calendar_Hour::_construct()
	 */
	public function setHourStatus($id, $hs) {
		$v = null;
		if ($hs) {
			if ($hs instanceof EtuDev_Calendar_Hour) {
				$v = $hs;
			} else {
				$v = new EtuDev_Calendar_Hour($hs);
			}
		}
		$this->hoursStatus[$id] = $v;
		return $this;
	}

	/**
	 * @param array|EtuDev_Calendar_Hour $defHours array of EtuDev_Calendar_Hour
	 *
	 * @uses $defaultHours
	 * @uses EtuDev_Calendar_Hour
	 * @return EtuDev_Calendar_Control
	 */
	public function setDefaultHours($defHours) {
		if ($defHours instanceof EtuDev_Calendar_Hour) {
			$a = array($defHours);
		} elseif (is_array($defHours)) {
			$a = $defHours;
		} else {
			$a = array();
		}

		$this->defaultHours = array();
		foreach ($a as $hs) {
			if ($hs) {
				/** @var $v EtuDev_Calendar_Hour */
				if ($hs instanceof EtuDev_Calendar_Hour) {
					$v = $hs;
				} else {
					$v = new EtuDev_Calendar_Hour($hs);
				}
				$this->defaultHours[$v->getId()] = $v;
				$this->addHourStatus($v->getId(), $v);
			}
		}

		return $this;
	}

	protected static $instances = array();

	/**
	 * @param string $id
	 *
	 * @return EtuDev_Calendar_Control
	 * @uses $instances
	 */
	static public function getCalendarControl($id) {
		return self::$instances[$id];
	}

	/**
	 * static constructor
	 *
	 * @param array $a
	 *
	 * @uses __construct()
	 * @return EtuDev_Calendar_Control
	 */
	static public function newCalendarControl($a = array()) {
		return new EtuDev_Calendar_Control($a);
	}

	/**
	 * gets and if not exists creates using the given id
	 *
	 * @param string $id
	 * @param array  $a options for constructor
	 *
	 * @return EtuDev_Calendar_Control
	 */
	static public function getOrNewCalendarControl($id, $a = array()) {
		$x = self::getCalendarControl($id);
		if (!$x) {
			$a['id'] = $id;
			$x       = self::newCalendarControl($a);
		}

		return $x;
	}

	/**
	 * @param array $a
	 *
	 * @uses fromArray()
	 * @return EtuDev_Calendar_Control
	 */
	public function __construct($a = array()) {
		if ($a) {
			$this->fromArray($a);
		}

		if (!$this->defaultHours) {
			$this->setDefaultHours(EtuDev_Calendar_Hour::getDefaultCalendarHours());
		}

		while (array_key_exists($this->getId(), self::$instances)) {
			$this->setId(EtuDev_Util_String::getRandomString(10));
		}
		self::$instances[$this->getId()] = $this;
	}

	/**
	 * given an array with the variables exported, sets the info
	 *
	 * @param array $a
	 *
	 * @return EtuDev_Calendar_Control
	 * @uses setId()
	 * @uses setDefaultOpen()
	 * @uses setDatePickerId()
	 * @uses setMinDate()
	 * @uses setMaxDate()
	 * @uses addDateStatus()
	 */
	public function fromArray($a) {
		if (array_key_exists('id', $a)) {
			$this->setId($a['id']);
		}

		if (array_key_exists('defaultOpen', $a)) {
			$this->setDefaultOpen($a['defaultOpen']);
		}

		if (array_key_exists('datePickerId', $a)) {
			$this->setDatePickerId($a['datePickerId']);
		}

		if (array_key_exists('timePickerId', $a)) {
			$this->setTimePickerId($a['timePickerId']);
		}

		if (array_key_exists('minDate', $a)) {
			$this->setMinDate($a['minDate']);
		}

		if (array_key_exists('maxDate', $a)) {
			$this->setMaxDate($a['maxDate']);
		}

		if (array_key_exists('datesStatus', $a)) {
			foreach ($a['datesStatus'] as $ds) {
				$this->addDateStatus($ds);
			}
		}

		if (array_key_exists('hoursStatus', $a)) {
			foreach ($a['hoursStatus'] as $key => $hs) {
				$this->addHourStatus($key, $hs);
			}
		}

		if (array_key_exists('defaultHoursStatus', $a)) {
			$this->setDefaultHours($a);
		}


		return $this;
	}

	/**
	 * returns an array describing the object
	 *
	 * @param bool $withDateInDatesStatus
	 *
	 * @uses getId()
	 * @uses getDefaultOpen()
	 * @uses getDatePickerId()
	 * @uses DateTime::format()
	 * @uses getMinDate()
	 * @uses getMaxdate()
	 * @uses getDatesStatus()
	 * @uses EtuDev_Calendar_Date::toArray()
	 *
	 * @return array
	 */
	public function toArray($withDateInDatesStatus = true) {
		$this->addHourStatusOfDateStatus();

		$a = array('id' => $this->getId(), 'defaultOpen' => $this->getDefaultOpen(), 'datePickerId' => $this->getDatePickerId(), 'timePickerId' => $this->getTimePickerId());

		if (is_object($this->getMinDate()) || strlen(trim($this->getMinDate()))) {
			$md = $this->getMinDate();
			if ($md instanceof DateTime) {
				$md = $md->format('Y-m-d');
			}

			$a['minDate'] = $md;
		}

		if (is_object($this->getMaxDate()) || strlen(trim($this->getMaxDate()))) {
			$md = $this->getMaxDate();
			if ($md instanceof DateTime) {
				$md = $md->format('Y-m-d');
			}

			$a['maxDate'] = $md;
		}

		$dss  = array();
		$dsso = $this->getDatesStatus();
		foreach ($dsso as $key => $d) {
			/* @var $d EtuDev_Calendar_Date */
			$dss[$key] = $d->toArray(($withDateInDatesStatus == true));
		}

		$a['datesStatus'] = $dss;

		$hss  = array();
		$hsso = $this->getHoursStatus();
		foreach ($hsso as $key => $h) {
			/* @var $h EtuDev_Calendar_Hour */
			$hss[$key] = $h->toArray();
		}

		$a['hoursStatus'] = $hss;


		$hss  = array();
		$hsso = $this->getDefaultHoursStatus();
		foreach ($hsso as $key => $h) {
			/* @var $h EtuDev_Calendar_Hour */
			$hss[$key] = $h->toArray();
		}

		$a['defaultHoursStatus'] = $hss;

		return $a;
	}

	/**
	 * returns the script to execute the calendar control
	 *
	 * @param bool $withScriptWrapper
	 *
	 * @return string
	 */
	public function render($withScriptWrapper = true) {
		$script = $withScriptWrapper ? '<script type="text/javascript">' : '';
		$script .= '$(document).ready(function() {
						CalendarControlLibrary.getInstance().newCalendar("' . $this->getId() . '",' . json_encode($this->toArray()) . ').applyToDatepicker();
					});';
		$script .= $withScriptWrapper ? '</script>' : '';
		return $script;
	}


	public function applyMinHours($minHours = null) {
		if (!$minHours) {
			$minHours = $this->getMinHours();
		}

		if (!$minHours) {
			return $this;
		}

		/** @var $mh DateTime */
		$mh = EtuDev_Util_Date::getDateTimeNowPlusHours($minHours, $this->getTimeZone());

		if (!$mh) {
			return $this;
		}

		//comprobamos que las horas minimas no hayan acabado con el dia o incluso varios días
		/** @var $day DateTime */
		$day = EtuDev_Util_Date::getDateTimeToday(false, $this->getTimeZone());
		/** @var $diff DateInterval */
		$diff = $mh->diff($day);
		if ($diff->d) {
			for ($i = 0; $i < $diff->d; $i++) {
				$cday = EtuDev_Calendar_Date::newCalendarDate();
				$cday->setDate(clone($day));
				$cday->setOpen(false);
				$this->addDateStatus($cday);

				$day->modify('+1 day');
			}
		}

		EtuDev_Util_Date::dateTimeSetToday($mh);

		$cday = EtuDev_Calendar_Date::newCalendarDate();
		$cday->setDate(clone($day));

		$defs     = EtuDev_Calendar_Hour::getDefaultCalendarHours(false);
		$defs_ids = array();
		$hours     = array();
		/** @var $cch EtuDev_Calendar_Hour */
		foreach ($defs as $cch) {
			$defs_ids[] = $cch->getId();
			$x          = $cch->getCalendarHourFilteredByMinHour($mh, $this->getTimeZone());
			if ($x) {
				$hours[$x->getId()] = $x;
			}
		}

		if (array_keys($hours) != $defs_ids) {
			if ($hours) {
				$cday->addHours($hours);
			} else {
				$cday->setOpen(false);
			}

			$this->addDateStatus($cday);
		}

		return $this;
	}
}