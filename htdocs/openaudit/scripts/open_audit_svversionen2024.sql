DROP TABLE IF EXISTS `softwareversionen`;
CREATE TABLE `softwareversionen` (
		  `sv_datum` bigint(20) unsigned NOT NULL default '0',
          `sv_rating` varchar(45) NOT NULL default '',
          `sv_id` int(10) unsigned NOT NULL auto_increment,
          `sv_product` varchar(255) NOT NULL default '',
          `sv_version` varchar(50) NOT NULL default '1.0',
          `sv_bemerkungen` varchar(2000) NOT NULL default '',
          `sv_vorinstall` varchar(100) NOT NULL default '',
          `sv_quelle` varchar(100) NOT NULL default '',
          `sv_lizenztyp` varchar(100) NOT NULL default '',
          `sv_lizenzgeber` varchar(255) NOT NULL default '',
          `sv_lizenzbestimmungen` varchar(255) NOT NULL default '',
          `sv_instlocation` varchar(20) NOT NULL default '',
          `sv_herstellerwebsite` varchar(255) NOT NULL default '',
          `sv_linkempf` varchar(10) NOT NULL default '',
          `sv_icondata` varchar(20000) NOT NULL default '',
          PRIMARY KEY  (`sv_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
