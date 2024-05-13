<?php

namespace RecruitmentTask\Exception;

use Throwable;

class RateConnectionFailException extends \Exception {

	public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}