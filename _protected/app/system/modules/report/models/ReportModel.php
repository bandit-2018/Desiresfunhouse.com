<?php
/**
 * @title          Report Model Class
 *
 * @author         Pierre-Henry Soria <hello@ph7builder.com>
 * @copyright      (c) 2012-2019, Pierre-Henry Soria. All Rights Reserved.
 * @license        MIT License; See LICENSE.md and COPYRIGHT.md in the root directory.
 * @package        PH7 / App / System / Module / Report / Model
 */

declare(strict_types=1);

namespace PH7;

use PDO;
use PH7\Framework\Mvc\Model\Engine\Db;

class ReportModel extends ReportCoreModel
{
    /**
     * @param array $aData
     *
     * @return bool|string
     */
    public function add(array $aData)
    {
        $rStmt = Db::getInstance()->prepare('SELECT count(reportId) FROM' . Db::prefix(DbTableName::REPORT) .
            'WHERE reporterId = :reporterId AND spammerId = :spammerId AND url = :url AND contentType = :type');

        $rStmt->bindValue(':reporterId', $aData['reporter_id'], PDO::PARAM_INT);
        $rStmt->bindValue(':spammerId', $aData['spammer_id'], PDO::PARAM_INT);
        $rStmt->bindValue(':url', $aData['url'], PDO::PARAM_STR);
        $rStmt->bindValue(':type', $aData['type'], PDO::PARAM_STR);
        $rStmt->execute();

        if ($rStmt->fetchColumn() > 0) {
            return 'already_reported';
        } else {
            $rStmt = Db::getInstance()->prepare('INSERT INTO' . Db::prefix(DbTableName::REPORT) .
                '(reporterId, spammerId, url, contentType, description, dateTime)
            VALUES (:reporterId, :spammerId, :url, :type, :desc, :time)');

            $rStmt->bindValue(':reporterId', $aData['reporter_id'], PDO::PARAM_INT);
            $rStmt->bindValue(':spammerId', $aData['spammer_id'], PDO::PARAM_INT);
            $rStmt->bindValue(':url', $aData['url'], PDO::PARAM_STR);
            $rStmt->bindValue(':type', $aData['type'], PDO::PARAM_STR);
            $rStmt->bindValue(':desc', $aData['desc'], PDO::PARAM_STR);
            $rStmt->bindValue(':time', $aData['date'], PDO::PARAM_STR);

            return $rStmt->execute();
        }
    }

    /**
     * @return array|\stdClass|bool
     */
    public function get(?int $iId, int $iOffset, int $iLimit)
    {
        $sSqlId = !empty($iId) ? ' WHERE reportId = :id ' : ' ';
        $rStmt = Db::getInstance()->prepare('SELECT * FROM' . Db::prefix(DbTableName::REPORT) . $sSqlId . 'LIMIT :offset, :limit');
        if (!empty($iId)) {
            $rStmt->bindValue(':id', $iId, PDO::PARAM_INT);
        }
        $rStmt->bindValue(':offset', $iOffset, PDO::PARAM_INT);
        $rStmt->bindValue(':limit', $iLimit, PDO::PARAM_INT);
        $rStmt->execute();

        return !empty($iId) ? $rStmt->fetch(PDO::FETCH_OBJ) : $rStmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function delete(int $iReportId): bool
    {
        $rStmt = Db::getInstance()->prepare('DELETE FROM' . Db::prefix(DbTableName::REPORT) . 'WHERE reportId = :reportId LIMIT 1');
        $rStmt->bindValue(':reportId', $iReportId, PDO::PARAM_INT);

        return $rStmt->execute();
    }
}
