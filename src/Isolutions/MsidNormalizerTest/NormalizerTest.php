<?php

namespace Isolutions\MsidNormalizerTest;

use Isolutions\MsidNormalizer\Normalizer;
use Isolutions\MsidNormalizer\NormalizerException;

/**
 * Normalizer test case.
 *
 * @author Alexander Sergeychik
 */
class NormalizerTest extends \PHPUnit_Framework_TestCase {

	const VALID_EXPECTATION = '375291234567';

	/**
	 * Normalizer intstance
	 *
	 * @var Normalizer
	 */
	private $normalizer;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp();
		$this->normalizer = new Normalizer(375);
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->normalizer = null;
		parent::tearDown();
	}

	/**
	 * Checks normalizer always returns string
	 */
	public function testNormalizeTypeIsString() {
		$this->assertSame(self::VALID_EXPECTATION, $this->normalizer->normalize(self::VALID_EXPECTATION), 'String type fail');
		$this->assertSame(self::VALID_EXPECTATION, $this->normalizer->normalize((int)self::VALID_EXPECTATION), 'Integer type fail');
	}

	/**
	 * Test already normalized MSIDs not affected
	 */
	public function testNormalizeWithNoAction() {
		$this->assertSame('375291234567', $this->normalizer->normalize('375291234567'));
		$this->assertSame('123291234567', $this->normalizer->normalize('123291234567'));
		$this->assertSame('123291237', $this->normalizer->normalize('123291237'));
	}

	/**
	 * Plus sign ignoring test
	 */
	public function testNormalizeWithOrWithoutPlusSign() {

		$this->assertSame('375291234567', $this->normalizer->normalize('+375291234567'));
		$this->assertSame('375291234567', $this->normalizer->normalize('++375291234567'));

		try {
			$this->normalizer->normalize('375291234567++');
			$this->fail('Plus anywhare except leading position should rise exception');
		} catch (\Exception $e) {
			$this->assertInstanceOf(NormalizerException::class, $e);
		}

		try {
			$this->normalizer->normalize('3752912+34567');
			$this->fail('Plus anywhare except leading position should rise exception');
		} catch (\Exception $e) {
			$this->assertInstanceOf(NormalizerException::class, $e);
		}

		$this->assertSame('375291234567', $this->normalizer->normalize('375291234567'));
	}

	/**
	 * Tests Normalizer->normalize()
	 */
	public function testNormalizeCommonPrefixes() {

		$result = $this->normalizer->normalize('80291234567');
		$this->assertEquals(self::VALID_EXPECTATION, $result, 'Fail with common 80 prefix');

		$result = $this->normalizer->normalize('810375291234567');
		$this->assertEquals(self::VALID_EXPECTATION, $result, 'Fail with common 810 prefix');

		$result = $this->normalizer->normalize('0291234567');
		$this->assertEquals(self::VALID_EXPECTATION, $result, 'Fail with common 0 prefix');

		// plus sign means exact international number
		$this->assertEquals('810291234567', $this->normalizer->normalize('+810291234567'), 'Prefix should be ignored with leading + sign');
		$this->assertEquals('80291234567', $this->normalizer->normalize('+80291234567'), 'Prefix should be ignored with leading + sign');

	}

	/**
	 * Test invalid msids
	 *
	 */
	public function testInvalidMsids() {

		try {
			$this->normalizer->normalize('3752912+34567');
			$this->fail('Plus anywhare except leading position should rise exception');
		} catch (\Exception $e) {
			$this->assertInstanceOf(NormalizerException::class, $e);
		}

		try {
			$this->normalizer->normalize('37529s34567');
			$this->fail('Not digit signs in MSID should rise exception');
		} catch (\Exception $e) {
			$this->assertInstanceOf(NormalizerException::class, $e);
		}

		try {
			$this->normalizer->normalize('');
			$this->fail('Empty MSID should rise exception');
		} catch (\Exception $e) {
			$this->assertInstanceOf(NormalizerException::class, $e);
		}

	}

}

