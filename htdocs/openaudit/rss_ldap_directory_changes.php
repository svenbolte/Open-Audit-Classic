<?php
include_once "include_config.php";
include_once "include_functions.php";
include_once "include_lang.php";

header('Content-type: application/rss+xml');

//Variables
$site_protocol = (isset($use_https) AND $use_https == "y") ? "https://" : "http://";
$sitebaseurl = $site_protocol  . $_SERVER["SERVER_NAME"] . ':' . $oaserver_port  . dirname($_SERVER["SCRIPT_NAME"]) . "/";

$sitename = "LDAP Directory changes in the last ".$ldap_changes_days." Day(s)";
$sitedescription = "LDAP Directory changes detected by Open Audit.";

//New Translatation-System
if($language=="") $GLOBALS["language"]="en";
$language_file="./lang/".$GLOBALS["language"].".inc";
if(is_file($language_file)){
    include($language_file);
}else{
    die("Language-File not found: ".$language_file);
}

$db=GetOpenAuditDbConnection() or die('Could not connect: ' . mysqli_error($db));
mysqli_select_db($db,$mysqli_database);

$sql ="
SELECT * FROM (

(SELECT ldap_connections_name, ldap_users_cn as cn, ldap_users_dn as dn, 'deleted' as img, 'User' as objtype
FROM ldap_users
LEFT JOIN ldap_paths ON ldap_users.ldap_users_path_id=ldap_paths.ldap_paths_id
LEFT JOIN ldap_connections ON ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id
WHERE ldap_users_timestamp<>ldap_paths_timestamp
AND ldap_users_timestamp>'".adjustdate(0,0,-$ldap_changes_days)."000000')

UNION

(SELECT ldap_connections_name, ldap_users_cn as cn, ldap_users_dn as dn, 'active' as img, 'User' as objtype
FROM ldap_users
LEFT JOIN ldap_paths ON ldap_users.ldap_users_path_id=ldap_paths.ldap_paths_id
LEFT JOIN ldap_connections ON ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id
WHERE ldap_users_timestamp=ldap_paths_timestamp
AND ldap_users_first_timestamp>'".adjustdate(0,0,-$ldap_changes_days)."000000')

UNION

(SELECT ldap_connections_name, ldap_computers_cn as cn, ldap_computers_dn as dn, 'deleted' as img, 'Computer' as objtype
FROM ldap_computers
LEFT JOIN ldap_paths ON ldap_computers.ldap_computers_path_id=ldap_paths.ldap_paths_id
LEFT JOIN ldap_connections ON ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id
WHERE ldap_computers_timestamp<>ldap_paths_timestamp
AND ldap_computers_timestamp>'".adjustdate(0,0,-$ldap_changes_days)."000000')

UNION

(SELECT ldap_connections_name, ldap_computers_cn as cn, ldap_computers_dn as dn, 'active' as img, 'Computer' as objtype
FROM ldap_computers
LEFT JOIN ldap_paths ON ldap_computers.ldap_computers_path_id=ldap_paths.ldap_paths_id
LEFT JOIN ldap_connections ON ldap_paths.ldap_paths_connection_id=ldap_connections.ldap_connections_id
WHERE ldap_computers_timestamp=ldap_paths_timestamp
AND ldap_computers_first_timestamp>'".adjustdate(0,0,-$ldap_changes_days)."000000')

) AS U ORDER BY ldap_connections_name, cn";

$result = mysqli_query($db,$sql);

echo '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/">'."\n";
echo '<channel>'."\n";
echo '<image>'."\n";
echo '<url>'.$sitename.'favicon.ico</url>'."\n";
echo '</image>'."\n";
echo '<title>'.$sitename.'</title>'."\n";
echo '<link>'.$sitebaseurl.'</link>'."\n";

echo '<description>'.$sitedescription.'</description>'."\n";

// Loop through results
if ($myrow = mysqli_fetch_array($result)){ 
 
 do {
		echo '<item>'."\n";
		echo '<guid isPermaLink="false">openaudit-'.htmlentities($myrow["cn"])."</guid>\n";
		$status = ($myrow["img"] == 'active') ? "Added" : "Deleted";
		echo '<title>'.$myrow["objtype"]." Account ".htmlentities($status.": ".$myrow["cn"]).'</title>'."\n";
		$content = __("LDAP Directory").': '.$myrow["ldap_connections_name"].'<br />';
		$content .= __("LDAP Path").': '.$myrow["dn"];
		echo '<content:encoded><![CDATA['.$content.']]></content:encoded>\n';
		echo '</item>'."\n";

	} while ($myrow = mysqli_fetch_array($result));
}

echo '</channel>'."\n";
echo '</rss>'."\n";
?>
