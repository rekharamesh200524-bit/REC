<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-02-25 06:06:42 --> 404 Page Not Found: ../modules/admin/controllers/Admin/http%3A
ERROR - 2026-02-25 06:07:12 --> 404 Page Not Found: ../modules/admin/controllers/Admin/http%3A
ERROR - 2026-02-25 11:18:06 --> Query error: Unknown column 'ja.StageId' in 'on clause' - Invalid query: SELECT `c`.`CandidateId`, `c`.`CandidateCode`, `c`.`Fullname`, `c`.`Email`, `c`.`PhoneNo`, `c`.`ExpYrs`, `c`.`ResumePath`, `c`.`ATS_Status`, `c`.`ATS_Stage`, `c`.`ProfileMatchPer`, `c`.`MatchedSkills`, `c`.`EducationMatch`, `c`.`ExperienceMatch`, `c`.`ScoreBreakdown`, `ja`.`ApplicationId`, `ja`.`CurrentStage`, `ja`.`CurrentStatus`, `ja`.`AppliedOn`, `ja`.`UpdatedAt` AS `ApplicationUpdatedAt`, `j`.`JobCode`, `j`.`JobTitle`, `j`.`EducationRequired`, `j`.`ExpMin`, `j`.`ExpMax`, `j`.`JobLocation`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS JobSkills, `rst`.`StageName` AS `CurrentStageName`, `cst`.`Action` AS `LastAction`, `cst`.`ActionAt` AS `LastActionAt`, `u`.`EmpName` AS `ActionByUser`
FROM `IHrCandidates` `c`
INNER JOIN `JobApplications` `ja` ON `c`.`CandidateId` = `ja`.`CandidateId`
INNER JOIN `IHRJobsList` `j` ON `ja`.`Jid` = `j`.`Jid`
LEFT JOIN `JobSkills` `js` ON `j`.`Jid` = `js`.`Jid`
LEFT JOIN `IHSkills` `s` ON `js`.`SkillId` = `s`.`SkillId`
LEFT JOIN `CandidateStageTracking` `cst` ON `ja`.`ApplicationId` = `cst`.`ApplicationId`
LEFT JOIN `RecruitmentStages` `rst` ON `rst`.`StageId` = `ja`.`StageId`
LEFT JOIN `ihusers` `u` ON `u`.`IUid` = `cst`.`ActionBy`
WHERE `ja`.`Jid` = '1'
GROUP BY `c`.`CandidateId`, `ja`.`ApplicationId`
ORDER BY `cst`.`ActionAt` DESC
ERROR - 2026-02-25 06:51:51 --> Severity: error --> Exception: syntax error, unexpected '{', expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1147
ERROR - 2026-02-25 12:19:01 --> Query error: Column 'Action' cannot be null - Invalid query: INSERT INTO `candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `Remarks`) VALUES ('37', '2', NULL, '2', 'reka is a good girl')
ERROR - 2026-02-25 12:19:02 --> Query error: Column 'Action' cannot be null - Invalid query: INSERT INTO `candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `Remarks`) VALUES ('37', '2', NULL, '2', 'reka is a good girl')
ERROR - 2026-02-25 12:19:03 --> Query error: Column 'Action' cannot be null - Invalid query: INSERT INTO `candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `Remarks`) VALUES ('37', '2', NULL, '2', 'reka is a good girl')
ERROR - 2026-02-25 12:19:04 --> Query error: Column 'Action' cannot be null - Invalid query: INSERT INTO `candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `Remarks`) VALUES ('37', '2', NULL, '2', 'reka is a good girl')
ERROR - 2026-02-25 12:19:04 --> Query error: Column 'Action' cannot be null - Invalid query: INSERT INTO `candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `Remarks`) VALUES ('37', '2', NULL, '2', 'reka is a good girl')
ERROR - 2026-02-25 12:19:04 --> Query error: Column 'Action' cannot be null - Invalid query: INSERT INTO `candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `Remarks`) VALUES ('37', '2', NULL, '2', 'reka is a good girl')
ERROR - 2026-02-25 12:21:36 --> Query error: Table 'ihrms_core_db.candidate_stage_track' doesn't exist - Invalid query: INSERT INTO `candidate_stage_track` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES ('37', '2', 'Updated', NULL, '2026-02-25 12:21:36', 'sdsds')
ERROR - 2026-02-25 12:21:40 --> Query error: Table 'ihrms_core_db.candidate_stage_track' doesn't exist - Invalid query: INSERT INTO `candidate_stage_track` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES ('37', '2', 'Updated', NULL, '2026-02-25 12:21:40', 'sdsds')
ERROR - 2026-02-25 12:23:47 --> Query error: Table 'ihrms_core_db.candidate_stage_track' doesn't exist - Invalid query: INSERT INTO `candidate_stage_track` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES ('37', '2', 'Updated', NULL, '2026-02-25 12:23:47', 'fff')
ERROR - 2026-02-25 12:38:41 --> Query error: Column 'ApplicationId' cannot be null - Invalid query: INSERT INTO `Candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES (NULL, '2', 'Approved', '2', '2026-02-25 12:38:41', 'sss')
ERROR - 2026-02-25 12:38:41 --> Query error: Column 'ApplicationId' cannot be null - Invalid query: INSERT INTO `Candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES (NULL, '2', 'Approved', '2', '2026-02-25 12:38:41', 'sss')
ERROR - 2026-02-25 12:38:42 --> Query error: Column 'ApplicationId' cannot be null - Invalid query: INSERT INTO `Candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES (NULL, '2', 'Approved', '2', '2026-02-25 12:38:42', 'sss')
ERROR - 2026-02-25 12:38:43 --> Query error: Column 'ApplicationId' cannot be null - Invalid query: INSERT INTO `Candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES (NULL, '2', 'Approved', '2', '2026-02-25 12:38:43', 'sss')
ERROR - 2026-02-25 12:38:45 --> Query error: Column 'ApplicationId' cannot be null - Invalid query: INSERT INTO `Candidatestagetracking` (`ApplicationId`, `StageId`, `Action`, `ActionBy`, `ActionAt`, `Remarks`) VALUES (NULL, '2', 'Approved', '2', '2026-02-25 12:38:45', 'sss')
ERROR - 2026-02-25 08:29:48 --> Severity: error --> Exception: syntax error, unexpected 'if' (T_IF), expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1238
ERROR - 2026-02-25 14:59:27 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '', '1', '2', '2026-02-25 14:59:27')
ERROR - 2026-02-25 14:59:30 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '', '1', '2', '2026-02-25 14:59:30')
ERROR - 2026-02-25 15:07:59 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:07:59')
ERROR - 2026-02-25 15:08:00 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:08:00')
ERROR - 2026-02-25 15:08:00 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:08:00')
ERROR - 2026-02-25 15:08:00 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:08:00')
ERROR - 2026-02-25 15:08:01 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:08:01')
ERROR - 2026-02-25 15:08:01 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:08:01')
ERROR - 2026-02-25 15:08:01 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:08:01')
ERROR - 2026-02-25 15:08:01 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-27T15:08', '1', '2', '2026-02-25 15:08:01')
ERROR - 2026-02-25 15:10:33 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-03-06T15:10', '1', '2', '2026-02-25 15:10:33')
ERROR - 2026-02-25 15:16:58 --> Query error: Unknown column 'InterviewDate' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewDate`, `InterviewerId`, `CreatedBy`, `CreatedAt`) VALUES ('36', '1', '2026-02-28T15:16', '5', '2', '2026-02-25 15:16:58')
ERROR - 2026-02-25 11:58:12 --> 404 Page Not Found: ../modules/admin/controllers/Admin/MyInterviews
ERROR - 2026-02-25 11:59:22 --> 404 Page Not Found: ../modules/admin/controllers/Admin/MyInterviews
ERROR - 2026-02-25 17:05:45 --> Query error: Unknown column 'ci.InterviewerId' in 'where clause' - Invalid query: SELECT `c`.`Fullname`, `c`.`Email`, `c`.`PhoneNo`, `j`.`JobTitle`, `ci`.`ScheduledAt`
FROM `candidateinterviews` `ci`
JOIN `jobapplications` `ja` ON `ja`.`ApplicationId` = `ci`.`ApplicationId`
JOIN `ihrcandidates` `c` ON `c`.`CandidateId` = `ja`.`CandidateId`
JOIN `ihrjobslist` `j` ON `j`.`Jid` = `ja`.`Jid`
WHERE `ci`.`InterviewerId` = '5'
ERROR - 2026-02-25 17:33:02 --> Query error: Unknown column 'CreatedAt' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewType`, `ScheduledAt`, `InterviewerId`, `Result`, `CreatedAt`) VALUES ('37', '9', 'Offline', '2026-02-27T17:32', '', 'Hold', '2026-02-25 17:33:02')
ERROR - 2026-02-25 17:33:03 --> Query error: Unknown column 'CreatedAt' in 'field list' - Invalid query: INSERT INTO `CandidateInterviews` (`ApplicationId`, `InterviewRound`, `InterviewType`, `ScheduledAt`, `InterviewerId`, `Result`, `CreatedAt`) VALUES ('37', '9', 'Offline', '2026-02-27T17:32', '', 'Hold', '2026-02-25 17:33:03')
ERROR - 2026-02-25 18:06:44 --> Query error: Unknown column 'ja.ProfileMatchPer' in 'field list' - Invalid query: SELECT `c`.`CandidateId`, `c`.`CandidateCode`, `c`.`Fullname`, `c`.`Email`, `c`.`PhoneNo`, `ja`.`ProfileMatchPer`, `ja`.`CurrentStage`, `ja`.`AppliedOn`, `rs`.`StageOrder` as `CurrentStageOrder`, `j`.`Jid`
FROM `candidateinterviews` `ci`
JOIN `jobapplications` `ja` ON `ja`.`ApplicationId` = `ci`.`ApplicationId`
JOIN `ihrcandidates` `c` ON `c`.`CandidateId` = `ja`.`CandidateId`
JOIN `ihrjobslist` `j` ON `j`.`Jid` = `ja`.`Jid`
LEFT JOIN `recruitmentstages` `rs` ON `rs`.`StageId` = `ja`.`CurrentStage`
WHERE `ci`.`InterviewerId` = '5'
ERROR - 2026-02-25 13:59:54 --> Severity: error --> Exception: syntax error, unexpected '->' (T_OBJECT_OPERATOR) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1421
ERROR - 2026-02-25 14:00:16 --> Severity: error --> Exception: syntax error, unexpected '->' (T_OBJECT_OPERATOR) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1421
ERROR - 2026-02-25 14:01:57 --> Severity: error --> Exception: syntax error, unexpected '{', expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1395
ERROR - 2026-02-25 14:01:58 --> Severity: error --> Exception: syntax error, unexpected '{', expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1395
ERROR - 2026-02-25 14:01:58 --> Severity: error --> Exception: syntax error, unexpected '{', expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1395
ERROR - 2026-02-25 14:01:59 --> Severity: error --> Exception: syntax error, unexpected '{', expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1395
ERROR - 2026-02-25 14:01:59 --> Severity: error --> Exception: syntax error, unexpected '{', expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1395
ERROR - 2026-02-25 14:01:59 --> Severity: error --> Exception: syntax error, unexpected '{', expecting function (T_FUNCTION) or const (T_CONST) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 1395
