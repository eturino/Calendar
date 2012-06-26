<?php

class EtuDev_Calendar_HTMLForm {

	/**
	 * @var string|DateTime
	 */
	protected $value;

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var int|string|DateTime
	 */
	protected $min_date;

	/**
	 * @var int|string|DateTime
	 */
	protected $max_date;

	/**
	 * @var EtuDev_Calendar_Control
	 */
	protected $calendar_control;

	protected function checkData(){
		$this->id = $this->id ?: $this->name;
		$this->value = (EtuDev_Util_Date::isZeroDate($this->value)) ? '' : $this->value;
	}

	public function render() {
		$this->checkData();

		$html = '
		<input type="text" class="text" autocomplete="off" value="' . EtuDev_Util_Date::formatDateOutput($this->value) . '" id="' . $this->id . '_input" name="' . $this->name . '_value" />' . '
		<input type="hidden" value="' . $this->value . '" id="' . $this->id . '" name="' . $this->name . '" />
		';

		$minDate = ($this->min_date == 'today' ? '+0d' : $this->min_date);
		$maxDate = $this->max_date;

		/** @var $calendarControl EtuDev_Calendar_Control */
		$calendarControl = $this->calendar_control;

		if ($calendarControl || is_numeric($minDate) || strlen(trim($minDate)) || is_numeric($maxDate) || strlen(trim($maxDate))) {
			if (!$calendarControl) {
				$calendarControl = EtuDev_Calendar_Control::newCalendarControl()->setDefaultOpen(true);
			}

			$name_input = ($this->id ? : $this->name) . '_input';
			$calendarControl->setDatePickerId($name_input);

			if (is_numeric($minDate) || strlen(trim($minDate))) {
				$calendarControl->setMinDate($minDate);
			}

			if (is_numeric($maxDate) || strlen(trim($maxDate))) {
				$calendarControl->setMaxDate($maxDate);
			}

			$calendarControl->addHourStatusOfDateStatus();

			$html .= $calendarControl->render(true);
		}

		$html .= '
		<script type="text/javascript">$(document).ready(function() {$("#' . $this->id . '_input").datepicker({ showButtonPanel: true, showOn: "focus", dateFormat: "' . str_replace(' ', EtuDev_Util_Date::DATE_FORMAT_INPUT_SEPARATOR, static::$formats[EtuDev_Util_Date::DATE_FORMAT_INPUT]) . '", altField: "#' . $this->id . '", altFormat: "yy-mm-dd", changeYear: true, changeMonth: true, onClose: function(dateText, int) { if($("#' . $this->id . '_input").val() == "") { $("#' . $this->id . '").val(""); } } }); }); </script>';


		return $html;
	}

	static protected $formats = array('m-d-Y' => 'mm dd yy', 'd-m-Y' => 'dd mm yy', 'Y-m-d' => 'yy mm dd', 'Y-d-m' => 'yy dd mm');


	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param \DateTime|int|string $max_date
	 * @return EtuDev_Calendar_HTMLForm
	 */
	public function setMaxDate($max_date) {
		$this->max_date = $max_date;
		return $this;
	}

	/**
	 * @return \DateTime|int|string
	 */
	public function getMaxDate() {
		return $this->max_date;
	}

	/**
	 * @param \DateTime|int|string $min_date
	 * @return EtuDev_Calendar_HTMLForm
	 */
	public function setMinDate($min_date) {
		$this->min_date = $min_date;
		return $this;
	}

	/**
	 * @return \DateTime|int|string
	 */
	public function getMinDate() {
		return $this->min_date;
	}

	/**
	 * @param string $name
	 * @return EtuDev_Calendar_HTMLForm
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param \DateTime|string $value
	 * @return EtuDev_Calendar_HTMLForm
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * @return \DateTime|string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param \EtuDev_Calendar_Control $calendar_control
	 *
	 * @return EtuDev_Calendar_HTMLForm
	 */
	public function setCalendarControl($calendar_control) {
		$this->calendar_control = $calendar_control;
		return $this;
	}

	/**
	 * @return \EtuDev_Calendar_Control
	 */
	public function getCalendarControl() {
		return $this->calendar_control;
	}

	public function __toString(){
		return $this->render();
	}

}