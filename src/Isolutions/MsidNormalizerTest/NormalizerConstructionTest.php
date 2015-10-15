<?php

namespace Isolutions\MsidNormalizerTest;

use Isolutions\MsidNormalizer\Normalizer;
use Isolutions\MsidNormalizer\NormalizerException;

/**
 * Normalizer construction case.
 *
 * @author Alexander Sergeychik
 */
class NormalizerConstructionTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Tests construction with empty country code
	 *
	 * @expectedException Isolutions\MsidNormalizer\NormalizerException
	 * @throws NormalizerException
	 */
	public function testConstructionWithEmptyCountryCode() {
		new Normalizer('');
	}

	/**
	 * Tests construction with not numeric
	 *
	 * @expectedException Isolutions\MsidNormalizer\NormalizerException
	 * @throws NormalizerException
	 */
	public function testConstructionWithNotNumericCode() {
		new Normalizer('abcd');
	}

	/**
	 * Tests construction with + sign
	 */
	public function testConstructionPlusSignFiltered() {
		$normalizer = new Normalizer('+375');
		$this->assertEquals('375', $normalizer->getCountryCode());
	}

	/**
	 * Tests construction with various types
	 */
	public function testConstructionCountryCodeAlwaysString() {
		// integer provided
		$normalizer = new Normalizer(375);
		$this->assertSame('375', $normalizer->getCountryCode());
	}

}

