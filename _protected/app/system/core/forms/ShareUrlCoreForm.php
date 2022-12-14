<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Core / Form
 */

namespace PH7;

use PFBC\Element\HTMLExternal;
use PFBC\Element\Url;
use PH7\Framework\Mvc\Request\Http;

class ShareUrlCoreForm
{
    /**
     * @param $sUrl The URL to share. If you enter nothing, it will be the current URL.
     * @param int $iWidth Width of the form in pixel.
     * @param bool $bShowShareUrlLabel If TURE, it shows 'Share URL:' label beside the field.
     *
     * @return void
     */
    public static function display($sUrl = null, $iWidth = null, $bShowShareUrlLabel = true)
    {
        $sUrl = !empty($sUrl) ? $sUrl : (new Http)->currentUrl();
        $sLabel = $bShowShareUrlLabel ? t('Share URL:') : '';

        $oForm = new \PFBC\Form('form_share_url', $iWidth);
        $oForm->configure(['action' => '', 'class' => 'center']);
        $oForm->addElement(
            new Url(
                $sLabel,
                'share',
                [
                    'value' => $sUrl,
                    'readonly' => 'readonly',
                    'onclick' => 'this.select()'
                ]
            )
        );
        $oForm->addElement(new HTMLExternal('<br />'));
        $oForm->render();
    }
}
