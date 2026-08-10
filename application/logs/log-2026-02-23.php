<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2026-02-23 05:34:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:34:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:01 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:01 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:01 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:01 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:01 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:01 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 05:44:01 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 10:38:13 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 967
ERROR - 2026-02-23 10:38:16 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 967
ERROR - 2026-02-23 10:38:35 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 967
ERROR - 2026-02-23 10:38:36 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 967
ERROR - 2026-02-23 10:38:36 --> Severity: Warning --> Invalid argument supplied for foreach() C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 967
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 06:21:00 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 10:56:45 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`ihrms_core_db`.`jobskills`, CONSTRAINT `jobskills_ibfk_2` FOREIGN KEY (`SkillId`) REFERENCES `ihskills` (`SkillId`) ON DELETE CASCADE) - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', 'chennai')
ERROR - 2026-02-23 10:56:50 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`ihrms_core_db`.`jobskills`, CONSTRAINT `jobskills_ibfk_2` FOREIGN KEY (`SkillId`) REFERENCES `ihskills` (`SkillId`) ON DELETE CASCADE) - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', 'chennai')
ERROR - 2026-02-23 11:04:56 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`ihrms_core_db`.`jobskills`, CONSTRAINT `jobskills_ibfk_2` FOREIGN KEY (`SkillId`) REFERENCES `ihskills` (`SkillId`) ON DELETE CASCADE) - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', 'chennai')
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 07:37:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:12:51 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '7'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:16:48 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:17:02 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '2'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:17:03 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '2'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:17:04 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '2'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:17:04 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '2'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:17:08 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '1'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:47:32 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:17:38 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '1'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:17:39 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '1'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:17:40 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `j`.`PostedBy`
WHERE `jl`.`Jid` = '2'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:19:20 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:19:27 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:19:27 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:19:28 --> Query error: Table 'ihrms_core_db.users' doesn't exist - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `users` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:20:20 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:20:21 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 08:52:35 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 905
ERROR - 2026-02-23 08:53:24 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:53:24 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:53:25 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:53:25 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:53:25 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:54:12 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:54:14 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:54:14 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:54:14 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:54:14 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:54:15 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 08:54:15 --> Severity: error --> Exception: syntax error, unexpected 'public' (T_PUBLIC) C:\xampp\htdocs\ihrms\application\modules\admin\controllers\Admin.php 908
ERROR - 2026-02-23 13:25:13 --> Query error: Unknown column 'u.FullName' in 'field list' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, `u`.`FullName` AS `PostedByName`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:25:18 --> Query error: Unknown column 'u.FullName' in 'field list' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, `u`.`FullName` AS `PostedByName`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '3'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:28:45 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '1'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:28:46 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '1'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:29:02 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '4'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:29:03 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '4'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:29:03 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '4'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:29:07 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '4'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 08:59:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:29:56 --> Query error: Unknown column 'u.id' in 'on clause' - Invalid query: SELECT `jl`.*, `d`.`Departmentname`, `jl`.`RoleSummary`, GROUP_CONCAT(s.SkillName SEPARATOR ', ') AS Skills
FROM `ihrjobslist` `jl`
LEFT JOIN `departments` `d` ON `d`.`Did` = `jl`.`Did`
LEFT JOIN `jobskills` `js` ON `js`.`Jid` = `jl`.`Jid`
LEFT JOIN `ihskills` `s` ON `s`.`SkillId` = `js`.`SkillId`
LEFT JOIN `ihusers` `u` ON `u`.`id` = `jl`.`PostedBy`
WHERE `jl`.`Jid` = '4'
GROUP BY `jl`.`Jid`
ERROR - 2026-02-23 13:51:27 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:51:29 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:51:30 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:51:31 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:51:31 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:51:31 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:52:28 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:52:31 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:52:31 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:52:31 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 13:52:31 --> Query error: Duplicate entry '7-9' for key 'PRIMARY' - Invalid query: INSERT INTO `jobskills` (`Jid`, `SkillId`) VALUES ('7', '9')
ERROR - 2026-02-23 11:08:27 --> 404 Page Not Found: ../modules/admin/controllers/Admin/forgot-password.html
ERROR - 2026-02-23 11:10:05 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:05 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:30 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:54 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:55 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:55 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:10:55 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 11:11:02 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:00:13 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:01:07 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 12:47:26 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:03:24 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:05:06 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:39 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:42 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:42 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:42 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:42 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:43 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:43 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:43 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:43 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:43 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:21:48 --> 404 Page Not Found: ../modules/admin/controllers/Admin/dist
ERROR - 2026-02-23 13:36:47 --> 404 Page Not Found: ../modules/admin/controllers/Admin/SaveDepartment
ERROR - 2026-02-23 13:36:51 --> 404 Page Not Found: ../modules/admin/controllers/Admin/SaveDepartment
ERROR - 2026-02-23 14:01:19 --> 404 Page Not Found: ../modules/admin/controllers/Admin/SaveDepartment
