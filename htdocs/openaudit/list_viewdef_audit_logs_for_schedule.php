<?php

    $query_array=array("headline"=>"Audit Logs for Schedule \"{$_GET['name']}\"",
                       "sql"=>"SELECT DISTINCT *, COUNT(audit_log_schedule_id) as log_entries FROM audit_log
                               WHERE audit_log_schedule_id = '{$_GET['schedule_id']}'
                               GROUP BY audit_log_timestamp",
                       "sort"=>"audit_log_timestamp",
                       "dir"=>"DESC",
                       "get"=>array("file"=>"list.php",
                                    "title"=>__("Audit Log For Date"),
                                    "var"=>array("view"=>"audit_log_for_timestamp",
                                                 "schedule_id"=>"%audit_log_schedule_id",
                                                 "name"=>"%audit_log_schedule_id",
                                                 "timestamp"=>"%audit_log_timestamp",
                                                ),
                                   ),
                       "fields"=>array("10"=>array("name"=>"audit_log_timestamp",
                                                   "head"=>__("Date Audited"),
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
