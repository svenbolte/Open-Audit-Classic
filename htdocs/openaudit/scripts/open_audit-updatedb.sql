ALTER TABLE `system` ADD `system_vcpu` int(11) unsigned NOT NULL default '0';
ALTER TABLE `system` ADD `system_lcpu` int(11) unsigned NOT NULL default '0';
ALTER TABLE `partition` ADD `partition_used_space` int(11) unsigned NOT NULL default '0';