<?php

namespace RecruitmentTask\Services;

use RecruitmentTask\Exception\MissingRateCurrencyException;
use RecruitmentTask\Exception\RateConnectionFailException;

class ExchangeRateParser {

	/**
	 * @param string $currency
	 * @return mixed
	 * @throws MissingRateCurrencyException
	 * @throws RateConnectionFailException
	 */
	public function getRate(string $currency)
	{
		$ratesResponse = json_decode(file_get_contents('http://api.exchangeratesapi.io/latest?access_key=8afa8f502848f498b9b2b7f61d2042e3'), true);

		if (!isset($ratesResponse['rates'])) {
			throw new RateConnectionFailException('Rate Connection Fail');
		}
		
		if (!isset($ratesResponse['rates'][$currency])) {
			throw new MissingRateCurrencyException('Missing Rate Currency');
		}
		return $ratesResponse['rates'][$currency];
	}
}
