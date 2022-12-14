<?php
/**
 * @author           Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright        (c) 2017-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license          MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package          PH7 / App / System / Core / Model
 */

namespace PH7;

use PH7\Framework\Mvc\Model\Engine\Db;
use PH7\Framework\Mvc\Model\Engine\Model;

class ReportCoreModel extends Model
{
    /**
     * @return int Total of reports
     */
    public static function totalReports()
    {
        $rStmt = Db::getInstance()->prepare('SELECT COUNT(reportId) FROM' . Db::prefix(DbTableName::REPORT));
        $rStmt->execute();
        $iTotalReports = (int)$rStmt->fetchColumn();
        Db::free($rStmt);

        return $iTotalReports;
    }
}
