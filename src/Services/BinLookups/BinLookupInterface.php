<?php

namespace RecruitmentTask\Services\BinLookups;

use RecruitmentTask\DTOs\BinDTO;
use RecruitmentTask\Exception\LookupConnectionFailException;

interface BinLookupInterface {
	/**
	 * @param string $bin
	 * @throws LookupConnectionFailException
	 * @return BinDTO
	 */
	function get(string $bin): BinDTO;
}
