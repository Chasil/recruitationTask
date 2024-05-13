<?php

namespace RecruitmentTask\DTOs;

class BinDTO {
	public function __construct(
		protected string $countryCode
	) {
	}

	public function getCountryCode(): string {
		return $this->countryCode;
	}
}
