<?php
/**********************************************************************************************************
Version $Id: index.php  24th May 2007
Author:		The Open Audit Developer Team
Objective:	Index Page for Open Audit.
Package:		Open-audit (www.open-audit.org)
Copyright:	Copyright (C) open-audit.org All rights reserved.

License:
	license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see ../gpl.txt
	Open-Audit is free software. This version may have been modified pursuant
	to the GNU General Public License, and as distributed it includes or
	is derivative of works licensed under the GNU General Public License or
	other free or open source software licenses.
	See www.open-audit.org for further copyright notices and details.

Module:	index.php

Description:
	Home page for Open Audit application.

Recent Changes:
	[Edoardo]		01/02/2008	$latest_version  now "08.02.01"
	[Edoardo]		15/04/2008	$latest_version  now "08.04.15"
	[Edoardo]		19/05/2008	$latest_version  now "08.05.19"
	[Edoardo]		06/06/2008	$latest_version  now "08.06.06"
	[Edoardo]		23/07/2008	$latest_version  now "08.07.23"
	[Nick Brown]	29/04/2009	Removed reference to $validate - doesn't appear to be used anywhere in the application.
	[Nick Brown]	05/05/2009	$latest_version  now "09.05.05"
	[Edoardo]		01/08/2009	$latest_version  now "09.08.01"
	[Nick Brown]	03/09/2009	$latest_version  now "09.09.03"
	[Chad Sikorra]	05/10/2009	$latest_version  now "09.10.05"
	[Chad Sikorra]	05/10/2009	$latest_version  now "09.11.15"
	[Edoardo]		28/05/2010	$latest_version  now "10.05.25"
	[Edoardo]		27/07/2010	$latest_version  now "10.07.26"
	[Edoardo]		01/09/2010	$latest_version  now "10.09.01"

**********************************************************************************************************/

$page = "";
$extra = "";
$software = "";
$count = 0;
$total_rows = 0;
$latest_version = "10.09.01";

// Check for config, otherwise run setup
if(!file_exists("include_config.php"))exit(header("Location: setup.php"));
include "include.php";

$software = GetGETOrDefaultValue("software","");
$sort = GetGETOrDefaultValue("sort","system_name");
?>

<!-- Create HttpRequestors -->
<script type='text/javascript'>//<![CDATA[
<?php
if ($show_system_discovered == "y") echo "var DiscoveredSystemsXml=new HttpRequestor('RecentlyDiscoveredSystems');\n";
if ($show_other_discovered == "y") echo "var OtherDiscoveredXml=new HttpRequestor('OtherDiscovered');\n";
if ($show_systems_not_audited == "y") echo "var SystemsNotAuditedXml=new HttpRequestor('SystemsNotAudited');\n"; 
if ($show_partition_usage == "y") echo "var PartitionUsageXml=new HttpRequestor('PartitionUsage');\n"; 
if ($show_software_detected == "y") echo "var DetectedSoftwareXml=new HttpRequestor('DetectedSoftware');\n";
if ($show_detected_servers == "y" )
{	
  echo "var WebServersXml=new HttpRequestor('WebServers');\n";
  echo "var FtpServersXml=new HttpRequestor('FtpServers');\n";
  echo "var TelnetServersXml=new HttpRequestor('TelnetServers');\n";
  echo "var EmailServersXml=new HttpRequestor('EmailServers');\n";
  echo "var VncServersXml=new HttpRequestor('VncServers');\n";
	if ($show_detected_rdp == "y") echo "var RDPServersXml=new HttpRequestor('RDPServers');\n";
  echo "var DbServersXml=new HttpRequestor('DbServers');\n";
}
if ($show_detected_xp_av == "y")  echo "var DetectedXpAvXml=new HttpRequestor('DetectedXpAv');\n";
if ($show_ldap_changes == 'y') echo "var AdInfoXml=new HttpRequestor('AdInfo');\n";
if ($show_systems_audited_graph == 'y') echo "var AuditedSystemsXml=new HttpRequestor('AuditedSystems');\n";
if ($show_hard_disk_alerts == 'y') echo "var HardDisksAlertsXml=new HttpRequestor('HardDisksAlerts');\n";
?>
//]]></script>

<?php
$title = "";
$show_all="1";
if (isset($_GET["show_all"])){ $count_system = '10000'; }
if (isset($_GET["page_count"])){ $page_count = $_GET["page_count"]; } else { $page_count = 0;}
$page_prev = $page_count - 1;
if ($page_prev < 0){ $page_prev = 0; }
$page_next = $page_count + 1;
$page_current = $page_count;
$page_count = $page_count * $count_system;

echo "<td id='CenterColumn'>\n";

// ****** Display various sections *****************************************************
if ($show_system_discovered == "y") 
	DisplaySection('f1',__("Systems Discovered in the last ").$system_detected.__(" Days"),'RecentlyDiscoveredSystems','Systems','rss_new_systems.php');
if ($show_other_discovered == "y") 
	DisplaySection('f2',__("Other Items Discovered in the last ").$other_detected.__(" Days"),'OtherDiscovered','Other Items','rss_new_other.php');
if ($show_systems_not_audited == "y") 
	DisplaySection('f3',__("Systems Not Audited in the last ").$days_systems_not_audited.__(" Days"),'SystemsNotAudited','Systems');
if ($show_partition_usage == "y") 
	DisplaySection('f4',__("Partition free space less than ").$partition_free_space.__(" MB"),'PartitionUsage','Partitions');
if ($show_software_detected == "y")
	DisplaySection('f5',__("Software detected in the last ").$days_software_detected.__(" Days"),'DetectedSoftware','Packages','rss_new_software.php');
if ($show_detected_servers == "y")
{
  DisplaySection('f6',__("Web Servers"),'WebServers','Systems');
  DisplaySection('f7',__("FTP Servers"),'FtpServers','Systems');  
  DisplaySection('f8',__("Telnet Servers"),'TelnetServers','Systems');  
  DisplaySection('f9',__("Email Servers"),'EmailServers','Systems');
	DisplaySection('f10',__("VNC Servers"),'VncServers','Systems');
	if ($show_detected_rdp == "y") DisplaySection('f12',__('RDP and Terminal Servers'),'RDPServers','Systems');
	DisplaySection('f13',__('Database Servers'),'DbServers','Systems');
}
if ($show_detected_xp_av == "y") 
	DisplaySection('f11',__("windows systems without up to date AntiVirus"),'DetectedXpAv','Systems');
	
if ($show_ldap_changes == 'y') DisplaySection('f15',__("LDAP Directory changes in the last ".$ldap_changes_days." days"),'AdInfo','Accounts','rss_ldap_directory_changes.php');
if ($show_systems_audited_graph == 'y') DisplayAuditGraph();
if ($show_hard_disk_alerts == "y") 
	DisplaySection('f16',__("Hard Disks Alerts detected in the last ").$hard_disk_alerts_days.__(" Days"),'HardDisksAlerts','Systems','rss_hard_disk_alerts.php');



//******* Display Graph *****************************************************
function DisplayAuditGraph()
{
	global $systems_audited_days;
	
	echo "<div class='npb_section_shadow'>";
	echo "	<div class='npb_section_content'>";
	echo "		<div class='npb_section_heading'>";
	echo "			<a>Systems Audited in the last ".$systems_audited_days." Days</a>";
	echo "		</div>";
	echo "		<div class='npb_section_data' id='AuditedSystems'>";
	echo "			<img class='npb_auditedsystems_hourglass' alt=' Retrieving...' src='images/hourglass-busy.gif'/>";
	echo "		</div>";
	echo "	</div>";
	echo "</div>";
}
/******* Generic display section function *****************************************************
	$SwitchID			- String	-	Unique element ID to be used by switchUl() function
	$Display			-	String	-	Section description (heading) string to be displayed
	$DivID				-	String	-  Unique element ID used by the HttpRequestor object
	$TotalString		- String	- String used in "total" description
	$RssUrl				- String	- RSS URL string
**********************************************************************************************/
function DisplaySection($SwitchID, $Display, $DivID, $TotalString, $RssUrl='')
{
  $i="i".$SwitchID;
	echo "<div class='npb_section_shadow'>";
	echo "	<div class='npb_section_content'>";
	echo "		<div class='npb_section_heading'>";
	
	// **** Only for sections with RSS feed *******************
	if (strlen($RssUrl)>0){echo "<a href='$RssUrl'><img class='npb_rss' src=\"images/feed-icon.png\" alt=\"RSS Feed\" /></a>";}
	// ****************************************************
	
	echo "			<a href=\"javascript://\" onclick=\"switchUl('$SwitchID');\">$Display</a>";
	echo "			<img class='npb_down' src=\"images/down.png\" alt=\"\" onclick=\"switchUl('$SwitchID');\"/>";
	echo "		</div>";
	echo "		<div class='npb_section_data' id='$DivID'>";
	echo "			<p class='npb_section_summary'>".__($TotalString).": <img class='npb_hourglass' alt='Retrieving...' src='images/hourglass-busy.gif'/></p>";
	echo "		</div>";
	echo "	</div>";
	echo "</div>";
}

echo "</td>\n";
echo "</table>\n";
?>

<script type='text/javascript'>//<![CDATA[
<?php
// Initiate retrieval of data for each section
if ($show_system_discovered == "y") echo "DiscoveredSystemsXml.send(\"index_data.php?sub=f1\");\n";
if ($show_other_discovered == "y") echo "OtherDiscoveredXml.send('index_data.php?sub=f2')\n";
if ($show_systems_not_audited == "y") echo "SystemsNotAuditedXml.send('index_data.php?sub=f3');\n"; 
if ($show_partition_usage == "y") echo "PartitionUsageXml.send('index_data.php?sub=f4');\n"; 
if ($show_software_detected == "y") echo "DetectedSoftwareXml.send('index_data.php?sub=f5');\n";
if ($show_detected_servers == "y" )
{	
  echo "WebServersXml.send('index_data.php?sub=f6');\n";
  echo "FtpServersXml.send('index_data.php?sub=f7');";
  echo "TelnetServersXml.send('index_data.php?sub=f8');\n";
  echo "EmailServersXml.send('index_data.php?sub=f9');\n";
  echo "VncServersXml.send('index_data.php?sub=f10');\n";
	if ($show_detected_rdp == "y")   echo "RDPServersXml.send('index_data.php?sub=f12');\n";
  echo "DbServersXml.send('index_data.php?sub=f13');\n";
}
if ($show_detected_xp_av == "y") echo "DetectedXpAvXml.send('index_data.php?sub=f11');\n";
if ($show_ldap_changes == 'y') echo "AdInfoXml.send('index_data.php?sub=f15');\n";
if ($show_systems_audited_graph == 'y') echo "AuditedSystemsXml.send('index_data.php?sub=f14');\n";
if ($show_hard_disk_alerts == "y") echo "HardDisksAlertsXml.send(\"index_data.php?sub=f16\");\n";
?>
//]]></script>

</body>
</html>

