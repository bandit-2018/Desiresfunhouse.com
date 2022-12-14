<?php
/**
 * @title          Escape Exception Trait
 * @desc           Escape the exception message.
 *
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7/ Framework / Error / CException
 * @version        1.1
 */

namespace PH7\Framework\Error\CException;

defined('PH7') or exit('Restricted access');

trait Escape
{
    /** @var string */
    private $sAllowTags = '<br><i><em><b><strong><u>';

    /**
     * Escape the exception message.
     *
     * @param string $sMsg
     */
    protected function strip($sMsg)
    {
        $this->message = strip_tags($sMsg, $this->sAllowTags);
    }
}
