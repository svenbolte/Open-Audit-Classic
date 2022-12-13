<?php
/**********************************************************************************************************
Module:	setup.php

Description:
			
Recent Changes:
	
	[Edoardo]		12/06/2010	Added missing $show_hard_disk_alerts and $hard_disk_alerts_days in function ReturnConfig(). Suggested by jpa.

**********************************************************************************************************/

include "include_config.php";
if (isset($_POST['language_post'])) $GLOBALS["language"] = $_POST['language_post'];
include_once "include_lang.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Open-AudIT Setup</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link rel="stylesheet" type="text/css" href="default.css" />
  </head>
  <body>
  <div class="main_each">
    <img src="images/logo.png" width="300" height="48" alt="" border="0"/>
  </div>
  <div style="float: left; width: 200px">
  <div class="main_each">
<?php
  if(!isset($_POST['step']) or ($_POST['step'] == 1)) {
    echo "  <b>" . __("1. Choose language") . "</b><br />\n";
  } else {
    echo "  " . __("1. Choose language") . "<br />\n";
  }

  if (isset($_POST['step']) and $_POST['step'] == 2) {
    echo "  <b>" . __("2. Check Prerequisites") . "</b><br />\n";
  } else {
    echo "  " . __("2. Check Prerequisites") . "<br />\n";
  }

  if (isset($_POST['step']) and (($_POST['step'] == 3) or ($_POST['step'] == 3.5))) {
    echo "  <b>" . __("3. Setup database") . "</b><br />\n";
  } else {
    echo "  " . __("3. Setup database") . "<br />\n";
  }
?>
  </div>
  </div>
  <div style="padding-left: 200px; padding-top: 1px;"><div class="main_each"><div style="width: 550px">
<?php
// Content below
  if(!isset($_POST['step']) or ($_POST['step'] == 1)) {
    step1ChooseLanguage();
  } else if ($_POST['step'] == 2) {
    step2CheckPrereq();
  } else if ($_POST['step'] == 3.1) {
   step31SetupDB();
  } else if ($_POST['step'] == 3.2 and $_POST['rootacc_database'] == "y") {
    step33SetupDB();
  } else if ($_POST['step'] == 3.2 and $_POST['rootacc_database'] == "n") {
    step34SetupDB();
  } else if ($_POST['step'] == 3.5) {
    step35SetupDB();
  } else if ($_POST['step'] == 3.6) {
    step36SetupDB();
  }
?>
  </div></div></div>
</body>
</html>

<?php

// STEP 1
function step1ChooseLanguage() {
?>
  <span class="contenthead"><?php __("Setup") ?></span>
  <p><?php echo __("Welcome to the setup for Open-AudIT!") ?></p>
  <form method="post" action="setup.php" name="admin_config">
  <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr>
    <td><?php echo __("Choose your language:") ?></td>
    <td><select size="1" name="language_post" class="for_forms">
<?php
// Check for available languages
$handle=opendir('./lang/');
while ($file = readdir ($handle)) {
    if ($file != "." && $file != "..") {
        if(substr($file,strlen($file)-4)==".inc"){
            if($GLOBALS["language"] == substr($file,0,strlen($file)-4) ) $selected="selected"; else $selected="";
            echo "<option $selected>".substr($file,0,strlen($file)-4)."</option>\n";
        }
    }
}
closedir($handle);
?>
    </select></td>
  </tr>
  </table>
  <br />
  <input type="hidden" name="step" value="2" />
  <input type="submit" value="<?php echo __("Submit Language Choice") ?>" name="submit_button" />
  </form>
  <br />
<?php
}


// STEP 2
function step2CheckPrereq() {
  $failed = 0; // number of fails
  echo "<span class=\"contenthead\">" . __("Setup") . "</span>";
  echo "<p>" . __("Checking that the following files are writeable:") . "</p>";
  echo "<ul>";
  echo "<li>include_config.php ... ";
  $filename = "include_config.php";
  if (!file_exists($filename) or is_writable($filename)) {
    $handle = @fopen($filename, 'a');
	if ($handle) {
      @fclose($handle);
      echo __("Success!") . "<br />";
    } else {
      $failed += 1;
      echo __("Failed.") . "<br />";
    }
  } else {
      $failed += 1;
    echo __("Failed.") . "<br />";
  }

  echo "<li>scripts/audit.config ... ";
  $filename = "scripts/audit.config";
  if (!file_exists($filename) or is_writable($filename)) {
    $handle = @fopen($filename, 'a');
	if ($handle) {
      @fclose($handle);
      echo __("Success!") . "<br />";
    } else {
      $failed += 1;
      echo __("Failed.") . "<br />";
    }
  } else {
      $failed += 1;
    echo __("Failed.") . "<br />";
  }

  echo "</ul>";

  // Check for success
  if($failed == 0) {
?>
  <form method="post" action="setup.php" name="admin_config">
  <input type="hidden" name="language_post" value="<?php echo $_POST['language_post']; ?>" />
  <input type="hidden" name="step" value="3.1" />
  <input type="submit" value="<?php echo __("Continue") ?> >>" name="submit_button" />
  </form>
<?php
  } else {
?>
  <p><?php echo __("For each failed file, check the permissions on the file. For linux, chmod them with permissions 646. You will need to create the file if it does not exist. When this is completed, press retry to verify the changes.") ?></p>
  <form method="post" action="setup.php" name="admin_config">
  <input type="hidden" name="language_post" value="<?php echo $_POST['language_post']; ?>" />
  <input type="hidden" name="step" value="2" />
  <input type="submit" value="<?php echo __("Retry") ?>" name="submit_button" />
  </form>
<?php
  }  
}


// STEP 3.1
function step31SetupDB() {
?>
  <span class="contenthead"><?php echo __("Setup") ?></span>
  <p><?php echo __("To perform an automated setup, choose the way to handle database creation .") ?></p>
  
  <hr />
 
  <form method="post" action="setup.php" name="admin_config">
  
      <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><input type="radio" name="rootacc_database" value="y" checked="checked" /></td><td><?php echo __("I have Root access to database.") ?></td></tr>
  <tr><td>&nbsp;</td><td><?php echo __("CAUTION: you have to have root access to this database.") ?></td></tr>
  </table>
  <hr />
  <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><input type="radio" name="rootacc_database" value="n"   /></td><td><?php echo __("I do not have root access to database. Just create tables.") ?></td></tr>
  <tr><td>&nbsp;</td><td><?php echo __("Recommended for on-line hosting.") ?></td></tr>
  </table>
  <hr />
  
  <input type="hidden" name="language_post" value="<?php echo $_POST['language_post']; ?>" />
  <input type="hidden" name="step" value="3.2" />
  <input type="submit" value="<?php echo __("Submit") ?>" name="submit_button" />
  </form>
  <br />
<?php
}
/// Step 3.3
if (isset($_POST['drop_database']))  {$drop_database = $_POST['drop_database'];}  else { $drop_database = "n";}
if (isset($_POST['drop_user']))  {$drop_user = $_POST['drop_user'];}  else { $drop_user = "n";}
if (isset($_POST['bindlocal']))  {$bindlocal = $_POST['bindlocal'];}  else { $bindlocal = "n";}
// <form method="post" action="setup.php" name="admin_config">

function step33SetupDB() {
?>
  <span class="contenthead"><?php echo __("Setup") ?></span>
  <p><?php echo __("To perform an automated setup, enter the details on the root account below. This user must have the privileges to create and modify users and databases.") ?></p>
  <p><?php echo __("By clicking 'Submit Credentials,' you are allowing Open-AudIT to create a user and database for use with Open-AudIT. This is the recommended configuration, as Open-AudIT does not need root privileges after installation.") ?></p>
  <hr />
  <?php echo __("Root User Credentials").":<br />";

  echo "<form method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\" name=\"setup.php\">";
 ?>
 <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><?php echo __("MySQL Server") ?>:&nbsp;</td><td><input type="text" name="mysqli_server_post" size="12" value="localhost" class="for_forms"/></td></tr>
  <tr><td><?php echo __("MySQL Username") ?>:&nbsp;</td><td><input type="text" name="mysqli_user_post" size="12" value="root" class="for_forms" /></td></tr>
  <tr><td><?php echo __("MySQL Password") ?>:&nbsp;</td><td><input type="password" name="mysqli_password_post" size="12" value="flocke" class="for_forms" /></td></tr>
  </table>
  <hr />
  <?php echo __("Database for Open-AudIT") ?>:<br />
  <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><?php echo __("Database Name") ?>:&nbsp;</td><td><input type="text" name="mysqli_new_db" size="12" value="openaudit" class="for_forms" /></td></tr>
  </table>
  
   <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><input type="checkbox" name="drop_database" value="n"  /></td><td><?php echo __("Delete database if it exists.") ?></td></tr>
  <tr><td>&nbsp;</td><td><?php echo __("CAUTION: This will delete ALL data in this database.") ?></td></tr>
  </table>
  <hr />
  <?php echo __("Credentials for Open-AudIT database") ?>:<br />
  <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><?php echo __("New Username") ?>:&nbsp;</td><td><input type="text" name="mysqli_new_user" maxlength="16" size="12" value="openaudit" class="for_forms" /></td></tr>
   

  <tr><td><?php echo __("New Password") ?>:&nbsp;</td><td><input type="password" name="mysqli_new_pass" size="12" value="flocke" class="for_forms" /></td></tr>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><input type="checkbox" name="drop_user" value="n"  /></td><td><?php echo __("Delete user if it exists.") ?></td></tr>
  <tr><td>&nbsp;</td><td><?php echo __("CAUTION: This will also remove all permissions for this user.") ?></td></tr>
  </table>

<table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><input type="checkbox" name="bindlocal" value="y" checked="checked" /></td><td><?php echo __("Bind user to localhost.") ?></td></tr>
  <tr><td>&nbsp;</td><td><?php echo __("This option will allow this user to only connect from the localhost. It is recommended that you leave this checked unless your MySQL server is not on the same server as your web server.") ?></td></tr>
  </table>
  <br />
  <input type="hidden" name="language_post" value="<?php echo $_POST['language_post']; ?>" />
  <input type="hidden" name="step" value="3.5" />
  <input type="submit" value="<?php echo __("Submit Credentials") ?>" name="submit_button" />
  </form>
  <br />
<?php
}

/// Step 3.4
if (isset($_POST['drop_database']))  {$drop_database = $_POST['drop_database'];}  else { $drop_database = "n";}
if (isset($_POST['drop_user']))  {$drop_user = $_POST['drop_user'];}  else { $drop_user = "n";}
if (isset($_POST['bindlocal']))  {$bindlocal = $_POST['bindlocal'];}  else { $bindlocal = "n";}
// <form method="post" action="setup.php" name="admin_config">

function step34SetupDB() {
?>
  <span class="contenthead"><?php echo __("Setup") ?></span>
  <p><?php echo __("To perform an automated setup, enter the details on the Database account below. This user must have the privileges to access and modify databases.") ?></p>
  <p><?php echo __("By clicking 'Submit Credentials,' you are allowing Open-AudIT to create a Tables for use with Open-AudIT. This is the recommended configuration.") ?></p>
  <hr />
  <?php echo __("Database account details ").":<br />";

  echo "<form method=\"post\" action=\"" . $_SERVER["PHP_SELF"] . "\" name=\"setup.php\">";
 ?>
 <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><?php echo __("MySQL Server") ?>:&nbsp;</td><td><input type="text" name="mysqli_server_post" size="12" value="localhost" class="for_forms"/></td></tr>
  <tr><td><?php echo __("MySQL Username") ?>:&nbsp;</td><td><input type="text" name="mysqli_new_user" size="12" value="root" class="for_forms" /></td></tr>
  <tr><td><?php echo __("MySQL Password") ?>:&nbsp;</td><td><input type="password" name="mysqli_new_pass" size="12" value="" class="for_forms" /></td></tr>
  </table>
  <hr />
  <?php echo __("Database for Open-AudIT") ?>:<br />
  <table border="0" cellpadding="0" cellspacing="0" class="content">
  <tr><td><?php echo __("Database Name") ?>:&nbsp;</td><td><input type="text" name="mysqli_new_db" size="12" value="openaudit" class="for_forms" /></td></tr>
  </table>
  
   
  <hr />
  
   </table>
  <br />
  <input type="hidden" name="language_post" value="<?php echo $_POST['language_post']; ?>" />
  <input type="hidden" name="step" value="3.6" />
  <input type="submit" value="<?php echo __("Submit Crentials") ?>" name="submit_button" />
  </form>
  <br />
<?php
}
// STEP 3.5
function step35SetupDB() {
    echo __("Connecting to the MySQL Server... ");
    $db = @mysqli_connect($_POST['mysqli_server_post'],$_POST['mysqli_user_post'],$_POST['mysqli_password_post']) or die('Could not connect: ' . mysqli_error($db));
    echo __("Success!") . "<br />";
    // Added drop existing database code (AJH)
    if ($_POST['drop_database'] = 'y'){
    $sql = "DROP DATABASE IF EXISTS`" . $_POST['mysqli_new_db'] . "` ";
    echo __("Dropping existing database... ");
    $result = mysqli_query($db,$sql) or die('Could not drop existing db: ' . mysqli_error($db));
    echo __("Success!") ."<br />";
    }
    //
    $sql = "CREATE DATABASE `" . $_POST['mysqli_new_db'] . "` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci";
    echo __("Creating the database... ");
    $result = mysqli_query($db,$sql) or die('Could not create db: ' . mysqli_error($db));
    echo __("Success!") ."<br />";

    $sql = "DROP USER '" . $_POST['mysqli_new_user'] . "'@";
    if ($_POST['bindlocal'] = 'y') {
      $sql .= "'localhost' ";
    } else {
      $sql .= "'%' ";
    }
    $result = mysqli_query($db,$sql) or die('Could not drop user: ' . mysqli_error($db));

    $sql = "CREATE USER '" . $_POST['mysqli_new_user'] . "'@";
    if ($_POST['bindlocal'] = 'y') {
      $sql .= "'localhost' ";
    } else {
      $sql .= "'%' ";
    }
    $sql .= "IDENTIFIED BY '" . $_POST['mysqli_new_pass'] . "'";
	
	echo __("Creating the user... ". $_POST['mysqli_new_user']);
    $result = mysqli_query($db,$sql) or die('Could not create the user: ' . mysqli_error($db));
    echo __("Success!") . "<br />";
    $sql = "GRANT SELECT , INSERT , UPDATE , DELETE , CREATE , DROP , INDEX , ALTER , CREATE TEMPORARY TABLES";
    $sql .= " , CREATE VIEW , SHOW VIEW , CREATE ROUTINE, ALTER ROUTINE, EXECUTE ";
    $sql .= "ON `" . $_POST['mysqli_new_db'] . "`.* TO '" . $_POST['mysqli_new_user'] . "'@";
    if ($_POST['bindlocal'] = 'y') {
      $sql .= "'localhost'";
    } else {
      $sql .= "'%'";
    }
    echo __("Granting user priveleges... ");
    $result = mysqli_query($db,$sql) or die('Could not grant privileges: ' . mysqli_error($db));
    echo __("Success!") . "<br />";
    echo __("Switching connection to new user... ");
    mysqli_close($db);
    $db = @mysqli_connect($_POST['mysqli_server_post'],$_POST['mysqli_new_user'],$_POST['mysqli_new_pass']) or die('Could not connect: ' . mysqli_error($db));
    mysqli_select_db($db,$_POST['mysqli_new_db']);
    echo __("Success!") . "<br />";

    // Load SQL contents to write to server
    echo __("Creating tables... ");
    $filename = "scripts/open_audit.sql";
    $handle = fopen($filename, "rb");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    $sql = stripslashes($contents);
    $sql2 = explode(";", $sql);
    foreach ($sql2 as $sql3) {
		// echo $sql3.'<br>*<br>';
      if (!empty($sql3)) $result = mysqli_query($db,$sql3 . ";");
    }
    echo __("Success!") . "<br />";
    mysqli_close($db);

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
    <br /><br /><?php echo __("Setup has completed creating your database. Continue on to finish setup.") ?><br /><br />
    <form method="post" action="index.php" name="options">
    <input type="hidden" name="step" value="4" />
    <input type="submit" value="<?php echo __("Finish Setup") ?> >>" name="submit_button" />
    </form>
<?php
}

// STEP 3.6
function step36SetupDB() {
    echo __("Connecting to the MySQL Server... ");
    $db = @mysqli_connect($_POST['mysqli_server_post'],$_POST['mysqli_new_user'],$_POST['mysqli_new_pass']) or die('Could not connect: ' . mysqli_error($db));
    echo __("Success!") . "<br />";
   mysqli_select_db($db,$_POST['mysqli_new_db']);
    echo __("Success!") . "<br />";
  
    // Load SQL contents to write to server
    echo __("Creating tables... ");
    $filename = "scripts/open_audit.sql";
    $handle = fopen($filename, "rb");
    $contents = fread($handle, filesize($filename));
    fclose($handle);
    $sql = stripslashes($contents);
    $sql2 = explode(";", $sql);
    foreach ($sql2 as $sql3) {
      $result = mysqli_query($db,$sql3 . ";");
    }
    echo __("Success!") . "<br />";
    mysqli_close($db);

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
    <br /><br /><?php echo __("Setup has completed creating your database. Continue on to finish setup.") ?><br /><br />
    <form method="post" action="index.php" name="options">
    <input type="hidden" name="step" value="4" />
    <input type="submit" value="<?php echo __("Finish Setup") ?> >>" name="submit_button" />
    </form>
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
    <form method="post" action="setup.php" name="options">
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
$content .= $_POST['language_post'];
$content .= "'; ";
$content .= "?";
$content .= ">";

  return $content;
}
?>
