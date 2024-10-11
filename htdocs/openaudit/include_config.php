<?php 
// Ensures that all variables have a default value
include_once "include_config_defaults.php"; 

$mysqli_server = 'localhost'; 
$mysqli_database = 'openaudit'; 
$mysqli_user = 'openaudit'; 
$mysqli_password = 'flocke'; 
$use_https = '';
// An array of allowed users and their passwords
// Make sure to set use_pass = "n" if you do not wish to use passwords
$use_pass = 'n';
$users = array(
  'admin' => 'Open-AudIT'
);
// Config options for index.php
$show_other_discovered = 'y';
$other_detected = '30';

$show_system_discovered = 'y';
$system_detected = '30';

$show_systems_not_audited = 'y';
$days_systems_not_audited = '30';

$show_partition_usage = 'y';
$partition_free_space = '1000';

$show_software_detected = 'y';
$days_software_detected = '30';

$show_patches_not_detected = 'y';
$number_patches_not_detected = '10';

$show_detected_servers = 'y';
$show_detected_xp_av = 'y';
$show_detected_rdp = 'y';

$show_os = 'y';
$show_date_audited = 'y';
$show_type = 'y';
$show_description = 'y';
$show_domain = 'y';
$show_service_pack = 'n';

$count_system = '30';

$round_to_decimal_places = '2';

$management_domain_suffix = 'local' ;
$vnc_type = 'ultra';

$ldap_user = 'unknown@domain.local';
$ldap_secret = 'password';
$ldap_server = 'myserver.local';
$ldap_base_dn = 'dc=domain,dc=local';
$ldap_connect_string = 'LDAP:\/\/server.domain.local';
$use_ldap_login = 'n';
$show_ldap_changes = 'y';
$ldap_changes_days = 7;
$show_systems_audited_graph = 'y';
$systems_audited_days = 30;
$show_hard_disk_alerts = 'y';
$hard_disk_alerts_days = '7';
$language = 'de_de'; ?>