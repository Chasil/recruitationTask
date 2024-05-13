<?php

namespace RecruitmentTask\Services;

class FileHandler {

	/**
	 * @param $filename
	 * @return array
	 */
	public function read($filename): array {
		$transactions = file_get_contents($filename);
		return explode(PHP_EOL, $transactions);
	}
}