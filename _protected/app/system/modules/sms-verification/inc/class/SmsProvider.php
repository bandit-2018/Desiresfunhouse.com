<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / SMS Verification / Inc / Class
 */

namespace PH7;

class SmsProvider
{
    /** @var string */
    protected $sSenderNumber;

    /** @var string */
    protected $sApiToken;

    /** @var string */
    protected $sApiId;

    /**
     * @param string $sSenderNumber
     * @param string $sApiToken
     * @param string|null $sApiId At the moment, this parameter is only used by Twilio API.
     */
    public function __construct($sSenderNumber, $sApiToken, $sApiId = null)
    {
        $this->sSenderNumber = $sSenderNumber;
        $this->sApiToken = $sApiToken;
        $this->sApiId = $sApiId;
    }
}
