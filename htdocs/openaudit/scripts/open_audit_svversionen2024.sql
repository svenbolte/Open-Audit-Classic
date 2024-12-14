DROP TABLE IF EXISTS `softwareversionen`;
CREATE TABLE `softwareversionen` (
  `sv_datum` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `sv_rating` varchar(45) NOT NULL DEFAULT '',
  `sv_id` int(10) UNSIGNED NOT NULL,
  `sv_product` varchar(255) NOT NULL DEFAULT '',
  `sv_version` varchar(50) NOT NULL DEFAULT '1.0',
  `sv_bemerkungen` varchar(2000) NOT NULL DEFAULT '',
  `sv_vorinstall` varchar(100) NOT NULL DEFAULT '',
  `sv_quelle` varchar(100) NOT NULL DEFAULT '',
  `sv_lizenztyp` varchar(100) NOT NULL DEFAULT '',
  `sv_lizenzgeber` varchar(255) NOT NULL DEFAULT '',
  `sv_lizenzbestimmungen` varchar(255) NOT NULL DEFAULT '',
  `sv_instlocation` varchar(20) NOT NULL DEFAULT '',
  `sv_herstellerwebsite` varchar(255) NOT NULL DEFAULT '',
  `sv_linkempf` varchar(10) NOT NULL DEFAULT '',
  `sv_icondata` varchar(20000) NOT NULL DEFAULT '',
  `sv_supportmail` varchar(60) NOT NULL,
  `sv_supporttel` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
