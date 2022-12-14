<?php
/**
 * @title            Vimeo Class
 *
 * @author           Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright        (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license          MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package          PH7 / Framework / Video / Api
 * @link             http://ph7builder.com
 */

namespace PH7\Framework\Video\Api;

defined('PH7') or exit('Restricted access');

class Vimeo extends Api implements Apible
{
    const API_URL = 'https://vimeo.com/api/v2/video/';
    const PLAYER_URL = 'https://player.vimeo.com/video/';
    const REGEX_VIDEO_ID = '#/(\d+)($|/)#i';

    /**
     * @param string $sUrl
     *
     * @return string|bool Returns the video embed URL if it was found and is valid, FALSE otherwise.
     */
    public function getVideo(string $sUrl)
    {
        return $this->getEmbedUrl($sUrl);
    }

    /**
     * @param string $sUrl
     *
     * @return Vimeo|bool FALSE if unable to open the url, otherwise Vimeo class.
     */
    public function getInfo(string $sUrl)
    {
        $sDataUrl = static::API_URL . $this->getVideoId($sUrl) . '.json';

        if ($aData = $this->getData($sDataUrl)) {
            $this->oData = $aData[0];
            return $this;
        }

        return false;
    }

    /**
     * @param string $sUrl
     * @param string $sMedia
     * @param int|string $iWidth
     * @param int|string $iHeight
     *
     * @return string
     */
    public function getMeta(string $sUrl, string $sMedia, $iWidth, $iHeight): string
    {
        if ($sMedia === 'preview') {
            // First load the video information.
            $this->getInfo($sUrl);

            // Then retrieve the thumbnail.
            return $this->oData->thumbnail_medium;
        } else {
            $sParam = $this->bAutoplay ? '?autoplay=1&amp;' : '?';

            return '<iframe src="' . $this->getEmbedUrl($sUrl) . $sParam . 'title=0&amp;byline=0&amp;portrait=0" width="' . $iWidth . '" height="' . $iHeight . '" frameborder="0"></iframe>';
        }
    }

    /**
     * @param string $sUrl
     *
     * @return int|bool Returns the ID of the video if it was found, FALSE otherwise.
     */
    public function getVideoId(string $sUrl)
    {
        preg_match(static::REGEX_VIDEO_ID, $sUrl, $aMatch);

        return !empty($aMatch[1]) ? $aMatch[1] : false;
    }
}
