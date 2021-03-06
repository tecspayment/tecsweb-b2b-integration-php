<?php

/**
 * @author Branislav Zvolensky <branislav.zvolensky@tecs.at>
 * @copyright TECS telecommunication & e-commercesolutions GmbH
 * @license GNU-v3
 */

namespace Tecs\Generator;

use Tecs\Common\ResponseDataInterface;

/**
 * Class ResponseSign
 * @package Tecs\Generator
 */
class ResponseSign implements ResponseDataInterface
{
    /**
     * @var string
     */
    private $secret = 'merchantSecret';

    /**
     * @var array
     */
    private $data = [];

    /**
     * ResponseSign constructor.
     * @param string $secret
     * @param array $data
     */
    public function __construct($secret, array $data)
    {
        $this->secret = $secret;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getSign()
    {
        $toHash = $this->data[strtolower(self::RESPONSE_CODE)];
        $toHash .= $this->data[strtolower(self::RESPONSE_TEXT)];
        $toHash .= $this->data[strtolower(self::TX_ID)];
        $toHash .= isset($this->data[strtolower(self::CARD_REF_NUMBER)])
            ? $this->data[strtolower(self::CARD_REF_NUMBER)] : '';
        $toHash .= isset($this->data[strtolower(self::USER_DATA)])
            ? $this->data[strtolower(self::USER_DATA)] : '';
        $toHash .= $this->secret;

        $toHashASCII = iconv('UTF-8', 'ASCII//TRANSLIT', $toHash);

        return strtoupper(sha1($toHashASCII));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getSign();
    }
}
