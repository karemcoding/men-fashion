<?php

namespace common\util;

use Mpdf\MpdfException;

/**
 * Class Mpdf
 * @package common\util
 */
class Mpdf extends \Mpdf\Mpdf
{

    /**
     * Mpdf constructor.
     * @param array $config
     * @throws MpdfException
     */
    public function __construct(array $config = [])
    {
        $config['mode'] = $config['mode'] ?? 'utf-8';
        $config['format'] = $config['format'] ?? 'A4';
        parent::__construct($config);
    }
}