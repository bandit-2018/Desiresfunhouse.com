<?php
/**
 * @title            Config Class
 * @desc             Loading and management config files.
 *
 * @author           Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright        (c) 2017-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license          MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package          PH7 / Framework / Config
 */

namespace PH7\Framework\Config;

use PH7\Framework\File\IOException;

defined('PH7') or exit('Restricted access');

class FileNotFoundException extends IOException
{
    const APP_FILE = 1;
    const SYS_FILE = 2;
}
