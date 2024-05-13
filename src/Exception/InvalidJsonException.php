<?php

namespace RecruitmentTask\Exception;

use Throwable;

class InvalidJsonException extends \Exception {

	public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}