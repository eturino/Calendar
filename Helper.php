<?php

class EtuDev_Calendar_Helper {

	protected $id;
	protected $name;
	protected $value;
	protected $classes;
	protected $attributes;


	public function getDateFieldHTML($name) {

		$html = '<input type="text" autocomplete="off" value="' . EtuDev_Util_String::cleanFormOutput($this->value) . '"' . EtuDev_Util_Form::parseAttributesToString($this->attributes) . ' ' . EtuDev_Util_Form::parseClasses($this->classes) . ' />';

		$minDate = ($this->attributes['min_date'] == 'today' ? '+0d' : $this->attributes['min_date']);
		$maxDate = $this->attributes['max_date'];

		$minHours = $this->attributes['min_hours'];

		/** @var $calendarControl CalendarControl */
		$calendarControl = $this->attributes['calendarControl'];

		if ($calendarControl || is_numeric($minDate) || strlen(trim($minDate)) || is_numeric($maxDate) || strlen(trim($maxDate) || $minHours)) {
			if (!$calendarControl) {
				$calendarControl = CalendarControl::newCC()->setDefaultOpen(true);
			}

			$name_input = ($this->attributes['id'] ? $this->attributes['id'] : $name . $suf) . '_input';
			$calendarControl->setDatePickerId($name_input);

			if ($this->attributes['booking_time_id']) {
				$calendarControl->setTimePickerId($this->attributes['booking_time_id']);
			}

			if (is_numeric($minDate) || strlen(trim($minDate))) {
				$calendarControl->setMinDate($minDate);
			}

			if (is_numeric($maxDate) || strlen(trim($maxDate))) {
				$calendarControl->setMaxDate($maxDate);
			}


			if ($minHours) {
				$calendarControl->setMinHours($minHours);
			}

			$calendarControl->applyMinHours();
			$calendarControl->addHourStatusOfDateStatus();

			$html .= $calendarControl->render(true);
		}

		return $html;
	}

}