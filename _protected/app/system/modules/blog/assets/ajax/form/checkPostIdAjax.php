<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Blog / Asset / Ajax / Form
 */

namespace PH7;

defined('PH7') or exit('Restricted access');

use PH7\Framework\Mvc\Request\Http;

$oHttpRequest = new Http;
$iStatus = 0; // Error Default Value

if ($oHttpRequest->postExists('post_id')) {
    $iPostId = $oHttpRequest->post('post_id');
    $iStatus = (new Blog)->checkPostId($iPostId, new BlogModel) ? 1 : 0;
}

echo json_encode(['status' => $iStatus]);
