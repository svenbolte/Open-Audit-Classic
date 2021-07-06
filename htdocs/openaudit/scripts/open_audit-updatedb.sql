DROP TABLE IF EXISTS `optionalfeatures`;
CREATE TABLE `optionalfeatures` (
  `opt_id` int(10) unsigned NOT NULL auto_increment,
  `opt_uuid` varchar(100) NOT NULL default '',
  `caption` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`opt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
