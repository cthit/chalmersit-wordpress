<?php

class PrintJobException extends PrintException {
	public function __construct($message) {
		parent::__construct($message);
	}
}

class PrintJob {

	private $valid_sides = array('one-sided', 'two-sided-long-edge');
	private $default_options = array(
		'sides' => 'two-sided-long-edge'
	);


	public function __construct($file, $printer, $copies = 1, $options = array()) {
		$this->file = $file;
		$this->printer = $printer;
		$this->setCopies($copies);
		$this->options = array_merge($this->default_options, $options);

		$this->validateSides($this->options['sides']);
		$this->validatePrinter($this->printer);

		if (isset($this->options['page-ranges'])) {
			$this->options['page-ranges'] = str_replace(' ', '', $this->options['page-ranges']);
			$this->validateRange($this->options['page-ranges']);
		}
	}

	public function __toString() {
		return "lpr -P {$this->printer} -# {$this->copies} {$this->stringifyOptions()} {$this->file}";
	}

	private function stringifyOptions() {
		$options = array();
		foreach ($this->options as $key => $value) {
			$options[] = "-o {$key}={$value}";
		}
		return join(' ', $options);
	}

	private function setCopies($copies) {
		if (!is_int($copies)) {
			throw new PrintJobException("Copies must be integer");
		}
		if ($copies < 1) {
			throw new PrintJobException("Copies must be greater than 0");
		}
		$this->copies = $copies;
	}

	private function validateSides($sides) {
		if (!in_array($sides, $this->valid_sides)) {
			throw new PrintJobException("Invalid side: $sides");
		}
	}

	private function validateRange($range) {
		if (preg_match("/^[-\d,]+$/", $range) !== 1) {
			throw new PrintJobException("Range contains invalid characters: '$range', valid characters are: , - 0-9");
		}
	}

	private function validatePrinter($printer) {
		if (empty($printer)) {
			throw new PrintJobException("Printer not set");
		}
	}
}