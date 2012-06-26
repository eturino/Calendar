//Date Utils
Date.isCalendarControlDateString = function (string) {
	var dateformat = /^\d{4}-\d{1,2}-\d{1,2}$/;
	return dateformat.test(string);
};

/**
 *
 * @returns String YYYY-MM-DD using real month (01-12, not 0-11)
 */
Date.prototype.getCalendarDateString = function () {
	var d = this.getDate();
	var m = this.getMonth() + 1;

	var ds = (d < 10) ? '0' + d.toString() : d.toString();
	var ms = (m < 10) ? '0' + m.toString() : m.toString();

	return this.getFullYear().toString() + '-' + ms + '-' + ds;
};

/**
 * @author eturino
 */

// BIBLIOTECA
function CalendarControlLibrary() {
	this.calendars = {};
	this.hours = {};
}

/**
 * @param {String} id
 * @returns CalendarControl
 */
CalendarControlLibrary.prototype.getCalendar = function (id) {
	return this.calendars[id];
};

/**
 *
 * @param {String} id
 * @param {Object} options
 * @returns CalendarControl
 */
CalendarControlLibrary.prototype.newCalendar = function (id, options) {
	this.calendars[id] = new CalendarControl(id, options);
	return this.calendars[id];
};

/**
 * @param {String} id
 * @returns CalendarHoursStatus
 */
CalendarControlLibrary.prototype.getCalendarHoursStatus = function (id) {
	return this.hours[id];
};

/**
 *
 * @param {String} id
 * @param {Object} options
 * @returns CalendarHoursStatus
 */
CalendarControlLibrary.prototype.newCalendarHoursStatus = function (id, options) {
	this.hours[id] = new CalendarHoursStatus(id, options);
	return this.hours[id];
};

/**
 * returns the singleton BibliotecaCalendarControl
 * @returns CalendarControlLibrary
 */
CalendarControlLibrary.getInstance = function () {
	if (CalendarControlLibrary._inst) {
		return CalendarControlLibrary._inst;
	}

	CalendarControlLibrary._inst = new CalendarControlLibrary();
	return CalendarControlLibrary._inst;
};

// CalendarControl

function CalendarControl(id, options) {
	this.id = id;
	this.date_picker_id = null;
	this.min_date = null;
	this.max_date = null;

	this.default_open = true;

	this.options = {};

	this.dates_status = {};
	this.hours_status = {};
	this.default_hours_status = {};

	var _ops = options || {};

	this.setOptions(_ops);
}

CalendarControl.prototype.setOptions = function (options) {
	var i;
	for (i in options) {
		switch (i) {
			case 'id':
				if (options[i]) {
					this.id = options[i];
				}
				break;

			case 'min_date':
			case 'minDate':
				this.setMinDate(options[i]);
				break;

			case 'max_date':
			case 'maxDate':
				this.setMaxDate(options[i]);
				break;

			case 'default_open':
			case 'defaultOpen':
				this.setDefaultOpen(options[i]);
				break;

			case 'date_picker_id':
			case 'datePickerId':
				this.setDatePickerId(options[i]);
				break;

			case 'dates_status':
			case 'dates_statuses':
			case 'datesStatus':
			case 'datesStatuses':
				this.setDatesStatus(options[i]);
				break;

			case 'hours_status':
			case 'hours_statuses':
			case 'hoursStatus':
			case 'hoursStatuses':
				this.setHoursStatus(options[i]);
				break;

			case 'default_hours_status':
			case 'default_hours_statuses':
			case 'defaultHoursStatus':
			case 'defaultHoursStatuses':
				this.setDefaultHoursStatus(options[i]);
				break;

			default:
				this.options[i] = options[i];
				break;
		}
	}
};

CalendarControl.prototype.getDefaultOpen = function () {
	return this.default_open;
};

CalendarControl.prototype.getDatePickerId = function () {
	return this.date_picker_id;
};

CalendarControl.prototype.getMinDate = function () {
	return this.min_date;
};

CalendarControl.prototype.getMaxDate = function () {
	return this.max_date;
};

CalendarControl.prototype.setDefaultOpen = function (v) {
	this.default_open = (v == true);
	return this;
};

CalendarControl.prototype.setDatePickerId = function (v) {
	this.date_picker_id = v;
	return this;
};

CalendarControl.prototype.setMinDate = function (v) {
	if (Date.isCalendarControlDateString(v)) {
		this.min_date = Date.parse(v);
	} else {
		this.min_date = v;
	}
	return this;
};

CalendarControl.prototype.setMaxDate = function (v) {

	if (Date.isCalendarControlDateString(v)) {
		this.max_date = Date.parse(v);
	} else {
		this.max_date = v;
	}

	return this;
};

CalendarControl.prototype.setDatesStatus = function (dss) {
	var i;
	for (i in dss) {
		this.dates_status[i] = dss[i];
	}
	return this;
};


CalendarControl.prototype.setHoursStatus = function (dss) {
	var i;
	for (i in dss) {
		this.hours_status[i] = dss[i];
	}
	return this;
};

CalendarControl.prototype.setDefaultHoursStatus = function (dss) {
	var id;
	this.default_hours_status = {};
	for (id in dss) {
		var ds = dss[id];
		if (ds) {
			if (ds != null && typeof ds == 'object' && !(ds instanceof CalendarHoursStatus)) {
				this.default_hours_status[id] = new CalendarHoursStatus(id, ds);
			} else {
				this.default_hours_status[id] = ds;
			}
		}
	}
	return this;
};

CalendarControl.prototype.applyToDatepicker = function () {
	var dpid = this.getDatePickerId();
	if (dpid == null || $('#' + dpid).size() == 0) {
		return false;
	}

	var cc = this;
	var o = {
		beforeShowDay:function (date) {
			return cc.datePickerBeforeShowDay(date);
		}
	};

	var mind = this.getMinDate();
	if (mind != null) {
		o.minDate = mind;
	}

	var maxd = this.getMaxDate();
	if (maxd != null) {
		o.maxDate = maxd;
	}

	$('#' + dpid).datepicker("option", o);

	//check already selected date
	if ($('#' + dpid).datepicker("getDate")) {
		this.datePickerOnSelectDay($('#' + dpid).datepicker("getDate"));
	}

	return this;
};

/**
 *
 * @param {Date} date
 * @returns Array with [0] = true/false (selectable or not), [1] = css class name, [2] = optional tooltip popup
 */
CalendarControl.prototype.datePickerBeforeShowDay = function (date) {
	var ds = this.getDateStatus(date);
	var t = '';
	var c = '';

	if (ds) {
		t = ds.getText();
		c = ds.getCSSClass();
	}

	return [ this.isOpen(date), c, t ];
};

/**
 *
 * @param {Date} date
 * @param  inst
 */
CalendarControl.prototype.datePickerOnSelectDay = function (dateString, inst) {

	var date;
	if (dateString instanceof Date) {
		date = dateString;
	} else {
		date = new Date(inst.selectedYear, inst.selectedMonth, inst.selectedDay);
	}

	return true;
};

/**
 *
 * @param {Date} date
 * @returns Boolean
 */
CalendarControl.prototype.isOpen = function (date) {
	var ds = this.getDateStatus(date);
	if (ds != null && typeof ds == 'object' && ds instanceof CalendarDateStatus) {
		return ds.isOpen();
	}

	var minDate = this.getMinDate();
	if (minDate instanceof Date && minDate.isAfter(date)) {
		return false;
	}

	var maxDate = this.getMaxDate();
	if (maxDate instanceof Date && maxDate.isBefore(date)) {
		return false;
	}

	return this.getDefaultOpen();
};

/**
 * @param {Date} date
 * @returns CalendarDateStatus
 */
CalendarControl.prototype.getDateStatus = function (date) {
	var key = date.getCalendarDateString();
	var ds = this.dates_status[key] || null;
	if (ds != null && typeof ds == 'object' && !(ds instanceof CalendarDateStatus)) {
		this.dates_status[key] = new CalendarDateStatus(ds, this.getDefaultOpen());
		return this.dates_status[key];
	}

	return ds;
};


CalendarControl.prototype.getHourStatus = function (id) {
	var ds = this.hours_status[id] || null;
	if (ds != null && typeof ds == 'object' && !(ds instanceof CalendarHoursStatus)) {
		this.hours_status[id] = new CalendarHoursStatus(id, ds);
		return this.hours_status[id];
	}

	return ds;
};


CalendarControl.prototype.getDefaultHoursStatus = function () {
	return this.default_hours_status;
};


function CalendarDateStatus(content, default_open) {

	var d_o = (default_open == true);

	this.open = (content.open !== undefined) ? content.open : ((content.o !== undefined ) ? content.o : null);

	if (this.open === null || this.open === undefined) {
		this.open = d_o;
	}

	this.text = content.text || content.t || '';
	this.css_class = content.css_class || content.c || '';
	this.hour_status_ids = content.h || [];
}

CalendarDateStatus.prototype.getHourStatusIds = function () {
	return this.hour_status_ids;
};

CalendarDateStatus.prototype.isOpen = function () {
	return this.open || false;
};

CalendarDateStatus.prototype.getText = function () {
	return this.text || '';
};

CalendarDateStatus.prototype.getCSSClass = function () {
	return this.css_class || '';
};

function CalendarHoursStatus(id, options) {
	this.id = id || null;
	this.name = null;
	this.firstElement = null;
	this.lastElement = null;
	this.init = null;
	this.end = null;
	this.endIncluded = false;
	this.periodMinutes = null;

	this.options = {};
	var _ops = options || {};
	this.setOptions(_ops);
}

CalendarHoursStatus.newCalendarHour = function (options) {
	var i = options.id || null;
	return new CalendarHoursStatus(i, options);
};

CalendarHoursStatus.createMultiple = function (a) {
	var r = [];
	var i;
	for (i in a) {
		r.push(CalendarHoursStatus.newCalendarHour(a[i]));
	}
	return r;
};

CalendarHoursStatus.prototype.setOptions = function (options) {
	var i;
	for (i in options) {
		switch (i) {
			case 'id':
				if (options[i]) {
					this.setId(options[i]);
				}
				break;

			case 'name':
			case 'n':
				this.setName(options[i]);
				break;

			case 'init':
			case 'i':
				this.setInit(options[i]);
				break;

			case 'end':
			case 'e':
				this.setEnd(options[i]);
				break;

			case 'period':
			case 'periodMinutes':
			case 'period_minutes':
			case 'p':
				this.setPeriodMinutes(options[i]);
				break;

			case 'firstElement':
			case 'first_element':
			case 'f':
				this.setFirstElement(options[i]);
				break;

			case 'lastElement':
			case 'last_element':
			case 'l':
				this.setLastElement(options[i]);
				break;

			case 'endIncluded':
			case 'end_included':
			case 'ei':
				this.setEndIncluded(options[i]);
				break;

			default:
				this.options[i] = options[i];
				break;
		}
	}
};

/**
 * @returns String
 */
CalendarHoursStatus.prototype.getId = function () {
	return this.id || null;
};

/**
 * @param {String} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setId = function (v) {
	this.id = v || null;
	return this;
};

/**
 * @returns String
 */
CalendarHoursStatus.prototype.getName = function () {
	return this.name || null;
};

/**
 * @param {String} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setName = function (v) {
	this.name = v || '';
	return this;
};

/**
 * @returns Date
 */
CalendarHoursStatus.prototype.getInit = function () {
	return this.init || null;
};

/**
 * @param {Date} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setInit = function (v) {
	if (v instanceof Date) {
		this.init = v;
	} else if (v) {
		this.init = Date.parse(v);
	} else {
		this.init = null;
	}

	return this;
};

/**
 * @returns Date
 */
CalendarHoursStatus.prototype.getEnd = function () {
	return this.end || null;
};

/**
 * @param {Date} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setEnd = function (v) {
	if (v instanceof Date) {
		this.end = v;
	} else if (v) {
		this.end = Date.parse(v);
	} else {
		this.end = null;
	}

	return this;
};

/**
 * @returns Boolean
 */
CalendarHoursStatus.prototype.getEndIncluded = function () {
	return this.endIncluded || false;
};

/**
 * @returns Boolean
 */
CalendarHoursStatus.prototype.isEndIncluded = function () {
	return this.getEndIncluded();
};

/**
 * @param {Boolean} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setEndIncluded = function (v) {
	this.endIncluded = v || false;
	return this;
};

/**
 * @returns Number
 */
CalendarHoursStatus.prototype.getPeriodMinutes = function () {
	return this.periodMinutes || 0;
};

/**
 * @param {Number} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setPeriodMinutes = function (v) {
	this.periodMinutes = v || 0;
	return this;
};

CalendarHoursStatus.prototype.getFirstElement = function () {
	return this.firstElement || '';
};

/**
 * @param {String} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setFirstElement = function (v) {
	this.firstElement = v || '';
	return this;
};

/**
 * @returns String
 */
CalendarHoursStatus.prototype.getLastElement = function () {
	return this.lastElement || '';
};

/**
 * @param {String} v
 * @returns CalendarHoursStatus
 */
CalendarHoursStatus.prototype.setLastElement = function (v) {
	this.lastElement = v || '';
	return this;
};

/**
 * @returns Array
 */
CalendarHoursStatus.prototype.getArrayElements = function () {
	var a = [];

	if (this.getFirstElement()) {
		a.push(this.getFirstElement());
	}

	var i = this.getInit();

	var e = this.getEnd();
	var ei = this.getEndIncluded();
	var p = this.getPeriodMinutes();

	if (i && (p > 0)) {
		if (!e) {
			e = Date.today().clearTime().addDays(1);
			ei = false;
		}

		/** @var {Date} x Date */
		var x = i.clone();
		do {
			a.push(x.toString('HH:mm'));
			x = x.addMinutes(p);
		} while (x.isBefore(e) || (ei && x.equals(e)));
	}

	if (this.getLastElement()) {
		a.push(this.getLastElement());
	}

	return a;
};
