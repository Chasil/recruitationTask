<?php

namespace RecruitmentTask\Services;

use RecruitmentTask\Enums\EuCountries;
use RecruitmentTask\Exception\InvalidJsonException;
use RecruitmentTask\Exception\LookupConnectionFailException;
use RecruitmentTask\Exception\MissingRateCurrencyException;
use RecruitmentTask\Exception\RateConnectionFailException;
use RecruitmentTask\Services\BinLookups\BinLookupInterface;

class CommissionCalculator {

	const EU_RATE = 0.01;
	const NON_EU_RATE = 0.02;

	public function __construct(
		private readonly BinLookupInterface $binService,
		private readonly ExchangeRateParser $exchangeRate,
	) {
	}

	/**
	 * @param string $transactionJSON
	 * @return float|null
	 * @throws LookupConnectionFailException
	 * @throws RateConnectionFailException
	 * @throws MissingRateCurrencyException
	 * @throws InvalidJsonException
	 */
	public function calculate(string $transactionJSON): ?float {
		$transaction = json_decode($transactionJSON, true);
		if(!$transaction) {
			throw new InvalidJsonException('Invalid JSON');
		}
		$bin = $transaction['bin'];
		$amount = $transaction['amount'];
		$currency = $transaction['currency'];

		$binDTO = $this->binService->get($bin);

		$isEu = $this->isEu($binDTO->getCountryCode());
		$rate = $this->exchangeRate->getRate($currency) ?? 1;

		$fixedAmount = $amount / $rate;

		return ceil($fixedAmount * ($isEu ? self::EU_RATE : self::NON_EU_RATE) * 100) / 100;
	}

	/**
	 * @param string $countryCode
	 * @return bool
	 */
	private function isEu(string $countryCode): bool {
		return in_array($countryCode, EuCountries::names());
	}
}