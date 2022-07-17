<?php
/**********************************************************************************************************
Module:	changedatabase.php
**********************************************************************************************************/

if (isset($_POST['language_post'])) $GLOBALS["language"] = $_POST['language_post'];
include "include.php";
?>
 <td width="100%">
 <div class="main_each">
<?php
// Content below
  if(!isset($_POST['step'])) {
    step33SetupDB();
  } else if ($_POST['step'] == 3.5) {
    step35SetupDB();
  }
?>
  </div>
</body>
</html>

<?php

/// Step 3.3
if (isset($_POST['drop_database']))  {$drop_database = $_POST['drop_database'];}  else { $drop_database = "n";}
if (isset($_POST['drop_user']))  {$drop_user = $_POST['drop_user'];}  else { $drop_user = "n";}
if (isset($_POST['bindlocal']))  {$bindlocal = $_POST['bindlocal'];}  else { $bindlocal = "n";}
// <form method="post" action="changedatabase.php" name="admin_config">

function step33SetupDB() {
?>
  <span class="contenthead"><?php echo __("Change Databasse") ?></span>
  <p><?php echo __("Select name of database from the list:") ?></p>
  <hr />
  <?php 

  echo "<form method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\" name=\"changedatabasephp\">";
 ?>
 <table border="0" cellpadding="0" cellspacing="0" class="content">
  <input type="hidden" name="mysqli_server_post" size="12" value="localhost" class="for_forms"/>
  <input type="hidden" name="mysqli_user_post" size="12" value="root" class="for_forms" />
  <input type="hidden" name="mysqli_password_post" size="12" value="flocke" class="for_forms" />

  <br />
  <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><?php echo __("Database Name") ?>:&nbsp;</td><td><input type="text" readonly name="mysqli_new_db" size="12" value="openaudit" class="for_forms" />
    <select size="1" name="dbselect" class="for_forms" onchange="document.changedatabasephp.mysqli_new_db.value=document.changedatabasephp.dbselect.value";>
	<?php
	// Check for available languages
	$handle=opendir('../../mysql/data/');
	while ($file = readdir ($handle)) {
		if ($file != "." && $file != "..") {
			if(strpos($file,"openaudit")!==false) {
				echo "<option>".$file."</option>\n";
			}
		}
	}
	closedir($handle);
	?>
  </select>
  </td></tr>
  </table>
    
  <input type="hidden" name="mysqli_new_user" maxlength="16" size="12" value="openaudit" class="for_forms" />
  <input type="hidden" name="mysqli_new_pass" size="12" value="flocke" class="for_forms" />
  <br>
  <!-- <input type="hidden" name="language_post" value="<?php echo $_POST['language_post']; ?> "> -->
  <input type="hidden" name="step" value="3.5" />
  <input type="submit" value="<?php echo __("change to database") ?>" name="submit_button" />
  </form>
  <br />
<?php
}

// STEP 3.5
function step35SetupDB() {

    // Write configuration file
    echo __("Writing configuration file... ");
    $filename = 'include_config.php';
    $content = returnConfig();
    if (!file_exists($filename) or is_writable($filename)) {
      $handle = @fopen($filename, 'w') or die(writeConfigHtml());
      @fwrite($handle, $content) or die(writeConfigHtml());
      @fclose($handle);
      echo __("Success!") . "<br />";
    } else {
      writeConfigHtml();
    }

	?>
<script type="text/javascript">
<!--
window.location.href = "index.php";
//–>
</script>
<?php
	
}




// Write error message and give include_config.php details
function writeConfigHtml() {
  echo __("Failed.") . "<br /><br />";
  echo "<b>" . __("ERROR:") . "</b> " . __("Config file could not be written.") . "<br /><br />" . __("Please create a file named \"include_config.php\" in the openaudit directory with the contents below.  If on linux, set permissions of this file to 646.") . "<br /><br />";
  echo "<textarea rows=\"10\" name=\"add\" cols=\"60\" readonly=\"readonly\">";
  echo returnConfig();
  echo "</textarea><br /><br />";
  echo __("When this file is created, continue by clicking the button below.") . "<br /><br />\n";
?>
    <form method="post" action="changedatabase.php" name="options">
    <input type="hidden" name="step" value="4" />
    <input type="submit" value="<?php echo __("Finish Setup") ?> >>" name="submit_button" />
    </form>
<?php
  echo "</div></div></div>\n";
  echo "</body></html>";
  exit;
}

function returnConfig() {
  $content = "<";
  $content .= "?";
  $content .= "php \n";
  $content .= "\$mysqli_server = '" . $_POST['mysqli_server_post'] . "'; \n";
  $content .= "\$mysqli_database = '" . $_POST['mysqli_new_db'] . "'; \n";
  $content .= "\$mysqli_user = '" . $_POST['mysqli_new_user'] . "'; \n";
  $content .= "\$mysqli_password = '" . $_POST['mysqli_new_pass'] . "'; \n";
  $content .= "\$use_https = '';
// An array of allowed users and their passwords
// Make sure to set use_pass = \"n\" if you do not wish to use passwords
\$use_pass = 'n';
\$users = array(
  'admin' => 'Open-AudIT'
);\n";
  $content .= "// Config options for index.php
\$show_other_discovered = 'y';
\$other_detected = '30';

\$show_system_discovered = 'y';
\$system_detected = '30';

\$show_systems_not_audited = 'y';
\$days_systems_not_audited = '30';

\$show_partition_usage = 'y';
\$partition_free_space = '1000';

\$show_software_detected = 'y';
\$days_software_detected = '30';

\$show_patches_not_detected = 'y';
\$number_patches_not_detected = '10';

\$show_detected_servers = 'y';
\$show_detected_xp_av = 'y';
\$show_detected_rdp = 'y';

\$show_os = 'y';
\$show_date_audited = 'y';
\$show_type = 'y';
\$show_description = 'y';
\$show_domain = 'y';
\$show_service_pack = 'n';

\$count_system = '30';

\$round_to_decimal_places = '2';

\$management_domain_suffix = 'local' ;
\$vnc_type = 'ultra';

\$ldap_user = 'unknown@domain.local';
\$ldap_secret = 'password';
\$ldap_server = 'myserver.local';
\$ldap_base_dn = 'dc=domain,dc=local';
\$ldap_connect_string = 'LDAP:\/\/server.domain.local';
\$use_ldap_login = 'n';\n";
$content .= "\$show_ldap_changes = 'y';\n";
$content .= "\$ldap_changes_days = 7;\n";
$content .= "\$show_systems_audited_graph = 'y';\n";
$content .= "\$systems_audited_days = 30;\n";
$content .= "\$show_hard_disk_alerts = 'y';\n";
$content .= "\$hard_disk_alerts_days = '7';\n";

$content .= "\$language = '";
$content .= "de_de";
// $_POST['language_post'];
$content .= "'; ";
$content .= "?";
$content .= ">";

  return $content;
}
?>
