<?php
/**
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Note / Form / Processing
 */

declare(strict_types=1);

namespace PH7;

defined('PH7') or exit('Restricted access');

use PH7\Framework\Mvc\Model\DbConfig;
use PH7\Framework\Mvc\Model\Engine\Db;
use PH7\Framework\Mvc\Request\Http;
use PH7\Framework\Mvc\Router\Uri;
use PH7\Framework\Security\Moderation\Filter;
use PH7\Framework\Url\Header;

class NoteFormProcess extends Form implements NudityDetectable
{
    private int $iApproved;

    public function __construct()
    {
        parent::__construct();

        $this->iApproved = DbConfig::getSetting('noteManualApproval') == 0 ? 1 : 0;

        $oNote = new Note;
        $oNoteModel = new NoteModel;
        $sCurrentTime = $this->dateTime->get()->dateTime('Y-m-d H:i:s');
        $iProfileId = (int)$this->session->get('member_id');
        $iTimeDelay = (int)DbConfig::getSetting('timeDelaySendNote');

        $sPostId = $this->str->lower($this->httpRequest->post('post_id'));
        if (!$oNote->checkPostId($sPostId, $iProfileId, $oNoteModel)) {
            \PFBC\Form::setError('form_note', t('The post ID already exists or is incorrect.'));
        } elseif (!$oNoteModel->checkWaitSend($this->session->get('member_id'), $iTimeDelay, $sCurrentTime)) {
            \PFBC\Form::setError('form_note', Form::waitWriteMsg($iTimeDelay));
        } else {
            if ($oNote->isThumbnailUploaded() && $this->isNudityFilterEligible()) {
                $this->checkNudityFilter();
            }

            $aNoteData = [
                'profile_id' => $iProfileId,
                'post_id' => $sPostId,
                'lang_id' => $this->httpRequest->post('lang_id'),
                'title' => $this->httpRequest->post('title'),
                'content' => $this->httpRequest->post('content', Http::ONLY_XSS_CLEAN), // HTML contents, so we use Http::ONLY_XSS_CLEAN constant
                'slogan' => $this->httpRequest->post('slogan'),
                'tags' => $this->httpRequest->post('tags'),
                'page_title' => $this->httpRequest->post('page_title'),
                'meta_description' => $this->httpRequest->post('meta_description'),
                'meta_keywords' => $this->httpRequest->post('meta_keywords'),
                'meta_robots' => $this->httpRequest->post('meta_robots'),
                'meta_author' => $this->httpRequest->post('meta_author'),
                'meta_copyright' => $this->httpRequest->post('meta_copyright'),
                'enable_comment' => $this->httpRequest->post('enable_comment'),
                'created_date' => $sCurrentTime,
                'approved' => $this->iApproved
            ];

            if ($this->hasCategoriesReachedLimit()) {
                \PFBC\Form::setError(
                    'form_note',
                    t('You cannot select more than %0% categories.', Note::MAX_CATEGORY_ALLOWED)
                );
            } elseif (!$oNoteModel->addPost($aNoteData)) {
                \PFBC\Form::setError('form_note', t('An error occurred while adding the post.'));
            } else {
                $this->setCategories($iProfileId, $oNoteModel);

                /*** Set the thumbnail if there's one ***/
                $oPost = $oNoteModel->readPost($aNoteData['post_id'], $iProfileId, null);

                if ($oNote->isThumbnailUploaded()) {
                    $oNote->setThumb($oPost, $oNoteModel, $this->file);
                }

                Note::clearCache();

                $this->redirectToPostPage($sPostId);
            }
        }
    }

    public function isNudityFilterEligible(): bool
    {
        return $this->iApproved === 1 && DbConfig::getSetting('nudityFilter');
    }

    public function checkNudityFilter(): void
    {
        if (Filter::isNudity($_FILES['thumb']['tmp_name'])) {
            $this->iApproved = 0;
        }
    }

    /**
     * Set the categorie(s).
     *
     * @param int $iProfileId
     * @param NoteModel $oNoteModel
     *
     * @return void
     *
     * @internal WARNING: Be careful, you should use Http::NO_CLEAN constant,
     * otherwise Http::post() method removes the special tags and damages the SQL queries for entry into the database.
     */
    private function setCategories($iProfileId, NoteModel $oNoteModel): void
    {
        $aCategoryIds = $this->httpRequest->post('category_id', Http::NO_CLEAN);

        if (is_array($aCategoryIds)) {
            $iNoteId = Db::getInstance()->lastInsertId();

            foreach ($aCategoryIds as $iCategoryId) {
                $oNoteModel->addCategory($iCategoryId, $iNoteId, $iProfileId);
            }
        }
    }

    /**
     * @return bool TRUE if the maximum allowed categories has been reached, FALSE otherwise.
     */
    private function hasCategoriesReachedLimit(): bool
    {
        $aCategories = $this->httpRequest->post('category_id');

        return is_array($aCategories) && count($aCategories) > Note::MAX_CATEGORY_ALLOWED;
    }
    private function redirectToPostPage(string $sPostId): void
    {
        if ($this->iApproved === 0) {
            $sMsg = t('Your note has been received. It will not be visible until it is approved by our moderators. Please do not send a new one.');
        } else {
            $sMsg = t('Post successfully created!');
        }

        Header::redirect(
            Uri::get(
                'note',
                'main',
                'read',
                $this->session->get('member_username') . ',' . $sPostId
            ),
            $sMsg
        );
    }
}
