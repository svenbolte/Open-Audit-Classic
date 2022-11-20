<?php

include_once("include_config.php");
include_once("include_functions.php");
include_once("include_lang.php");


//MySQL-Connect
$db=GetOpenAuditDbConnection() or die('Could not connect: ' . mysqli_error($db));
mysqli_select_db($db,$mysqli_database);

//Some Config fcr Layout


//MySQL-Connect
$db=GetOpenAuditDbConnection() or die('Could not connect: ' . mysqli_error($db));
mysqli_select_db($db,$mysqli_database);

//Include the view-definition
if(isset($_REQUEST["view"]) AND $_REQUEST["view"]!=""){
    $include_filename = "list_viewdef_".$_REQUEST["view"].".php";
} else {
    $include_filename = "list_viewdef_all_systems.php";
}
if(is_file($include_filename)){
    include_once($include_filename);
    $viewdef_array=$query_array;
} else {
    die("FATAL: Could not find view $include_filename");
}

    //Executing the Qeuery
    $sql=urldecode($_REQUEST["sql"]);
    $result = mysqli_query($db,$sql);
    if(!$result) {die( "<br>".__("Fatal Error").":<br><br>".$sql."<br><br>".mysqli_error($db)."<br><br>" );};
    $this_page_count = mysqli_num_rows($result);
    

 header("Content-Type: application/vnd.dia-win-remote");
 header("Content-Disposition: inline; filename=\"Open-Audit_".$_REQUEST["view"]."_rdcman.rdg\"");

//Create Objects

//Table body. This section creates a list of network objects and distributes them across the page
// The exact number across each page depends on the page size and layout.
//

$server_liste = '';
if ($myrow = mysqli_fetch_array($result)) {

    do {
		foreach($query_array["fields"] as $field) {
			if($field["show"]!="n") {
				if ( $field["head"]=="Rechnername" || $field["head"]=="Hostname" ) { 
            //
			$netipadr = preg_replace('/\b0+(?=\d)/', '', $myrow["net_ip_address"]);

			$meine_domain = $myrow["net_domain"];
			$server_liste .= '      <server>
        <properties>
          <displayName>'.$myrow[$field["name"]].'</displayName>
          <name>'.$netipadr.'</name>
          <comment>'.$myrow["system_os_name"].' '.$myrow["net_domain"].' '.$myrow["system_vendor"].' '.$myrow["system_model"].' RAM:'.$myrow["system_memory"].'</comment>
        </properties>
      </server>
			' . "\n";
			//
				}
			}			
            
		} // For Schleife Ende
	
    } while ($myrow = mysqli_fetch_array($result));

}

//
// Setup the format of the .rdg page. This is VERY crude, we should create functions to allow proper control of all elements on the page,
// and a setup page to allow contorl over this ... OOPS (AJH)
//
	$dia_page_setup_1 = '<?xml version="1.0" encoding="utf-8"?>
<RDCMan programVersion="2.90" schemaVersion="3">
  <file>
    <credentialsProfiles />
    <properties>
      <expanded>True</expanded>
      <name>AlleRDPServer</name>
    </properties>
    <logonCredentials inherit="None">
      <profileName scope="Local">Custom</profileName>
      <userName>administrator</userName>
      <password />
      <domain>'.$meine_domain.'</domain>
    </logonCredentials>
    <connectionSettings inherit="None">
      <connectToConsole>True</connectToConsole>
      <startProgram />
      <workingDir />
      <port>3389</port>
      <loadBalanceInfo />
    </connectionSettings>
    <remoteDesktop inherit="None">
      <sameSizeAsClientArea>True</sameSizeAsClientArea>
      <fullScreen>False</fullScreen>
      <colorDepth>24</colorDepth>
    </remoteDesktop>
    <group>
      <properties>
        <expanded>False</expanded>
        <name>Azure virtuelle Server</name>
      </properties>
    </group>
    <group>
      <properties>
        <expanded>False</expanded>
        <name>OnPrem physikalisch</name>
      </properties>
    </group>
    <group>
      <properties>
        <expanded>True</expanded>
        <name>OnPrem virtuell</name>
      </properties>
    </group>
    <group>
      <properties>
        <expanded>True</expanded>
        <name>z-importiert</name>
      </properties>
	';
//

echo $dia_page_setup_1;
echo $server_liste;


// Close Layer and Document
echo '    </group>
  </file>
  <connected />
  <favorites />
  <recentlyUsed />
</RDCMan>
';
// Thats all folks

?>
