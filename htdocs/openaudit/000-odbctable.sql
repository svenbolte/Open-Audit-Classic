DROP TABLE IF EXISTS `odbc`;
CREATE TABLE `odbc` (
  `odbc_id` int(10) unsigned NOT NULL auto_increment,
  `odbc_uuid` varchar(100) NOT NULL default '',
  `odbc_dsn` varchar(200) NOT NULL default '',
  `odbc_config` varchar(3000) NOT NULL default '',
  `odbc_timestamp` bigint(20) unsigned NOT NULL default '0',
  `odbc_first_timestamp` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`odbc_id`),
  KEY `id` (`odbc_uuid`),
  KEY `id2` (`odbc_timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

