<?php

namespace RecruitmentTask\Services;

use RecruitmentTask\Exception\InvalidJsonException;
use RecruitmentTask\Exception\LookupConnectionFailException;
use RecruitmentTask\Exception\MissingRateCurrencyException;
use RecruitmentTask\Exception\RateConnectionFailException;
use RecruitmentTask\Services\BinLookups\BinListLookup;

class TransactionProcessor {

	public function __construct(
		private readonly FileHandler $fileHandler,
	) {
	}

	/**
	 * @param string $filename
	 * @return array
	 * @throws LookupConnectionFailException
	 * @throws MissingRateCurrencyException
	 * @throws RateConnectionFailException
	 * @throws InvalidJsonException
	 */
	public function processFromFile(string $filename): array {
		$transactions = $this->fileHandler->read($filename);
		$results = [];

		foreach ($transactions as $transaction) {
			if (empty($transaction)) {
				continue;
			}
			$commissionCalculator = new CommissionCalculator(
				new BinListLookup(),
				new ExchangeRateParser(),
			);
			$result = $commissionCalculator->calculate($transaction);
			$results[] = $result;
		}
		return $results;
	}
}