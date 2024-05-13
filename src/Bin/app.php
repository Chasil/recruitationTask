<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use RecruitmentTask\Services\BinLookups\BinListLookup;
use RecruitmentTask\Services\ExchangeRateParser;
use RecruitmentTask\Services\FileHandler;
use RecruitmentTask\Services\TransactionProcessor;

$processor = new TransactionProcessor(
	new FileHandler(),
);

$results = $processor->processFromFile($argv[1]);
foreach ($results as $result) {
	var_dump($result);
}