<?php

namespace RecruitmentTask\Services\BinLookups;

use RecruitmentTask\DTOs\BinDTO;
use RecruitmentTask\Exception\LookupConnectionFailException;

class BinListLookup implements BinLookupInterface {

	public function get(string $bin): BinDTO {
		$lookupResult = $this->lookup($bin);
		return new BinDTO($lookupResult['country']['alpha2']);
	}

	/**
	 * @param string $bin
	 * @return array|null
	 * @throws LookupConnectionFailException
	 */
	public function lookup(string $bin): ?array {
		$binResult = file_get_contents('https://lookup.binlist.net/'.$bin);
		if(!$binResult) {
			throw new LookupConnectionFailException('Lookup Connection Fail from bin: ' . $bin);
		}
		return json_decode($binResult, true);
	}
}
