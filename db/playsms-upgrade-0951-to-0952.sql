ALTER TABLE `playsms_tblUser` ADD `datetime_timezone` VARCHAR( 30 ) NOT NULL DEFAULT '+0700' ;
ALTER TABLE `playsms_gatewayClickatell_config` ADD `cfg_datetime_timezone` VARCHAR( 30 ) NOT NULL DEFAULT '+0700' ;
ALTER TABLE `playsms_gatewayKannel_config` ADD `cfg_datetime_timezone` VARCHAR( 30 ) NOT NULL DEFAULT '+0700' ;
ALTER TABLE `playsms_gatewayUplink_config` ADD `cfg_datetime_timezone` VARCHAR( 30 ) NOT NULL DEFAULT '+0700' ;
ALTER TABLE `playsms_tblConfig_main` ADD `cfg_datetime_timezone` VARCHAR( 30 ) NOT NULL DEFAULT '+0700' ;
ALTER TABLE `playsms_tblConfig_main` ADD `cfg_sms_max_count` TINYINT( 4 ) NOT NULL DEFAULT '3' ;

UPDATE `playsms_tblUser` SET `datetime_timezone`='+0700' ;
UPDATE `playsms_gatewayClickatell_config` SET `cfg_datetime_timezone`='+0700' ;
UPDATE `playsms_gatewayKannel_config` SET `cfg_datetime_timezone`='+0700' ;
UPDATE `playsms_gatewayUplink_config` SET `cfg_datetime_timezone`='+0700' ;
UPDATE `playsms_tblConfig_main` SET `cfg_datetime_timezone`='+0700' ;
UPDATE `playsms_tblConfig_main` SET `cfg_sms_max_count`='3' ;
