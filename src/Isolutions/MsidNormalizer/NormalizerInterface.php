<?php

namespace Isolutions\MsidNormalizer;

/**
 * MSID normalizer interface
 *
 * @author Alexander Sergeychik
 */
interface NormalizerInterface {

	/**
	 * Phone/msid normalization
	 *
	 * @param string $phone
	 * @return string
	 * @throws NormalizerException
	 */
	public function normalize($phone);

}
