<?php

namespace Isolutions\MsidNormalizer;

/**
 * Class performs phone/msid normalization
 *
 * @author Alexander Sergeychik
 */
class Normalizer implements NormalizerInterface {

	/**
	 * Country code
	 *
	 * @var string
	 */
	private $countryCode;

	/**
	 * Constructs normalizer with required country code
	 *
	 * @param string $baseCountryCode
	 */
	public function __construct($baseCountryCode) {
		if (!$baseCountryCode) {
			throw new NormalizerException('Base country code is not set or empty');
		} elseif (!is_numeric($baseCountryCode)) {
			throw new NormalizerException('Base country code is not numeric');
		}
		$this->countryCode = (string)(int)$baseCountryCode;
	}

	/**
	 * Returns coutry code
	 *
	 * @return string
	 */
	public function getCountryCode() {
		return $this->countryCode;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Isolutions\MsidNormalizer\NormalizerInterface::normalize()
	 */
	public function normalize($phone) {

		// filter phone, strip spaces, dashes and any brackets
		$phone = preg_replace('/[\s\(\)\[\]\-]/', '', (string)$phone);

		if (!$phone) {
			throw new NormalizerException(sprintf('Empty MSID provided %s', $phone));
		} elseif (!preg_match('/^\+*(\d+)$/', $phone)) {
			throw new NormalizerException(sprintf('Invalid MSID format %s', $phone));
		}

		$matches = array();

		if (preg_match('/^\++(.*)$/', $phone, $matches)) {
			// strip trailing plus signs and return phone immidiately
			return sprintf('%s', $matches[1]);
		} elseif (preg_match('/^(?:80|0)(.+)$/', $phone, $matches)) {
			// convert prefixes to full format
			$phone = sprintf('%s%s', $this->getCountryCode(), $matches[1]);
		} elseif (preg_match('/^810(.+)$/', $phone, $matches)) {
			// convert local to international format
			$phone = sprintf('%s', $matches[1]);
		}

		return $phone;
	}

}
