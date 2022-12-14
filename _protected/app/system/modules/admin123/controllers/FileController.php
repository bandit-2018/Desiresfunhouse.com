<?php
/**
 * @title          File Controller
 *
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Admin / Controller
 * @version        1.4
 * @history        Removed the functionality of deletions and adding files for security reasons and to maintain proper operations.
 */

namespace PH7;

use PH7\Framework\Layout\Tpl\Engine\PH7Tpl\PH7Tpl;
use PH7\Framework\Security\Ban\Ban;
use PH7\Framework\Service\Suggestion;

class FileController extends Controller
{
    const THEME_FILE_EXTS = [
        '.tpl',
        '.css',
        '.js'
    ];

    private string $sTitle;

    public function themeDisplay()
    {
        $this->sTitle = t('Template Files');

        $this->displayAction(PH7_PATH_TPL, self::THEME_FILE_EXTS);
        $this->manualTplInclude('publicdisplay.inc.tpl');

        $this->output();
    }

    public function mailDisplay()
    {
        $this->sTitle = t('Email Templates');

        $this->displayAction(
            PH7_PATH_SYS . 'global' . PH7_DS . PH7_VIEWS . PH7_TPL_MAIL_NAME . PH7_DS . 'tpl' . PH7_DS . 'mail' . PH7_DS,
            '.tpl'
        );

        $this->manualTplInclude('protecteddisplay.inc.tpl');

        $this->output();
    }

    public function banDisplay()
    {
        $this->sTitle = t('Banned Files');

        $this->displayAction(PH7_PATH_APP_CONFIG . Ban::DIR, Ban::EXT);
        $this->manualTplInclude('protecteddisplay.inc.tpl');

        $this->output();
    }

    public function suggestionDisplay()
    {
        $this->sTitle = t('Suggestion Files');

        $this->displayAction(PH7_PATH_APP_CONFIG . Suggestion::DIR, Suggestion::EXT);
        $this->manualTplInclude('protecteddisplay.inc.tpl');

        $this->output();
    }

    public function pageDisplay()
    {
        $this->sTitle = t('Pages');

        $this->displayAction(
            PH7_PATH_SYS_MOD . 'page' . PH7_DS . PH7_VIEWS . PH7_TPL_MOD_NAME, PH7Tpl::TEMPLATE_FILE_EXT
        );

        $this->manualTplInclude('protecteddisplay.inc.tpl');

        $this->output();
    }

    public function somethingProtectedAppDisplay()
    {
        $this->displayAction(PH7_PATH_APP . $this->httpRequest->get('dir'));
        $this->manualTplInclude('protecteddisplay.inc.tpl');

        $this->output();
    }

    public function publicEdit()
    {
        $this->sTitle = t('Edit Public Files');
        $this->view->page_title = $this->view->h2_title = $this->sTitle;

        $this->output();
    }

    public function protectedEdit()
    {
        $this->sTitle = t('Edit Protected Files');
        $this->view->page_title = $this->view->h2_title = $this->sTitle;

        $this->output();
    }

    /**
     * Prototype method to show the public and protected files.
     *
     * @param string $sFile Full path.
     * @param string|array|null $mExt Retrieves only files with specific extensions.
     *
     * @return void
     */
    private function displayAction($sFile, $mExt = null)
    {
        if (!$this->isPageTitleSet()) {
            $this->sTitle = t('File Manager');
        }

        $this->view->page_title = $this->view->h2_title = $this->sTitle;
        $this->view->filesList = $this->file->getFileList($sFile, $mExt);
    }

    /**
     * @return bool
     */
    private function isPageTitleSet()
    {
        return !empty($this->sTitle);
    }
}
