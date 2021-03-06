<?php

$query_array=array("name"=>array("name"=>__("OS Settings"),
                                 "sql"=>"SELECT `system_name` FROM `system` WHERE `system_uuid` = '" . $_GET["pc"] . "'",
                                ),
                   "image"=>"images/os_l.png",
                   "views"=>array( "os"=>array(
                                                    "headline"=>__("OS"),
                                                    "sql"=>"SELECT * FROM system WHERE system_uuid = '" . $_GET["pc"] . "' AND system_timestamp = '".$GLOBAL["system_timestamp"]."' ",
                                                    "image"=>"images/os_l.png",
                                                    "fields"=>array("10"=>array("name"=>"system_os_name", "head"=>__("Operating System"),),
                                                                    "20"=>array("name"=>"system_registered_user", "head"=>__("Registered User"),),
                                                                    "30"=>array("name"=>"system_organisation", "head"=>__("Registered Organization"),),
                                                                    "40"=>array("name"=>"system_build_number", "head"=>__("OS Version (Build Number)"),),
                                                                    "50"=>array("name"=>"system_service_pack", "head"=>__("Service Pack"),),
																	"60"=>array("name"=>"system_os_arch", "head"=>__("OS Architecture"),),
                                                                    "70"=>array("name"=>"system_windows_directory", "head"=>__("Windows Directory"),),
                                                                    "80"=>array("name"=>"system_serial_number", "head"=>__("Windows Serial"),),
                                                                    "90"=>array("name"=>"date_system_install", "head"=>__("OS Installed On"),),
                                                                    "100"=>array("name"=>"system_language", "head"=>__("Language"),),
                                                                    "110"=>array("name"=>"time_caption", "head"=>__("Time Zone"),),
                                                                    "120"=>array("name"=>"time_daylight", "head"=>__("Daylight Savings Zone"),),
                                                                    "130"=>array("name"=>"system_last_boot", "head"=>__("Last Boot On"),),
                                                                   ),
                                                    ),
                                   "optionalfeatures"=>array(
                                                    "headline"=>__("Optional Features"),
                                                    "sql"=>"SELECT opt_uuid, caption, name
                                                            FROM optionalfeatures
                                                            WHERE opt_uuid = '".$_REQUEST["pc"]."' 
                                                            ORDER BY name ",
                                                    "table_layout"=>"horizontal",
                                                    "image"=>"images/kvm.png",
                                                    "fields"=>array("10"=>array("name"=>"name", "head"=>__("Name"),),
                                                                    "20"=>array("name"=>"caption", "head"=>__("Version"),),
                                                                   ),
                                                    ),
                                   "software"=>array(
                                                    "headline"=>__("Software"),
                                                    "sql"=>"SELECT software_name, software_version
                                                            FROM software, system
                                                            WHERE system_uuid = '".$_REQUEST["pc"]."' AND software_uuid = system_uuid AND software_timestamp = system_timestamp
                                                            AND (software_name LIKE 'Internet Explorer%' OR software_name LIKE 'DirectX%' OR software_name = 'Windows Media Player' 
                                                                 OR software_name LIKE 'MSXML%' OR software_name LIKE 'Microsoft .NET Framework%' OR software_name = 'MDAC'
                                                                 OR software_name = 'Windows DAC')
                                                            AND software_name NOT LIKE '%Language Pack%' AND software_name NOT LIKE '%hotfix%' 
                                                            ORDER BY software_name ",
                                                    "table_layout"=>"horizontal",
                                                    "image"=>"images/software_l.png",
                                                    "fields"=>array("10"=>array("name"=>"software_name", "head"=>__("Name"),),
                                                                    "20"=>array("name"=>"software_version", "head"=>__("Version"),),
                                                                   ),
                                                    ),
                                   "shares"=>array(
                                                    "headline"=>__("Shared Folders"),
                                                    "sql"=>"SELECT * FROM shares WHERE shares_uuid = '".$_REQUEST["pc"]."' AND shares_timestamp = '".$GLOBAL["system_timestamp"]."' ORDER BY shares_path, shares_name",
                                                    "image"=>"images/shared_drive_l.png",
                                                    "table_layout"=>"horizontal",
                                                    "fields"=>array("10"=>array("name"=>"shares_name", "head"=>__("Share Name"),),
                                                                    "20"=>array("name"=>"shares_caption", "head"=>__("Description"),),
                                                                    "30"=>array("name"=>"shares_path", "head"=>__("Folder"),),
                                                                   ),
                                                    ),
                                   "scheduled_tasks"=>array(
                                                    "headline"=>__("Scheduled Tasks"),
                                                    "sql"=>"SELECT * FROM scheduled_task WHERE sched_task_uuid = '".$_REQUEST["pc"]."' AND sched_task_timestamp = '".$GLOBAL["system_timestamp"]."' ORDER BY sched_task_name",
                                                    "image"=>"images/sched_task_l.png",
                                                    "fields"=>array("10"=>array("name"=>"sched_task_name", "head"=>__("Name"),),
                                                                    "20"=>array("name"=>"sched_task_next_run", "head"=>__("Next Run Time"),),
                                                                    "30"=>array("name"=>"sched_task_status", "head"=>__("Status"),),
                                                                    "40"=>array("name"=>"sched_task_last_run", "head"=>__("Last Run Time"),),
                                                                    "50"=>array("name"=>"sched_task_last_result", "head"=>__("Last Result"),),
                                                                    "60"=>array("name"=>"sched_task_creator", "head"=>__("Creator"),),
                                                                    "70"=>array("name"=>"sched_task_schedule", "head"=>__("Schedule"),),
                                                                    "80"=>array("name"=>"sched_task_task", "head"=>__("Task to Run"),),
                                                                    "90"=>array("name"=>"sched_task_state", "head"=>__("State"),),
                                                                    "100"=>array("name"=>"sched_task_runas", "head"=>__("Run As"),),
                                                                   ),
                                                    ),
                                   "env_variables"=>array(
                                                    "headline"=>__("Environment Variables"),
                                                    "sql"=>"SELECT * FROM environment_variable WHERE env_var_uuid = '".$_REQUEST["pc"]."' AND env_var_timestamp = '".$GLOBAL["system_timestamp"]."' ORDER BY env_var_name",
                                                    "image"=>"images/o_console.png",
                                                    "table_layout"=>"horizontal",
                                                    "fields"=>array("10"=>array("name"=>"env_var_name", "head"=>__("Name"),),
                                                                    "20"=>array("name"=>"env_var_value", "head"=>__("Value"),),
                                                                   ),
                                                    ),
                                   "event_logs"=>array(
                                                    "headline"=>__("Event Logs"),
                                                    "sql"=>"SELECT * FROM event_log WHERE evt_log_uuid = '".$_REQUEST["pc"]."' AND evt_log_timestamp = '".$GLOBAL["system_timestamp"]."' ORDER BY evt_log_name",
                                                    "image"=>"images/summary_l.png",
                                                    "table_layout"=>"horizontal",
                                                    "fields"=>array("10"=>array("name"=>"evt_log_name", "head"=>__("Name"),),
                                                                    "20"=>array("name"=>"evt_log_file_name", "head"=>__("File Name"),),
                                                                    "30"=>array("name"=>"evt_log_file_size", "head"=>__("File Size"),),
                                                                    "40"=>array("name"=>"evt_log_max_file_size", "head"=>__("Maximum File Size"),),
                                                                    "50"=>array("name"=>"evt_log_overwrite", "head"=>__("Overwrite Policy"),),
                                                                   ),
                                                    ),
                                   "ip_routes"=>array(
                                                    "headline"=>__("IP Routes"),
                                                    "sql"=>"SELECT * FROM ip_route WHERE ip_route_uuid = '".$_REQUEST["pc"]."' AND ip_route_timestamp = '".$GLOBAL["system_timestamp"]."' ORDER BY ip_route_destination",
                                                    "image"=>"images/network_device_l.png",
                                                    "table_layout"=>"horizontal",
                                                    "fields"=>array("10"=>array("name"=>"ip_route_destination", "head"=>__("Destination"),),
                                                                    "20"=>array("name"=>"ip_route_mask", "head"=>__("Mask"),),
                                                                    "30"=>array("name"=>"ip_route_metric", "head"=>__("Metric"),),
                                                                    "40"=>array("name"=>"ip_route_next_hop", "head"=>__("Next Hop"),),
                                                                    "50"=>array("name"=>"ip_route_protocol", "head"=>__("Protocol"),),
                                                                    "60"=>array("name"=>"ip_route_type", "head"=>__("Type"),),
                                                                   ),
                                                    ),
                                   "pagefile"=>array(
                                                    "headline"=>__("Pagefile"),
                                                    "sql"=>"SELECT * FROM pagefile WHERE pagefile_uuid = '".$_REQUEST["pc"]."' AND pagefile_timestamp = '".$GLOBAL["system_timestamp"]."' ORDER BY pagefile_name",
                                                    "image"=>"images/memory_l.png",
                                                    "table_layout"=>"horizontal",
                                                    "fields"=>array("10"=>array("name"=>"pagefile_name", "head"=>__("Name"),),
                                                                    "20"=>array("name"=>"pagefile_initial_size", "head"=>__("Initial Size"),),
                                                                    "30"=>array("name"=>"pagefile_max_size", "head"=>__("Maximum Size"),),
                                                                   ),
                                                    ),
                                   "mapped"=>array(
                                                    "headline"=>__("Mapped Drives"),
                                                    "sql"=>"SELECT * FROM mapped WHERE mapped_uuid = '".$_REQUEST["pc"]."' AND mapped_timestamp = '".$GLOBAL["system_timestamp"]."' ORDER BY mapped_username, mapped_device_id",
                                                    "image"=>"images/shared_drive_l.png",
                                                    "table_layout"=>"horizontal",
                                                    "fields"=>array("10"=>array("name"=>"mapped_username", "head"=>__("UserName"),),
                                                                    "20"=>array("name"=>"mapped_device_id", "head"=>__("Drive ID"),),
                                                                    "30"=>array("name"=>"mapped_provider_name", "head"=>__("UNC Path"),),
                                                                    "40"=>array("name"=>"mapped_connect_as", "head"=>__("Connected As"),),
                                                                    //"50"=>array("name"=>"mapped_file_system", "head"=>__("File System"),),
                                                                    //"60"=>array("name"=>"mapped_size", "head"=>__("Size"),),
                                                                    //"70"=>array("name"=>"mapped_free_space", "head"=>__("Free Space"),),
                                                                   ),
                                                    ),
                                ),
                  );
?>
