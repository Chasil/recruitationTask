<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use RecruitmentTask\Exception\InvalidJsonException;
use RecruitmentTask\Services\BinLookups\BinListLookup;
use RecruitmentTask\Services\CommissionCalculator;
use RecruitmentTask\Services\ExchangeRateParser;
use RecruitmentTask\Exception\LookupConnectionFailException;
use RecruitmentTask\Exception\MissingRateCurrencyException;
use RecruitmentTask\Exception\RateConnectionFailException;

class RecruitmentTaskTest extends TestCase {

	/**
	 * @return array[]
	 */
	public function calculateProvider(): array {
		return [
			['{"bin":"45717360","amount":"100.00","currency":"EUR"}', 1.67],
			['{"bin":"516793","amount":"50.00","currency":"USD"}', 0.84],
			['{"bin":"45417360","amount":"10000.00","currency":"JPY"}', 166.67],
			['{"bin":"41417360","amount":"130.00","currency":"USD"}', 2.17],
			['{"bin":"4745030","amount":"2000.00","currency":"GBP"}', 33.34],
		];
	}

	/**
	 * @param string $transaction
	 * @param float $expectedResult
	 * @dataProvider calculateProvider
	 * @return void
	 * @throws LookupConnectionFailException
	 * @throws MissingRateCurrencyException
	 * @throws RateConnectionFailException
	 * @throws InvalidJsonException
	 */
	public function testCalculate(string $transaction, float $expectedResult): void {

		$binProviderMock = $this->createMock(BinListLookup::class);
		$binProviderMock->method('lookup')->willReturn(['country' => ['alpha2' => 'DK']]);

		$exchangeRatesMock = $this->createMock(ExchangeRateParser::class);
		$exchangeRatesMock->method('getRate')->willReturn(1.2);

		$commissionCalculator = new CommissionCalculator($binProviderMock, $exchangeRatesMock);

		$result = $commissionCalculator->calculate($transaction);

		$this->assertEquals($expectedResult,$result);
	}

	/**
	 * @return array
	 */
	public function calculateInvalidJsonProvider(): array {
		return [
			['invalid_json']
		];
	}

	/**
	 * @param string $transaction
	 * @dataProvider calculateInvalidJsonProvider
	 * @return void
	 * @throws InvalidJsonException
	 * @throws LookupConnectionFailException
	 * @throws MissingRateCurrencyException
	 * @throws RateConnectionFailException
	 */
	public function testCalculateInvalidJson(string $transaction): void {
		$this->expectException(InvalidJsonException::class);
		$this->expectExceptionMessage('Invalid JSON');

		$binProviderMock = $this->createMock(BinListLookup::class);
		$binProviderMock->method('lookup')->willReturn(['country' => ['alpha2' => 'DK']]);

		$exchangeRatesMock = $this->createMock(ExchangeRateParser::class);
		$exchangeRatesMock->method('getRate')->willReturn(1.2);

		$commissionCalculator = new CommissionCalculator($binProviderMock, $exchangeRatesMock);

		$commissionCalculator->calculate($transaction);
	}
}