<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-02-26 14:44:22 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'SELECT Action
               FROM CandidateStageTracking
               WHERE Ap' at line 3 - Invalid query: SELECT `c`.`CandidateId`, `c`.`CandidateCode`, `c`.`Fullname`, `c`.`Email`, `c`.`PhoneNo`, `c`.`ProfileMatchPer`, `ja`.`CurrentStage`, `ja`.`AppliedOn`, `rs`.`StageOrder` as `CurrentStageOrder`, `j`.`Jid`, ci.InterviewId
            (
               SELECT Action
               FROM CandidateStageTracking
               WHERE ApplicationId = ja.ApplicationId
               ORDER BY ActionAt DESC
               LIMIT 1
            ) as LastAction
FROM `candidateinterviews` `ci`
JOIN `jobapplications` `ja` ON `ja`.`ApplicationId` = `ci`.`ApplicationId`
JOIN `ihrcandidates` `c` ON `c`.`CandidateId` = `ja`.`CandidateId`
JOIN `ihrjobslist` `j` ON `j`.`Jid` = `ja`.`Jid`
LEFT JOIN `recruitmentstages` `rs` ON `rs`.`StageId` = `ja`.`CurrentStage`
WHERE `ci`.`InterviewerId` = '5'
GROUP BY `ja`.`ApplicationId`
ERROR - 2026-02-26 17:43:27 --> Array
ERROR - 2026-02-26 18:04:28 --> Array
ERROR - 2026-02-26 18:04:57 --> Array
