<?php

class EtuDev_Calendar_Date {
	//TODO hours!

	/**
	 * @var array of CalendarControlHours
	 */
	protected $hours = array();

	/**
	 * @var DateTime
	 */
	protected $date;

	/**
	 * @var bool
	 */
	protected $open;

	/**
	 * @var string
	 */
	protected $cssClass;

	/**
	 * @var string
	 */
	protected $text;

	/**
	 * using getDate(), retrieves the date using DateTime::format() with format Y-m-d
	 * @return null|string
	 */
	public function getDateStringKey() {
		$d = $this->getDate();
		if ($d) {
			return $d->format('Y-m-d');
		}

		return null;
	}

	/**
	 * @return DateTime the $date
	 * @uses $date
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return bool the $open
	 * @uses $open
	 */
	public function getOpen() {
		return $this->open;
	}

	/**
	 * @return string the $cssClass
	 * @uses $cssClass
	 */
	public function getCssClass() {
		return $this->cssClass;
	}

	/**
	 * @return string the $text
	 * @uses $text
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @param DateTime|string $date
	 *
	 * @uses $date
	 * @return EtuDev_Calendar_Date
	 * @uses EtuDev_Util_Date::getInstance()
	 * @uses EtuDev_Util_Date::isDate()
	 * @uses EtuDev_Util_Date::getDateSection()
	 */
	public function setDate($date) {
		if ($date instanceof DateTime) {
			$this->date = $date;
		} elseif (is_string($date) && EtuDev_Util_Date::isDate($date)) {
			$this->date = new DateTime(EtuDev_Util_Date::getDateSection($date));
		} else {
			$this->date = null;
		}
		return $this;
	}

	/**
	 * @param bool $open
	 *
	 * @uses $open
	 * @return EtuDev_Calendar_Date
	 */
	public function setOpen($open) {
		$this->open = $open == true;
		return $this;
	}

	/**
	 * @param string $cssClass
	 *
	 * @uses $cssClass
	 * @return EtuDev_Calendar_Date
	 */
	public function setCssClass($cssClass) {
		$this->cssClass = $cssClass;
		return $this;
	}

	/**
	 * @param string $text
	 *
	 * @uses $text
	 * @return EtuDev_Calendar_Date
	 */
	public function setText($text) {
		$this->text = $text;
		return $this;
	}

	/**
	 * @param string $id
	 *
	 * @return EtuDev_Calendar_Hour
	 * @uses $hours
	 */
	public function getHour($id) {
		return $this->hours[$id];
	}

	/**
	 * @uses $hours
	 * @return array of EtuDev_Calendar_Hour
	 */
	public function getHours() {
		return $this->hours;
	}

	/**
	 * @return array hours ids
	 */
	public function getHourIds() {
		return array_keys($this->hours);
	}

	/**
	 * removes all hours and then adds the given array
	 *
	 * @param array $a of EtuDev_Calendar_Hour
	 *
	 * @uses addHours()
	 * @uses $hours
	 * @return EtuDev_Calendar_Date
	 */
	public function setHours($a) {
		$this->hours = array();
		return $this->addHours($a);
	}

	/**
	 * adds multiple hours (foreach the given array using addHour())
	 *
	 * @param array $a
	 *
	 * @uses addHour()
	 * @return EtuDev_Calendar_Date
	 */
	public function addHours($a) {
		if ($a instanceof EtuDev_Calendar_Hour) {
			$this->addHour($a);
			return $this;
		}

		if (is_array($a)) {
			foreach ($a as $x) {
				$this->addHour($x);
			}
		}

		return $this;
	}

	/**
	 * @param EtuDev_Calendar_Hour|string $id cch object or its ID (in that case we look for it using static functions)
	 * @param array                      $a
	 *
	 * @return EtuDev_Calendar_Date
	 * @uses EtuDev_Calendar_Hour::getId()
	 * @uses EtuDev_Calendar_Hour::getCalendarHour()
	 * @uses EtuDev_Calendar_Hour::getOrNewCalendarHour()
	 * @uses EtuDev_Calendar_Hour::newCalendarHour()
	 */
	public function addHour($id, $a = null) {
		if ($id instanceof EtuDev_Calendar_Hour && $id->getId()) {
			$x                = $id;
			$id               = $id->getId();
			$this->hours[$id] = $x;
			return $this;
		}

		$x = EtuDev_Calendar_Hour::getCalendarHour($id);
		if ($x) {
			$this->hours[$id] = $x;
			return $this;
		}

		if ($a) {
			if ($id) {
				$x = EtuDev_Calendar_Hour::getOrNewCalendarHour($id, $a);
			} else {
				$x = EtuDev_Calendar_Hour::newCalendarHour($a);
			}

			$this->hours[$x->getId()] = $x;
			return $this;
		}

		//DO NOTHING

		return $this;
	}

	/**
	 * static constructor
	 *
	 * @param array $a
	 *
	 * @uses __construct()
	 * @return EtuDev_Calendar_Date
	 */
	static public function newCalendarDate($a = null) {
		return new EtuDev_Calendar_Date($a);
	}

	/**
	 * @param array $a
	 *
	 * @uses fromArray()
	 */
	public function __construct($a = null) {
		if ($a) {
			$this->fromArray($a);
		}
	}

	/**
	 * given an array with the variables exported, sets the info
	 *
	 * @param array $a
	 *
	 * @return EtuDev_Calendar_Date
	 * @uses setDate()
	 * @uses setText()
	 * @uses setCssClass()
	 * @uses setOpen()
	 */
	public function fromArray($a) {

		if ($a['d']) {
			$this->setDate($a['d']);
		}

		if (array_key_exists('t', $a)) {
			$this->setText($a['t']);
		}

		if (array_key_exists('c', $a)) {
			$this->setCssClass($a['c']);
		}

		if (array_key_exists('o', $a)) {
			$this->setOpen($a['o']);
		}

		if (array_key_exists('h', $a)) {
			$this->setHours($a['h']);
		}

		return $this;
	}

	/**
	 *
	 * returns an array describing the object
	 *
	 * @param bool $withdate if false ignores the date element
	 *
	 * @uses getOpen()
	 * @uses getText()
	 * @uses getCssClass()
	 * @uses getDate()
	 * @uses DateTime::format()
	 *
	 * @return array
	 */
	public function toArray($withdate = true) {
		$a = array('o' => $this->getOpen(), 't' => $this->getText(), 'c' => $this->getCssClass());

		if ($withdate) {
			$d = $this->getDate();
			if ($d) {
				$a['d'] = $d->format('Y-m-d');
			}
		}

		$a['h'] = $this->getHourIds();

		return $a;
	}

}