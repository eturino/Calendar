<?php

class EtuDev_Calendar_Hour {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $firstElement;

	/**
	 * @var string
	 */
	protected $lastElement;

	/**
	 * @var string
	 */
	protected $init;

	/**
	 * @var string
	 */
	protected $end;

	/**
	 * @var bool
	 */
	protected $endIncluded = false;

	/**
	 * @var int minutes
	 */
	protected $period;


	//GETTERS y SETTERS

	/**
	 * @return string the $id
	 * @uses $id
	 */
	public function getId() {
		if (!$this->id) {
			$this->id = EtuDev_Util_String::getRandomString(10);
		}
		return $this->id;
	}

	/**
	 * @return string the $name
	 * @uses $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string the $firstElement
	 * @uses $firstElement
	 */
	public function getFirstElement() {
		return $this->firstElement;
	}

	/**
	 * @return string the $lastElement
	 * @uses $lastElement
	 */
	public function getLastElement() {
		return $this->lastElement;
	}

	/**
	 * @return string the $init
	 * @uses $init
	 */
	public function getInit() {
		return $this->init;
	}

	/**
	 * @param null $tz
	 *
	 * @return DateTime|null
	 * @throws Exception
	 */
	public function getInitDateTime($tz = null) {
		if (!$this->init) {
			return null;
		}

		$dt = DateTime::createFromFormat('H:i', $this->init, EtuDev_Util_Date::getDateTimeZone($tz));
		if (!$dt) {
			throw new Exception('invalid init');
		}
		EtuDev_Util_Date::dateTimeSetToday($dt);
		return $dt;
	}

	/**
	 * @return string the $end
	 * @uses $end
	 */
	public function getEnd() {
		return $this->end;
	}

	/**
	 * @param string|null $tz
	 *
	 * @return DateTime|null
	 * @throws Exception
	 */
	public function getEndDateTime($tz = null) {
		if (!$this->end) {
			return null;
		}

		$dt = DateTime::createFromFormat('H:i', $this->end, EtuDev_Util_Date::getDateTimeZone($tz));
		if (!$dt) {
			throw new Exception('invalid end');
		}
		EtuDev_Util_Date::dateTimeSetToday($dt);
		return $dt;
	}

	/**
	 * @return bool the $endIncluded
	 * @uses $endIncluded
	 */
	public function getEndIncluded() {
		return $this->endIncluded;
	}

	/**
	 * @return int the $period
	 * @uses $period
	 */
	public function getPeriod() {
		return $this->period;
	}

	/**
	 * @param string $id
	 *
	 * @uses $id
	 * @return EtuDev_Calendar_Hour
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @uses $name
	 * @return EtuDev_Calendar_Hour
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $firstElement
	 *
	 * @uses $firstElement
	 * @return EtuDev_Calendar_Hour
	 */
	public function setFirstElement($firstElement) {
		$this->firstElement = $firstElement;
		return $this;
	}

	/**
	 * @param string $lastElement
	 *
	 * @uses $lastElement
	 * @return EtuDev_Calendar_Hour
	 */
	public function setLastElement($lastElement) {
		$this->lastElement = $lastElement;
		return $this;
	}

	/**
	 * @param string|DateTime $init
	 *
	 * @uses $init
	 * @return EtuDev_Calendar_Hour
	 */
	public function setInit($init) {
		if ($init instanceOf DateTime) {
			$init = $init->format('H:i');
		}
		$this->init = $init;
		return $this;
	}

	/**
	 * @param string|DateTime $end
	 *
	 * @uses $end
	 * @return EtuDev_Calendar_Hour
	 */
	public function setEnd($end) {
		if ($end instanceOf DateTime) {
			$end = $end->format('H:i');
		}
		$this->end = $end;
		return $this;
	}

	/**
	 * @param bool $endIncluded
	 *
	 * @uses $endIncluded
	 * @return EtuDev_Calendar_Hour
	 */
	public function setEndIncluded($endIncluded) {
		$this->endIncluded = $endIncluded;
		return $this;
	}

	/**
	 * @param int $period
	 *
	 * @uses $period
	 * @return EtuDev_Calendar_Hour
	 */
	public function setPeriod($period) {
		$this->period = $period;
		return $this;
	}


	//FUNCTIONS

	static protected $instances = array();

	/**
	 * static constructor
	 *
	 * @param array $a
	 *
	 * @uses __construct()
	 * @return EtuDev_Calendar_Hour
	 */
	static public function newCalendarHour($a = null) {
		return new EtuDev_Calendar_Hour($a);
	}

	/**
	 * gets the instance if exists
	 *
	 * @param string $id
	 *
	 * @return EtuDev_Calendar_Hour
	 */
	static public function getCalendarHour($id) {
		return @self::$instances[$id];
	}

	/**
	 * gets and if not exists creates using the given id
	 *
	 * @param string $id
	 * @param array  $a options for constructor
	 *
	 * @return EtuDev_Calendar_Hour
	 */
	static public function getOrNewCalendarHour($id, $a = array()) {
		$x = self::getCalendarHour($id);
		if (!$x) {
			$a['id'] = $id;
			$x       = self::newCalendarHour($a);
		}

		return $x;
	}

	/**
	 * @uses toArray()
	 * @return EtuDev_Calendar_Hour
	 */
	public function getClone() {
		$a = $this->toArray(false);
		return new EtuDev_Calendar_Hour($a);
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

		while (array_key_exists($this->getId(), self::$instances)) {
			$this->setId(EtuDev_Util_String::getRandomString(10));
		}
		self::$instances[$this->getId()] = $this;
	}

	/**
	 *
	 *
	 * devuelve un EtuDev_Calendar_Hour que puede ser este mismo si la hora mínima pasada no influye, o un clon con los datos modificados en caso contrario
	 *
	 * Se considera que "influye" la hora mínima pasada si esta está dentro del intervalo [INIT, END] del presente CalendarHour, o si es superior a END
	 *
	 * Si la hora mínima es superior a END, devolvemos null
	 * Si la hora mínima es inferior a INIT, devolvemos este mismo CalendarHour (this)
	 * Si la hora mínima está entre INIT y END, modificamos el intervalo, descartando paso a paso (según los minutos indicados por PERIOD) hasta que el nuevo INIT sea >= que MinHour
	 *
	 * @param int|string|DateTime $minHour Un DateTime del que sólo se usa la hora (la fecha se pasará a TODAY), o un string con la hora en formato H:i, o bien un numero positivo que indica el numero de horas desde NOW
	 * @param null                $timezone
	 *
	 * @return EtuDev_Calendar_Hour|null
	 * @throws Exception
	 */
	public function getCalendarHourFilteredByMinHour($minHour, $timezone = null) {
		$tz = EtuDev_Util_Date::getDateTimeZone($timezone);
		/** @var $mh DateTime */
		$mh = EtuDev_Util_Date::getDateTimeNowPlusHours($minHour, $tz);

		if ($mh) {
			//comprobamos que no sea ya mañana
			$tomorrow = EtuDev_Util_Date::getDateTimeToday(false, $tz);
			$tomorrow->modify('+1 day');
			if ($tomorrow <= $mh) {
				//no hay horas suficientes para hoy
				return null;
			}
		} else {
			return $this;
		}

		$init = $this->getInitDateTime($tz);
		if (!$init) {
			$init = EtuDev_Util_Date::getDateTimeToday(false, $tz);
			$init->setTime(0, 0, 0);
		}

		$end = $this->getEndDateTime($tz);
		if (!$end) {
			$end = EtuDev_Util_Date::getDateTimeToday(false, $tz);
			$end->setTime(0, 0, 0);
			$end->modify('+1 day');
		}

		//si el inicio es superior al final, está mal construido el CalendarHour
		if ($init > $end) {
			throw new Exception('invalid CalendarHour: init cannot be greater than end');
		}

		//si el final es superior al mínimo de horas => NO usar
		if ($end < $mh) {
			return null;
		}

		//si la hora mínima es la misma o inferior que init => usamos THIS
		if ($mh < $init || $mh == $init) {
			return $this;
		}

		if (!$this->getPeriod()) {
			throw new Exception('invalid CalendarHour: no period');
		}

		//clonamos y vamos modificando hasta que nuevo_init >= min_hora
		$interval = new DateInterval('PT' . $this->getPeriod() . 'M');
		//si init sigue siendo menor que la hora mínima, y tambien menor que END, seguimos subiendo
		while ($mh > $init && $end > $init) {
			$init->add($interval);
		}

		//si al final el init resultante es superior a END, o si sigue siendo inferior a la hora mínima, devolvemos NULL
		if ($init > $end || $mh > $init) {
			return null;
		}

		//si ha ido bien, establecemos el nuevo INIT en un clon del persente CalendarHour y lo devolvemos
		$c = $this->getClone();
		$c->setFirstElement(self::createFirstElementSpecialWithBookingTimeDialog(static::$lang_previous_hours));
		$c->setInit($init);
		return $c;
	}

	static protected $lang_previous_hours = 'Previous Hours';
	static protected $lang_lunch_title = 'Lunch';
	static protected $lang_dinner_title = 'Dinner';
	static protected $lang_lunch_anytime = 'Lunch Anytime';
	static protected $lang_dinner_anytime = 'Dinner Anytime';


	/**
	 * gets a special First Element, to work with the Booking Time element
	 * @static
	 *
	 * @param string $text
	 *
	 * @return array
	 * //TODO quitar esto de aquí, produce un acoplamiento excesivo
	 */
	static public function createFirstElementSpecialWithBookingTimeDialog($text) {
		return array('html' => $text, 'class' => 'brtp_option_dialog_element', 'hook' => 'dialog');
	}

	/**
	 * given an array with the variables exported, sets the info
	 *
	 * @param array $a
	 *
	 * @return EtuDev_Calendar_Hour
	 */
	public function fromArray($a) {
		if (array_key_exists('id', $a)) {
			$this->setId($a['id']);
		}

		if (array_key_exists('n', $a)) {
			$this->setName($a['n']);
		}

		if (array_key_exists('i', $a)) {
			$this->setInit($a['i']);
		}

		if (array_key_exists('e', $a)) {
			$this->setEnd($a['e']);
		}

		if (array_key_exists('p', $a)) {
			$this->setPeriod($a['p']);
		}

		if (array_key_exists('f', $a)) {
			$this->setFirstElement($a['f']);
		}

		if (array_key_exists('l', $a)) {
			$this->setLastElement($a['l']);
		}

		if (array_key_exists('ei', $a)) {
			$this->setEndIncluded($a['ei']);
		}

		return $this;
	}

	/**
	 *
	 * returns an array describing the object
	 *
	 * @return array
	 */
	public function toArray($withId = true) {
		$a = array('i' => $this->getInit() ? $this->getInit() : null,
				   'e' => $this->getEnd() ? $this->getEnd() : null,
				   'p' => $this->getPeriod() ? $this->getPeriod() : 0,
				   'ei' => $this->getEndIncluded());

		if ($withId) {
			$a['id'] = $this->getId();
		}

		if ($this->getName()) {
			$a['n'] = $this->getName();
		}

		if ($this->getFirstElement()) {
			$a['f'] = $this->getFirstElement();
		}

		if ($this->getLastElement()) {
			$a['l'] = $this->getLastElement();
		}

		return $a;
	}

	const DEFAULT_INSTANCE_ID_MORNING = 'def_morning';
	const DEFAULT_INSTANCE_ID_EVENING = 'def_evening';

	static public function getDefaultCalendarHours($toArray = false) {
		$ins = array();
		if (!self::getCalendarHour(self::DEFAULT_INSTANCE_ID_MORNING)) {
			$a = array('id' => self::DEFAULT_INSTANCE_ID_MORNING,
					   'i' => '13:00',
					   'e' => '15:30',
					   'p' => 30,
					   'ei' => true,
					   'f' => static::$lang_lunch_anytime,
					   'n' => static::$lang_lunch_title);

			$x = self::getOrNewCalendarHour(self::DEFAULT_INSTANCE_ID_MORNING, $a);
		} else {
			$x = self::getCalendarHour(self::DEFAULT_INSTANCE_ID_MORNING);
		}
		if ($toArray) {
			$ins[] = $x->toArray();
		} else {
			$ins[] = $x;
		}


		if (!self::getCalendarHour(self::DEFAULT_INSTANCE_ID_EVENING)) {
			$a = array('id' => self::DEFAULT_INSTANCE_ID_EVENING,
					   'i' => '19:30',
					   'e' => '23:00',
					   'p' => 30,
					   'ei' => true,
					   'f' => static::$lang_dinner_anytime,
					   'n' => static::$lang_lunch_title);
			$x = self::getOrNewCalendarHour(self::DEFAULT_INSTANCE_ID_EVENING, $a);
		} else {
			$x = self::getCalendarHour(self::DEFAULT_INSTANCE_ID_EVENING);
		}

		if ($toArray) {
			$ins[] = $x->toArray();
		} else {
			$ins[] = $x;
		}

		return $ins;
	}
}