<?php

    $query_array=array("headline"=>"Run Now Audit Logs for Configuration \"{$_GET['name']}\"",
                       "sql"=>"SELECT DISTINCT *, COUNT(audit_log_schedule_id) as log_entries FROM audit_log, audit_configurations
                               WHERE audit_log_config_id = '{$_GET['config_id']}'
                                 AND audit_log_config_id = audit_cfg_id
                                 AND audit_log_schedule_id = 'none'
                               GROUP BY audit_log_timestamp",
                       "sort"=>"audit_log_timestamp",
                       "dir"=>"DESC",
                       "get"=>array("file"=>"list.php",
                                    "title"=>__("Audit Log For Date"),
                                    "var"=>array("view"=>"run_now_for_timestamp",
                                                 "config_id"=>"%audit_log_config_id",
                                                 "name"=>"%audit_cfg_name",
                                                 "timestamp"=>"%audit_log_timestamp",
                                                ),
                                   ),
                       "fields"=>array(
                                       "10"=>array("name"=>"audit_log_timestamp",
                                                   "head"=>__("Audit Now Date/Time"),
                                                   "show"=>"y",
                                                   "link"=>"y",
                                                  ),
                                       "20"=>array("name"=>"log_entries",
                                                   "head"=>__("Log Entries"),
                                                   "show"=>"y",
                                                  ),
                                      ),
                      );
?>
